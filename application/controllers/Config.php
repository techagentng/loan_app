<?php

require_once ("Secure_area.php");

class Config extends Secure_area {

    function __construct()
    {
        parent::__construct('config');
    }

    function index()
    {
        $data["smtp_info"] = $this->Email->get_smtp_info();
        $this->load->view("config", $data);
    }

    function save()
    {
        $batch_save_data = array(
            'company' => $this->input->post('company'),
            'address' => $this->input->post('address'),
            'phone' => $this->input->post('phone'),
            'email' => $this->input->post('email'),
            'fax' => $this->input->post('fax'),
            'website' => $this->input->post('website'),
            'currency_symbol' => $this->input->post('currency_symbol'),
            'currency_side' => $this->input->post('currency_side'),
            'language' => 'en',
            'language_used' => $this->input->post('language_used'),
            'timezone' => $this->input->post('timezone'),
            'date_format' => $this->input->post('date_format'),
        );
        
        $smtp_data = [            
            "smtp_id" => $this->input->post("smtp_id"),
            "smtp_host" => $this->input->post("smtp_host"),
            "smtp_port" => $this->input->post("smtp_port"),
            "smtp_user" => $this->input->post("smtp_user"),
            "smtp_pass" => $this->input->post("smtp_pass"),
        ]; 

        if ($this->Appconfig->batch_save($batch_save_data))
        {
            // saving SMTP settings             
            $this->Email->save_smtp($smtp_data);
            echo json_encode(array('success' => true, 'message' => $this->lang->line('config_saved_successfully')));
        }
    }
    
    function upload()
    {
        $directory = FCPATH . "uploads/logo/";
        $this->load->library('uploader');
        $data = $this->uploader->upload($directory);

        $this->Appconfig->save("logo", $data['filename']);
        $data['company_name'] = strtolower(preg_replace('/\s+/', '', $this->config->item('company')));

        echo json_encode($data);
        exit;
    }

}

?>