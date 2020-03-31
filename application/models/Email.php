<?php

class Email extends CI_Model {
    
    function get_smtp_info()
    {
        $query = $this->db->get("smtp");
        
        if ($query->num_rows() == 1)
        {
            return $query->row();
        }
        else
        {
            //Get empty base parent object
            $obj = new stdClass();
            
            $obj->created_by = '';
            //Get all the fields from table
            $fields = $this->db->list_fields('smtp');

            foreach ($fields as $field)
            {
                $obj->$field = '';
            }

            return $obj;
        }
    }
    
    function save_smtp(&$data)
    {
        if (!$this->smtp_exists($data["smtp_id"]))
        {
            if ($this->db->insert("smtp", $data))
            {
                $data["smtp_id"] = $this->db->insert_id();
                return true;
            }
            
            return false;
        }
        
        $this->db->where("smtp_id", $data["smtp_id"]);
        $this->db->update("smtp", $data);
        
        return true;
        
    }
    
    function smtp_exists($smtp_id)
    {
        $this->db->from('smtp');
        $this->db->where('smtp_id', $smtp_id);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }
    /*
      Determines if a given email_id exists
     */

    function exists($email_id)
    {
        $this->db->from('emails');
        $this->db->where('email_id', $email_id);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    function get_all($limit = 10000, $offset = 0, $search = "", $order = array())
    {
        $sorter = array(
            "email_id",
            "template_name",
            "descriptions",
            "person_id",
        );

        $this->db->select("emails.*");
        $this->db->select("CONCAT(b.first_name,' ', b.last_name) AS created_by", FALSE);
        $this->db->from('emails');
        $this->db->join("people as b", "emails.person_id=b.person_id", "LEFT");

        if ($search !== "")
        {
            $this->db->where('template_name LIKE ', '%' . $search . '%');
            $this->db->or_where('descriptions LIKE', '%' . $search . '%');
            $this->db->or_where('person_id LIKE', '%' . $search . '%');
        }

        if (count($order) > 0 && $order['index'] < count($sorter))
        {
            $this->db->order_by($sorter[$order['index']], $order['direction']);
        }
        else
        {
            $this->db->order_by("email_id", "asc");
        }

        $this->db->limit($limit);
        $this->db->offset($offset);
        return $this->db->get();
    }

    function count_all()
    {
        $this->db->from('emails');
        return $this->db->count_all_results();
    }

    /*
      Gets information about a particular item kit
     */

    function get_info($email_id)
    {
        $this->db->select("CONCAT(b.first_name,' ', b.last_name) AS created_by", FALSE);
        $this->db->select("emails.*");
        $this->db->from('emails');
        $this->db->join("people as b", 'b.person_id = emails.person_id', "LEFT");                
        $this->db->where('email_id', $email_id);

        $query = $this->db->get();

        if ($query->num_rows() == 1)
        {
            return $query->row();
        }
        else
        {
            //Get empty base parent object, as $item_kit_id is NOT an item kit
            $item_obj = new stdClass();
            
            $item_obj->created_by = '';
            //Get all the fields from emails table
            $fields = $this->db->list_fields('emails');

            foreach ($fields as $field)
            {
                $item_obj->$field = '';
            }

            return $item_obj;
        }
    }
    
    function get_template($template_name)
    {
        $this->db->where("template_name", $template_name);
        $query = $this->db->get("emails");
        
        if ($query->num_rows() > 0)
        {
            $obj = $query->row();
            return $obj->templates;            
        }
        
        return false;
    }

    /*
      Inserts or updates an item kit
     */

    function save(&$email_data, $email_id = false)
    {
        $user_id = $this->Employee->get_logged_in_employee_info()->person_id;
        $email_data["person_id"] = $user_id;
        
        if (!$email_id or ! $this->exists($email_id))
        {
            if ($this->db->insert('emails', $email_data))
            {
                $email_data['email_id'] = $this->db->insert_id();
                return true;
            }
            return false;
        }

        $this->db->where('email_id', $email_id);
        return $this->db->update('emails', $email_data);
    }

    /*
      Deletes one item kit
     */

    function delete($email_id)
    {
        return $this->db->delete('emails', array('email_id' => $email_id));
    }

    /*
      Deletes a list of item kits
     */

    function delete_list($email_ids)
    {
        $this->db->where_in('email_id', $email_ids);
        return $this->db->delete('emails');
    }
}

?>