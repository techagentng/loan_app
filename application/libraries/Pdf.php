<?php

class pdf {

    function pdf()
    {
        $CI = & get_instance();
        log_message('Debug', 'mPDF class is loaded.');
    }

    function load($params = NULL)
    {
        include_once APPPATH . '/third_party/mpdf/mpdf.php';

        if ($params == NULL)
        {
            $params = '"en-GB-x","A4","","",10,10,10,10,6,3,"L"';
        }
        
        $tmp = explode(",", $params);
        
        $param = ["en-GB-x","A4","","",10,10,10,10,6,3,"P"];
        $i=0;
        foreach($tmp as $row)
        {
            $param[$i] = trim(str_replace('"','',$row));
            $i++;
        }
        
        return new mPDF($param[0],$param[1],$param[2],$param[3],$param[4],$param[5],$param[6],$param[7],$param[8],$param[9],$param[10]);
    }

}

?>
