<?php

require_once ("Person_controller.php");

class Employees extends Person_controller {
    
    function __construct()
    {
        parent::__construct('employees');
        $this->load->model("Role");    
        
        $this->load->library('DataTableLib');
    }

    function index()
    {
        $res = $this->Employee->getLowerLevels();
        $data['staffs'] = $res;
        
        $data["has_lower_level"] = $this->Employee->hasLowerLevel();
        
        $this->set_dt_employees($this->datatablelib->datatable());
        $data["tbl_employees"] = $this->datatablelib->render();
        
        $this->load->view('employees/list', $data);
    }
    
    function ajax()
    {
        $type = $this->input->post('type');
        switch ($type)
        {
            case 1: // Get employees table
                $this->_dt_employees();
                break;
            case 2: // Archive employee
                $this->delete();
                break;
            
        }
    }
    
    function set_dt_employees($datatable)
    {
        $datatable->add_server_params('', '', [$this->security->get_csrf_token_name() => $this->security->get_csrf_hash(), "type" => 1]);
        $datatable->ajax_url = site_url('employees/ajax');

        $datatable->add_column('actions', false);
        $datatable->add_column('username', false);
        $datatable->add_column('last_name', false);
        $datatable->add_column('first_name', false);
        $datatable->add_column('user_type', false);
        $datatable->add_column('email', false);
        $datatable->add_column('phone_number', false);
        

        $datatable->add_table_definition(["orderable" => false, "targets" => 0]);
        $datatable->order = [[1, 'desc']];

        $datatable->allow_search = true;
        
        $datatable->table_id = "#tbl_employees";
        $datatable->add_titles('Employees');
        $datatable->has_edit_dblclick = 0;
    }

    function _dt_employees()
    {
        $selected_user = $this->input->post("employee_id");
        $status = $this->input->post("status");

        $offset = $this->input->post("start");
        $limit = $this->input->post("length");

        $index = $this->input->post("order")[0]["column"];
        $dir = $this->input->post("order")[0]["dir"];
        $keywords = $this->input->post("search")["value"];

        $order = array("index" => $index, "direction" => $dir);
        
        $people = $this->Employee->get_all($limit, $offset, $keywords, $order);
        
        $tmp = array();

        $count_all = 0;
        foreach ($people->result() as $person)
        {
            $actions = "<a href='" . site_url('employees/view/' . $person->person_id) . "' class='btn btn-xs btn-default btn-secondary' title='View'><span class='fa fa-eye'></span></a> ";
            $actions .= "<a href='javascript:void(0)' class='btn btn-xs btn-danger btn-delete' data-employee-id='" . $person->person_id . "' title='Delete'><span class='fa fa-trash'></span></a>";

            $data_row = [];
            $data_row["DT_RowId"] = $person->person_id;
            $data_row["actions"] = $actions;
            
            $data_row["username"] = $person->username;
            $data_row["last_name"] = $person->last_name;
            $data_row["first_name"] = $person->first_name;
            $data_row["user_type"] = ucwords($person->user_type);
            $data_row["email"] = $person->email;
            $data_row["phone_number"] = $person->phone_number;

            $tmp[] = $data_row;
            $count_all++;
        }

        $data["data"] = $tmp;
        $data["recordsTotal"] = $count_all;
        $data["recordsFiltered"] = $count_all;

        send($data);
    }

    /*
      Returns employee table data rows. This will be called with AJAX.
     */

    function search()
    {
        $search = $this->input->post('search');
        $data_rows = get_people_manage_table_data_rows($this->Employee->search($search), $this);
        echo $data_rows;
    }

    /*
      Gives search suggestions based on what is being searched for
     */

    function suggest()
    {
        $suggestions = $this->Employee->get_search_suggestions($this->input->post('q'), $this->input->post('limit'));
        echo implode("\n", $suggestions);
    }

    /*
      Loads the employee edit form
     */

    function view($employee_id = -1)
    {
        if (is_plugin_active('activity_log'))
        {
            if ( $employee_id > 0 )
            {
                $user_id = $this->Employee->get_logged_in_employee_info()->person_id;
                track_action($user_id, "Employees", "Viewed employee: " . $employee_id);
            }
        }
        
        $data['person_info'] = $this->Employee->get_info($employee_id);
        $data['all_modules'] = $this->Module->get_all_modules();
        $data['all_subpermissions'] = $this->Module->get_all_subpermissions();
        
        $role_info = $this->Role->get_info( $this->Employee->get_logged_in_employee_info()->role_id );
        $low_levels = $role_info->low_level !== "false" ? json_decode($role_info->low_level) : false;
        $data['roles'] = $this->Role->get_all_roles($low_levels);
        $this->load->view("employees/form", $data);
    }

