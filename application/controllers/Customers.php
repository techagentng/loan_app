<?php

require_once ("Person_controller.php");

class Customers extends Person_controller {

    function __construct()
    {
        parent::__construct('customers');
        
        $this->load->library('DataTableLib');
    }

    function index()
    {
        $res = $this->Employee->getLowerLevels();
        $data['staffs'] = $res;

        $data['controller_name'] = strtolower(get_class());
        
        $this->set_dt_borrowers($this->datatablelib->datatable());
        $data["tbl_borrowers"] = $this->datatablelib->render();        
        
        $this->load->view('customers/list', $data);
    }
    
    function ajax()
    {
        $type = $this->input->post('type');
        switch ($type)
        {
            case 1: // Get customers table
                $this->_dt_borrowers();
                break;
            case 2: // Archive customer
                $this->delete();
                break;
            
        }
    }
    
    function set_dt_borrowers($datatable)
    {
        $datatable->add_server_params('', '', [$this->security->get_csrf_token_name() => $this->security->get_csrf_hash(), "type" => 1]);
        $datatable->ajax_url = site_url('customers/ajax');

        $datatable->add_column('actions', false);
        $datatable->add_column('last_name', false);
        $datatable->add_column('first_name', false);
        $datatable->add_column('email', false);
        $datatable->add_column('phone_number', false);
        

        $datatable->add_table_definition(["orderable" => false, "targets" => 0]);
        $datatable->order = [[1, 'desc']];

        $datatable->allow_search = true;
        $datatable->no_expand_height = true;
        
        $datatable->table_id = "#tbl_borrowers";
        $datatable->add_titles('Borrowers');
        $datatable->has_edit_dblclick = 0;
    }

    function _dt_borrowers()
    {
        $selected_user = $this->input->post("employee_id");
        $status = $this->input->post("status");

        $offset = $this->input->post("start");
        $limit = $this->input->post("length");

        $index = $this->input->post("order")[0]["column"];
        $dir = $this->input->post("order")[0]["dir"];
        $keywords = $this->input->post("search")["value"];

        $order = array("index" => $index, "direction" => $dir);
        
        $people = $this->Customer->get_all($limit, $offset, $keywords, $order, $selected_user);

        $tmp = array();

        $count_all = 0;
        foreach ($people->result() as $person)
        {
            $actions = "<a href='" . site_url('customers/view/' . $person->person_id) . "' class='btn btn-xs btn-default btn-secondary' title='View'><span class='fa fa-eye'></span></a> ";
            $actions .= "<a href='javascript:void(0)' class='btn-xs btn-danger btn-delete btn' data-customer-id='" . $person->person_id . "' title='Delete'><span class='fa fa-trash'></span></a>";

            $data_row = [];
            $data_row["DT_RowId"] = $person->person_id;
            $data_row["actions"] = $actions;
            
            $data_row["last_name"] = $person->last_name;
            $data_row["first_name"] = $person->first_name;
            $data_row["email"] = $person->email;
            $data_row["phone_number"] = $person->phone_number;

            $tmp[] = $data_row;
            $count_all++;
        }

        $data["data"] = $tmp;
        $data["recordsTotal"] = $count_all;
        $data["recordsFiltered"] = $count_all;

        send($data);
    }

    /*
      Returns customer table data rows. This will be called with AJAX.
     */

    function search()
    {
        $search = $this->input->post('search');
        $data_rows = get_people_manage_table_data_rows($this->Customer->search($search), $this);
        echo $data_rows;
    }

    /*
      Gives search suggestions based on what is being searched for
     */

    function suggest()
    {
        //$suggestions = $this->Customer->get_search_suggestions($this->input->post('q'), $this->input->post('limit'));
        $suggestions = $this->Customer->get_search_suggestions($this->input->post('query'), 30);
        //echo implode("\n", $suggestions);

        $data = $tmp = array();

        foreach ($suggestions as $suggestion):
            $t = explode("|", $suggestion);
            $tmp = array("value" => $t[1], "data" => $t[0]);
            $data[] = $tmp;
        endforeach;

        echo json_encode(array("suggestions" => $data));
        exit;
    }

    /*
      Loads the customer edit form
     */

