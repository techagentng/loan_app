<?php

class Secure_area extends CI_Controller
{
    public $allowed_modules;
    public $role_name;
    public $user_info;
    
    /*
      Controllers that are considered secure extend Secure_area, optionally a $module_id can
      be set to also check if a user can access a particular module in the system.
     */
    function __construct($module_id = null, $submodule_id = null, $index_bypass = [])
    {
        parent::__construct();

        $this->load->model('Employee');

        $this->load->model('Message');

        if ( count($index_bypass) > 0 && in_array( $this->router->fetch_method(), $index_bypass ) )
        {
            
        }
        else
        {
            if (!$this->Employee->is_logged_in())
            {
                redirect('login');
            }

            $this->user_info = $this->Employee->get_logged_in_employee_info();
            $data['allowed_modules'] = $this->Module->get_allowed_modules($this->user_info->person_id);
            
            $data["write_modules"] = false;
            if ( model_exists("Role_model") )
            {
                foreach ( $data['allowed_modules']->result() as $row )
                {
                    if ( $row->module_id == 'roles' && strtolower( $row->status_flag ) == 'active' )
                    {
                        $this->load->model('roles/Role_model');
                        $data["write_modules"] = $this->Role_model->get_write_modules( $this->user_info->role_id );
                        break;
                    }
                }
            }
            
            //load up global data
            $this->allowed_modules = ( $data['allowed_modules'] ) ? $data['allowed_modules']->result() : [];
            
            if (!$this->Employee->has_module_grant(strtolower($module_id), $this->allowed_modules))
            {
                if ( $module_id != 'home' )
                    redirect('no_access');
            }
            
            $data['user_info'] = $this->user_info;

            $data['controller_name'] = $module_id;

            $data["messages"] = $this->Message->get_notifications("mails");

            $data["alerts"] = $this->Message->get_notifications("alerts");

            $this->load->vars($data);
        }
    }

    function _remove_duplicate_cookies()
    {
        //php < 5.3 doesn't have header remove so this function will fatal error otherwise
        if (function_exists('header_remove'))
        {
            $CI = &get_instance();

            // clean up all the cookies that are set...

            $headers = headers_list();

            $cookies_to_output = array();

            $header_session_cookie = '';

            $session_cookie_name = $CI->config->item('sess_cookie_name');

            foreach ($headers as $header)
            {
                list ($header_type, $data) = explode(':', $header, 2);

                $header_type = trim($header_type);

                $data = trim($data);

                if (strtolower($header_type) == 'set-cookie')
                {

                    header_remove('Set-Cookie');

                    $cookie_value = current(explode(';', $data));

                    list ($key, $val) = explode('=', $cookie_value);

                    $key = trim($key);

                    if ($key == $session_cookie_name)
                    {
                        // OVERWRITE IT (yes! do it!)
                        $header_session_cookie = $data;

                        continue;
                    }
                    else
                    {
                        // Not a session related cookie, add it as normal. Might be a CSRF or some other cookie we are setting
                        $cookies_to_output[] = array('header_type' => $header_type, 'data' => $data);
                    }
                }
            }

            if (!empty($header_session_cookie))
            {
                $cookies_to_output[] = array('header_type' => 'Set-Cookie', 'data' => $header_session_cookie);
            }

            foreach ($cookies_to_output as $cookie)
            {
                header("{$cookie['header_type']}: {$cookie['data']}", false);
            }
        }
    }

}

?>