<?php

require_once ("Secure_area.php");
require_once ("interfaces/idata_controller.php");

class Loans extends Secure_area implements iData_controller {

    function __construct()
    {
        parent::__construct('loans');
    }

    function index()
    {
        $data['controller_name'] = strtolower(get_class());
        $data['form_width'] = $this->get_form_width();

        $data['count_overdues'] = $this->_count_overdues();

        $res = $this->Employee->getLowerLevels();
        $data['staffs'] = $res;

        $this->load->library('DataTableLib');

        $this->set_dt_transactions($this->datatablelib->datatable());
        $data["tbl_loan_transactions"] = $this->datatablelib->render();
        $this->load->view('loans/list', $data);
    }

    function set_dt_transactions($datatable)
    {
        $datatable->add_server_params('', '', [$this->security->get_csrf_token_name() => $this->security->get_csrf_hash(), "ajax_type" => 3]);
        $datatable->ajax_url = site_url('loans/ajax');

        $datatable->add_column('actions', false);
        $datatable->add_column('id', false);
        $datatable->add_column('interest_type', false);
        $datatable->add_column('description', false);
        $datatable->add_column('loan_amount', false);
        $datatable->add_column('loan_balance', false);
        $datatable->add_column('customer', false);
        $datatable->add_column('agent', false);
        $datatable->add_column('approved_by', false);
        $datatable->add_column('formatted_loan_approved_date', false);
        $datatable->add_column('formatted_payment_date', false);
        $datatable->add_column('loan_status', false);

        $datatable->add_table_definition(["orderable" => false, "targets" => 0]);
        $datatable->order = [[1, 'desc']];

        $datatable->allow_search = true;
        $datatable->fixedColumns = true;
        $datatable->leftColumns = 3;
        $datatable->scrollX = true;
        $datatable->dt_height = '350px';

        $datatable->table_id = "#tbl_loans_transactions";
        $datatable->add_titles('leads');
        $datatable->has_edit_dblclick = 0;
    }

    function _dt_transactions()
    {
        $selected_user = $this->input->post("employee_id");
        $status = $this->input->post("status");
        $no_delete = $this->input->post("no_delete");

        $offset = $this->input->post("start");
        $limit = $this->input->post("length");

        $index = $this->input->post("order")[0]["column"];
        $dir = $this->input->post("order")[0]["dir"];
        $keywords = $this->input->post("search")["value"];

        $order = array("index" => $index, "direction" => $dir);

        $loans = $this->Loan->get_all($limit, $offset, $keywords, $order, $status, $selected_user);

        $tmp = array();

        $count_all = 0;
        foreach ($loans->result() as $loan)
        {
            $loan_status = $loan->loan_status;
            if ($loan->loan_balance <= 0)
            {
                $loan_status = "Paid";
            }

            $actions = "<a href='" . site_url('loans/view/' . $loan->loan_id) . "' class='btn-xs btn-default' title='View'><span class='fa fa-eye'></span></a> ";
            
            if ( !$no_delete )
            {
                $actions .= "<a href='javascript:void(0)' class='btn-xs btn-danger btn-delete' data-loan-id='" . $loan->loan_id . "' title='Delete'><span class='fa fa-trash'></span></a>";
            }

            $data_row = [];
            $data_row["DT_RowId"] = $loan->loan_id;
            $data_row["actions"] = $actions;
            $data_row["id"] = $loan->loan_id;
            $data_row["interest_type"] = ucwords(str_replace("_", " ", $loan->interest_type));
            $data_row["description"] = $loan->description;
            $data_row["loan_amount"] = to_currency($loan->loan_amount);
            $data_row["loan_balance"] = to_currency($loan->loan_balance);
            $data_row["customer"] = $loan->customer_name;
            $data_row["agent"] = $loan->agent_name;
            $data_row["approved_by"] = $loan->approver_name;
            $data_row["formatted_loan_approved_date"] = $loan->loan_approved_date > 0 ? date($this->config->item('date_format'), $loan->loan_approved_date) : '';
            $data_row["formatted_payment_date"] = ($loan->loan_payment_date > 0) ? date($this->config->item('date_format'), $loan->loan_payment_date) : '';
            $data_row["loan_status"] = $loan->loan_balance > 0 ? ucwords($loan->loan_status) : 'Paid';


            $tmp[] = $data_row;
            $count_all++;
        }

        $data["data"] = $tmp;
        $data["recordsTotal"] = $count_all;
        $data["recordsFiltered"] = $count_all;

        send($data);
    }

    function search()
    {
        
    }

    /*
      Gives search suggestions based on what is being searched for
     */

    function suggest()
    {
        
    }

    function get_row()
    {
        
    }

    function view($loan_id = -1)
    {
        $data['loan_info'] = $this->Loan->get_info($loan_id);
        $data['guarantee_info'] = $this->Guarantee->get_info($loan_id);
        $loan_types = $this->Loan_type->get_multiple_loan_types();

        $tmp = array("" => $this->lang->line("common_please_select"));
        foreach ($loan_types as $loan_type):
            $tmp[$loan_type->loan_type_id] = $loan_type->name;
        endforeach;

        $data['loan_types'] = $tmp;

        $data['misc_fees'] = array(
            array("", "")
        );

        $misc_fees = json_decode($data['loan_info']->misc_fees, true);

        if (is_array($misc_fees))
        {
            $tmp = array();
            foreach ($misc_fees as $fee => $charge):
                $tmp[] = array($fee, $charge);
            endforeach;
            $data['misc_fees'] = $tmp;
        }

        // payment scheds - start
        $c_payment_sches = [
            "term" => "",
            "term_period" => "",
            "payment_sched" => "",
            "interest_rate" => "",
            "penalty" => "",
            "payment_breakdown" => [
                "schedule" => [],
                "balance" => [],
                "interest" => [],
                "amount" => []
            ]
        ];

        $c_payment_scheds = trim($data["loan_info"]->payment_scheds) !== "" ? json_decode($data["loan_info"]->payment_scheds, TRUE) : $c_payment_sches;
        $data["c_payment_scheds"] = $c_payment_scheds;
        // payment scheds - end

        $attachments = $this->Loan->get_attachments($loan_id);

        $file = array();
        foreach ($attachments as $attachment)
        {
            $dFile = $this->_get_formatted_file($attachment->attachment_id, $attachment->filename, $attachment->descriptions);
            $file[$dFile["id"]] = $dFile;
        }

        $data['attachments'] = $file;

        $loan_status = (isset($data['loan_info']->loan_status) && trim($data['loan_info']->loan_status) !== "") ? $this->lang->line("common_" . strtolower($data['loan_info']->loan_status)) : $this->lang->line("common_pending");
        if ($data['loan_info']->loan_balance <= 0 && $loan_id > -1)
        {
            $loan_status = "paid";
        }
        $data['loan_status'] = $loan_status;

        $employees = $this->Employee->get_all()->result();
        $emps = array();
        foreach ($employees as $employee)
        {
            $emps[$employee->person_id] = $employee->first_name . " " . $employee->last_name;
        }

        $data['employees'] = $emps;

        $proof_ids = json_decode($data['guarantee_info']->proof, TRUE);
        $pimage_ids = json_decode($data['guarantee_info']->images, TRUE);

        $data["proofs"] = $this->_get_files($proof_ids, $file);
        $data["pimages"] = $this->_get_files($pimage_ids, $file);

        $this->load->view("loans/form", $data);
    }

    private function _get_files($ids, $file)
    {
        $tmp = array();
        if (is_array($ids))
        {
            foreach ($ids as $id):
                $tmp[] = $file[$id];
            endforeach;
        }

        return $tmp;
    }

