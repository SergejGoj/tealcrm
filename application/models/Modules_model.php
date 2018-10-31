<?php

/**
 * Created by PhpStorm.
 * User: kkuk6
 * Date: 10/30/2018
 * Time: 11:17 AM
 */
class Modules_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function add_fields_list($type, $fields)
    {
        if ($type != "" && $fields != "") {
            $field = explode("-", $fields);
            $fields_json = json_encode($field);

            $this->db->where("directory", $type);
            $this->db->update("sc_modules", array("list_layout"=>$fields_json));
        }
    }

    function get_fields_list($type){
        if($type != ""){
            $this->db->select("list_layout")->from("sc_modules");
            $this->db->where("directory", $type);
            $result = $this->db->get()->result();
            if($result){
                $fields_json = $result[0]->list_layout;
                $fields = json_decode($fields_json);
                $fields_list = array();
                if(isset($fields)){
                    foreach($fields as $fld){
                        if(isset($_SESSION["field_dictionary"][$type][$fld])){
                            $fields_list[] = (object)$_SESSION["field_dictionary"][$type][$fld];
                        }
                        else{
                            $fields_list[] = (object) ["field_name" => $fld];
                        }
                    }
                }

                return $fields_list;
            }
        }
    }
}