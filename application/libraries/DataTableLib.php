<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DataTableLib {

    private $ci = null;
    private $_params = null;
    private $_datatable = null;

    function __construct()
    {
        $this->ci = &get_instance();
        $this->_params = new stdClass();
    }

    public function params()
    {
        return $this;
    }
    
    public function datatable()
    {
        $this->_datatable = new Datatable();
        return $this->_datatable;
    }

    function render()
    {
        //var_dump($this->_datatable->data_columns);
        return $this->ci->load->view("datatables/table", ["object" => $this->_datatable], true);
    }
}

class Datatable{
    private $_editor_field = null;
    private $_button = null;
    
    public $ajax_url = null;
    public $create_title = null;
    public $edit_title = null;
    public $table_id = null;
    public $table_buttons = [];
    public $server_params = [];
    public $data_columns = [];
    public $table_definitions = [];
    public $editor_fields = [];
    public $delete_row_callback_message = "dt_remove_row_callback";
    public $has_delete_row = true;
    public $has_edit_dblclick = true;
    public $has_inline_edit = false;
    public $callbacks = [];
    public $display_length = 50;
    public $ajaxwork_move_function = 0;
    public $has_btn_move_up_and_down = false;
    public $root_function_name = "";
    public $dt_height = "calc(85vh - 200px)";
    
    function __construct() {}
    
    public function add_titles($title = "") {
        $this->create_title = "New $title";
        $this->edit_title = "Edit $title";
    }

    public function editor_field()
    {
        $this->_editor_field = new DT_Editor_field();
        return $this->_editor_field;
    }

    public function table_button()
    {
        $this->_button = new DT_Button();
        return $this->_button;
    }

    //adding Datatable Column
    public function add_column($data_column, $show_editor = true, $label = "", $type="text", $editor_data_column = null, $other_editor_params = array())
    {
        //adding column
        if (!isset($other_editor_params["hide_list"]) || (isset($other_editor_params["hide_list"]) && !$other_editor_params["hide_list"]) )
        {
            $this->data_columns[] = $data_column;            
        }

        //adding editor field
        if($show_editor) {
            $this->add_editor_field(isset($editor_data_column) ? $editor_data_column : $data_column, $label, $type, $other_editor_params);
        }
    }

    public function add_editor_field($data_column, $label = "", $type="text" , $other_editor_params = array())
    {
        //adding editor field
        $_editor_field = new DT_Editor_field();
        $_editor_field->add("label", $label);

        //checking if there is other data source for editor  
        $_editor_field->add("data_column", $data_column );
        $_editor_field->add("type", $type);
        
        if(count($other_editor_params) > 0 ) {
            foreach( $other_editor_params as $key => $param) {
                $_editor_field->add($key, $param);
            }
        }
        
        $this->editor_fields[] = $_editor_field;
    }

    public function add_button($type, $text = "", $action_callback = "")
    {
        $_button = new DT_Button();
        $_button->add("type", $type);

        if($text != "") {
            $_button->add("text", $text);
        }

        if($action_callback != "") {
            $_button->add("action_callback", $action_callback);
        }
        
        $this->table_buttons[] = $_button;
    }

    public function add_callback($key, $value)
    {
        $this->callbacks[$key] = $value;
    }

    public function add_table_definition($table_definition)
    {
        $this->table_definitions[] = $table_definition;
    }

    public function add_server_params($sections, $type, $additional_params = null)
    {
        $this->server_params["table_list"] = array(
            "sections" => $sections,
            "ajax_action" => "list",
            "type" => $type
        );
        
        $this->server_params[ "table_editor"] = array(
            "sections" => $sections,
            "ajax_action" => "editor",
            "type" => $type
        );

        if( $additional_params != null) {
            $this->server_params["table_list"] = array_merge( $this->server_params["table_list"], $additional_params );
            $this->server_params["table_editor"] = array_merge( $this->server_params["table_editor"], $additional_params );
        }
    }
}

class DT_Button {
    private $_params = null;
    function __construct()
    {
        $this->_params = new stdClass();
    }
    
    function add($key, $value)
    {
        $this->_params->$key = $value;
    }
    
    function get_buttons()
    {
        return $this->_params;
    }
}

class DT_Editor_field {
    private $_params = null;
    function __construct()
    {
        $this->_params = new stdClass();
    }
    
    function add($key, $value)
    {
        $this->_params->$key = $value;
    }
    
    function get_fields()
    {
        return $this->_params;
    }
}
