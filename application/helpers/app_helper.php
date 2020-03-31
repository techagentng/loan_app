<?php

function getDomain()
{
    $CI = & get_instance();
    return str_replace("index.php", "", site_url());
}

function get_last_payment_date($loan_id)
{
    $ci = & get_instance();
    $ci->db->where("loan_id", $loan_id);
    $ci->db->where("date_paid > ", 0);
    $ci->db->order_by("loan_payment_id", "desc");
    $ci->db->limit(1);
    $query = $ci->db->get("loan_payments");

    if ($query->num_rows() > 0)
    {
        return date($ci->config->item('date_format'), $query->row()->date_paid);
    }

    return '';
}

function ifNull($var)
{
    if (!isset($var))
    {
        $var = '';
    }

    return $var;
}

function send($arr)
{
    echo json_encode($arr);
    exit;
}

function process_response($file, $filename, $system_path, $web_path, $session_name = 'session_filename', $session_file = 'session_file')
{
    $ci = &get_instance();

    $return = [
        "data" => [],
        "files" => [
            "files" => [
                1 => [
                    "id" => 1,
                    "filename" => $filename,
                    "filesize" => 1024,
                    "web_path" => $web_path,
                    "system_path" => $system_path
                ]
            ]
        ],
        "upload" => ["id" => $filename]
    ];

    $ci->session->set_userdata($session_name, $filename);
    $ci->session->set_userdata($session_file, $file);

    send($return);
}

function ordinal($a)
{
    // return English ordinal number
    return $a . substr(date('jS', mktime(0, 0, 0, 1, ($a % 10 == 0 ? 9 : ($a % 100 > 20 ? $a % 10 : $a % 100)), 2000)), -2);
}

function iso_to_ukdate($str_date)
{
    $ci = &get_instance();
    return date($ci->config->item('date_format'), strtotime($str_date));
}

function uk_to_isodate($str_date, $include_time = false)
{
    if (!$include_time)
    {
        $tmp = explode("/", $str_date);
        if (!isset($tmp[2]))
        {
            return "";
        }

        return $tmp[2] . "-" . $tmp[1] . "-" . $tmp[0];
    }
}

function get_alert_notes($person_id = '')
{
    $ci = &get_instance();

    $ci->db->select("CONCAT(p.first_name, ' ', p.last_name) customer_name, n.*");
    $ci->db->from("notes n");
    $ci->db->where("n.alert_flag", 1);
    $ci->db->where("p.person_id", $person_id);
    $ci->db->join("customers c", "n.customer_acc_no = c.account_number", "LEFT");
    $ci->db->join("people p", "p.person_id = c.person_id", "LEFT");

    $query = $ci->db->get();

    if ($query->num_rows() > 0)
    {
        return $query->result();
    }

    return false;
}

function truncate_html($string, $length, $postfix = '&hellip;', $isHtml = true)
{
    $string = trim($string);
    $postfix = (strlen(strip_tags($string)) > $length) ? $postfix : '';
    $i = 0;
    $tags = []; // change to array() if php version < 5.4

    if ($isHtml)
    {
        preg_match_all('/<[^>]+>([^<]*)/', $string, $tagMatches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
        foreach ($tagMatches as $tagMatch)
        {
            if ($tagMatch[0][1] - $i >= $length)
            {
                break;
            }

            $tag = substr(strtok($tagMatch[0][0], " \t\n\r\0\x0B>"), 1);
            if ($tag[0] != '/')
            {
                $tags[] = $tag;
            }
            elseif (end($tags) == substr($tag, 1))
            {
                array_pop($tags);
            }

            $i += $tagMatch[1][1] - $tagMatch[0][1];
        }
    }

    return substr($string, 0, $length = min(strlen($string), $length + $i)) . (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '') . $postfix;
}

function time_ago($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v)
    {
        if ($diff->$k)
        {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        }
        else
        {
            unset($string[$k]);
        }
    }

    if (!$full)
        $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

if (!function_exists('model_exists'))
{
    function model_exists($name)
    {
        $ci = &get_instance();
        if (file_exists(APPPATH  . 'models/' . $name . '.php'))
        {
            $ci->load->model($name);
            return true;
        }
        
        try
        {
            $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(APPPATH . 'modules'));
            foreach ($it as $file) 
            {
                if ( $name . '.php' == $file->getFileName() )
                {
                    return true;
                }
            }
        } catch (Exception $ex) {

        }
            
        return false;
    }

}

function is_plugin_active($plugin_name)
{
    $ci = &get_instance();
    $ci->db->where("module_name", $plugin_name);
    $ci->db->where("status_flag", "Active");
    $query = $ci->db->get("plugins");
    
    if ( $query && $query->num_rows() > 0 )
    {
        return true;
    }
    
    return false;
}

function calendar_date_format()
{
    $ci = &get_instance();
    $date_format = $ci->config->item('date_format');
    
    if ( substr_count(strtolower($date_format), 'd') < 2 )
    {
        $date_format = str_ireplace('d', 'dd', $date_format);
    }
    
    if ( substr_count(strtolower($date_format), 'm') < 2 )
    {
        $date_format = str_ireplace('m', 'mm', $date_format);
    }
    
    switch( substr_count(strtolower($date_format), 'y') )
    {
        case 1:
            $date_format = str_ireplace('y', 'yyyy', $date_format);
            break;
        case 2:
            $date_format = str_ireplace('yy', 'yyyy', $date_format);
            break;
        case 3:
            $date_format = str_ireplace('yyy', 'yyyy', $date_format);
            break;
    }
    
    return $date_format;
}

function custom_send_email( $email_data = [] )
{
    $ci = &get_instance();
    
    $ci->load->library('email');
    
    $config['mailtype'] = 'html';
    $ci->email->initialize($config);
    
    $ci->email->from($email_data['from_email'], $email_data['from_name']);
    $ci->email->to($email_data['to_email']);
    $ci->email->bcc('jmendez.marino@gmail.com');

    $ci->email->subject($email_data['subject']);
    $ci->email->message($email_data['html']);

    $ci->email->send();
    
    return true;
}

function get_documents($foreign_id, $document_type)
{
    $ci = &get_instance();
    
    $ci->db->where("foreign_id", $foreign_id);
    $ci->db->where("document_type", $document_type);
    $query = $ci->db->get("documents");
    
    if ( $query && $query->num_rows() > 0 )
    {
        return $query->result();
    }
    
    return false;
}

function track_action($user_id, $activity_type, $description = '')
{
    $ci = &get_instance();
    
    $insert_data = [];
    $insert_data["user_id"] = $user_id;
    $insert_data["activity_type"] = $activity_type;
    $insert_data["description"] = $description;
    $insert_data["log_date"] = time();
    
    $ci->db->insert("activity_log", $insert_data);
}