    private function _get_formatted_file($id, $filename, $desc)
    {
        $words = array("doc", "docx", "odt");
        $xls = array("xls", "xlsx", "csv");
        $tmp = explode(".", $filename);
        $ext = $tmp[1];

        if (in_array(strtolower($ext), $words))
        {
            $tmp['icon'] = "images/word-filetype.jpg";
            $tmp['filename'] = $filename;
            $tmp['id'] = $id;
            $tmp['descriptions'] = $desc;
        }
        else if (strtolower($ext) === "pdf")
        {
            $tmp['icon'] = "images/pdf-filetype.jpg";
            $tmp['filename'] = $filename;
            $tmp['id'] = $id;
            $tmp['descriptions'] = $desc;
        }
        else if (in_array(strtolower($ext), $xls))
        {
            $tmp['icon'] = "images/xls-filetype.jpg";
            $tmp['filename'] = $filename;
            $tmp['id'] = $id;
            $tmp['descriptions'] = $desc;
        }
        else
        {
            $tmp['icon'] = "images/image-filetype.jpg";
            $tmp['filename'] = $filename;
            $tmp['id'] = $id;
            $tmp['descriptions'] = $desc;
        }

        return $tmp;
    }

    function save($loan_id = -1)
    {
        $fees = $this->input->post("fees");
        $amounts = $this->input->post("amounts");

        $misc_fees = array();
        for ($i = 0; $i < count($fees); $i++):
            $misc_fees[$fees[$i]] = $amounts[$i];
        endfor;

        // payment scheds - start        
        $post_var["InterestType"] = $this->input->post("interest_type");
        $post_var["NoOfPayments"] = $this->input->post("term");
        $post_var["ApplyAmt"] = $this->input->post("apply_amount");
        $post_var["TotIntRate"] = $this->input->post("interest_rate");
        $post_var["InstallmentStarted"] = $this->input->post("start_date");
        $post_var["PayTerm"] = $this->input->post("term_period");
        $post_var["exclude_sundays"] = $this->input->post('exclude_sundays') == 'on' ? 1 : 0;
        $post_var['penalty_value'] = $this->input->post("penalty_value");
        $post_var['penalty_type'] = $this->input->post("penalty_type");

        $apply_amount = $this->input->post('apply_amount');

        $penalty_amount = $post_var['penalty_value'];
        if ($post_var['penalty_type'] == 'percentage')
        {
            $penalty_amount = ($apply_amount * ($post_var['penalty_value'] / 100));
        }
        $post_var["penalty_amount"] = $penalty_amount;
        $loan_schedule = $this->_get_loan_schedule($post_var);
        // payment scheds - end


        $loan_data = array(
            'account' => $this->input->post('account'),
            'description' => $this->input->post('description'),
            'loan_type_id' => $this->input->post('loan_type_id') > 0 ? $this->input->post('loan_type_id') : 0,
            'loan_amount' => $this->input->post('amount'),
            'customer_id' => $this->input->post('customer'),
            'loan_applied_date' => $this->config->item('date_format') == 'd/m/Y' ? strtotime(uk_to_isodate($this->input->post('apply_date'))) : strtotime($this->input->post('apply_date')),
            'remarks' => $this->input->post('remarks'),
            'loan_agent_id' => $this->input->post('agent'),
            'loan_approved_by_id' => $this->input->post('approver') > 0 ? $this->input->post('approver') : 0,
            'loan_status' => ($this->input->post('approver') > 0) ? "approved" : $this->input->post("status"),
            'misc_fees' => json_encode($misc_fees),
            'periodic_loan_table' => json_encode($loan_schedule),
            'apply_amount' => $this->input->post('apply_amount'),
            'interest_rate' => $this->input->post('interest_rate'),
            'interest_type' => ($this->input->post('interest_type') == '' ? 'fixed' : $this->input->post('interest_type')),
            'term_period' => $this->input->post('term_period'),
            'payment_term' => $this->input->post('term'),
            'payment_start_date' => $this->config->item('date_format') == 'd/m/Y' ? strtotime(uk_to_isodate($this->input->post('start_date'))) : strtotime($this->input->post('start_date')),
            'exclude_sundays' => $post_var["exclude_sundays"],
            'penalty_value' => ($this->input->post("penalty_value") > 0 ? $this->input->post("penalty_value") : 0),
            'penalty_type' => $this->input->post("penalty_type") != '' ? $this->input->post("penalty_type") : 'percentage',
        );

        // check loan payment date
        if ($loan_data["loan_type_id"] > 0)
        {
            
        }
        else
        {
            $loan_data["loan_payment_date"] = $this->config->item('date_format') == 'd/m/Y' ? strtotime(uk_to_isodate($this->input->post('start_date'))) : strtotime($this->input->post("start_date"));
        }

        $guarantee_data = array(
            'loan_id' => $loan_id,
            'name' => $this->input->post("guarantee_type"),
            'type' => $this->input->post("guarantee_name"),
            'brand' => $this->input->post("guarantee_brand"),
            'make' => $this->input->post("guarantee_make"),
            'serial' => $this->input->post("guarantee_serial"),
            'proof' => json_encode($this->input->post("proofs")),
            'images' => json_encode($this->input->post("images")),
            'price' => $this->input->post("guarantee_price") > 0 ? $this->input->post("guarantee_price") : 0,
            'observations' => $this->input->post("guarantee_observations")
        );

        $has_payment = false;
        $paid_amount = $this->input->post("paid_amount");
        // check if paid amount is greater than 0
        if ($paid_amount > 0)
        {
            $balance_amount = $this->input->post("current_balance") - $paid_amount;
            $loan_data["loan_balance"] = $balance_amount;
            $has_payment = true;
        }

        if ($this->Loan->save($loan_data, $loan_id, $has_payment))
        {
            //New Loan
            if ($loan_id == -1)
            {
                echo json_encode(array('success' => true, 'message' => $this->lang->line('loans_successful_adding') . ' ' .
                    $loan_data['account'], 'loan_id' => $loan_data['loan_id']));
                $loan_id = $loan_data['loan_id'];
            }
            else //previous loan
            {
                echo json_encode(array('success' => true, 'message' => $this->lang->line('loans_successful_updating') . ' ' .
                    $loan_data['account'], 'loan_id' => $loan_id));
            }

//            if ($has_payment)
//            {
//                $loan_id = ( isset($loan_data['loan_id']) ) ? $loan_data['loan_id'] : $loan_id;
//                $payment_data = array(
//                    'account' => $this->input->post('account'),
//                    'loan_id' => $loan_id,
//                    'customer_id' => $this->input->post('customer'),
//                    'paid_amount' => $this->input->post('paid_amount'),
//                    'balance_amount' => $balance_amount,
//                    'date_paid' => strtotime($this->input->post('date_paid')),
//                    'remarks' => $this->input->post('remarks'),
//                    'teller_id' => $this->input->post('teller'),
//                );
//
//                $this->Payment->save($payment_data, -1);
//            }

            $this->Guarantee->save($guarantee_data, $loan_id);
        }
        else//failure
        {
            echo json_encode(array('success' => false, 'message' => $this->lang->line('loans_error_adding_updating') . ' ' .
                $loan_data['account'], 'loan_id' => -1));
        }
    }

    function delete()
    {
        $loans_to_delete = $this->input->post('ids');

        if ($this->Loan->delete_list($loans_to_delete))
        {
            echo json_encode(array('success' => true, 'message' => $this->lang->line('loans_successful_deleted') . ' ' .
                count($loans_to_delete) . ' ' . $this->lang->line('loans_one_or_multiple')));
        }
        else
        {
            echo json_encode(array('success' => false, 'message' => $this->lang->line('loans_cannot_be_deleted')));
        }
    }

