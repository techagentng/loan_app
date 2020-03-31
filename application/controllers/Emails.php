<?php

require_once ("Secure_area.php");
require_once ("interfaces/idata_controller.php");

class Emails extends Secure_area implements iData_controller {

    function __construct()
    {
        parent::__construct('emails');
        
        $this->load->library('DataTableLib');
    }

    function index()
    {
        $this->set_dt_templates($this->datatablelib->datatable());
        $data["tbl_templates"] = $this->datatablelib->render();
        
        $this->load->view('emails/manage', $data);
    }
    
    function ajax()
    {
        $type = $this->input->post('type');
        switch ($type)
        {
            case 1: // Get templates table
                $this->_dt_templates();
                break;
            case 2: // Archive template
                $this->delete();
                break;
        }
    }
    
    function set_dt_templates($datatable)
    {
        $datatable->add_server_params('', '', [$this->security->get_csrf_token_name() => $this->security->get_csrf_hash(), "type" => 1]);
        $datatable->ajax_url = site_url('emails/ajax');

        $datatable->add_column('actions', false);
        $datatable->add_column('template_name', false);
        $datatable->add_column('description', false);
        $datatable->add_column('created_by', false);
        

        $datatable->add_table_definition(["orderable" => false, "targets" => 0]);
        $datatable->order = [[1, 'desc']];

        $datatable->allow_search = true;
        
        $datatable->table_id = "#tbl_templates";
        $datatable->add_titles('Templates');
        $datatable->has_edit_dblclick = 0;
    }

    function _dt_templates()
    {
        $selected_user = $this->input->post("employee_id");
        $status = $this->input->post("status");

        $offset = $this->input->post("start");
        $limit = $this->input->post("length");

        $index = $this->input->post("order")[0]["column"];
        $dir = $this->input->post("order")[0]["dir"];
        $keywords = $this->input->post("search")["value"];

        $order = array("index" => $index, "direction" => $dir);
        
        $emails = $this->Email->get_all($limit, $offset, $keywords, $order);
        
        $tmp = array();

        $count_all = 0;
        foreach ($emails->result() as $email)
        {
            $actions = "<a href='" . site_url('emails/view/' . $email->email_id) . "' class='btn-xs btn-default btn btn-secondary' title='View'><span class='fa fa-eye'></span></a> ";
            $actions .= "<a href='javascript:void(0)' class='btn-xs btn-danger btn-delete btn' data-template-id='" . $email->email_id . "' title='Delete'><span class='fa fa-trash'></span></a>";

            $data_row = [];
            $data_row["DT_RowId"] = $email->email_id;
            $data_row["actions"] = $actions;
            
            $data_row["template_name"] = $email->template_name;
            $data_row["description"] = $email->descriptions;
            $data_row["created_by"] = $email->created_by;

            $tmp[] = $data_row;
            $count_all++;
        }

        $data["data"] = $tmp;
        $data["recordsTotal"] = $count_all;
        $data["recordsFiltered"] = $count_all;

        send($data);
    }

    function view($email_id = -1)
    {
        $data['email_info'] = $this->Email->get_info($email_id);
        $this->load->view("emails/form", $data);
    }
    
    function data()
    {
        $index = isset($_GET['order'][0]['column']) ? $_GET['order'][0]['column'] : 1;
        $dir = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : "asc";
        $order = array("index" => $index, "direction" => $dir);
        $length = isset($_GET['length'])?$_GET['length']:50;
        $start = isset($_GET['start'])?$_GET['start']:0;
        $key = isset($_GET['search']['value'])?$_GET['search']['value']:"";

        $emails = $this->Email->get_all($length, $start, $key, $order);

        $format_result = array();

        foreach ($emails->result() as $email)
        {
            $format_result[] = array(
                "<input type='checkbox' name='chk[]' id='email_$email->email_id' value='" . $email->email_id . "'/>",
                $email->email_id,
                $email->template_name,
                $email->descriptions,
                $email->created_by,                
                anchor('emails/view/' . $email->email_id, $this->lang->line('common_view'), array('class' => 'btn btn-success', "title" => "Update Email Templates"))
            );
        }

        $data = array(
            "recordsTotal" => $this->Email->count_all(),
            "recordsFiltered" => $this->Email->count_all(),
            "data" => $format_result
        );

        echo json_encode($data);
        exit;
    }

    function save($email_id = -1)
    {
        $email_data = array(
            'template_name' => $this->input->post('template_name'),
            'templates' => $this->input->post('hid-template'),
            'descriptions' => $this->input->post('descriptions')
        );

        if ($this->Email->save($email_data, $email_id))
        {
            //New Messages
            echo json_encode(array('success' => true, 'message' => 'You have successfully saved template'));
        }
        else//failure
        {
            echo json_encode(array('success' => false, 'message' => 'Template failed to save'));
        }
        exit;
    }

    function delete()
    {
        $email_ids = $this->input->post('ids');

        if ($this->Email->delete_list($email_ids))
        {
            echo json_encode(array('success' => true, 'message' => 'Email Templates have been successfully deleted '));
        }
        else
        {
            echo json_encode(array('success' => false, 'message' => 'Email Templates cannot be deleted'));
        }
    }

    function generate_barcodes($item_kit_ids)
    {
        $result = array();

        $item_kit_ids = explode(':', $item_kit_ids);
        foreach ($item_kit_ids as $item_kid_id)
        {
            $item_kit_info = $this->Item_kit->get_info($item_kid_id);

            $result[] = array('name' => $item_kit_info->name, 'id' => 'KIT ' . $item_kid_id);
        }

        $data['items'] = $result;
        $this->load->view("barcode_sheet", $data);
    }

    /*
      get the width for the add/edit form
     */

    function get_form_width()
    {
        return 360;
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

    public function upload_attachment()
    {
        $directory = FCPATH . 'uploads/messages-' . $_REQUEST["message_id"] . "/";
        $this->load->library('uploader');
        $data = $this->uploader->upload($directory);

        $this->Message->save_attachments($data['params']['message_id'], $data);

        $file = $this->_get_formatted_file($data['attachment_id'], $data['filename']);
        $file['message_id'] = $data['params']['message_id'];

        echo json_encode($file);
        exit;
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

}

?>