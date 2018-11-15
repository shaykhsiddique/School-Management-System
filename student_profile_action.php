

<?php 


include 'layout/header_script.php';
 
if(isset($_POST['student_profile'])){
  $per=$_POST['student_profile'];
  $id=$_POST['student_id'];
  $ob=$student_ob->get_program_list(452);
  
  if($per=="personal_info")student_info($id,$student,$student_ob);
  else if($per=="program")program($student,$batch,$program,$id,$user);
  else if($per=="payment"){
    payment();
  }
  
}

if(isset($_POST['send_sms'])){
  $text=$_POST['send_sms'];
  $student_id=$_POST['id'];
  $receive_by=$_POST['recever'];
  $msg=$sms->get_sms($student_id,$text);
  $sms->send_sms_student($student_id,$msg,$receive_by);
}
if(isset($_POST['add_program'])){
  $id=$_POST['add_program'];
  add_program($student_ob,$id);
}

if(isset($_POST['add_batch'])){
  $program_id=$_POST['add_batch'];
  add_batch($program_ob,$program_id);
}
if(isset($_POST['save_program'])){
  $info['student_id']=$_POST['save_program'];
  $info['program_id']=$_POST['program_id'];
  $info['batch_id']=$_POST['batch_id'];
  $info['fee']=$_POST['fee'];
  $info['admit_date']=date('Y-m-d H:i:s');;
  $info['admit_by']=$login_user['id'];
  $site->myprint_r($info); 
  $db->sql_action("admit_program","insert",$info,$msg="no");
}

if(isset($_POST['delete_program'])){
  $info['id']=$_POST['delete_program'];
  $db->sql_action("admit_program","delete",$info,$msg="no");
}

if(isset($_POST['view_program'])){
  $admit_id=$_POST['view_program'];
  view_program($admit_id);
}

if(isset($_POST['update_program'])){
   $info['id']=$_POST['update_program'];
   $info['batch_id']=$_POST['update_batch_id'];
   $info['fee']=$_POST['update_fee'];
   $db->sql_action("admit_program","update",$info,$msg="no");
}


if(isset($_POST['edit_program'])){
  $id=$_POST['edit_program'];
  $info=$student_ob->get_program_list();
  $program_id=$info[$id]['program_id'];
  $name=$program[$program_id]['name'];
  $batch_id=$info[$id]['batch_id'];
  $fee=$info[$id]['fee'];
 
?>


<b class="txt">Select Batch</b>
  <select class="select" id="edit_batch_id">
     <option value="-1">Select <?php echo "$name"; ?> Batch</option>
      <?php $program_ob->select_program_batch($program_id,$batch_id); ?>
  </select>
  <b class="txt">Fee</b>
  <input type="number" required class="select" style="padding: 10px;" value="<?php echo "$fee" ?>" id='edit_fee' name="">
   <center> 
    <button class="save_btn" onclick="update_program(<?php echo "$id"; ?>)">Update Program</button>

<?php
  
}

function student_info($id,$student,$student_ob){
   $info=$student[$id];
   
   $name=$info['name'];
   $nick=$info['nick'];
   $father_name=$info['father_name'];
   $mother_name=$info['mother_name'];
   $personal_mobile=$info['personal_mobile'];
   $father_mobile=$info['father_mobile'];
   $mother_mobile=$info['mother_mobile'];
   $birthday=$info['birth_day'];
   $religion=$info['religion'];
   $address=$info['address'];
   $school=$info['school'];

?>

<div style="text-align: right;margin-bottom: 10px;">
<button class="btn btn-primary btn-xs" onclick="edit_student(<?php echo "$id"; ?>)" style="margin-right: 4px; padding: 10px" title="Edit" data-title="Add Product" data-toggle="modal" data-target="#student_edit_<?php echo "$id"; ?>" ><span class="glyphicon glyphicon-pencil"></span>Edit Student Information</button>

</div>
<div class="panel panel-default">
  
<style type="text/css">
  .td_class{
    width: 20%;
    padding: 25px;
    font-weight: bold;
  }
</style>

  <table class="table table-bordered">
    <tbody> 

        <tr>
          <td class="td_class">Full Name :</td>
          <td><?php echo "$name"; ?></td>
        </tr>
        <tr>
          <td class="td_class">Nick Name:</td>
          <td><?php echo "$nick"; ?></td>
        </tr>
        <tr>
          <td class="td_class">Student Id:</td>
          <td><?php echo "$id"; ?></td>
        </tr>
        <tr>
          <td class="td_class">Father Name: </td>
          <td><?php echo "$father_name"; ?></td>
        </tr>
        <tr>
          <td class="td_class">Mother Name: </td>
          <td><?php echo "$mother_name"; ?></td>
        </tr>
        <tr>
          <td class="td_class">Student Mobile:</td>
          <td><?php echo "$personal_mobile"; ?></td>
        </tr>
        <tr>
          <td class="td_class">Father Mobile: </td>
          <td><?php echo "$father_mobile"; ?></td>
        </tr>
        <tr>
          <td class="td_class">Mother Mobile: </td>
          <td><?php echo "$mother_mobile"; ?></td>
        </tr>
        <tr>
          <td class="td_class">Birthday: </td>
          <td><?php echo "$birthday"; ?></td>
        </tr>
        <tr>
          <td class="td_class">Address: </td>
          <td><?php echo "$address"; ?></td>
        </tr>
        <tr>
          <td class="td_class">School/College: </td>
          <td><?php echo "$school"; ?></td>
        </tr>


    </tbody>
  </table>
        
  </div>

<?php
 }

