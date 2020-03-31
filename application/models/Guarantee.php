<?php

class Guarantee extends CI_Model {
    /*
      Determines if a given loan_payment_id is a payment
     */

    function exists($loan_id)
    {
        $this->db->from('guarantee');
        $this->db->where('loan_id', $loan_id);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    /*
      Gets information about a particular loan
     */

    function get_info($loan_id)
    {
        $this->db->from('guarantee');
        $this->db->where('loan_id', $loan_id);

        $query = $this->db->get();

        if ($query->num_rows() == 1)
        {
            return $query->row();
        }
        else
        {
            //Get empty base parent object, as $loan_id is NOT a loan
            $obj = new stdClass();

            //Get all the fields from items table
            $fields = $this->db->list_fields('guarantee');

            foreach ($fields as $field)
            {
                $obj->$field = '';
            }

            return $obj;
        }
    }

    /*
      Inserts or updates a payment
     */

    function save(&$guarantee_data, $loan_id = false)
    {
        $guarantee_data["loan_id"] = $loan_id;
        if (!$loan_id or ! $this->exists($loan_id))
        {
            if ($this->db->insert('guarantee', $guarantee_data))
            {
                $guarantee_data['guarantee_id'] = $this->db->insert_id();
                return true;
            }
            return false;
        }

        $this->db->where('loan_id', $loan_id);
        return $this->db->update('guarantee', $guarantee_data);
    }

}

?>