    /*
      Inserts/updates an employee
     */

    function save($employee_id = -1)
    {
        $person_data['first_name'] = $this->input->post('first_name');
        $person_data['last_name'] = $this->input->post('last_name');
        $person_data['email'] = $this->input->post('email');
        $person_data['phone_number'] = $this->input->post('phone_number');
        $person_data['address_1'] = $this->input->post('address_1');
        $person_data['address_2'] = $this->input->post('address_2');
        $person_data['city'] = $this->input->post('city');
        $person_data['state'] = $this->input->post('state');
        $person_data['zip'] = $this->input->post('zip');
        $person_data['country'] = $this->input->post('country');
        $person_data['comments'] = $this->input->post('comments');
        $person_data['role_id'] = $this->input->post('role_id');
        
        $grants_data = json_decode($this->Role->get_info($this->input->post("role_id"))->rights, TRUE );

        //Password has been changed OR first time password set
        if ($this->input->post('password') != '')
        {
            $employee_data = array(
                'username' => $this->input->post('username'),
                'password' => md5($this->input->post('password'))
            );
        }
        else //Password not changed
        {
            $employee_data = array('username' => $this->input->post('username'));
        }
        
        $employee_data["can_approve_loan"] = $this->input->post("can_approve_loan");

        if ($this->Employee->save($person_data, $employee_data, $employee_id, $grants_data))
        {
            //New employee
            if ($employee_id == -1)
            {
                echo json_encode(array('success' => true, 'message' => $this->lang->line('employees_successful_adding') . ' ' .
                    $person_data['first_name'] . ' ' . $person_data['last_name'], 'person_id' => $employee_data['person_id']));
            }
            else //previous employee
            {
                echo json_encode(array('success' => true, 'message' => $this->lang->line('employees_successful_updating') . ' ' .
                    $person_data['first_name'] . ' ' . $person_data['last_name'], 'person_id' => $employee_id));
            }
        }
        else//failure
        {
            echo json_encode(array('success' => false, 'message' => $this->lang->line('employees_error_adding_updating') . ' ' .
                $person_data['first_name'] . ' ' . $person_data['last_name'], 'person_id' => -1));
        }
    }

    /*
      This deletes employees from the employees table
     */

    function delete()
    {
        $employees_to_delete = $this->input->post('ids');

        if ($this->Employee->delete_list([$employees_to_delete]))
        {
            echo json_encode(array('success' => true, 'message' => $this->lang->line('employees_successful_deleted') . ' ' .
                count($employees_to_delete) . ' ' . $this->lang->line('employees_one_or_multiple')));
        }
        else
        {
            echo json_encode(array('success' => false, 'message' => $this->lang->line('employees_cannot_be_deleted')));
        }
    }

    /*
      get the width for the add/edit form
     */

    function get_form_width()
    {
        return 650;
    }

    function data()
    {
        $index = isset($_GET['order'][0]['column']) ? $_GET['order'][0]['column'] : 1;
        $dir = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : "asc";
        $order = array("index" => $index, "direction" => $dir);
        $length = isset($_GET['length']) ? $_GET['length'] : 50;
        $start = isset($_GET['start']) ? $_GET['start'] : 0;
        $key = isset($_GET['search']['value']) ? $_GET['search']['value'] : "";

        $people = $this->Employee->get_all($length, $start, $key, $order);

        $format_result = array();

        $width = 50;

        foreach ($people->result() as $person)
        {
            $format_result[] = array(
                "<input type='checkbox' name='chk[]' id='person_$person->person_id' value='" . $person->person_id . "'/>",
                $person->username,
                $person->last_name,
                $person->first_name,
                ucwords($person->user_type),
                $person->email,
                $person->phone_number,
                anchor('employees/view/' . $person->person_id, $this->lang->line('common_edit'), array('class' => 'btn btn-success'))
            );
        }

        $data = array(
            "recordsTotal" => $this->Employee->count_all(),
            "recordsFiltered" => $this->Employee->count_all(),
            "data" => $format_result
        );

        echo json_encode($data);
        exit;
    }
    
    function employee_search()
    {
        $suggestions = $this->Employee->get_employee_search_suggestions($this->input->get('query'), 30);
        $data = $tmp = array();

        foreach ($suggestions as $suggestion):
            $t = explode("|", $suggestion);
            $tmp = array("value" => $t[1], "data" => $t[0], "email" => $t[2]);
            $data[] = $tmp;
        endforeach;

        echo json_encode(array("suggestions" => $data));
        exit;
    }
}

?>