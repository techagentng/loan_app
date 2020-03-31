<?php

class Appconfig extends CI_Model {

    function exists($key)
    {
        $this->db->from('app_config');
        $this->db->where('app_config.key', $key);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    function get_all()
    {
        $this->db->from('app_config');
        $this->db->order_by("key", "asc");
        return $this->db->get();
    }

    function get($key)
    {
        $query = $this->db->get_where('app_config', array('key' => $key), 1);

        if ($query->num_rows() == 1)
        {
            return $query->row()->value;
        }

        return "";
    }

    function save($key, $value)
    {
        $config_data = array(
            'key' => $key,
            'value' => $value
        );

        if (!$this->exists($key))
        {
            return $this->db->insert('app_config', $config_data);
        }

        $this->db->where('key', $key);
        return $this->db->update('app_config', $config_data);
    }

    function batch_save($data)
    {
        $success = true;

        //Run these queries as a transaction, we want to make sure we do all or nothing
        $this->db->trans_start();
        foreach ($data as $key => $value)
        {
            if (!$this->save($key, $value))
            {
                $success = false;
                break;
            }
        }

        $this->db->trans_complete();
        return $success;
    }

    function delete($key)
    {
        return $this->db->delete('app_config', array('key' => $key));
    }

    function delete_all()
    {
        return $this->db->empty_table('app_config');
    }

    function get_wp_info()
    {
        $this->db->limit(1);
        $query = $this->db->get("wp_setup");

        if ($query->num_rows() == 1)
        {
            return $query->row();
        }
        else
        {
            //Get empty base parent object
            $obj = new stdClass();

            //Get all the fields from table
            $fields = $this->db->list_fields('wp_setup');

            foreach ($fields as $field)
            {
                $obj->$field = '';
            }

            return $obj;
        }
    }
    
    function save_wp(&$data)
    {
        if (!$this->wp_exists($data["wp_id"]))
        {
            if ($this->db->insert("wp_setup", $data))
            {
                $data["wp_id"] = $this->db->insert_id();
                return true;
            }
            
            return false;
        }
        
        $this->db->where("wp_id", $data["wp_id"]);
        $this->db->update("wp_setup", $data);
        
        return true;
        
    }
    
    function wp_exists($wp_id)
    {
        $this->db->from('wp_setup');
        $this->db->where('wp_id', $wp_id);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

}

?>