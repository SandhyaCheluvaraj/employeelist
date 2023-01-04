<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class Employees extends CI_Model { 
    function __construct() { 
        $this->tableName = 'employee_details'; 
    } 
     
     public function getemployees($where = array()){
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
     public function search($keyword){
        $where = '(empId LIKE "%'.$keyword.'%" or emp_name LIKE "%'.$keyword.'%")';
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