    /*
      get the width for the add/edit form
     */

    function get_form_width()
    {
        return 360;
    }

    function data($status = "")
    {
        $sel_user = $this->input->get("employee_id");
        $order = array("index" => $_GET['order'][0]['column'], "direction" => $_GET['order'][0]['dir']);
        $loans = $this->Loan->get_all($_GET['length'], $_GET['start'], $_GET['search']['value'], $order, $status, $sel_user);

        $format_result = array();

        foreach ($loans->result() as $loan)
        {
            $loan_status = $loan->loan_status;
            if ($loan->loan_balance <= 0)
            {
                $loan_status = "Paid";
            }

            $format_result[] = array(
                "<input type='checkbox' name='chk[]' id='loan_$loan->loan_id' value='" . $loan->loan_id . "'/>",
                $loan->loan_id,
                ucwords(str_replace('_', '<br/>', $loan->interest_type)),
                $loan->account,
                $loan->description,
                to_currency($loan->loan_amount),
                to_currency($loan->loan_balance),
                ucwords($loan->customer_name),
                ucwords($loan->agent_name),
                ucwords($loan->approver_name),
                $loan->loan_approved_date > 0 ? date($this->config->item('date_format'), $loan->loan_approved_date) : '',
                ($loan->loan_payment_date > 0) ? date($this->config->item('date_format'), $loan->loan_payment_date) : '',
                $this->lang->line("common_" . strtolower($loan_status)),
                anchor('loans/view/' . $loan->loan_id, $this->lang->line('common_view'), array('class' => 'btn btn-success'))
            );
        }

        $data = array(
            "recordsTotal" => $this->Loan->count_all(),
            "recordsFiltered" => $this->Loan->count_all(),
            "data" => $format_result
        );

        echo json_encode($data);
        exit;
    }
    
    function overdues()
    {
        $order = array("index" => $_GET['order'][0]['column'], "direction" => $_GET['order'][0]['dir']);

        $loans = $this->Loan->get_all($_GET['length'], $_GET['start'], $_GET['search']['value'], $order, "overdue");

        $format_result = array();

        foreach ($loans->result() as $loan)
        {
            $loan_status = $loan->loan_status;
            if ($loan->loan_balance <= 0)
            {
                $loan_status = "Paid";
            }

            $format_result[] = array(
                "<input type='checkbox' name='chk[]' id='loan_$loan->loan_id' value='" . $loan->loan_id . "'/>",
                $loan->loan_id,
                $loan->loan_type,
                $loan->account,
                $loan->description,
                to_currency($loan->loan_amount),
                to_currency($loan->loan_balance),
                ucwords($loan->customer_name),
                ucwords($loan->agent_name),
                ucwords($loan->approver_name),
                date($this->config->item('date_format'), $loan->loan_applied_date),
                date($this->config->item('date_format'), $loan->loan_payment_date),
                $this->lang->line("common_" . strtolower($loan_status)),
                anchor('loans/view/' . $loan->loan_id, $this->lang->line('common_view'), array('class' => 'modal_link btn btn-success', "title" => "Update Loan"))
            );
        }

        $data = array(
            "recordsTotal" => $this->Loan->count_all(),
            "recordsFiltered" => $this->Loan->count_all(),
            "data" => $format_result
        );

        echo json_encode($data);
        exit;
    }

    function fix_breakdown($loan_id)
    {
        $loan = $this->Loan->get_info($loan_id);
        $loan_type = $this->Loan_type->get_info($loan->loan_type_id);
        $customer = $this->Customer->get_info($loan->customer_id);

        $filename = "schedule" . time();
        // As PDF creation takes a bit of memory, we're saving the created file in /downloads/reports/
        $pdfFilePath = FCPATH . "/downloads/reports/$filename.pdf";

        $data['company_name'] = $this->config->item("company"); // company name
        $data['company_address'] = $this->config->item("address"); // company address
        $data['phone'] = $this->config->item("phone"); // company address
        $data['fax'] = $this->config->item("fax"); // company address
        $data['email'] = $this->config->item("email"); // company address

        $data['loan_amount'] = to_currency($loan->apply_amount); // loan amount
        $data['payable'] = to_currency($loan->loan_amount);

        $data['rate'] = $loan->interest_rate; // interest rate
        $data['term'] = $loan->payment_term;

        $data['loan'] = $loan;
        $data['loan_type'] = $loan->interest_type;
        $data['term_period'] = $loan->term_period;
        $data['schedules'] = json_decode($loan->periodic_loan_table);

        $data['misc_fees'] = array();

        $misc_fees = json_decode($loan->misc_fees, true);
        $total_deductions = 0;

        if (is_array($misc_fees))
        {
            $tmp = array();
            foreach ($misc_fees as $fee => $charge):
                if (trim($charge) !== "")
                {
                    $tmp[] = array($fee, to_currency($charge));
                }
                $total_deductions += $charge;
            endforeach;
            $data['misc_fees'] = $tmp;
        }

        $data['customer_name'] = ucwords($customer->first_name . " " . $customer->last_name);
        $data['customer_address'] = ucwords($customer->address_1);
        $data['total_deductions'] = to_currency($total_deductions);
        $data['net_loan'] = to_currency($loan->apply_amount - $total_deductions);


        //$data['repayment_amount'] = to_currency($payable);
        //$data['breakdown_data'] = $this->_calculate_breakdown($loan_type->term, $period, $loan_type->percent_charge1, $payable, $loan->loan_balance, $loan->loan_payment_date, $loan_type->payment_schedule);

        ini_set('memory_limit', '64M'); // boost the memory limit if it's low <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
        $html = $this->load->view('loans/pdf/payment_schedule', $data, true); // render the view into HTML

        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->SetFooter($_SERVER['HTTP_HOST'] . '|{PAGENO}|' . date(DATE_RFC822)); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
        $pdf->WriteHTML($html); // write the HTML into the PDF
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can

        redirect(base_url("downloads/reports/$filename.pdf"));
    }

