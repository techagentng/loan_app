<?php

require_once ("Secure_area.php");
require_once ("interfaces/idata_controller.php");

class Overdues extends Secure_area implements iData_controller {

    function __construct()
    {
        parent::__construct('overdues');
    }

    function index()
    {
        $data['controller_name'] = strtolower(get_class());
        $data['form_width'] = $this->get_form_width();

        $res = $this->Employee->getLowerLevels();
        $data['staffs'] = $res;
        
        $this->load->library('DataTableLib');

        $this->set_dt_transactions($this->datatablelib->datatable());
        $data["tbl_loan_transactions"] = $this->datatablelib->render();
        $this->load->view('loans/overdue_list', $data);
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
//        $datatable->fixedColumns = true;
//        $datatable->leftColumns = 3;
//        $datatable->scrollX = true;
        $datatable->dt_height = '350px';

        $datatable->table_id = "#tbl_loans_transactions";
        $datatable->add_titles('leads');
        $datatable->has_edit_dblclick = 0;
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

    function delete()
    {
        $payments_to_delete = $this->input->post('ids');

        if ($this->Payment->delete_list($payments_to_delete))
        {
            echo json_encode(array('success' => true, 'message' => $this->lang->line('loans_successful_deleted') . ' ' .
                count($payments_to_delete) . ' ' . $this->lang->line('payments_one_or_multiple')));
        }
        else
        {
            echo json_encode(array('success' => false, 'message' => $this->lang->line('payments_cannot_be_deleted')));
        }
    }

    /*
      get the width for the add/edit form
     */

    function get_form_width()
    {
        return 360;
    }

    public function save($data_item_id = -1)
    {
        
    }

    public function view($data_item_id = -1)
    {
        
    }

}

?>