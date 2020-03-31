<?php

require_once ("Secure_area.php");
require_once ("interfaces/idata_controller.php");

class Messages extends Secure_area implements iData_controller {

    function __construct()
    {
        parent::__construct('Messages');
        
        $this->load->library('DataTableLib');
    }

    function index()
    {
        redirect('messages/inbox');
    }

    function inbox()
    {
        $this->set_dt_inbox($this->datatablelib->datatable());
        $data["tbl_inbox"] = $this->datatablelib->render();
        
        $this->load->view('messages/inbox', $data);
    }
    
    function ajax()
    {
        $type = $this->input->post('type');
        switch ($type)
        {
            case 1: // Get inbox table
                $this->_dt_inbox();
                break;
            case 2: // Archive inbox
                $this->delete();
                break;
            case 3: // Get outbox table
                $this->_dt_outbox();
                break;
            
        }
    }
    
    function set_dt_inbox($datatable)
    {
        $datatable->add_server_params('', '', [$this->security->get_csrf_token_name() => $this->security->get_csrf_hash(), "type" => 1]);
        $datatable->ajax_url = site_url('messages/ajax');

        $datatable->add_column('actions', false);
        $datatable->add_column('subject', false);
        $datatable->add_column('sender_name', false);
        $datatable->add_column('date', false);
        

        $datatable->add_table_definition(["orderable" => false, "targets" => 0]);
        $datatable->order = [[1, 'desc']];

        $datatable->allow_search = true;
        
        $datatable->table_id = "#tbl_inbox";
        $datatable->add_titles('Inbox');
        $datatable->has_edit_dblclick = 0;
    }

    function _dt_inbox()
    {
        $selected_user = $this->input->post("employee_id");
        $status = $this->input->post("status");

        $offset = $this->input->post("start");
        $limit = $this->input->post("length");

        $index = $this->input->post("order")[0]["column"];
        $dir = $this->input->post("order")[0]["dir"];
        $keywords = $this->input->post("search")["value"];

        $order = array("index" => $index, "direction" => $dir);
        
        $messages = $this->Message->get_all_inbox($limit, $offset, $keywords, $order);
        
        $tmp = array();

        $count_all = 0;
        foreach ($messages->result() as $message)
        {
            $read = '';
            
            if ( $message->mark_as_read == '1' )
            {
                $read = 'read';
            }
            
            $actions = "<a href='" . site_url('messages/view/' . $message->message_id) . "' class='btn btn-secondary btn-xs btn-default select_ $read' title='View'><span class='fa fa-eye'></span></a> ";
            $actions .= "<a href='javascript:void(0)' class='btn-xs btn-danger btn-delete btn' data-message-id='" . $message->message_id . "' title='Delete'><span class='fa fa-trash'></span></a>";

            $data_row = [];
            $data_row["DT_RowId"] = $message->message_id;
            $data_row["actions"] = $actions;
            
            $data_row["subject"] = $message->header;
            $data_row["sender_name"] = $message->first_name . " " . $message->last_name;
            $data_row["date"] = date('m/d/Y', strtotime($message->send_date));

            $tmp[] = $data_row;
            $count_all++;
        }

        $data["data"] = $tmp;
        $data["recordsTotal"] = $count_all;
        $data["recordsFiltered"] = $count_all;

        send($data);
    }
    
    function outbox()
    {
        $this->set_dt_outbox($this->datatablelib->datatable());
        $data["tbl_outbox"] = $this->datatablelib->render();
        
        $this->load->view('messages/outbox', $data);
    }
    
    function set_dt_outbox($datatable)
    {
        $datatable->add_server_params('', '', [$this->security->get_csrf_token_name() => $this->security->get_csrf_hash(), "type" => 3]);
        $datatable->ajax_url = site_url('messages/ajax');

        $datatable->add_column('actions', false);
        $datatable->add_column('subject', false);
        $datatable->add_column('recipient', false);
        $datatable->add_column('date', false);
        

        $datatable->add_table_definition(["orderable" => false, "targets" => 0]);
        $datatable->order = [[1, 'desc']];

        $datatable->allow_search = true;
        
        $datatable->table_id = "#tbl_outbox";
        $datatable->add_titles('Outbox');
        $datatable->has_edit_dblclick = 0;
    }

    function _dt_outbox()
    {
        $selected_user = $this->input->post("employee_id");
        $status = $this->input->post("status");

        $offset = $this->input->post("start");
        $limit = $this->input->post("length");

        $index = $this->input->post("order")[0]["column"];
        $dir = $this->input->post("order")[0]["dir"];
        $keywords = $this->input->post("search")["value"];

        $order = array("index" => $index, "direction" => $dir);
        
        $messages = $this->Message->get_all_outbox($limit, $offset, $keywords, $order);
        
        $tmp = array();

        $count_all = 0;
        foreach ($messages->result() as $message)
        {
            $actions = "<a href='" . site_url('messages/view/' . $message->message_id) . "' class='btn-xs btn-default btn btn-secondary' title='View'><span class='fa fa-eye'></span></a> ";
            $actions .= "<a href='javascript:void(0)' class='btn-xs btn-danger btn-delete btn' data-message-id='" . $message->message_id . "' title='Delete'><span class='fa fa-trash'></span></a>";

            $data_row = [];
            $data_row["DT_RowId"] = $message->message_id;
            $data_row["actions"] = $actions;
            
            $data_row["subject"] = $message->header;
            $data_row["recipient"] = $message->first_name . " " . $message->last_name;
            $data_row["date"] = date('m/d/Y', strtotime($message->send_date));

            $tmp[] = $data_row;
            $count_all++;
        }

        $data["data"] = $tmp;
        $data["recordsTotal"] = $count_all;
        $data["recordsFiltered"] = $count_all;

        send($data);
    }