    function generate_breakdown($loan_id)
    {
        $loan = $this->Loan->get_info($loan_id);
        $loan_type = $this->Loan_type->get_info($loan->loan_type_id);
        $customer = $this->Customer->get_info($loan->customer_id);

        if ($loan_type->term_period_type === "year")
        {
            $period = $this->_get_period($loan_type->payment_schedule);
        }
        else
        {
            $period = $this->_get_period($loan_type->payment_schedule, false);
        }

        $payable = $this->_calculate_mortgage($loan->loan_balance, $loan_type->percent_charge1, $loan_type->term, $period);

        $filename = "schedule" . time();
        // As PDF creation takes a bit of memory, we're saving the created file in /downloads/reports/
        $pdfFilePath = FCPATH . "/downloads/reports/$filename.pdf";

        $data['company_name'] = $this->config->item("company"); // company name
        $data['company_address'] = $this->config->item("address"); // company address
        $data['phone'] = $this->config->item("phone"); // company address
        $data['fax'] = $this->config->item("fax"); // company address
        $data['email'] = $this->config->item("email"); // company address

        $data['loan_amount'] = to_currency($loan->loan_amount); // loan amount
        $data['payable'] = to_currency($loan->loan_balance);
        $data['rate'] = $loan_type->percent_charge1; // interest rate
        $data['term'] = $loan_type->term;

        $data['loan'] = $loan;
        $data['loan_type'] = $loan_type;
        $data['period_type'] = $loan_type->payment_schedule;
        $data['schedules'] = $this->Payment_schedule->get_schedules();

        $data['misc_fees'] = array();

        $misc_fees = json_decode($loan->misc_fees, true);
        $total_deductions = 0;

        if (is_array($misc_fees))
        {
            $tmp = array();
            foreach ($misc_fees as $fee => $charge):
                if (trim($charge) !== "")
                {
                    $tmp[] = array($fee, to_currency($charge));
                }
                $total_deductions += $charge;
            endforeach;
            $data['misc_fees'] = $tmp;
        }

        $data['customer_name'] = ucwords($customer->first_name . " " . $customer->last_name);
        $data['customer_address'] = ucwords($customer->address_1);
        $data['total_deductions'] = to_currency($total_deductions);
        $data['net_loan'] = to_currency($loan->loan_amount - $total_deductions);
        $data['total_interest'] = $this->_calculate_total_interest($loan->loan_balance, $loan_type->term, $payable, $period); // pass data to the view

        if ($loan_type->term_period_type === "year")
        {
            $tmp = $this->_get_repayment_amount_year_term($loan_type, $loan->loan_amount);
        }
        else
        {
            $tmp = $this->_get_repayment_amount_month_term($loan_type, $loan->loan_amount);
        }

        $data['repayment_amount'] = to_currency($payable);
        $data['payment_sched'] = strtoupper($tmp['payment_sched']);
        $data['apr'] = $tmp['apr'];


        $data['breakdown_data'] = $this->_calculate_breakdown($loan_type->term, $period, $loan_type->percent_charge1, $payable, $loan->loan_balance, $loan->loan_payment_date, $loan_type->payment_schedule);

        ini_set('memory_limit', '64M'); // boost the memory limit if it's low <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
        $html = $this->load->view('loans/payment_schedule', $data, true); // render the view into HTML

        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->SetFooter($_SERVER['HTTP_HOST'] . '|{PAGENO}|' . date(DATE_RFC822)); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
        $pdf->WriteHTML($html); // write the HTML into the PDF
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can

        redirect(base_url("downloads/reports/$filename.pdf"));
    }

    function print_disclosure($loan_id)
    {
        $loan = $this->Loan->get_info($loan_id);
        $loan_type = $this->Loan_type->get_info($loan->loan_type_id);
        $customer = $this->Customer->get_info($loan->customer_id);

        if ($loan_type->term_period_type === "year")
        {
            $period = $this->_get_period($loan_type->payment_schedule);
        }
        else
        {
            $period = $this->_get_period($loan_type->payment_schedule, false);
        }


        $payable = $this->_calculate_mortgage($loan->loan_balance, $loan_type->percent_charge1, $loan_type->term, $period);

        $filename = "disclosure" . time();
        // As PDF creation takes a bit of memory, we're saving the created file in /downloads/reports/
        $pdfFilePath = FCPATH . "/downloads/reports/$filename.pdf";

        $data['company_name'] = $this->config->item("company"); // company name
        $data['company_address'] = $this->config->item("address"); // company address
        $data['phone'] = $this->config->item("phone"); // company address
        $data['fax'] = $this->config->item("fax"); // company address
        $data['email'] = $this->config->item("email"); // company address

        $data['loan_amount'] = to_currency($loan->loan_amount); // loan amount
        $data['payable'] = to_currency($loan->loan_balance);
        $data['rate'] = $loan_type->percent_charge1; // interest rate
        $data['term'] = $loan_type->term;

        $data['loan'] = $loan;
        $data['loan_type'] = $loan_type;
        $data['period_type'] = $loan_type->payment_schedule;
        $data['schedules'] = $this->Payment_schedule->get_schedules();

        $data['misc_fees'] = array();

        $misc_fees = json_decode($loan->misc_fees, true);
        $total_deductions = 0;

        if (is_array($misc_fees))
        {
            $tmp = array();
            foreach ($misc_fees as $fee => $charge):
                if (trim($charge) !== "")
                {
                    $tmp[] = array($fee, to_currency($charge));
                }
                $total_deductions += $charge;
            endforeach;
            $data['misc_fees'] = $tmp;
        }

        $data['customer_name'] = ucwords($customer->first_name . " " . $customer->last_name);
        $data['customer_address'] = ucwords($customer->address_1);
        $data['total_deductions'] = to_currency($total_deductions);
        $data['net_loan'] = to_currency($loan->loan_amount - $total_deductions);
        //$data['total_interest'] = $this->_calculate_total_interest($loan->loan_balance, $loan_type->term, $payable, $period); // pass data to the view

        if ($loan_type->term_period_type === "year")
        {
            $tmp = $this->_get_repayment_amount_year_term($loan_type, $loan->loan_amount);
        }
        else
        {
            $tmp = $this->_get_repayment_amount_month_term($loan_type, $loan->loan_amount);
        }

        $data['repayment_amount'] = to_currency($payable);
        $data['payment_sched'] = strtoupper($tmp['payment_sched']);
        $data['apr'] = $tmp['apr'];

        //$data['breakdown_data'] = $this->_calculate_breakdown($loan_type->term, $period, $loan_type->percent_charge1, $payable, $loan->loan_balance, $loan->loan_payment_date);
        //if (file_exists($pdfFilePath) == FALSE)
        //{
        ini_set('memory_limit', '64M'); // boost the memory limit if it's low <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
        $html = $this->load->view('loans/pdf_report', $data, true); // render the view into HTML

        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->SetFooter($_SERVER['HTTP_HOST'] . '|{PAGENO}|' . date(DATE_RFC822)); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
        $pdf->WriteHTML($html); // write the HTML into the PDF
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        //}

        redirect(base_url("downloads/reports/$filename.pdf"));
    }

    function fix_disclosure($loan_id)
    {
        $loan = $this->Loan->get_info($loan_id);
        $loan_type = $this->Loan_type->get_info($loan->loan_type_id);
        $customer = $this->Customer->get_info($loan->customer_id);

        $filename = "disclosure" . time();
        // As PDF creation takes a bit of memory, we're saving the created file in /downloads/reports/
        $pdfFilePath = FCPATH . "/downloads/reports/$filename.pdf";

        $data['company_name'] = $this->config->item("company"); // company name
        $data['company_address'] = $this->config->item("address"); // company address
        $data['phone'] = $this->config->item("phone"); // company address
        $data['fax'] = $this->config->item("fax"); // company address
        $data['email'] = $this->config->item("email"); // company address

        $data['loan_amount'] = to_currency($loan->loan_amount); // loan amount
        $data['payable'] = to_currency($loan->loan_balance);
        $data['rate'] = $loan_type->percent_charge1; // interest rate
        $data['term'] = $loan_type->term;

        $data['loan'] = $loan;
        $data['loan_type'] = $loan_type;
        $data['period_type'] = $loan_type->payment_schedule;
        $data['schedules'] = $this->Payment_schedule->get_schedules();

        $data['misc_fees'] = array();

        $misc_fees = json_decode($loan->misc_fees, true);
        $total_deductions = 0;

        if (is_array($misc_fees))
        {
            $tmp = array();
            foreach ($misc_fees as $fee => $charge):
                if (trim($charge) !== "")
                {
                    $tmp[] = array($fee, to_currency($charge));
                }
                $total_deductions += $charge;
            endforeach;
            $data['misc_fees'] = $tmp;
        }

        $data['customer_name'] = ucwords($customer->first_name . " " . $customer->last_name);
        $data['customer_address'] = ucwords($customer->address_1);
        $data['total_deductions'] = to_currency($total_deductions);
        $data['net_loan'] = to_currency($loan->loan_amount - $total_deductions);


        $data['repayment_amount'] = to_currency($payable);
        $c_payment_sches = [
            "term" => "",
            "term_period" => "",
            "payment_sched" => "",
            "interest_rate" => "",
            "penalty" => "",
            "payment_breakdown" => [
                "schedule" => [],
                "balance" => [],
                "interest" => [],
                "amount" => []
            ]
        ];

        $c_payment_scheds = trim($loan->payment_scheds) !== "" ? json_decode($loan->payment_scheds, TRUE) : $c_payment_sches;
        $data["c_payment_scheds"] = $c_payment_scheds;

        ini_set('memory_limit', '64M'); // boost the memory limit if it's low <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
        $html = $this->load->view('loans/pdf_report_fix', $data, true); // render the view into HTML

        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->SetFooter($_SERVER['HTTP_HOST'] . '|{PAGENO}|' . date(DATE_RFC822)); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
        $pdf->WriteHTML($html); // write the HTML into the PDF
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can

        redirect(base_url("downloads/reports/$filename.pdf"));
    }

