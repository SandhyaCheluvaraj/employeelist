<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		$data['employees'] = $this->employees->getemployees();
		//echo '<pre>';
		//print_r($data);exit;
		$this->load->view('index',$data);
	}
	public function search($keyword=''){
		$employees = $this->employees->search($keyword);
		foreach($employees as $employee){
			$emprole = $this->roles->getrole(array('role_type'=>$employee['emp_role']));
            ?>
            <tr>
                <td><?php echo $employee['empId'];?></td>
                <td><?php echo $employee['emp_name'];?></td>
                <td><?php echo $emprole['role_name'];?></td>
                <td><?php echo date('d M Y, h:i A',strtotime($employee['added_on']));?></td>
                <td>
				<div class="custom-control custom-switch">
					<?php
                    $checked = ($employee['status']== 0) ? "" : " checked";
                    ?>
                    <input type="checkbox" <?php echo $checked;?> class="custom-control-input" id="customSwitches<?php echo $employee['empId'];?>" data-id="<?php echo $employee['empId'];?>">
                    <label class="custom-control-label" for="customSwitches<?php echo $employee['empId'];?>"></label></td>
				</div>
            </tr>
            <?php
		}

	}
	public function getemployeesbyrole($empid){
		$empData = $this->db->query('with recursive cte_connect_by as ( select 1 as level, s.* from `employee_details` s where empId='.$empid.' union all select level + 1 as level, s.* from cte_connect_by r inner join `employee_details` s on r.empId=s.reporting_to ) select * from cte_connect_by order by emp_role')->result_array();
		foreach($empData as $employee){
			$emprole = $this->roles->getrole(array('role_type'=>$employee['emp_role']));
            ?>
            <tr>
                <td><?php echo $employee['empId'];?></td>
                <td><?php echo $employee['emp_name'];?></td>
                <td><?php echo $emprole['role_name'];?></td>
                <td><?php echo date('d M Y, h:i A',strtotime($employee['added_on']));?></td>
                <td><?php
                    $checked = ($employee['status']== 0) ? "" : " checked";
                    ?>
					<div class="custom-control custom-switch">

                    <input type="checkbox" <?php echo $checked;?> class="custom-control-input" id="customSwitches<?php echo $employee['empId'];?>" data-id="<?php echo $employee['empId'];?>">
                    <label class="custom-control-label" for="customSwitches<?php echo $employee['empId'];?>"></label></td>
				</div>
            </tr>
            <?php
		}
	}
	public function updatestatus(){
		$empid = $this->input->post('id');	
		$status = $this->input->post('status');	
		$this->db->update('employee_details',array('status'=>$status),array('empId'=>$empid));
		print_r($empid);
		print_r($this->input->post());
	}
}
