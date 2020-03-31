<?php

class Customer extends Person {
    /*
      Determines if a given person_id is a customer
     */

    function exists($person_id, $email = false)
    {
        $this->db->from('customers');
        $this->db->join('people', 'people.person_id = customers.person_id');
        $this->db->where('customers.person_id', $person_id);
        if ($email)
        {
            $this->db->or_where('people.email', $email);
        }
        $query = $this->db->get();

        return ($query->num_rows() > 0);
    }

    /*
      Returns all the customers
     */

    function get_all($limit = 10000, $offset = 0, $search = "", $order = array(), $sel_user = false)
    {
        $sorter = array("", "last_name", "first_name", "email", "phone_number");

        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->where('deleted', 0);

        $employee_id = $this->session->userdata('person_id');

        $employee_id = ($sel_user) ? $sel_user : $employee_id;

        if ($employee_id !== "1")
        {
            $this->db->where("added_by", $employee_id);
        }

        if ($search !== "")
        {
            $this->db->where('first_name LIKE ', '%' . $search . '%');
            $this->db->or_where('last_name LIKE', '%' . $search . '%');
            $this->db->or_where('email LIKE', '%' . $search . '%');
            $this->db->or_where('phone_number LIKE', '%' . $search . '%');
        }

        if (count($order) > 0 && $order['index'] < count($sorter))
        {
            $this->db->order_by($sorter[$order['index']], $order['direction']);
        }
        else
        {
            $this->db->order_by("last_name", "asc");
        }

        $this->db->limit($limit);
        $this->db->offset($offset);
        return $this->db->get();
    }

    function count_all()
    {
        $this->db->from('customers');
        $this->db->where('deleted', 0);
        return $this->db->count_all_results();
    }

    /*
      Gets information about a particular customer
     */