    private function _get_repayment_amount_year_term($loan_type, $loan_balance_amount)
    {
        // get the term of payment
        $term = $loan_type->term; // 1yr
        // period_type1 means recurrence of interest
        // how to get the APR (Annual Percentage Rate) if 
        // im going to give an interest rate of 3% every 3 weeks
        // of course im going get how many time 3 weeks in a year
        // 52 weeks / 3 weeks = 17 weeks then multiply it by 3%
        // the answer is the APR, which is 51% in year.
        // interest rate is computed by APR / number times in a year

        switch ($loan_type->period_type1)
        {
            case "Week": // 52 weeks in 1yr
                $period = 52 * $term;
                $apr = (52 / $loan_type->period_charge1) * $loan_type->percent_charge1;
                $interest_rate = $apr / 52;
                break;
            case "Month": // 12 months in 1yr
                $period = 12 * $term;
                $apr = (12 / $loan_type->period_charge1) * $loan_type->percent_charge1;
                $interest_rate = $apr / 12;
                break;
            case "Year": // 1yr in 1yr
                $period = 1 * $term;
                $apr = (1 / $loan_type->period_charge1) * $loan_type->percent_charge1;
                $interest_rate = $apr / 1;
                break;
        }

        switch ($loan_type->payment_schedule)
        {
            case "weekly": // 52 weeks in 1yr
                $factor = 52 * $term;
                $payment_sched = "Weekly payment";
                break;
            case "monthly": // 12 months in 1yr
                $factor = 12 * $term;
                $payment_sched = "Monthly payment";
                break;
            case "yearly": // 1yr in 1yr
                $factor = 1 * $term;
                $payment_sched = "Yearly payment";
                break;
            case "daily":
                $factor = 365 * $term;
                $payment_sched = "Daily payment";
                break;
        }

        if ($apr > 0)
        {
            $rate_per_payment = 1 / (1 + ($interest_rate / 100) );
            $payment = $loan_balance_amount * ( (1 - $rate_per_payment) / ( $rate_per_payment - (pow($rate_per_payment, $factor)) ) );

            return array("repayment_amount" => $payment, "apr" => $apr, "payment_sched" => $payment_sched);
        }
        else
        {
            $payment = $loan_balance_amount / $factor;
            return array("repayment_amount" => $payment, "apr" => $apr, "payment_sched" => $payment_sched);
        }
    }

    private function _get_repayment_amount_month_term($loan_type, $loan_balance_amount)
    {
        // get the term of payment
        $term = $loan_type->term; // 1month
        // period_type1 means recurrence of interest
        // how to get the APR (Annual Percentage Rate) if 
        // im going to give an interest rate of 3% every 3 weeks
        // of course im going get how many time 3 weeks in a year
        // 52 weeks / 3 weeks = 17 weeks then multiply it by 3%
        // the answer is the APR, which is 51% in year.
        // interest rate is computed by APR / number times in a year

        /* switch ($loan_type->period_type1)
          {
          case "Week": // 52 weeks in 1yr
          $period = 52 * $term;
          $amr = (52 / $loan_type->period_charge1) * $loan_type->percent_charge1;
          $interest_rate = $amr / 52;
          break;
          case "Month": // 12 months in 1yr
          $period = 12 * $term;
          $amr = (12 / $loan_type->period_charge1) * $loan_type->percent_charge1;
          $interest_rate = $amr / 12;
          break;
          case "Year": // 1yr in 1yr
          $period = 1 * $term;
          $amr = (1 / $loan_type->period_charge1) * $loan_type->percent_charge1;
          $interest_rate = $amr / 1;
          break;
          } */

        switch ($loan_type->payment_schedule)
        {
            case "weekly": // 4 weeks in 1month
                $factor = 4 * $term;
                $payment_sched = "Weekly payment";
                break;
            case "biweekly": // 4 weeks in 1month
                $factor = 2 * $term;
                $payment_sched = "Bi-Weekly payment";
                break;
            case "monthly": // 1 month in 1month
                $factor = 1 * $term;
                $payment_sched = "Monthly payment";
                break;
            case "daily":
                $factor = 30 * $term;
                $payment_sched = "Daily payment";
                break;
        }

        $interest_rate = $loan_type->percent_charge1;

        if ($interest_rate > 0)
        {
            $rate_per_payment = 1 / (1 + ($interest_rate / 100) );
            $repayment = $loan_balance_amount * ( (1 - $rate_per_payment) / ( $rate_per_payment - (pow($rate_per_payment, $factor)) ) );
            return array("repayment_amount" => $repayment, "apr" => $interest_rate, "payment_sched" => $payment_sched);
        }
        else
        {
            $payment = $loan_balance_amount / @$factor;
            return array("repayment_amount" => $payment, "apr" => $amr, "payment_sched" => $payment_sched);
        }
    }

    private function _calculate_breakdown($term, $period, $rate, $pay, $balance, $payment_date, $payment_schedule)
    {
        $data = array();
        for ($i = 0; $i < ($term * $period); $i++)
        {
            $tmp = (($pay) - ($balance * ($rate / 100 / $period)));
            $diff = round($tmp, 2);
            $int = round(($balance * $rate / 100 / $period), 2);
            $princ = $balance - $diff;
            $balance = round($balance, 0);

            $data[$i]['month'] = date("M d, Y", $payment_date);

            switch ($payment_schedule)
            {
                case "weekly":
                    $payment_date = strtotime("+7 day", $payment_date);
                    break;
                case "biweekly":
                    $payment_date = strtotime("+14 day", $payment_date);
                    break;
                case "monthly":
                    $payment_date = strtotime("+1 month", $payment_date);
                    break;
                case "bimonthly":
                    $payment_date = strtotime("+2 month", $payment_date);
                    break;
                case "daily":
                    $payment_date = strtotime("+1 day", $payment_date);
                    break;
            }

            $data[$i]['balance'] = to_currency($balance);
            $data[$i]['interest'] = $int;
            $data[$i]['pay'] = to_currency($pay);

            $balance = $princ;
        }

        return $data;
    }

    private function _get_period($period_type, $is_yearly = true)
    {
        if ($is_yearly)
        {
            // 52 - Weekly
            // 26 - Biweekly
            // 12 - Monthly
            //  6 - Bimonthly
            // $period = 12;
            $period = 12;
            switch ($period_type)
            {
                case "weekly":
                    $period = 52;
                    break;
                case "biweekly":
                    $period = 26;
                    break;
                case "monthly":
                    $period = 12;
                    break;
                case "bimonthly":
                    $period = 6;
                    break;
            }
        }
        else
        {
            // 4 - Weekly
            // $period = 12;
            $period = 4;
            switch ($period_type)
            {
                case "weekly":
                    $period = 4;
                    break;
                case "biweekly":
                    $period = 2;
                    break;
                case "daily":
                    $period = 30;
                    break;
                case "monthly":
                    $period = 1;
                    break;
            }
        }

        return $period;
    }

