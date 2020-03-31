<?php

require_once ("secure_area.php");
require_once ("interfaces/idata_controller.php");

class Reports extends Secure_area implements iData_controller {

    private $_model;
    private $_model_name;

    function __construct()
    {
        parent::__construct(strtolower(get_class()));
        $model_name = ucfirst(str_replace("s", "", get_class()));
        $this->load->model($model_name);
        $this->_model = $this->$model_name;
        $this->_model_name = strtolower($model_name);
    }

    function index()
    {
        $data['controller_name'] = strtolower(get_class());
        // just make sure that controller name is equivalent to the table name in database
        $data['fields'] = $this->_model->get_fields($data['controller_name']);

        $this->load->view($data['controller_name'] . '/manage', $data);
    }

    function view($id = -1)
    {
        $controller_name = strtolower(get_class());
        $data['info'] = $this->_model->get_info($id);
        $data['id'] = $id;
        $data['fields'] = $this->_model->get_fields($controller_name);
        $data['controller_name'] = $controller_name;
        $this->load->view($controller_name . "/form", $data);
    }

    function save($id = -1)
    {
        $data = $this->input->post();
        unset($data['submit']);

        if ($this->_model->save($data, $id))
        {
            //New
            if ($id == -1)
            {
                echo json_encode(array('success' => true, 'message' => $this->lang->line(strtolower(get_class()) . '_successful_adding') . ' ' .
                    $data[$this->_model_name . '_id'], $this->_model_name . '_id' => $data[$this->_model_name . '_id']));
                $id = $data[$this->_model_name . '_id'];
            }
            else //previous item
            {
                echo json_encode(array('success' => true, 'message' => $this->lang->line(strtolower(get_class()) . '_successful_updating') . ' ' .
                    $data[$this->_model_name . '_id'], $this->_model_name . '_id' => $id));
            }
        }
        else//failure
        {
            echo json_encode(array('success' => false, 'message' => $this->lang->line(strtolower(get_class()) . '_error_adding_updating') . ' ' .
                $data[$this->_model_name . '_id'], $this->_model_name . '_id' => -1));
        }
    }

    function delete()
    {
        $ids = $this->input->post('ids');
        $controller_name = strtolower(get_class());

        if ($this->_model->delete_list($ids))
        {
            echo json_encode(array('success' => true, 'message' => $this->lang->line($controller_name.'_successful_deleted') . ' ' .
                count($ids) . ' ' . $this->lang->line($controller_name.'_one_or_multiple')));
        }
        else
        {
            echo json_encode(array('success' => false, 'message' => $this->lang->line('loan_type_cannot_be_deleted')));
        }
    }

    function data()
    {
        $order = array("index" => $_REQUEST['order'][0]['column'], "direction" => $_REQUEST['order'][0]['dir']);

        $controller_name = strtolower(get_class());
        $$controller_name = $this->_model->get_all($_REQUEST['length'], $_REQUEST['start'], $_REQUEST['search']['value'], $order);

        $id = strtolower(str_replace("s", "", $controller_name) . "_id");
        $format_result = array();

        $fields = $this->_model->get_fields(ucfirst($controller_name));

        foreach ($$controller_name->result() as $data)
        {
            $tmp = [];
            $tmp[] = "<input type='checkbox' name='chk[]' class='select_' id='" . $controller_name . "_" . $data->$id . "' value='" . $data->$id . "'/>";
            foreach ($fields as $field)
            {
                $tmp[] = $data->$field;
            }
            $tmp[] = anchor($controller_name . '/view/' . $data->$id, $this->lang->line('common_edit'), array('class' => 'modal_link btn btn-success', 'data-toggle' => 'modal', 'data-target' => '#loan_type_modal', "title" => $this->lang->line($controller_name.'_update')));
            $format_result[] = $tmp;
        }

        $data = array(
            "recordsTotal" => $this->_model->count_all(),
            "recordsFiltered" => $this->_model->count_all(),
            "data" => $format_result
        );

        echo json_encode($data);
        exit;
    }

    public function get_row()
    {
        
    }

    public function search()
    {
        
    }

    public function suggest()
    {
        
    }

    public function get_form_width()
    {
        
    }

}

?>