    function view($customer_id = -1)
    {
        $data['person_info'] = $this->Customer->get_info($customer_id);

        $financial_infos = "";
        if (isset($data['person_info']->income_sources))
        {
            $financial_infos = json_decode($data['person_info']->income_sources, true);
        }

        $tmp = array();

        if (is_array($financial_infos))
        {
            foreach ($financial_infos as $financial_info):
                if ($financial_info !== '=')
                {
                    $tmp[] = explode("=", $financial_info);
                }
            endforeach;
        }

        if (count($tmp) === 0)
        {
            $tmp[] = array("", "");
        }

        $attachments = $this->Customer->get_attachments($customer_id);

        $file = array();
        foreach ($attachments as $attachment)
        {
            $file[] = $this->_get_formatted_file($attachment->attachment_id, $attachment->filename);
        }

        $data['attachments'] = $file;

        $data['customer_id'] = $customer_id;
        $data['financial_infos'] = $tmp;

        $this->load->view("customers/form", $data);
    }
    
    private function _get_formatted_file($id, $filename)
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
        }
        else if (strtolower($ext) === "pdf")
        {
            $tmp['icon'] = "images/pdf-filetype.jpg";
            $tmp['filename'] = $filename;
            $tmp['id'] = $id;
        }
        else if (in_array(strtolower($ext), $xls))
        {
            $tmp['icon'] = "images/xls-filetype.jpg";
            $tmp['filename'] = $filename;
            $tmp['id'] = $id;
        }
        else
        {
            $tmp['icon'] = "images/image-filetype.jpg";
            $tmp['filename'] = $filename;
            $tmp['id'] = $id;
        }

        return $tmp;
    }

    /*
      Inserts/updates a customer
     */

    function save($customer_id = -1)
    {
        $person_data = array(
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'email' => $this->input->post('email'),
            'phone_number' => $this->input->post('phone_number'),
            'address_1' => $this->input->post('address_1'),
            'address_2' => $this->input->post('address_2'),
            'city' => $this->input->post('city'),
            'state' => $this->input->post('state'),
            'zip' => $this->input->post('zip'),
            'country' => $this->input->post('country'),
            'comments' => $this->input->post('comments')
        );

        $customer_data = array(
            'account_number' => $this->input->post('account_number') == '' ? null : $this->input->post('account_number'),
            'taxable' => $this->input->post('taxable') == '' ? 0 : 1,
        );

        if (is_array($this->input->post("sources")))
        {
            $income_sources = array();
            $i = 0;
            foreach ($this->input->post("sources") as $sources)
            {
                $tmp = $this->input->post("values");
                $income_sources[] = $sources . "=" . $tmp[$i];
                $i++;
            }
        }

        $financial_data = array(
            "financial_status_id" => $this->input->post("financial_status_id") > 0 ? $this->input->post("financial_status_id") : 0,
            "income_sources" => json_encode($income_sources)
        );

        if ($this->Customer->save($person_data, $customer_data, $customer_id, $financial_data))
        {
            //New customer
            if ($customer_id == -1)
            {
                echo json_encode(array('success' => true, 'message' => $this->lang->line('customers_successful_adding') . ' ' .
                    $person_data['first_name'] . ' ' . $person_data['last_name'], 'person_id' => $customer_data['person_id']));
            }
            else //previous customer
            {
                echo json_encode(array('success' => true, 'message' => $this->lang->line('customers_successful_updating') . ' ' .
                    $person_data['first_name'] . ' ' . $person_data['last_name'], 'person_id' => $customer_id));
            }
        }
        else//failure
        {
            echo json_encode(array('success' => false, 'message' => $this->lang->line('customers_error_adding_updating') . ' ' .
                $person_data['first_name'] . ' ' . $person_data['last_name'], 'person_id' => -1));
        }
    }

    /*
      This deletes customers from the customers table
     */

    function delete()
    {
        $customers_to_delete = $this->input->post('ids');

        if ($this->Customer->delete_list($customers_to_delete))
        {
            echo json_encode(array('success' => true, 'message' => $this->lang->line('customers_successful_deleted') . ' ' .
                count($customers_to_delete) . ' ' . $this->lang->line('customers_one_or_multiple')));
        }
        else
        {
            echo json_encode(array('success' => false, 'message' => $this->lang->line('customers_cannot_be_deleted')));
        }
    }

    function excel()
    {
        $data = file_get_contents("import_customers.csv");
        $name = 'import_customers.csv';
        force_download($name, $data);
    }

    function excel_import()
    {
        $this->load->view("customers/excel_import", null);
    }

    function do_excel_import()
    {
        $msg = 'do_excel_import';
        $failCodes = array();
        if ($_FILES['file_path']['error'] != UPLOAD_ERR_OK)
        {
            $msg = $this->lang->line('items_excel_import_failed');
            echo json_encode(array('success' => false, 'message' => $msg));
            return;
        }
        else
        {
            if (($handle = fopen($_FILES['file_path']['tmp_name'], "r")) !== FALSE)
            {
                //Skip first row
                fgetcsv($handle);

                $i = 1;
                while (($data = fgetcsv($handle)) !== FALSE)
                {
                    $person_data = array(
                        'first_name' => $data[0],
                        'last_name' => $data[1],
                        'email' => $data[2],
                        'phone_number' => $data[3],
                        'address_1' => $data[4],
                        'address_2' => $data[5],
                        'city' => $data[6],
                        'state' => $data[7],
                        'zip' => $data[8],
                        'country' => $data[9],
                        'comments' => $data[10]
                    );

                    $customer_data = array(
                        'account_number' => $data[11] == '' ? null : $data[11],
                        'taxable' => $data[12] == '' ? 0 : 1,
                    );

                    if (!$this->Customer->save($person_data, $customer_data))
                    {
                        $failCodes[] = $i;
                    }

                    $i++;
                }
            }
            else
            {
                echo json_encode(array('success' => false, 'message' => 'Your upload file has no data or not in supported format.'));
                return;
            }
        }

        $success = true;
        if (count($failCodes) > 1)
        {
            $msg = "Most customers imported. But some were not, here is list of their CODE (" . count($failCodes) . "): " . implode(", ", $failCodes);
            $success = false;
        }
        else
        {
            $msg = "Import Customers successful";
        }

        echo json_encode(array('success' => $success, 'message' => $msg));
    }

    /*
      get the width for the add/edit form
     */

    function get_form_width()
    {
        return 350;
    }

    function data()
    {
        $sel_user = $this->input->get("employee_id");
        $index = isset($_GET['order'][0]['column']) ? $_GET['order'][0]['column'] : 1;
        $dir = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : "asc";
        $order = array("index" => $index, "direction" => $dir);
        $length = isset($_GET['length'])?$_GET['length']:50;
        $start = isset($_GET['start'])?$_GET['start']:0;
        $key = isset($_GET['search']['value'])?$_GET['search']['value']:"";

        $people = $this->Customer->get_all($length, $start, $key, $order, $sel_user);

        $format_result = array();

        foreach ($people->result() as $person)
        {
            $format_result[] = array(
                "<input type='checkbox' name='chk[]' id='person_$person->person_id' value='" . $person->person_id . "'/>",
                $person->last_name,
                $person->first_name,
                $person->email,
                $person->phone_number,
                anchor('customers/view/' . $person->person_id, $this->lang->line('common_view'), array('class' => 'btn btn-success', "title" => "Update Customer"))
            );
        }

        $data = array(
            "recordsTotal" => $this->Customer->count_all(),
            "recordsFiltered" => $this->Customer->count_all(),
            "data" => $format_result
        );

        echo json_encode($data);
        exit;
    }
    
    function upload_profile_pic()
    {
        $directory = FCPATH . 'uploads/profile-' . $_REQUEST["user_id"] . "/";
        $this->load->library('uploader');
        $data = $this->uploader->upload($directory);

        $this->Customer->save_profile_pic($data['params']['user_id'], $data);

        $return = [
            "status" => "OK", 
            "token_hash" => $this->security->get_csrf_hash()
        ];
        echo json_encode($return);
        exit;
    }
    
    function upload_attachment()
    {
        $directory = FCPATH . 'uploads/customer-' . $_REQUEST["customer_id"] . "/";
        $this->load->library('uploader');
        $data = $this->uploader->upload($directory);

        $this->Customer->save_attachments($data['params']['customer_id'], $data);

        $file = $this->_get_formatted_file($data['attachment_id'], $data['filename']);
        $file['customer_id'] = $data['params']['customer_id'];
        $file['token_hash'] = $this->security->get_csrf_hash();

        echo json_encode($file);
        exit;
    }
    
    function remove_file()
    {
        $file_id = $this->input->post("file_id");
        $return = array(
            "status" => $this->Customer->remove_file($file_id),
            "token_hash" => $this->security->get_csrf_hash()
        );
        echo json_encode($return);
        exit;
    }

    function customer_search()
    {
        $suggestions = $this->Customer->get_customer_search_suggestions($this->input->get('query'), 30);
        $data = $tmp = array();

        foreach ($suggestions as $suggestion):
            $t = explode("|", $suggestion);
            $tmp = array("value" => $t[1], "data" => $t[0], "email" => $t[2]);
            $data[] = $tmp;
        endforeach;

        echo json_encode(array("suggestions" => $data));
        exit;
    }
}

?>