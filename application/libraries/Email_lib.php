<?php

class Email_lib {

    var $template_name = "";

    function send($template_name, $params = null)
    {
        $CI = &get_instance();
        $CI->load->model("email");

        $template = $CI->email->get_template($template_name);
        
        if (!$template) return;
        
        $smtp_info = $CI->email->get_smtp_info();      
        
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => $smtp_info->smtp_host,
            'smtp_port' => $smtp_info->smtp_port,
            'smtp_user' => $smtp_info->smtp_user,
            'smtp_pass' => $smtp_info->smtp_pass,
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'wordwrap' => TRUE
        ];

        if (array_key_exists("placeholders", $params))
        {
            $template = $this->_set_placeholder($template, $params["placeholders"]);
        }

        $CI->load->library("email", $config);
        $CI->email->from("support@ramfg.com.au");
        $CI->email->to($params["to"]);
        $CI->email->subject($params["subject"]);
        $CI->email->message($template);

        if (!$CI->email->send())
        {
            echo $CI->email->print_debugger();
        }        
    }

    private function _set_placeholder($str, $placeholders)
    {
        foreach ($placeholders as $key => $value)
        {
            $str = str_replace("{".$key."}", $value, $str);
        }
        
        return $str;
    }
}

?>