    function view($message_id = -1)
    {
        if ( $message_id > 0 )
        {
            $update_data = ["mark_as_read" => 1];
            $this->Message->save($update_data, $message_id);
        }
        
        $data['message_info'] = $this->Message->get_info($message_id);
        $this->load->view("messages/form", $data);
    }
    
    function view_outbox($message_id = -1)
    {
        $data['message_info'] = $this->Message->get_info($message_id);
        $this->load->view("messages/form", $data);
    }

    function save($message_id = -1)
    {
        $user_id = $this->Employee->get_logged_in_employee_info()->person_id;
        $recipient_name = $this->input->post("recipient");
        $recipient_email = $this->input->post("recipient_email");
        
        $message_data = array(
            'recipient_id' => $this->input->post('hid_recipient_id'),            
            'header' => $this->input->post('subject'),
            'body' => $this->input->post('message'),
            'send_date' => date('Y-m-d'),
            'sender_id' => $user_id
        );

        if ($this->Message->save($message_data))
        {
            //New Messages
            echo json_encode(array('success' => true, 'message' => 'You have successfully sent a message to ' .
                $recipient_name, 'message_id' => $message_data['message_id']));
            
            $params["placeholders"] = [
                "recipient_name" => ucwords($recipient_name),
                "message" => $message_data["body"]
            ];
            
            $params["to"] = $recipient_email;
            $params["subject"] = "RAMFG Notification: ".$message_data["header"];
            
            // send email here            
            $this->load->library("email_lib");
            $this->email_lib->send("message_notify", $params);
        }
        else//failure
        {
            echo json_encode(array('success' => false, 'message' => 'Message sending failed to ' .
                $recipient_name, 'message_id' => -1));
        }
        exit;
    }

    function delete()
    {
        $messages_to_delete = $this->input->post('ids');

        if ($this->Message->delete_list($messages_to_delete))
        {
            echo json_encode(array('success' => true, 'message' => $this->lang->line('message_successful_deleted') . ' ' .
                count($messages_to_delete) . ' ' . $this->lang->line('message_one_or_multiple')));
        }
        else
        {
            echo json_encode(array('success' => false, 'message' => $this->lang->line('message_cannot_be_deleted')));
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

    function data_inbox()
    {
        $index = isset($_GET['order'][0]['column']) ? $_GET['order'][0]['column'] : 1;
        $dir = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : "asc";
        $order = array("index" => $index, "direction" => $dir);
        $length = isset($_GET['length']) ? $_GET['length'] : 50;
        $start = isset($_GET['start']) ? $_GET['start'] : 0;
        $key = isset($_GET['search']['value']) ? $_GET['search']['value'] : "";

        $messages = $this->Message->get_all_inbox($length, $start, $key, $order);

        $format_result = array();

        foreach ($messages->result() as $message)
        {
            $read = '';
            
            if ( $message->mark_as_read == '1' )
            {
                $read = 'read';
            }
            
            $format_result[] = array(
                "<input type='checkbox' name='chk[]' class='select_ $read' id='message_$message->message_id' value='" . $message->message_id . "'/>",
                $message->header,
                $message->first_name . " " . $message->last_name,
                date('m/d/Y', strtotime($message->send_date)),
                anchor('messages/view/' . $message->message_id, $this->lang->line('common_view'), array('class' => 'btn btn-success btn-xs'))
            );
        }

        $data = array(
            "recordsTotal" => $this->Message->count_all_inbox(),
            "recordsFiltered" => $this->Message->count_all_inbox(),
            "data" => $format_result
        );

        echo json_encode($data);
        exit;
    }
    
    function data_outbox()
    {
        $index = isset($_GET['order'][0]['column']) ? $_GET['order'][0]['column'] : 1;
        $dir = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : "asc";
        $order = array("index" => $index, "direction" => $dir);
        $length = isset($_GET['length']) ? $_GET['length'] : 50;
        $start = isset($_GET['start']) ? $_GET['start'] : 0;
        $key = isset($_GET['search']['value']) ? $_GET['search']['value'] : "";

        $messages = $this->Message->get_all_outbox($length, $start, $key, $order);

        $format_result = array();

        foreach ($messages->result() as $message)
        {
            $format_result[] = array(
                "<input type='checkbox' name='chk[]' class='select_' id='message_$message->message_id' value='" . $message->message_id . "'/>",
                $message->header,
                $message->first_name . " " . $message->last_name,
                date('m/d/Y',strtotime($message->send_date)),
                anchor('messages/view_outbox/' . $message->message_id, $this->lang->line('common_view'), array('class' => 'btn btn-success btn-xs'))
            );
        }

        $data = array(
            "recordsTotal" => $this->Message->count_all_outbox(),
            "recordsFiltered" => $this->Message->count_all_outbox(),
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