function program($student,$batch,$program,$id,$user){
  ?>
<div style="text-align: right;margin-bottom: 10px;">
<button class="btn btn-primary btn-xs" onclick="add_program()" style="margin-right: 4px; padding: 10px" title="Edit" data-title="Add Product" data-toggle="modal" data-target="#add_program"><span class="glyphicon glyphicon-pencil"></span> Add Program</button>

</div>
<table style="width: 100%">
  <tr>
    <td class="program_td">Admit ID</td>
    <td class="program_td">Program Name</td>
    <td class="program_td">Batch Name</td>
    
    <td class="program_td">Fee</td>
    <td class="program_td">Admission Date</td>
    <td class="program_td">Admit By</td>
    <td class="program_td">Action</td>
  </tr>
<?php

 foreach ($student[$id]['program_list'] as $key => $value) {
  $id=$value['id'];
  $program_id=$value['program_id'];
  $program_name=$program[$program_id]['name'];
  $batch_id=$value['batch_id'];
  $batch_name=$batch[$batch_id]['name'];
  $day=$batch[$batch_id]['day_string'];
  $start=$batch[$batch_id]['start'];
  $end=$batch[$batch_id]['end'];
  $fee=$value['fee']." TK";
  $admit_date=$value['admit_date'];
  $admit_by=$value['admit_by'];
  $uname=$user[$admit_by]['uname'];

  ?>  
  <tr>
    <td class="program_td_body"><?php echo "$id"; ?></td>
    <td class="program_td_body"><?php echo "$program_name"; ?></td>
    <td class="program_td_body"><?php echo "$batch_name"; ?></td>
    
    <td class="program_td_body"><?php echo "$fee"; ?></td>
    <td class="program_td_body"><?php echo "$admit_date"; ?></td>
    <td class="program_td_body"><?php echo "$uname"; ?></td>
    <td class="program_td_body" style="width: 100px;">
      <div class="btn-toolbar list-toolbar"><center>
      
      <button style="" title="view" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-eye-open" onclick="open_dilog_view_program(<?php echo "$id"; ?>)"></span></button>
      <button style="" title="Edit" onclick="edit_program(<?php echo "$id"; ?>)"  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-pencil"></span></button>
      <button class="btn btn-danger btn-xs" title="Delete"  onclick="open_dilog_delete(<?php echo "$id"; ?>)"><span class="glyphicon glyphicon-trash"></span></button>
    </center></div>
    </td>
  </tr>
 <?php } ?>
</table>

<?php

 }

 function add_program($program_ob,$student_id){
  ?>

  <b class="txt">Select Program</b>
    <select class="select" id="select_program1" onchange="add_batch()">
      <option value="-1"> Select Program </option>
      <?php $program_ob->select_program_by_student($student_id); ?>
    </select>
<div id="add_batch_field"></div>  

   </center>
<?php
 }

 function add_batch($program_ob,$program_id){
   

   $info=$program_ob->get_program_info();
  // print_r($info);
   $info=$info[$program_id];
   $fee=$info['fee'];
   $name=$info['name'];
   ?>
 <b class="txt">Select Batch</b>
  <select class="select" id="select_batch">
     <option value="-1">Select <?php echo "$name"; ?> Batch</option>
      <?php $program_ob->select_batch_option($program_id); ?>
  </select>
  <b class="txt">Fee</b>
  <input type="number" required class="select" style="padding: 10px;" value="<?php echo "$fee" ?>" id='fee' name="">
   <center> 
    <button class="save_btn" onclick="save_program()">Add Program</button>
<?php
 }

 function view_program($admit_id){
  ?>
<div class="row">
  <div class="col-xs-12 col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading resume-heading">
        <div class="row">
          <div class="col-lg-12">

            <div class="col-xs-12 col-sm-3">
              <figure>
                <img style="height: 150px; width: 150px" class="img_info" alt="" src="http://localhost/project/youth/upload/student_photo/10008.jpeg">
              </figure>
              <figure style="margin-top: 15px;">
                <img style="height: 30px; width: 150px" class="" alt="" src="barcode.php?10039">
              </figure>

            </div>

            <div class="col-xs-12 col-sm-9">
              <ul class="list-group" style="font-weight: bold;">
                <li class="list-group-item">Name: Musfiq</li>
                <li class="list-group-item">ID: 10039</li>
                <li class="list-group-item">Program: Medical </li>
                <li class="list-group-item">Batch: Normal</li>
                <li class="list-group-item">Batch Time: 4.00 AM-5.00 AM</li>
                <li class="list-group-item">Batch Day: Saturday</li>
                <li class="list-group-item">Admission Fee: 5000 TK</li>
                <li class="list-group-item">Duration: May 2018</li>
                <li class="list-group-item">Admit By- Rahim</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      
     
      
    </div>

  </div>
</div>
<?php  
 }

 function payment_month(){
  ?>

<div class="row">
  
  <?php for($i=0; $i<10; $i++){ ?>
     <div class="col-md-3 princing-item blue">
          <div class="pricing-divider " style="height: 50px;">
              <h3 class="text-light" style="margin-top: -8px;">January</h3>
            
             <svg class='pricing-divider-img' enable-background='new 0 0 300 50' height='50px' id='Layer_1' preserveAspectRatio='none' version='1.1' viewBox='0 0 300 60' width='300px' x='0px' xml:space='preserve' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns='http://www.w3.org/2000/svg' y='0px'>

          <path class='deco-layer deco-layer--1' d='M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729
  c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z' fill='#FFFFFF' opacity='0.6'></path>
          <path class='deco-layer deco-layer--2' d='M-35.667,44.628c0,0,42.91-34.463,87.51-14.191c77.31,35.141,113.304-1.952,146.639-4.729
  c48.653-4.055,69.939,16.218,69.939,16.218v54.396H-35.667V44.628z' fill='#FFFFFF' opacity='0.6'></path>
          <path class='deco-layer deco-layer--3' d='M43.415,98.342c0,0,48.283-68.927,109.133-68.927c65.886,0,97.983,67.914,97.983,67.914v3.716
  H42.401L43.415,98.342z' fill='#FFFFFF' opacity='0.7'></path>
          <path class='deco-layer deco-layer--4' d='M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428
  c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z' fill='#FFFFFF'></path>
        </svg>
          </div>

          <div class="card-body bg-white mt-0 shadow">
            <ul class="list-unstyled mb-5 position-relative">
              <center>
              <span class="glyphicon glyphicon-ban-circle" style="font-size: 50px;"></span>
              </center>
              <li><b>Total Payment: </b>500</li>
              <li><b>Total Pay: </b>300</li>
              <li><b>Total Due: </b>200</li>
              <li><b>Pay Date: </b>12 apr 2018</li>
              <li><b>Status: </b>Paid</li>
            </ul>
            <button type="button" class="btn btn-lg btn-block btn-custom " style="">View</button>
          </div>
        </div>

      <?php } ?>
</div>

<?php
}
function payment(){
?>

 <div class="row">
  <div class="col-md-12">
  <div class="process">
   <div class="process-row nav nav-tabs">
    <div class="process-step">
     <button type="button" class="btn btn-info btn-circle" data-toggle="tab" href="#menu1"><i class="fa fa-car fa-2x"></i></button>
     <p>Payment Info</p>
    </div>
    <div class="process-step">
     <button type="button" class="btn btn-default btn-circle" data-toggle="tab" href="#menu2"><i class="fa fa-file-text-o fa-3x"></i></button>
     <p>Add Payment</p>
    </div>
    <div class="process-step">
     <button type="button" class="btn btn-default btn-circle" data-toggle="tab" href="#menu3"><i class="fa fa-image fa-3x"></i></button>
     <p>Payment History</p>
    </div>
    <div class="process-step">
     <button type="button" class="btn btn-default btn-circle" data-toggle="tab" href="#menu4"><i class="fa fa-cogs fa-3x"></i></button>
     <p>Add Images</p>
    </div>
    <div class="process-step">
     <button type="button" class="btn btn-default btn-circle" data-toggle="tab" href="#menu5"><i class="fa fa-check fa-3x"></i></button>
     <p>Save & Preview</p>
    </div>
   </div>
  </div>
  </div>
</div>

<?php } ?>