    private function _calculate_mortgage($balance, $rate, $term, $period)
    {
        $N = (int) $term * (int) $period;
        $I = ((float) $rate / 100) / (int) $period;
        $v = pow((1 + $I), $N);
        $t = ($v - 1) > 0 ? ($I * $v) / ($v - 1) : 1;
        $result = $balance * $t;

        return $result;
    }

    private function _calculate_total_interest($balance, $term, $pay, $period)
    {
        return (($term * $pay * $period) - $balance);
    }

    private function _count_overdues()
    {
        return $this->Loan->count_overdues();
    }

    function customer_search()
    {
        $suggestions = $this->Customer->get_customer_search_suggestions($this->input->get('query'), 30);
        $data = $tmp = array();

        foreach ($suggestions as $suggestion):
            $t = explode("|", $suggestion);
            $tmp = array("value" => $t[1], "data" => $t[0]);
            $data[] = $tmp;
        endforeach;

        echo json_encode(array("suggestions" => $data));
        exit;
    }

    function select_customer()
    {
        $customer_id = $this->input->post("customer");
        $this->sale_lib->set_customer($customer_id);
        $this->_reload();
    }

    function upload()
    {
        $directory = FCPATH . 'uploads/loan-' . $_REQUEST["loan_id"] . "/";
        $this->load->library('uploader');
        $data = $this->uploader->upload($directory);

        $this->Loan->save_attachments($data['params']['loan_id'], $data);

        $file = $this->_get_formatted_file($data['attachment_id'], $data['filename'], "");
        $file['loan_id'] = $data['params']['loan_id'];
        $file['id'] = $data["attachment_id"];
        $file['token_hash'] = $this->security->get_csrf_hash();

        echo json_encode($file);
        exit;
    }

    function remove_file()
    {
        $file_id = $this->input->post("file_id");
        $return["status"] = $this->Loan->remove_file($file_id);
        $return['token_hash'] = $this->security->get_csrf_hash();
        echo json_encode($return);
        exit;
    }

    function attach_desc()
    {
        $id = $this->input->post("attach_id");
        $desc = $this->input->post("desc");
        $this->Loan->save_attach_desc($id, $desc);
        echo json_encode(array("success" => TRUE));
        exit;
    }

    function attachments($loan_id, $select_type)
    {
        $data['loan_info'] = $this->Loan->get_info($loan_id);
        $attachments = $this->Loan->get_attachments($loan_id);

        $file = array();
        foreach ($attachments as $attachment)
        {
            $file[] = $this->_get_formatted_file($attachment->attachment_id, $attachment->filename, $attachment->descriptions);
        }

        $data["select_type"] = $select_type;
        $data['attachments'] = $file;
        $this->load->view("loans/attachments", $data);
    }

    function ajax()
    {
        $ajax_type = $this->input->post('ajax_type');
        switch ($ajax_type)
        {
            case 1: // Calculator
                $this->_handle_calculator();
                break;
            case 2: // Approve loan
                $this->_handle_approve_loan();
                break;
            case 3: // Get transactions
                $this->_dt_transactions();
                break;
            case 4: // Delete transactions
                $this->_handle_delete_transactions();
                break;
        }
    }

    private function _handle_delete_transactions()
    {
        $id = $this->input->post("id");
        $this->Loan->delete_list([$id]);

        $return["status"] = "OK";
        send($return);
    }

    private function _handle_approve_loan()
    {
        $approver = $this->input->post("approver");
        $loan_id = $this->input->post("loan_id");

        $update_data = [];
        $update_data["loan_approved_by_id"] = $approver;
        $update_data["loan_status"] = "approved";
        $update_data["loan_approved_date"] = time();

        $this->db->where("loan_id", $loan_id);
        $this->db->update("loans", $update_data);

        $return["status"] = "OK";
        send($return);
    }

    private function _handle_calculator()
    {
        $apply_amount = $this->input->post('ApplyAmt');
        $penalty_value = $this->input->post("penalty_value");
        $penalty_type = $this->input->post("penalty_type");

        $penalty_amount = $penalty_value;
        if ($penalty_type == 'percentage')
        {
            $penalty_amount = ($apply_amount * ($penalty_value / 100));
        }

        $_POST['penalty_amount'] = $penalty_amount;
        $scheds = $this->_get_loan_schedule($this->input->post());

        $return["scheds"] = $scheds;
        $return["status"] = "OK";
        send($return);
    }

    function _get_loan_schedule($post_var)
    {
        switch ($post_var["InterestType"])
        {
            case 'fixed':
                $data_scheds = $this->calculate_fixed_rate($post_var);
                break;
            case 'standard':
                $data_scheds = $this->calculate_standard_rate($post_var);
                break;
            case 'interest_only':
                $data_scheds = $this->calculate_interest_only($post_var);
                break;
            case 'outstanding_interest':
                $data_scheds = $this->calculate_outstanding_interest($post_var);
                break;
            case 'one_time':
                $data_scheds = $this->calculate_one_time_payment($post_var);
                break;
            default:
                $data_scheds = $this->calculate_percentage($post_var);
                break;
        }

        return $data_scheds;
    }

    function calculate_standard_rate($post_var)
    {
        $term = $post_var["NoOfPayments"];

        $loan_amount = $post_var["ApplyAmt"];
        $fixed_rate = $post_var["TotIntRate"];

        $penalty_amount = $post_var["penalty_amount"];

        $data_scheds = [];

        $fixed_amount = ($loan_amount * ($fixed_rate / 100));
        $payment_amount = ($loan_amount + $fixed_amount) / $term;

        $total_amount = 0;
        $no_of_days = 0;
        $total_interest = 0;
        $total_principal = 0;

        for ($i = 1; $i <= $term; $i++)
        {

            $compound_interest = $fixed_rate;
            $principal_amount = $payment_amount - $compound_interest;
            $balance_owed = $loan_amount - $principal_amount;

            $total_amount += $payment_amount;

            $payment_date = $post_var["InstallmentStarted"];

            $tmp = [];
            $tmp["payment_date"] = $payment_date;
            $tmp["payment_balance"] = $balance_owed;
            $tmp["penalty_amount"] = $penalty_amount;
            $tmp["interest"] = $compound_interest;
            $tmp["payment_amount"] = $payment_amount;

            $data_scheds[] = $tmp;

            $loan_amount = $balance_owed;
            $no_of_days++;
            $total_interest += $compound_interest;
            $total_principal += $principal_amount;
        }

        return $data_scheds;
    }

