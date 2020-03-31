<?php

class Message extends CI_Model {
    /*
      Determines if a given item_id is an item kit
     */

    function exists($message_id)
    {
        $this->db->from('messages');
        $this->db->where('message_id', $message_id);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    function get_all_inbox($limit = 10000, $offset = 0, $search = "", $order = array())
    {
        $user_id = $this->Employee->get_logged_in_employee_info()->person_id;

        $sorter = array(
            "message_id",
            "header",
            "sender_name",
            "receive_date",
        );

        $this->db->from('messages');
        $this->db->join("people", "messages.sender_id=people.person_id", "LEFT");

        if ($search !== "")
        {
            $this->db->where('header LIKE ', '%' . $search . '%');
            $this->db->or_where('sender_id LIKE', '%' . $search . '%');
            $this->db->or_where('sender_id LIKE', '%' . $search . '%');
        }

        $this->db->where('recipient_id', $user_id);

        if (count($order) > 0 && $order['index'] < count($sorter))
        {
            $this->db->order_by($sorter[$order['index']], $order['direction']);
        }
        else
        {
            $this->db->order_by("sender_name", "asc");
        }

        $this->db->limit($limit);
        $this->db->offset($offset);
        $query = $this->db->get();
        
        return $query;
    }

    function get_all_outbox($limit = 10000, $offset = 0, $search = "", $order = array())
    {
        $user_id = $this->Employee->get_logged_in_employee_info()->person_id;

        $sorter = array(
            "message_id",
            "header",
            "recipient_name",
            "send_date",
        );

        $this->db->from('messages');
        $this->db->join("people", "messages.recipient_id=people.person_id", "LEFT");

        if ($search !== "")
        {
            $this->db->where('subject LIKE ', '%' . $search . '%');
            $this->db->or_where('recipient_id LIKE', '%' . $search . '%');
            $this->db->or_where('recipient_id LIKE', '%' . $search . '%');
        }

        $this->db->where('sender_id', $user_id);

        if (count($order) > 0 && $order['index'] < count($sorter))
        {
            $this->db->order_by($sorter[$order['index']], $order['direction']);
        }
        else
        {
            $this->db->order_by("send_date", "asc");
        }

        $this->db->limit($limit);
        $this->db->offset($offset);
        $query = $this->db->get();
        
        return $query;
    }

    function count_all_inbox()
    {
        $user_id = $this->Employee->get_logged_in_employee_info()->person_id;
        $this->db->where('recipient_id', $user_id);
        $this->db->from('messages');
        return $this->db->count_all_results();
    }

    function count_all_outbox()
    {
        $user_id = $this->Employee->get_logged_in_employee_info()->person_id;
        $this->db->where("sender_id", $user_id);
        $this->db->from('messages');
        return $this->db->count_all_results();
    }

    function get_multiple_messages($message_ids = -1)
    {
        $this->db->from('messages');
        if ($message_ids > -1)
        {
            $this->db->where_in('message_id', $message_ids);
        }
        return $this->db->get()->result();
    }

    /*
      Gets information about a particular item kit
     */

    function get_info($message_id)
    {
        $this->db->select("CONCAT(b.first_name,' ', b.last_name) AS sender_name", FALSE);
        $this->db->select("messages.*");
        $this->db->from('messages');
        $this->db->join("people as b", 'b.person_id = messages.sender_id', "LEFT");                
        $this->db->where('message_id', $message_id);

        $query = $this->db->get();

        if ($query->num_rows() == 1)
        {
            return $query->row();
        }
        else
        {
            //Get empty base parent object, as $item_kit_id is NOT an item kit
            $item_obj = new stdClass();
            
            $item_obj->sender_name = '';
            //Get all the fields from items table
            $fields = $this->db->list_fields('messages');

            foreach ($fields as $field)
            {
                $item_obj->$field = '';
            }

            return $item_obj;
        }
    }

    /*
      Inserts or updates an item kit
     */

    function save(&$message_data, $message_id = false)
    {
        if (!$message_id or ! $this->exists($message_id))
        {
            if ($this->db->insert('messages', $message_data))
            {
                $message_data['message_id'] = $this->db->insert_id();
                return true;
            }
            return false;
        }

        $this->db->where('message_id', $message_id);
        return $this->db->update('messages', $message_data);
    }

    /*
      Deletes one item kit
     */

    function delete($message_id)
    {
        return $this->db->delete('messages', array('message_id' => $message_id));
    }

    /*
      Deletes a list of item kits
     */

    function delete_list($message_ids)
    {
        $this->db->where_in('message_id', $message_ids);
        return $this->db->delete('messages');
    }

    function get_attachments($message_id)
    {
        $this->db->from('attachments');
        $this->db->where('message_id', $message_id);
        $query = $this->db->get();

        return $query->result();
    }

    function save_attachments($message_id, &$data)
    {
        if ($message_id > 0)
        {
            if ($this->db->insert('attachments', array("filename" => $data['filename'], "message_id" => $message_id)))
            {
                $data['attachment_id'] = $this->db->insert_id();
                return true;
            }
        }

        $session_id = $data['params']['linker'];
        $this->load->library('session');
        $this->session->set_userdata(array("linker" => $session_id));
        if ($this->db->insert('attachments', array("filename" => $data['filename'], "session_id" => $session_id)))
        {
            $data['attachment_id'] = $this->db->insert_id();
            return true;
        }
    }
    
    function get_notifications($type)
    {
        switch($type)
        {
            case "mails":
                $filters["mark_as_read"] = 0;
                $messages = $this->get_mails($filters);
                return $messages;
            case "alerts":    
                $filters["mark_as_read"] = 0;
                $alerts = $this->get_alerts($filters); 
                return $alerts;
        }
    }
    
    function get_mails( $filters = [] )
    {
        $user_id = $this->Employee->get_logged_in_employee_info()->person_id;
        $this->db->select("CONCAT(b.first_name,' ', b.last_name) AS sender_name", FALSE);
        $this->db->select("b.photo_url AS profile_pic", FALSE);
        $this->db->select("messages.*");
        $this->db->from('messages');
        $this->db->join("people as b", 'b.person_id = messages.sender_id', "LEFT");
        $this->db->where("recipient_id", $user_id);

        if ( isset($filters["mark_as_read"]) )
        {
            $this->db->where("mark_as_read", $filters["mark_as_read"]);
        }
        
        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
            $messages = $query->result();
            foreach($messages as $message)
            {
                $message->profile_url = "test";

                $image_url = base_url("uploads/profile-" . $message->sender_id . "/" . $message->profile_pic);
                $headers = @get_headers($image_url, 1); // @ to suppress errors. Remove when debugging.
                if (isset($headers['Content-Type']))
                {
                    if (strpos($headers['Content-Type'], 'image/') === FALSE)
                    {
                        // Not a regular image (including a 404).
                        $image_url = base_url("uploads/common/images.jpg");
                    }
                    else
                    {
                        // It's an image!
                    }
                }
                else
                {
                    // No 'Content-Type' returned.
                    $image_url = "/uploads/common/images.jpg";
                }

                $message->profile_pic = $image_url;
                $message->hours_ago = "2 hours ago";
                $message->str_timestamp = "test 9:15";                
            }
            return $messages;
        }
        
        return array();
    }

    function get_alerts( $filters = [] )
    {
        $user_id = $this->Employee->get_logged_in_employee_info()->person_id;        
        $this->db->select("messages.*");
        $this->db->from('messages');
        $this->db->join("people as b", 'b.person_id = messages.sender_id', "LEFT");
        $this->db->where("recipient_id", $user_id);
        
        if ( isset($filters["mark_as_read"]) )
        {
            $this->db->where("messages.mark_as_read", $filters["mark_as_read"]);
        }
        
        $query = $this->db->get();

        $alerts = [];
        $alert = new stdClass();
        if ($query->num_rows() > 0)
        {
            $alert->alert_url = site_url('messages/inbox');
            $alert->subject = "You have ".$query->num_rows()." messages";
            $alert->hours_ago = "2 hrs ago";
            $alerts[] = $alert;
        }

        return $alerts;
    }
}

?>