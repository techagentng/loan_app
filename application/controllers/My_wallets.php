<?php

require_once ("Secure_area.php");
require_once ("interfaces/idata_controller.php");

class My_wallets extends Secure_area implements iData_controller {

    function __construct()
    {
        parent::__construct('my_wallets');
        
        $this->load->library('DataTableLib');
    }

    function index()
    {
        $data['controller_name'] = strtolower(get_class());
        $data['form_width'] = $this->get_form_width();
        
        $res = $this->Employee->getLowerLevels();
        $data['staffs'] = $res;
        
        $data["wallet_total"] = $this->My_wallet->get_total();
        
        $this->set_dt_wallets($this->datatablelib->datatable());
        $data["tbl_wallets"] = $this->datatablelib->render();      
        
        $this->load->view('my_wallets/manage', $data);
    }
    function ajax()
    {
        $type = $this->input->post('type');
        switch ($type)
        {
            case 1: // Get my wallet table
                $this->_dt_wallets();
                break;
            case 2: // Delete wallet
                $this->delete();
                break;
            
        }
    }
    
    function set_dt_wallets($datatable)
    {
        $datatable->add_server_params('', '', [$this->security->get_csrf_token_name() => $this->security->get_csrf_hash(), "type" => 1]);
        $datatable->ajax_url = site_url('my_wallets/ajax');

        $datatable->add_column('actions', false);
        $datatable->add_column('amount', false);
        $datatable->add_column('description', false);
        $datatable->add_column('wallet_type', false);
        $datatable->add_column('trans_date', false);
        

        $datatable->add_table_definition(["orderable" => false, "targets" => 0]);
        $datatable->order = [[1, 'desc']];

        $datatable->allow_search = true;
        
        $datatable->table_id = "#tbl_wallets";
        $datatable->add_titles('My wallets');
        $datatable->has_edit_dblclick = 0;
    }

    function _dt_wallets()
    {
        $selected_user = $this->input->post("employee_id");
        $status = $this->input->post("status");

        $offset = $this->input->post("start");
        $limit = $this->input->post("length");

        $index = $this->input->post("order")[0]["column"];
        $dir = $this->input->post("order")[0]["dir"];
        $keywords = $this->input->post("search")["value"];

        $order = array("index" => $index, "direction" => $dir);
        
        
        $wallets = $this->My_wallet->get_all($limit, $offset, $keywords, $order);
        $user_id = $this->Employee->get_logged_in_employee_info()->person_id;

        $tmp = array();

        $count_all = 0;
        foreach ($wallets->result() as $wallet)
        {
            if($wallet->wallet_type === "transfer")
            {
                $employee = $this->Employee->get_info($wallet->transfer_to);                
                $wallet_type = "transfer to ".($employee->person_id === $user_id?"me":$employee->first_name." ".$employee->last_name);
            }
            else
            {
                $wallet_type = $wallet->wallet_type;
            }
            
            $actions = "<a href='javascript:void(0)' class='btn-xs btn-danger btn-delete btn' data-wallet-id='" . $wallet->wallet_id . "' title='Delete'><span class='fa fa-trash'></span></a>";

            $data_row = [];
            $data_row["DT_RowId"] = $wallet->wallet_id;
            $data_row["actions"] = $actions;
            
            $data_row["amount"] = to_currency($wallet->amount, 1);
            $data_row["description"] = $wallet->descriptions;
            $data_row["wallet_type"] = ucwords($wallet_type);
            $data_row["trans_date"] = date($this->config->item("date_format"),$wallet->trans_date);
            

            $tmp[] = $data_row;
            $count_all++;
        }

        $data["data"] = $tmp;
        $data["recordsTotal"] = $count_all;
        $data["recordsFiltered"] = $count_all;

        send($data);
    }

    function view($wallet_id = -1)
    {
        $data['wallet_info'] = $this->My_wallet->get_info($wallet_id);
        $data['wallet_types'] = array("debit" => "debit", "credit" => "credit", "transfer" => "transfer");
        $people = $this->Employee->get_all();
        
        $tmp = array(0=>"Please Select");
        foreach ($people->result() as $person)
        {
            $tmp[$person->person_id] = $person->first_name." ".$person->last_name;
        }
        $data["all_users"] = $tmp;
        $this->load->view("my_wallets/form", $data);
    }

