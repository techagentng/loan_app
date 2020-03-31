<?php

require_once ("interfaces/iperson_controller.php");
require_once ("Secure_area.php");

abstract class Person_controller extends Secure_area implements iPerson_controller {

    function __construct($module_id = null)
    {
        parent::__construct($module_id);
    }

    /*
      This returns a mailto link for persons with a certain id. This is called with AJAX.
     */

    function mailto()
    {
        $people_to_email = $this->input->post('ids');

        if ($people_to_email != false)
        {
            $mailto_url = 'mailto:';
            foreach ($this->Person->get_multiple_info($people_to_email)->result() as $person)
            {
                $mailto_url.=$person->email . ',';
            }
            //remove last comma
            $mailto_url = substr($mailto_url, 0, strlen($mailto_url) - 1);

            echo $mailto_url;
            exit;
        }
        echo '#';
    }

    /** GARRISON ADDED 4/25/2013 IN PROGRESS * */
    /*
      Gives search suggestions based on what is being searched for
     */
    function suggest()
    {
        $suggestions = $this->Person->get_search_suggestions($this->input->post('q'), $this->input->post('limit'));
        echo implode("\n", $suggestions);
    }

    /*
      Gets one row for a person manage table. This is called using AJAX to update one row.
     */

    function get_row()
    {
        $person_id = $this->input->post('row_id');
        $data_row = get_person_data_row($this->Person->get_info($person_id), $this);
        echo $data_row;
    }

    function upload()
    {
        $directory = FCPATH . 'uploads/profile-' . $_REQUEST["user_id"] . "/";
        $this->load->library('uploader');
        $data = $this->uploader->upload($directory);

        // let's hold a session data as well, guess will be using it for new person
        $this->load->library('session');
        $this->session->set_userdata(array("data" => $data));

        $this->Person->save_photo($data['params']['user_id'], $data);
    }

}

?>