    function calculate_one_time_payment($post_var)
    {
        $term = 1;

        $loan_amount = $post_var["ApplyAmt"];
        $fixed_amount = $post_var["TotIntRate"];
        $exclude_sundays = $post_var["exclude_sundays"];

        $penalty_amount = $post_var["penalty_amount"];

        $pay_term = $post_var["PayTerm"];


        $data_scheds = [];

        $fixed_amount = ($loan_amount * ($fixed_amount / 100)) * $term;
        $payment_amount = ($loan_amount + $fixed_amount) / $term;
        $fixed_rate = $fixed_amount / $term;

        $total_amount = 0;
        $no_of_days = 0;
        $total_interest = 0;
        $total_principal = 0;

        if ($this->config->item('date_format') == 'd/m/Y')
        {
            $payment_date = strtotime(uk_to_isodate($post_var["InstallmentStarted"]));
        }
        else
        {
            $payment_date = strtotime($post_var["InstallmentStarted"]);
        }

        for ($i = 1; $i <= $term; $i++)
        {

            $compound_interest = $fixed_rate;
            $principal_amount = $payment_amount - $compound_interest;
            $balance_owed = $loan_amount - $principal_amount;

            $total_amount += $payment_amount;

            $tmp = [];
            $tmp["payment_date"] = date($this->config->item('date_format'), $payment_date);
            $tmp["payment_balance"] = $balance_owed;
            $tmp["penalty_amount"] = $penalty_amount;
            $tmp["interest"] = $compound_interest;
            $tmp["payment_amount"] = $payment_amount;

            $data_scheds[] = $tmp;

            $payment_date = strtotime(date('Y-m-d', $payment_date) . '+1 ' . $pay_term);

            if ($exclude_sundays)
            {
                $in_day = date("l", $payment_date);
                $in_day = strtolower($in_day);

                if ($in_day == "sunday")
                {
                    $payment_date = strtotime(date('Y-m-d', $payment_date) . '+1 day');
                }
            }

            $loan_amount = $balance_owed;
            $no_of_days++;
            $total_interest += $compound_interest;
            $total_principal += $principal_amount;
        }

        return $data_scheds;
    }

    function calculate_fixed_rate($post_var)
    {
        $term = $post_var["NoOfPayments"];

        $loan_amount = $post_var["ApplyAmt"];
        $fixed_amount = $post_var["TotIntRate"];
        $exclude_sundays = $post_var["exclude_sundays"];

        $penalty_amount = $post_var["penalty_amount"];

        $pay_term = $post_var["PayTerm"];

        if ($pay_term == 'biweekly')
        {
            $fixed_amount = ($loan_amount * ($fixed_amount / 100)) * ($term);
            $payment_amount = ($loan_amount + $fixed_amount) / ($term * 2);
            $fixed_rate = $fixed_amount / ($term);

            $total_amount = 0;
            $no_of_days = 0;
            $total_interest = 0;
            $total_principal = 0;

            if ($this->config->item('date_format') == 'd/m/Y')
            {
                $payment_date = strtotime(uk_to_isodate($post_var["InstallmentStarted"]));
            }
            else
            {
                $payment_date = strtotime($post_var["InstallmentStarted"]);
            }

            $data_scheds = [];
            for ($i = 1; $i <= ($term * 2); $i++)
            {
                $compound_interest = $fixed_rate / 2;
                $principal_amount = $payment_amount - $compound_interest;
                $balance_owed = $loan_amount - $principal_amount;

                $total_amount += $payment_amount;

                $tmp = [];
                $tmp["payment_date"] = date($this->config->item('date_format'), $payment_date);
                $tmp["payment_balance"] = $balance_owed;

                $tmp["penalty_amount"] = $penalty_amount;

                $tmp["interest"] = $compound_interest;
                $tmp["payment_amount"] = $payment_amount;

                $data_scheds[] = $tmp;

                $payment_date = strtotime(date('Y-m-d', $payment_date) . '+15 days');

                if ($exclude_sundays)
                {
                    $in_day = date("l", $payment_date);
                    $in_day = strtolower($in_day);

                    if ($in_day == "sunday")
                    {
                        $payment_date = strtotime(date('Y-m-d', $payment_date) . '+1 day');
                    }
                }

                $loan_amount = $balance_owed;
                $no_of_days++;
                $total_interest += $compound_interest;
                $total_principal += $principal_amount;
            }
        }
        else
        {
            $data_scheds = [];

            $fixed_amount = ($loan_amount * ($fixed_amount / 100)) * $term;
            $payment_amount = ($loan_amount + $fixed_amount) / $term;
            $fixed_rate = $fixed_amount / $term;

            $total_amount = 0;
            $no_of_days = 0;
            $total_interest = 0;
            $total_principal = 0;

            if ($this->config->item('date_format') == 'd/m/Y')
            {
                $payment_date = strtotime(uk_to_isodate($post_var["InstallmentStarted"]));
            }
            else
            {
                $payment_date = strtotime($post_var["InstallmentStarted"]);
            }

            for ($i = 1; $i <= $term; $i++)
            {

                $compound_interest = $fixed_rate;
                $principal_amount = $payment_amount - $compound_interest;
                $balance_owed = $loan_amount - $principal_amount;

                $total_amount += $payment_amount;

                $tmp = [];
                $tmp["payment_date"] = date($this->config->item('date_format'), $payment_date);
                $tmp["payment_balance"] = $balance_owed;
                $tmp["penalty_amount"] = $penalty_amount;
                $tmp["interest"] = $compound_interest;
                $tmp["payment_amount"] = $payment_amount;

                $data_scheds[] = $tmp;

                $payment_date = strtotime(date('Y-m-d', $payment_date) . '+1 ' . $pay_term);

                if ($exclude_sundays)
                {
                    $in_day = date("l", $payment_date);
                    $in_day = strtolower($in_day);

                    if ($in_day == "sunday")
                    {
                        $payment_date = strtotime(date('Y-m-d', $payment_date) . '+1 day');
                    }
                }

                $loan_amount = $balance_owed;
                $no_of_days++;
                $total_interest += $compound_interest;
                $total_principal += $principal_amount;
            }
        }

        return $data_scheds;
    }

    function calculate_percentage($post_var)
    {
        $frequency = 1;
        $term = $post_var["NoOfPayments"];
        $period = $post_var["PayTerm"];
        $penalty_amount = $post_var["penalty_amount"];

        switch ($period)
        {
            case "day":
                $frequency = 365;
                break;
            case "week":
                $frequency = 52;
                break;
            case "month":
                $frequency = 12;
                break;
            case "year":
                $frequency = 1;
                break;
        }

        $loan_amount = $post_var["ApplyAmt"];
        $interest_rate = ( $post_var["TotIntRate"] / 100) / $frequency;

        $r = (1 + $interest_rate);
        $pow = pow($r, $term);

        $data_scheds = [];

        $payment_amount = $loan_amount * (($interest_rate * $pow) / ($pow - 1));

        $total_amount = 0;
        $no_of_days = 0;
        $total_interest = 0;
        $total_principal = 0;
        for ($i = 1; $i <= $term; $i++)
        {
            $compound_interest = $loan_amount * $interest_rate;
            $principal_amount = $payment_amount - $compound_interest;
            $balance_owed = $loan_amount - $principal_amount;

            $total_amount += $payment_amount;

            if ($this->config->item('date_format') == 'd/m/Y')
            {
                $payment_date = uk_to_isodate($post_var["InstallmentStarted"]);
            }
            else
            {
                $payment_date = $post_var["InstallmentStarted"];
            }

            switch ($period)
            {
                case "day":
                    $payment_date = date($this->config->item('date_format'), strtotime($payment_date . ' +' . ($i + 1) . ' days'));
                    break;
                case "week":
                    $payment_date = date($this->config->item('date_format'), strtotime($payment_date . ' +' . ($i * 7) . ' days'));
                    break;
                case "month":
                    $payment_date = date($this->config->item('date_format'), strtotime($payment_date . ' +' . ($i + 1) . ' months'));
                    break;
                case "year":
                    $payment_date = date($this->config->item('date_format'), strtotime($payment_date . ' +' . ($i + 1) . ' years'));
                    break;
            }

            $tmp = [];
            $tmp["payment_date"] = $payment_date;
            $tmp["payment_balance"] = $balance_owed;
            $tmp["penalty_amount"] = $penalty_amount;
            $tmp["interest"] = $compound_interest;
            $tmp["payment_amount"] = $payment_amount;

            $data_scheds[] = $tmp;

            $loan_amount = $balance_owed;
            $no_of_days++;
            $total_interest += $compound_interest;
            $total_principal += $principal_amount;
        }

        return $data_scheds;
    }