<style type="text/css">
  .student_add .modal-dialog{max-width: 800px; width: 100%;}
  .program_add .modal-dialog{max-width: 450px; width: 100%;}
  
.process-step .btn:focus{outline:none}
.process{display:table;width:100%;position:relative; background:#f2f2f2;padding:15px;border-radius:10px;}
.process-row{display:table-row}
.process-step button[disabled]{opacity:1 !important;filter: alpha(opacity=100) !important}
.process-row:before{top:40px;bottom:0;position:absolute;content:" ";width:100%;height:1px;background-color:#ccc;z-order:0}
.process-step{display:table-cell;text-align:center;position:relative}
.process-step p{margin-top:4px}
.btn-circle{width:80px;height:80px;text-align:center;font-size:12px;border-radius:50%}



  .bg-gradient {
background: #C9D6FF;
background: -webkit-linear-gradient(to right, #E2E2E2, #C9D6FF); 
background: linear-gradient(to right, #E2E2E2, #C9D6FF);

} 
.card-body{
  margin-top: 5px;
border-width: 0px 1px 0px 1px;
  border-style: solid;
  border-color: #E1E6EC;
  padding-left: 5px;
}
.btn_payment{
  background-color: var(--bg-color);
    color: var(--font-color);
}
.text-light{
  text-align: center;
  color: #ffffff;
  font-size: 20px;
}
.pricing-divider {
border-radius: 20px;
background: #C64545;
padding: 1em 0 4em;
position: relative;

}
.blue .pricing-divider{
 background-color: var(--bg-color);
    color: var(--font-color);
}
.green .pricing-divider {
background: #1AA85C; 
}
.red b {
  color:#C64545
}
.blue b {
  color:#2D5772
}
.green b {
  color:#1AA85C
}
.pricing-divider-img {
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 100%;
  height: 40px;
}
.deco-layer {
  -webkit-transition: -webkit-transform 0.5s;
  transition: transform 0.5s;
}
.btn-custom  {
  background-color: var(--bg-color);
  color: var(--font-color);
  border-radius:20px
}
.btn-custom:hover{
  background-color: #374B60;
  color: var(--font-color);
  font-size: 18px;
}

.img-float {
  width:50px; position:absolute;top:-3.5rem;right:1rem
}

.princing-item {
  transition: all 150ms ease-out;
  margin-bottom: 15px;

}
.princing-item:hover {
  transform: scale(1.05);
}
.princing-item:hover .deco-layer--1 {
  -webkit-transform: translate3d(15px, 0, 0);
  transform: translate3d(15px, 0, 0);
}
.princing-item:hover .deco-layer--2 {
  -webkit-transform: translate3d(-15px, 0, 0);
  transform: translate3d(-15px, 0, 0);
}



  .payment_month_header{
    background-color: #555555;
    color: var(--font-color);
    padding: 5px;
    text-align: center;
    font-weight: bold;
  }
  .program_td{
    padding: 10px;
    border-style: solid;
    border-width: 1px;
    background-color: #EFF0F1;
    font-weight: bold;
    font-family: "Big Caslon";
    border-color: #C6C9D1;
    text-align: center;
  }
  .img_info{
    border-radius: 5%;
    border-color: #ffffff;
    border-width: 2px;
    border-style: solid;
  }
  .txt{
    margin-bottom: -15px;
  }
  .program_td_body{
    padding: 10px;
    border-style: solid;
    border-width: 1px;
    background-color: #ffffff;
    border-color: #C6C9D1;
  }
  .select{
    position: relative;
    display: block;
    height: 3em;
    line-height: 3;
    width: 100%;
    overflow: hidden;
    border-radius: .25em;
    display: inline-block;
    display: -webkit-inline-box;
    border: 1px solid #667780;
    margin: 1em 0;
  }
  .save_btn{
    background-color: var(--bg-color);
    color: var(--font-color);
    padding: 10px;
    width: 70%;
    border-width: 0px;
  }
</style>