    function get_info($customer_id)
    {
        $this->db->from('customers');
        $this->db->select('customers.*');
        $this->db->select('people.*');
        $this->db->select('financial_status.financial_status_id');
        $this->db->select('financial_status.income_sources');
        $this->db->join('people', 'people.person_id = customers.person_id');
        $this->db->join('financial_status', 'financial_status.person_id = people.person_id', 'LEFT');
        $this->db->where('customers.person_id', $customer_id);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0)
        {
            return $query->row();
        }
        else
        {
            //Get empty base parent object, as $customer_id is NOT an customer
            $person_obj = parent::get_info(-1);

            //Get all the fields from customer table
            $fields = $this->db->list_fields('customers');

            //append those fields to base parent object, we we have a complete empty object
            foreach ($fields as $field)
            {
                $person_obj->$field = '';
            }

            return $person_obj;
        }
    }

    /*
      Gets information about multiple customers
     */

    function get_multiple_info($customer_ids)
    {
        $this->db->from('customers');
        $this->db->join('people', 'people.person_id = customers.person_id');
        $this->db->where_in('customers.person_id', $customer_ids);
        $this->db->order_by("last_name", "asc");
        return $this->db->get();
    }

    function get_attachments($customer_id)
    {
        $this->db->from('attachments');
        $this->db->where('customer_id', $customer_id);
        $query = $this->db->get();

        return $query->result();
    }

    /*
      Inserts or updates a customer
     */

    function save(&$person_data, &$customer_data = [], $customer_id = false, &$financial_data = array())
    {
        $success = false;
        //Run these queries as a transaction, we want to make sure we do all or nothing
        $this->db->trans_start();

        if (parent::save($person_data, $customer_data, $customer_id))
        {
            $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;

            if (!$customer_id || ! $this->exists($customer_id))
            {
                $financial_data['person_id'] = $person_data['person_id'];
                $this->db->insert('financial_status', $financial_data);
                $customer_data['person_id'] = $person_data['person_id'];
                $customer_data['added_by'] = $employee_id;
                $success = $this->db->insert('customers', $customer_data);
                $this->move_attachments($customer_data);
            }
            else
            {
                //$this->db->where('person_id', $customer_id);
                if ($financial_data['financial_status_id'] > 0)
                {
                    $this->db->where('financial_status_id', $financial_data['financial_status_id']);
                    $this->db->update('financial_status', $financial_data);
                }
                else
                {
                    $financial_data['person_id'] = $customer_id;
                    $this->db->insert('financial_status', $financial_data);
                }

                $this->db->where('person_id', $customer_id);
                $success = $this->db->update('customers', $customer_data);
            }
        }

        $this->db->trans_complete();
        return $success;
    }

    function move_attachments($customer_data)
    {
        $linker = $this->session->userdata('linker');

        $this->db->from('attachments');
        $this->db->where('session_id', $linker);
        $query = $this->db->get();

        $this->db->where('session_id', $linker);
        $this->db->update('attachments', array("customer_id" => $customer_data['person_id']));

        $attachments = $query->result();
        foreach ($attachments as $attachment)
        {
            $tmp_dir = FCPATH . "uploads/customer-/";
            $user_dir = FCPATH . "uploads/customer-" . $customer_data['person_id'] . "/";

            if (!file_exists($user_dir))
            {
                // temporary set to full access
                @mkdir($user_dir);
            }

            $target_dist = $user_dir . $attachment->filename;

            if (file_exists($tmp_dir . $attachment->filename))
            {
                copy($tmp_dir . $attachment->filename, $target_dist);
                unlink($tmp_dir . $attachment->filename);
            }
        }
    }

    /*
      Deletes one customer
     */

    function delete($customer_id)
    {
        $this->db->where('person_id', $customer_id);
        return $this->db->update('customers', array('deleted' => 1));
    }

    /*
      Deletes a list of customers
     */

    function delete_list($customer_ids)
    {
        $this->db->where_in('person_id', $customer_ids);
        return $this->db->update('customers', array('deleted' => 1));
    }

    /*
      Get search suggestions to find customers
     */

    function get_search_suggestions($search, $limit = 25)
    {
        $suggestions = array();

        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->where("(first_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
		last_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
		CONCAT(`first_name`,' ',`last_name`) LIKE '%" . $this->db->escape_like_str($search) . "%') and deleted=0");
        $this->db->order_by("last_name", "asc");
        $by_name = $this->db->get();
        foreach ($by_name->result() as $row)
        {
            $suggestions[] = $row->first_name . ' ' . $row->last_name;
        }

        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->where('deleted', 0);
        $this->db->like("email", $search);
        $this->db->order_by("email", "asc");
        $by_email = $this->db->get();
        foreach ($by_email->result() as $row)
        {
            $suggestions[] = $row->email;
        }

        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->where('deleted', 0);
        $this->db->like("phone_number", $search);
        $this->db->order_by("phone_number", "asc");
        $by_phone = $this->db->get();
        foreach ($by_phone->result() as $row)
        {
            $suggestions[] = $row->phone_number;
        }

        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->where('deleted', 0);
        $this->db->like("account_number", $search);
        $this->db->order_by("account_number", "asc");
        $by_account_number = $this->db->get();
        foreach ($by_account_number->result() as $row)
        {
            $suggestions[] = $row->account_number;
        }

        //only return $limit suggestions
        if (count($suggestions > $limit))
        {
            $suggestions = array_slice($suggestions, 0, $limit);
        }

        return $suggestions;
    }

    /*
      Get search suggestions to find customers
     */

    function get_customer_search_suggestions($search, $limit = 25)
    {
        $suggestions = array();

        $user_id = $this->Employee->get_logged_in_employee_info()->person_id;
        $add_where = '';
        if ($user_id > 1)
        {
            $add_where = " and added_by = " . $user_id . " ";
        }

        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->where("(first_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
		last_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
		CONCAT(`first_name`,' ',`last_name`) LIKE '%" . $this->db->escape_like_str($search) . "%') and deleted=0
                    " . $add_where . "
                    ");
        $this->db->order_by("last_name", "asc");
        $by_name = $this->db->get();
        
        
        foreach ($by_name->result() as $row)
        {
            $suggestions[] = $row->person_id . '|' . $row->first_name . ' ' . $row->last_name . '|' . $row->email . '|' . $row->account_number;
        }

        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->where('deleted', 0);
        $this->db->like("account_number", $search);
        $this->db->order_by("account_number", "asc");
        $by_account_number = $this->db->get();
        
        foreach ($by_account_number->result() as $row)
        {
            $suggestions[] = $row->person_id . '|' . $row->account_number . '|' . $row->email . '|' . $row->account_number;
        }

        //only return $limit suggestions
        if (count($suggestions) > $limit)
        {
            $suggestions = array_slice($suggestions, 0, $limit);
        }
        return $suggestions;
    }

    /*
      Preform a search on customers
     */

    function search($search)
    {
        $this->db->from('customers');
        $this->db->join('people', 'customers.person_id=people.person_id');
        $this->db->where("(first_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
		last_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
		email LIKE '%" . $this->db->escape_like_str($search) . "%' or 
		phone_number LIKE '%" . $this->db->escape_like_str($search) . "%' or 
		account_number LIKE '%" . $this->db->escape_like_str($search) . "%' or 
		CONCAT(`first_name`,' ',`last_name`) LIKE '%" . $this->db->escape_like_str($search) . "%') and deleted=0");
        $this->db->order_by("last_name", "asc");

        return $this->db->get();
    }
    
    function save_profile_pic($customer_id, &$data)
    {
        if ($customer_id > 0)
        {
            $save_data["photo_url"] = $data["filename"];
            $this->db->where("person_id", $customer_id);
            $this->db->update("people", $save_data);
            return true;
        }
    }

    function save_attachments($customer_id, &$data)
    {
        if ($customer_id > 0)
        {
            if ($this->db->insert('attachments', array("filename" => $data['filename'], "customer_id" => $customer_id)))
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
    
    function remove_file($file_id)
    {
        $this->db->from('attachments');
        $this->db->where('attachment_id', $file_id);
        $query = $this->db->get();
        $res = $query->row();

        $user_dir = FCPATH . "uploads/customer-/";
        if ($res->loan_id > 0)
        {
            $user_dir = FCPATH . "uploads/customer-" . $res->customer_id . "/";
        }

        if (file_exists($user_dir . $res->filename))
        {
            unlink($user_dir . $res->filename);
        }

        return $this->db->delete('attachments', array('attachment_id' => $file_id));
    }

}

?>
