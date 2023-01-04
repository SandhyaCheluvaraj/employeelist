<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class Roles extends CI_Model { 
    function __construct() { 
        $this->tableName = 'roles'; 
    } 
     
     public function getrole($where = array()){
        $this->db->select('*'); 
        $this->db->from($this->tableName); 
 
        $this->db->where($where);  
        $query = $this->db->get();  

        $check = $query->num_rows(); 
        if($check > 0){  
            return  $query->row_array();   
        }else{
            return array();
        }   
     }
     public function getroles($where = array()){
        $this->db->select('*'); 
        $this->db->from($this->tableName); 
 
        $this->db->where($where);  
        $query = $this->db->get();  

        $check = $query->num_rows(); 
        if($check > 0){  
            return  $query->result_array();   
        }else{
            return array();
        }   
     }

}