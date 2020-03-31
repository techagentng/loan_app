<?php

class Loan_type extends CI_Model {
    /*
      Determines if a given item_id is an item kit
     */

    function exists($loan_type_id)
    {
        $this->db->from('loan_types');
        $this->db->where('loan_type_id', $loan_type_id);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    function get_all($limit = 10000, $offset = 0, $search = "", $order = array())
    {
        $user_id = $this->Employee->get_logged_in_employee_info()->person_id;
        
        $sorter = array(
            "loan_type_id",
            "loan_type_id",
            "name",
            "description",
            "percent_charge1",
            "period_charge1",
            "period_type1",
            "term",
            "percent_charge2",
            "period_charge2",
            "period_type2"
        );

        $this->db->from('loan_types');

        if ($search !== "")
        {
            $this->db->where('name LIKE ', '%' . $search . '%');
            $this->db->or_where('description LIKE', '%' . $search . '%');
            $this->db->or_where('percent_charge1 LIKE', '%' . $search . '%');
            $this->db->or_where('period_charge1 LIKE', '%' . $search . '%');
            $this->db->or_where('period_type1 LIKE', '%' . $search . '%');
            $this->db->or_where('percent_charge2 LIKE', '%' . $search . '%');
            $this->db->or_where('period_charge2 LIKE', '%' . $search . '%');
            $this->db->or_where('period_type2 LIKE', '%' . $search . '%');
        }

        if (isset($_GET['employee_id']) && $_GET['employee_id'] > 0)
        {
            $user_id = $_GET['employee_id'];
        }
        
        if ($user_id !== '1')
        {
            $this->db->where('added_by', $user_id);
        }
        
        if (count($order) > 0 && $order['index'] < count($sorter))
        {
            $this->db->order_by($sorter[$order['index']], $order['direction']);
        }
        else
        {
            $this->db->order_by("name", "asc");
        }

        $this->db->limit($limit);
        $this->db->offset($offset);
        return $this->db->get();
    }

    function count_all()
    {
        $this->db->from('loan_types');
        return $this->db->count_all_results();
    }

    function get_multiple_loan_types($loan_type_ids = -1)
    {
        $this->db->from('loan_types');
        if ($loan_type_ids > -1)
        {
            $this->db->where_in('loan_type_id', $loan_type_ids);
        }
        return $this->db->get()->result();
    }

    /*
      Gets information about a particular item kit
     */

    function get_info($loan_type_id)
    {
        $this->db->from('loan_types');
        $this->db->where('loan_type_id', $loan_type_id);

        $query = $this->db->get();

        if ($query->num_rows() == 1)
        {
            return $query->row();
        }
        else
        {
            //Get empty base parent object, as $item_kit_id is NOT an item kit
            $item_obj = new stdClass();

            //Get all the fields from items table
            $fields = $this->db->list_fields('loan_types');

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

    function save(&$loan_type_data, $loan_type_id = false)
    {
        $user_id = $this->Employee->get_logged_in_employee_info()->person_id;
        $loan_type_data["added_by"] = $user_id;
        $loan_type_data["date_added"] = time();
        if (!$loan_type_id or ! $this->exists($loan_type_id))
        {
            if ($this->db->insert('loan_types', $loan_type_data))
            {
                $loan_type_data['loan_type_id'] = $this->db->insert_id();
                return true;
            }
            return false;
        }

        $this->db->where('loan_type_id', $loan_type_id);
        return $this->db->update('loan_types', $loan_type_data);
    }

    /*
      Deletes one item kit
     */

    function delete($loan_type_id)
    {
        return $this->db->delete('loan_types', array('loan_type_id' => $loan_type_id));
    }

    /*
      Deletes a list of item kits
     */

    function delete_list($loan_type_ids)
    {
        $this->db->where_in('loan_type_id', $loan_type_ids);
        return $this->db->delete('loan_types');
    }

}

?>