    function save($wallet_id = -1)
    {
        $wallet_data = array(
            'amount' => $this->input->post('amount'),
            'descriptions' => $this->input->post('description'),
            'wallet_type' => $this->input->post('wallet_type'),            
            'transfer_to' => $this->input->post('transfer_to'),
            'trans_date' => time()
        );

        if ($this->My_wallet->save($wallet_data, $wallet_id))
        {
            $wallet_total = $this->My_wallet->get_total();
            $wallet_amount = to_currency($wallet_total);
            //New wallet
            if ($wallet_id == -1)
            {
                echo json_encode(array('success' => true, 'message' => $this->lang->line('my_wallet_successful_adding') . ' ' .
                    $wallet_data['amount'], 'wallet_id' => $wallet_data['wallet_id'], "wallet_amount" => $wallet_amount, "wallet_total" => $wallet_total));
                $wallet_id = $wallet_data['wallet_id'];
            }
            else //previous item
            {
                echo json_encode(array('success' => true, 'message' => $this->lang->line('my_wallet_successful_updating') . ' ' .
                    $wallet_data['amount'], 'wallet_id' => $wallet_id, "wallet_amount" => $wallet_amount, "wallet_total" => $wallet_total));
            }
        }
        else//failure
        {
            echo json_encode(array('success' => false, 'message' => $this->lang->line('my_wallet_error_adding_updating') . ' ' .
                $wallet_data['amount'], 'wallet_id' => -1));
        }
    }

    function delete()
    {
        $my_wallet_to_delete = $this->input->post('ids');

        if ($this->My_wallet->delete_list($my_wallet_to_delete))
        {
            $wallet_total = $this->My_wallet->get_total();
            $wallet_amount = to_currency($wallet_total);
            echo json_encode(array('success' => true, 'message' => $this->lang->line('my_wallet_successful_deleted') . ' ' .
                count($my_wallet_to_delete) . ' ' . $this->lang->line('my_wallet_one_or_multiple'), "wallet_amount" => $wallet_amount, "wallet_total" => $wallet_total));
        }
        else
        {
            echo json_encode(array('success' => false, 'message' => $this->lang->line('my_wallet_cannot_be_deleted')));
        }
    }

    /*
      get the width for the add/edit form
     */

    function get_form_width()
    {
        return 360;
    }

    function data()
    {
        $index = isset($_GET['order'][0]['column']) ? $_GET['order'][0]['column'] : 1;
        $dir = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : "asc";
        $order = array("index" => $index, "direction" => $dir);
        $length = isset($_GET['length'])?$_GET['length']:50;
        $start = isset($_GET['start'])?$_GET['start']:0;
        $key = isset($_GET['search']['value'])?$_GET['search']['value']:"";

        $wallets = $this->My_wallet->get_all($length, $start, $key, $order);
        $user_id = $this->Employee->get_logged_in_employee_info()->person_id;
        
        $format_result = array();

        foreach ($wallets->result() as $wallet)
        {
            if($wallet->wallet_type === "transfer")
            {
                $employee = $this->Employee->get_info($wallet->transfer_to);                
                $wallet_type = "transfer to ".($employee->person_id === $user_id?"me":$employee->first_name." ".$employee->last_name);
            }
            else
            {
                $wallet_type = $wallet->wallet_type;
            }
            
            $format_result[] = array(
                "<input type='checkbox' name='chk[]' class='select_' id='my_wallet_$wallet->wallet_id' value='" . $wallet->wallet_id . "'/>",
                $wallet->wallet_id,
                $wallet->amount,
                $wallet->descriptions,
                ucwords($wallet_type),
                date("m/d/Y",$wallet->trans_date),
                anchor('my_wallets/view/' . $wallet->wallet_id, $this->lang->line('common_edit'), array('class' => 'modal_link btn btn-success', 'data-toggle' => 'modal', 'data-target' => '#wallet_modal', "title" => "Update Record"))
            );
        }

        $data = array(
            "recordsTotal" => $this->My_wallet->count_all(),
            "recordsFiltered" => $this->My_wallet->count_all(),
            "data" => $format_result
        );

        echo json_encode($data);
        exit;
    }

    public function get_row()
    {
        
    }

    public function search()
    {
        
    }

    public function suggest()
    {
        
    }

}

?>