    function calculate_outstanding_interest($post_var)
    {
        $term = $post_var["NoOfPayments"];
        $loan_amount = $post_var["ApplyAmt"];
        $interest_rate = $post_var["TotIntRate"];
        $period = $post_var["PayTerm"];
        $exclude_sundays = $post_var["exclude_sundays"];
        $penalty_amount = $post_var["penalty_amount"];

        $interest_amount = ( (float) ($loan_amount) * (((float) $interest_rate) / 100) );
        $principal_amount = (float) ($loan_amount) + (float) ($interest_amount);

        $payment_amount = (float) ($interest_amount) + 50;
        // Perform a loop to find the closest payment amount

        $data_scheds = [];

        $total_amount = 0;
        $no_of_days = 0;
        $total_interest = 0;
        $total_principal = 0;

        $i = 0;
        $increment_day = 0;

        do
        {
            $balance_owed = ($principal_amount - $payment_amount);

            $total_amount += $payment_amount;

            if ($this->config->item('date_format') == 'd/m/Y')
            {
                $payment_date = uk_to_isodate($post_var["InstallmentStarted"]);
            }
            else
            {
                $payment_date = $post_var["InstallmentStarted"];
            }

            switch ($period)
            {
                case "day":
                    $payment_date = date('Y-m-d', strtotime($payment_date . ' +' . ($increment_day + 1) . ' days'));
                    break;
                case "week":
                    $payment_date = date('Y-m-d', strtotime($payment_date . ' +' . ($increment_day * 7) . ' days'));
                    break;
                case "month":
                    $payment_date = date('Y-m-d', strtotime($payment_date . ' +' . ($increment_day + 1) . ' months'));
                    break;
                case "year":
                    $payment_date = date('Y-m-d', strtotime($payment_date . ' +' . ($increment_day + 1) . ' years'));
                    break;
            }

            if ($exclude_sundays)
            {
                $in_day = date("l", strtotime($payment_date));
                $in_day = strtolower($in_day);

                if ($in_day == "sunday")
                {
                    $payment_date = date('Y-m-d', strtotime(date('Y-m-d', strtotime($payment_date)) . '+1 day'));
                    $increment_day++;
                }
            }

            $tmp = [];
            $tmp["payment_date"] = date($this->config->item('date_format'), strtotime($payment_date));
            $tmp["payment_balance"] = $balance_owed;
            $tmp["penalty_amount"] = $penalty_amount;
            $tmp["interest"] = $interest_amount;
            $tmp["payment_amount"] = $payment_amount;

            $data_scheds[] = $tmp;

            $loan_amount = $balance_owed;
            $no_of_days++;
            $total_interest += $interest_amount;
            $total_principal += $principal_amount;

            $principal_amount = ((float) ($balance_owed) + ( ((float) ($balance_owed) * ((float) ($interest_rate) / 100)) ) );
            $interest_amount = ((float) ($balance_owed) * ((float) ($interest_rate) / 100));

            $increment_day++;
            $i++;
        } while ($balance_owed > 0);

        return $data_scheds;
    }

    function calculate_interest_only($post_var)
    {
        $term = $post_var["NoOfPayments"];
        $loan_amount = $post_var["ApplyAmt"];
        $interest_rate = $post_var["TotIntRate"];
        $period = $post_var["PayTerm"];
        $exclude_sundays = $post_var["exclude_sundays"];
        $penalty_amount = $post_var["penalty_amount"];

        $interest_amount = ( (float) ($loan_amount) * (((float) $interest_rate) / 100) );
        $principal_amount = (float) ($loan_amount) + (float) ($interest_amount);

        $payment_amount = (float) ($interest_amount);
        // Perform a loop to find the closest payment amount

        $data_scheds = [];

        $total_amount = 0;
        $no_of_days = 0;
        $total_interest = 0;
        $total_principal = 0;

        $i = 0;
        $increment_day = -1;

        do
        {
            $balance_owed = ($principal_amount - $payment_amount);

            $total_amount += $payment_amount;

            if ($this->config->item('date_format') == 'd/m/Y')
            {
                $payment_date = uk_to_isodate($post_var["InstallmentStarted"]);
            }
            else
            {
                $payment_date = $post_var["InstallmentStarted"];
            }

            switch ($period)
            {
                case "day":
                    $payment_date = date($this->config->item('date_format'), strtotime($payment_date . ' +' . ($increment_day + 1) . ' days'));
                    break;
                case "week":
                    $payment_date = date($this->config->item('date_format'), strtotime($payment_date . ' +' . ($increment_day * 7) . ' days'));
                    break;
                case "month":
                    $payment_date = date($this->config->item('date_format'), strtotime($payment_date . ' +' . ($increment_day + 1) . ' months'));
                    break;
                case "year":
                    $payment_date = date($this->config->item('date_format'), strtotime($payment_date . ' +' . ($increment_day + 1) . ' years'));
                    break;
            }

            if ($exclude_sundays)
            {
                $in_day = date("l", strtotime($payment_date));
                $in_day = strtolower($in_day);

                if ($in_day == "sunday")
                {
                    $payment_date = date($this->config->item('date_format'), strtotime(date($this->config->item('date_format'), strtotime($payment_date)) . '+1 day'));
                    $increment_day++;
                }
            }

            $tmp = [];
            $tmp["payment_date"] = $payment_date;
            $tmp["payment_balance"] = $balance_owed;
            $tmp["penalty_amount"] = $penalty_amount;
            $tmp["interest"] = $interest_amount;
            $tmp["payment_amount"] = $payment_amount;

            $data_scheds[] = $tmp;

            $loan_amount = $balance_owed;
            $no_of_days++;
            $total_interest += $interest_amount;
            $total_principal += $principal_amount;

            $principal_amount = ((float) ($balance_owed) + ( ((float) ($balance_owed) * ((float) ($interest_rate) / 100)) ) );
            $interest_amount = ((float) ($balance_owed) * ((float) ($interest_rate) / 100));
            $payment_amount = $interest_amount;

            $increment_day++;
            $i++;
        } while ($i < $term);

        return $data_scheds;
    }

    public function assets()
    {
        //---get working directory and map it to your module
        $file = getcwd() . '/application/modules/' . implode('/', $this->uri->segments);

        //----get path parts form extension
        $path_parts = pathinfo($file);
        //---set the type for the headers
        $file_type = strtolower($path_parts['extension']);

        if (is_file($file))
        {
            //----write propper headers
            switch ($file_type)
            {
                case 'css':
                    header('Content-type: text/css');
                    break;

                case 'js':
                    header('Content-type: text/javascript');
                    break;

                case 'json':
                    header('Content-type: application/json');
                    break;

                case 'xml':
                    header('Content-type: text/xml');
                    break;

                case 'pdf':
                    header('Content-type: application/pdf');
                    break;

                case 'woff2':
                    header('Content-type: application/font-woff2');
                    readfile($file);
                    exit;
                    break;

                case 'woff':
                    header('Content-type: application/font-woff');
                    readfile($file);
                    exit;
                    break;

                case 'ttf':
                    header('Content-type: applicaton/x-font-ttf');
                    readfile($file);
                    exit;
                    break;

                case 'jpg' || 'jpeg' || 'png' || 'gif':
                    header('Content-type: image/' . $file_type);
                    readfile($file);
                    exit;
                    break;
            }

            include $file;
        }
        else
        {
            show_404();
        }
        exit;
    }

}

?>