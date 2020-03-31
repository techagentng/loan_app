<?php

class Report extends CI_Model {
    /*
      Determines if a given id exist
     */

    function exists($id)
    {
        $this->db->from(strtolower(get_class()) . 's');
        $this->db->where(strtolower(get_class()) . '_id', $id);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    function get_fields($controller_name)
    {
        return $this->db->list_fields($controller_name);
    }

    function get_all($limit = 10000, $offset = 0, $search = "", $order = [])
    {
        $sorter = $this->get_fields(strtolower(get_class()) . 's');
        $this->db->from(strtolower(get_class()) . 's');

        if ($search !== "")
        {
            // customization needed
            $this->db->where($sorter[1] . ' LIKE ', '%' . $search . '%');
        }

        if (count($order) > 0 && $order['index'] < count($sorter))
        {
            $this->db->order_by($sorter[$order['index']], $order['direction']);
        }
        else
        {
            // customization needed
            $this->db->order_by(strtolower(get_class()) . "s_id", "asc");
        }

        $this->db->limit($limit);
        $this->db->offset($offset);
        return $this->db->get();
    }

    function count_all()
    {
        $this->db->from(strtolower(get_class()) . 's');
        return $this->db->count_all_results();
    }

    function get_multiple($ids = -1)
    {
        $this->db->from(strtolower(get_class()) . 's');
        if ($ids > -1)
        {
            $this->db->where_in(strtolower(get_class()) . '_id', $ids);
        }
        return $this->db->get()->result();
    }

    /*
      Gets information about a particular item kit
     */

    function get_info($id)
    {
        $this->db->from(strtolower(get_class()) . 's');
        $this->db->where(strtolower(get_class()) . '_id', $id);

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
            $fields = $this->db->list_fields(strtolower(get_class()) . 's');

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

    function save(&$data, $id = false)
    {
        if (!$id or ! $this->exists($id))
        {
            if ($this->db->insert(strtolower(get_class()) . 's', $data))
            {
                $data[strtolower(get_class()) . '_id'] = $this->db->insert_id();
                return true;
            }
            return false;
        }

        $this->db->where(strtolower(get_class()) . '_id', $id);
        return $this->db->update(strtolower(get_class()) . 's', $data);
    }

    /*
      Deletes one item
     */

    function delete($id)
    {
        // though customization if you wish to just have a soft delete
        return $this->db->delete(strtolower(get_class()) . 's', array(strtolower(get_class()) . '_id' => $id));
    }

    /*
      Deletes a list of item kits
     */

    function delete_list($ids)
    {
        // though customization if you wish to just have a soft delete
        $this->db->where_in(strtolower(get_class()) . '_id', $ids);
        return $this->db->delete(strtolower(get_class()) . 's');
    }
}

?>