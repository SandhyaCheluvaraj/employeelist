
<?php
//echo '<pre>';
//print_r($employees);
$roles = $this->roles->getroles();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Employee List</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link rel="stylesheet" href="assets/docs/css/bootstrap-4.5.2.min.css" type="text/css">
    <link rel="stylesheet" href="assets/docs/css/bootstrap-example.min.css" type="text/css">
    <link rel="stylesheet" href="assets/docs/css/prettify.min.css" type="text/css">
    <link rel="stylesheet" href="assets/docs/css/fontawesome-5.15.1-web/all.css" type="text/css">

    <script type="text/javascript" src="assets/docs/js/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="assets/docs/js/bootstrap.bundle-4.5.2.min.js"></script>
    <script type="text/javascript" src="assets/docs/js/prettify.min.js"></script>

    <link rel="stylesheet" href="assets/dist/css/bootstrap-multiselect.css" type="text/css">
    <script type="text/javascript" src="assets/dist/js/bootstrap-multiselect.js"></script>

</head>
<body>

<div class="container mt-3">
  <h2 style="Width:100%;">Employee List
  
    <span style="float:right;font-size:16px;display:flex">
        <input type="text" name="search" id="search" class="form-control" placeholder="Search by Name or Id" style="margin-right:10px;"/>
         <select id="example-multiple-optgroups" class="empl">
            <?php
            foreach($roles as $role){
                $roleid = $role['role_type'];
                $rolename = $role['role_name'];
                $rolemployees = $this->employees->getemployees(array('emp_role'=>$roleid));

                ?>
                <optgroup label="<?php echo $rolename;?>">
                    <?php
                    foreach($rolemployees as $emps){
                        ?>
                        <option value="<?php echo $emps['empId'];?>"><?php echo $emps['emp_name'];?></option>
                        <?php
                    }
                    ?>
                    
                </optgroup>
                <?php
            }
            ?>
        </select>



    </span>
    </h2>
    <hr>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Emplyoee ID</th>
        <th>Emplyoee Name</th>
        <th>Role</th>
        <th>Added on</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody id="empDt">
        <?php
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
                    <label class="custom-control-label" for="customSwitches<?php echo $employee['empId'];?>"></label>
                </div>

            </td>
            </tr>
            <?php

            // select * from employee_details connect by prior empId=reporting_to start with empId=2 order by emp_role 
        }
      ?>
      
    </tbody>
  </table>
  <input type="hidden" id="base_url" value=""/>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#example-multiple-optgroups').multiselect({
            maxHeight: 300,
            buttonWidth: '100px',
            includeSelectAllOption: true,
            allSelectedText: 'Showing All',
            onChange: function(option, checked, select) {
                var opselected = $(option).val();
                $.ajax({
                    url: "getemployeesbyrole/"+opselected,
                    type: 'GET',
                    success: function(res) {
                        console.log(res);
                        $('#empDt').html(res);

                    }
                });
            }
        });

        var queryString = '';
        $("#search").on("keyup", function(e) {
            queryString = $(this).val();
            //if(queryString != ''){
                $.ajax({
                    url: "search/"+queryString,
                    type: 'GET',
                    success: function(res) {
                        console.log(res);
                        $('#empDt').html(res);
                    }
                });
           // }

        });
        

    });
$(document).on("change", '.custom-control-input', function(e) { 

//$(".custom-control-input").on("change", function(e) {

    var empid = $(this).attr('data-id');
    // if ($(this).is(':checked')) {
    //     var status = 1;
    // }else{
    //     var status = 0;
    // }
    var status = ($(this).is(':checked')) ? 1 : 0;

    var formData = new FormData();
    formData.append('id', empid);
    formData.append('status', status);

    $.ajax({
        url: "updatestatus",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,

        success: function(res) {
        }
    });
});
</script>

</body>
</html>
