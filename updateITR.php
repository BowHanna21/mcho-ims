<?php
include("db_connect.php");
session_start();

if (isset($_POST['deletebutton']))
{
    $itrID = $_POST['iddelete'];
    $userid = $_SESSION['userid'];
    $sql = "SELECT fname, lname from acc_info where ai_id=$userid";
    $result = mysqli_query($con,$sql);
    $row = mysqli_fetch_array($result);
    $archivedby =$row['fname']." ".$row['lname'];

    $select = "SELECT fname,mname,lname from name inner join patient_enrollment inner join indiv_treat_rec on name.n_id=patient_enrollment.n_id and patient_enrollment.pe_id=indiv_treat_rec.pe_id and indiv_treat_rec.itr_id='$itrID' ";
    $resultselect = mysqli_query($con,$select);
    $row = mysqli_fetch_array($resultselect);
    $name = $row['fname']." ".$row['mname']." ".$row['lname'];

    $sqldeleteITR = "UPDATE indiv_treat_rec set status='inactive', archived_by='$archivedby' where itr_id='$itrID'";
    if(!mysqli_query($con,$sqldeleteITR))
    {
        echo "<script type='text/javascript'>
                alert('Unsuccessfully Archived');
            </script>";
    }
        else
    {
        $sqllog = "INSERT INTO system_log (date_time,action,user,subject) values( CURRENT_TIMESTAMP, 'Archived Patient Treatment', '$archivedby', '$name')";
        $resultlog = mysqli_query($con,$sqllog) or die(mysql_error($con));
        echo "<script type='text/javascript'>
                alert('Successfully Archived');
            </script>";
    }
}
    



if(isset($_POST['updatebutton']))
{
    $serialno = $_POST['f_serialno'];
    $itrID = $_POST['f_itrid'];
    $age = $_POST['f_age'];
    $fname = $_POST['f_fname'];
    $mname = $_POST['f_mname'];
    $lname = $_POST['f_lname'];
    $modetransact = $_POST['f_modeoftransact'];
    $dateconsult = $_POST['f_dateofconsult'];
    $timeconsult = $_POST['f_consulttime'];
    $bloodpressure = $_POST['f_bloodpressure'];
    $temperature = $_POST['f_temp'];
    $height = $_POST['f_height'];
    $weight = $_POST['f_weight'];
    $nameofattending = $_POST['f_attendingofficer'];    
    $referredfrom = $_POST['f_referredfrom'];
    $referredto = $_POST['f_referredto'];
    $reasonofref = $_POST['f_reasonofref'];
    $referredby = $_POST['f_referredby'];
    $natureofvisit = $_POST['f_natureofvisit'];
    $chiefcomplaints = $_POST['f_chiefcomplaints'];
    $diagnosis = $_POST['f_diagnosis'];
    $medication = $_POST['f_medication'];
    $labfindings = $_POST['f_labfindings'];
    $healthcare = $_POST['f_healthcare'];
    $labtest = $_POST['f_labtest'];
    $chronic = $_POST['f_chronic'];

    $sqlselect = "SELECT * from indiv_treat_rec where itr_id = '$itrID'";
    $resultselect = mysqli_query($con,$sqlselect);
    $row = mysqli_fetch_array($resultselect);

    $forCHURHUid = $row['fcr_id'];
    $refertransactid = $row['ref_tran_id'];
    $treatmentid = $row['treatment_id'];


    $sqlupdateforchurhu = "UPDATE for_chu_rhu SET mode_transaction='$modetransact', date_consultation='$dateconsult', time_consultation='$timeconsult', blood_pressure='$bloodpressure', temperature='$temperature', height='$height', weight='$weight', name_of_attending='$nameofattending', age='$age' where fcr_id='$forCHURHUid'";
    $resultupdateforchurhu  = mysqli_query($con, $sqlupdateforchurhu);


    $sqlupdaterefertransact = "UPDATE referral_transaction SET referred_from='$referredfrom', referred_to='$referredto', reason_of_referral='$reasonofref', referred_by='$referredby', chronic_disease='$chronic' where ref_tran_id='$refertransactid'";
    $resultupdaterefertransact  = mysqli_query($con, $sqlupdaterefertransact);


    $sqlupdatetreatment = "UPDATE treatment SET nature_of_visit='$natureofvisit', chief_complaints='$chiefcomplaints', diagnosis='$diagnosis', medication='$medication', lab_findings='$labfindings', name_health_careprovider='$healthcare', performed_lab_test='$labtest' where treatment_id='$treatmentid'";
    $resultupdatetreatment  = mysqli_query($con, $sqlupdatetreatment);

    $id = $_SESSION['userid'];
    $sql = "SELECT fname, lname from acc_info where ai_id=$id";
    $result = mysqli_query($con,$sql);
    $rowuser = mysqli_fetch_array($result);
    $addedby = $rowuser['fname'] . " " . $rowuser['lname'];

    if(!$resultupdatetreatment and !$resultupdateforchurhu and !$resultupdaterefertransact)
    {
        echo "<script type='text/javascript'>
                alert('Unsuccessfully Updated');
            </script>";
    }   
    else
    {
        $sqllog = "INSERT INTO system_log (date_time,action,user,subject) values( CURRENT_TIMESTAMP, 'Edit Patient Treatment', '$addedby', '$fname $mname $lname')";
        $resultlog = mysqli_query($con,$sqllog) or die(mysql_error($con));
        echo "<script type='text/javascript'>
                alert('Successfully Updated');
            </script>";
    } 

}


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>DevOOPS</title>
        <meta name="description" content="description">
        <meta name="author" content="DevOOPS">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">
        <link href="plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
        <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
        <link href="plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
        <link href="plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
        <link href="plugins/xcharts/xcharts.min.css" rel="stylesheet">
        <link href="plugins/select2/select2.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
                <script src="http://getbootstrap.com/docs-assets/js/html5shiv.js"></script>
                <script src="http://getbootstrap.com/docs-assets/js/respond.min.js"></script>
        <![endif]-->
    </head>
<body>
<!--Start Header-->
<div id="screensaver">
    <canvas id="canvas"></canvas>
    <i class="fa fa-lock" id="screen_unlock"></i>
</div>
<div id="modalbox">
    <div class="devoops-modal">
        <div class="devoops-modal-header">
            <div class="modal-header-name">
                <span>Basic table</span>
            </div>
            <div class="box-icons">
                <a class="close-link">
                    <i class="fa fa-times"></i>
                </a>
            </div>
        </div>
        <div class="devoops-modal-inner">
        </div>
        <div class="devoops-modal-bottom">
        </div>
    </div>
</div>
<header class="navbar">
    <div class="container-fluid expanded-panel">
        <div class="row">
            <div id="logo" class="col-xs-12 col-sm-2">
                <a href="homeOIC.php?userid=<?php $id=$_SESSION['userid']; echo $id; ?>">MCHOIMS</a>
            </div>
            <div id="top-panel" class="col-xs-12 col-sm-10">
                <div class="row">
                    <div class="col-xs-8 col-sm-4">
                        <a href="#" class="show-sidebar">
                          <i class="fa fa-bars"></i>
                        </a>
                    </div>
                    <div class="col-xs-4 col-sm-8 top-panel-right">
                        <ul class="nav navbar-nav pull-right panel-menu">
                            
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle account" data-toggle="dropdown">                                 
                                    <i class="fa fa-angle-down pull-right"></i>
                                    <div class="user-mini pull-right">
                                        <span class="welcome">Welcome,</span>
                                        <span><?php $id=$_SESSION['userid'];
                                        $sql = "SELECT fname, lname from acc_info where ai_id=$id";
                                        $result = mysqli_query($con,$sql);
                                        $row = mysqli_fetch_array($result);
                                        if(!$row)
                                        {
                                            header("Location: index.php");
                                        }
                                        else{
                                        echo $row['fname'] . " " . $row['lname'];
                                                                                    }
                                        ?></span>
                                    </div>
                                </a>
                                <ul class="dropdown-menu">
                                    
                                    <li>
                                        <a href="profileofficer.php?userid=<?php $id=$_SESSION['userid']; echo $id; ?>">
                                            <i class="fa fa-user"></i>
                                            <span class="hidden-sm text">Profile</span>
                                        </a>
                                        <a href="index.php">
                                            <i class="fa fa-power-off"></i>
                                            <span class="hidden-sm text">Logout</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!--End Header-->

<!--Start Container-->
<div id="main" class="container-fluid">
    <div class="row">
        <div id="sidebar-left" class="col-xs-2 col-sm-2">
            <ul class="nav main-menu">
                <li>
                    <a href="homeOIC.php?userid=<?php $id=$_SESSION['userid']; echo $id; ?>">
                        <i class="fa fa-dashboard"></i>
                        <span class="hidden-xs">Home</span>
                    </a>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        <i class="fa fa-medkit"></i>
                        <span class="hidden-xs">Activities/Programs</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="updateactivity.php?userid=<?php $id=$_SESSION['userid']; echo $id; ?>">Update Activities/Programs</a></li>
                        <li><a href="addactivity.php?userid=<?php $id=$_SESSION['userid']; echo $id; ?>">Add Activities/Programs</a></li>

                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        <i class="fa fa-list-alt"></i>
                         <span class="hidden-xs">Health Records</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle">
                                <i class="fa fa-plus-square"></i>
                                <span class="hidden-xs">Patient Enrollment Records</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="viewPER.php?userid=<?php $id=$_SESSION['userid']; echo $id; ?>">List of Records</a></li>
                                <li><a href="updatePER.php?userid=<?php $id=$_SESSION['userid']; echo $id; ?>">Update Records</a></li>
                                <li><a href="addPER.php?userid=<?php $id=$_SESSION['userid']; echo $id; ?>">Add Record</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle">
                                <i class="fa fa-plus-square"></i>
                                <span class="hidden-xs">Individual Treatment Record</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="viewITR.php?userid=<?php $id=$_SESSION['userid']; echo $id; ?>">List of Records</a></li>
                                <li><a href="updateITR.php?userid=<?php $id=$_SESSION['userid']; echo $id; ?>">Update Records</a></li>
                            </ul>
                        </li>
                    </ul>               
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        <i class="fa fa-table"></i>
                         <span class="hidden-xs">Record Request</span><?php
                                $sql="SELECT count(*) as cntupload from temp_per";
                                $result = mysqli_query($con,$sql);
                               $row = mysqli_fetch_array($result);
                               $count = $row['cntupload'];
                               $sql1="SELECT count(*) as cntupload1 from temp_itr";
                                $result1 = mysqli_query($con,$sql1);
                               $row1 = mysqli_fetch_array($result1);
                               $count1 = $row1['cntupload1'];
                               $sum = $count + $count1;
                               if(($count) or ($count1))
                               {
                                echo "<span class='badge'>" . $sum . "</span>   ";
                               }
                               ?>   
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="uploadPER.php?userid=<?php $id=$_SESSION['userid']; echo $id; ?>">PER from User<?php
                                $sql="SELECT count(*) as cntupload from temp_per where added_by = 'user'";
                                $result = mysqli_query($con,$sql);
                               $row = mysqli_fetch_array($result);
                               $count = $row['cntupload'];
                               if($count)
                               {
                                echo "<span class='badge'>". $count ."</span>";
                               }
                               ?></a></li>
                        <li><a href="uploadITR.php?userid=<?php $id=$_SESSION['userid']; echo $id; ?>">ITR from User<?php
                                $sql="SELECT count(*) as cntupload from temp_itrwhere added_by = 'user'";
                                $result = mysqli_query($con,$sql);
                               $row = mysqli_fetch_array($result);
                               $count = $row['cntupload'];
                               if($count)
                               {
                                echo "<span class='badge'>". $count ."</span>";
                               }
                               ?></a></li>
                        <li><a href="uploadPERapp.php?userid=<?php $id=$_SESSION['userid']; echo $id; ?>">PER from Mobile<?php
                                $sql="SELECT count(*) as cntupload from temp_per where added_by = 'userMobile'";
                                $result = mysqli_query($con,$sql);
                               $row = mysqli_fetch_array($result);
                               $count = $row['cntupload'];
                               if($count)
                               {
                                echo "<span class='badge'>". $count ."</span>";
                               }
                               ?></a></li>
                        <li><a href="uploadITRapp.php?userid=<?php $id=$_SESSION['userid']; echo $id; ?>">ITR from Mobile<?php
                                $sql="SELECT count(*) as cntupload from temp_itrwhere added_by = 'userMobile'";
                                $result = mysqli_query($con,$sql);
                               $row = mysqli_fetch_array($result);
                               $count = $row['cntupload'];
                               if($count)
                               {
                                echo "<span class='badge'>". $count ."</span>";
                               }
                               ?></a></li>                               
                        <li><a href="uploadPERbrgy.php?userid=<?php $id=$_SESSION['userid']; echo $id; ?>">PER from Barangay<?php
                                $sql="SELECT count(*) as cntupload from temp_per where added_by='brgy'";
                                $result = mysqli_query($con,$sql);
                               $row = mysqli_fetch_array($result);
                               $count = $row['cntupload'];
                               if($count)
                               {
                                echo "<span class='badge'>". $count ."</span>";
                               }
                               ?></a></li>
                        <li><a href="uploadITRbrgy.php?userid=<?php $id=$_SESSION['userid']; echo $id; ?>">ITR from Barangay<?php
                                $sql="SELECT count(*) as cntupload from temp_itr where added_by='brgy'";
                                $result = mysqli_query($con,$sql);
                               $row = mysqli_fetch_array($result);
                               $count = $row['cntupload'];
                               if($count)
                               {
                                echo "<span class='badge'>". $count ."</span>";
                               }
                               ?></a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="generatereport.php" target="_blank" class="dropdown-toggle">
                        <i class="fa fa-desktop"></i>
                         <span class="hidden-xs">Report</span>
                    </a>
                </li>
                
            </ul>
        </div>
        <!--Start Content-->
        <div id="content" class="col-xs-12 col-sm-10">
            

<div class="row">
    <div id="breadcrumb" class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="homeOIC.php?userid=<?php $id=$_SESSION['userid']; echo $id; ?>">Home</a></li>
            <li><a href="#">Individual Treatment Record</a></li>
            <li><a href="#">Update</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-medkit"></i>
                    <span>Update Individual Treatment Record</span>
                </div>
                <div class="box-icons">
                    <a class="expand-link">
                        <i class="fa fa-expand"></i>
                    </a>
                </div>
                <div class="no-move"></div>
            </div>
            <div class="box-content no-padding">
                <table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-3">
                    <thead>
                        <tr>
                            <th>Family Serial No.</th>
                                        <th>Lastname</th>
                                        <th>Firstname</th>
                                        <th>Middlename</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                   
                        </tr>
                    </thead>
                    <?php
                                    $sql = "SELECT patient_enrollment.family_serial_no, name.lname, name.fname, name.mname, contact_info.home_no, contact_info.street,contact_info.barangay, contact_info.city, indiv_treat_rec.itr_id from indiv_treat_rec inner join patient_enrollment inner join name inner join contact_info on contact_info.ci_id=patient_enrollment.ci_id and name.n_id=patient_enrollment.n_id and patient_enrollment.pe_id=indiv_treat_rec.pe_id  and indiv_treat_rec.status='active';";
                                    $result = mysqli_query($con, $sql) or die("Query fail: " . mysqli_error());
                                ?>
                    <tbody>
                         <?php while ($row = mysqli_fetch_array($result)) { 
                                        echo( "<tr class='trID_" .$row['family_serial_no']. "'>
                                            <td class='serialno'>" . $row['family_serial_no'] . "</td>
                                            <td class='lname'>" . $row[1] . "</td>
                                            <td class='fname'>" . $row[2] . "</td>
                                            <td class='mname'>" . $row[3] . "</td>
                                            <td class='address'>" . $row[4] ." " . $row[5] ." " . $row[6] ." " . $row[7] ."</td>
                                            
                                            <td> <input type='button' value='Edit' id='".$row['itr_id']."' class='btn btn-warning edit_data' />                                
                                            <button type='button' id='".$row['itr_id']."' class='btndelete btn btn-danger'>Archive</button>
                                            </td>
                                            
                                          </tr>"); }

                                      ?>
                    </tbody>

                </table>
            </div>

        </div>
    </div>
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <form role='form' method='post'>
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="myModalLabel">Full Information</h4>
                                        </div>
                                        <div class="modal-body">
                                            

                                            <div class="form-group">
                                                    <label>Family Serial Number</label>
                                                    <input class="form-control" type="hidden" id="m_itrid" name="f_itrid" readonly>
                                                    <input class="form-control" placeholder="Serial Number" id="m_serialno" name="f_serialno" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label>Fullname</label>
                                                    <input class="form-control" placeholder="Firstname" id="m_fname" name="f_fname" readonly>
                                                </div>
                                                 <div class="form-group">   
                                                    <input class="form-control" placeholder="Middlename" id="m_mname" name="f_mname" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <input class="form-control" placeholder="Lastname" id="m_lname" name="f_lname" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <input class="form-control" placeholder="e.g. Jr., Sr., II, III" id="m_suffix" name="f_suffix" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label>Residential Address</label>
                                                    <input class="form-control" placeholder="Street, Barangay, City, Province" id="m_address" name="f_address" readonly>
                                                </div>

                                                <div>
                                                    <label> For CHU/RHU Personnel only</label>
                                                </div>
                                                <div class="form-group">
                                                    <label>Mode of Transaction</label>
                                                    <input class="form-control" placeholder="Walk-in/Visited/Referral" id="m_modeoftransact" name="f_modeoftransact">                                                                                                             
                                                </div>
                                                <div class="form-group">
                                                    <label>Date of Consultation</label>
                                                    <input type="date" class="form-control" name="f_dateofconsult" id="m_dateofconsult">
                                                </div>
                                                <div class="form-group">
                                                    <label>Consultation time</label>
                                                    <input class="form-control" placeholder="e.g. 12:00 am " id="m_consulttime" name="f_consulttime">
                                                </div>
                                                <div class="form-group">
                                                    <label>Age</label>
                                                    <input class="form-control" placeholder="Enter here" id="m_age" name="f_age">
                                                </div>
                                                <div class="form-group">
                                                    <label>Blood Pressure</label>
                                                    <input class="form-control" placeholder="e.g. 80/120" id="m_bloodpressure" name="f_bloodpressure">
                                                </div>
                                                <div class="form-group">
                                                    <label>Height</label>
                                                    <input class="form-control" placeholder="e.g. 5.4 ft (Do NOT use apostrophe)" id="m_height" name="f_height">
                                                </div>
                                                <div class="form-group">
                                                    <label>Temperature</label>
                                                    <input class="form-control" placeholder="e.g. 36 degree C" id="m_temp" name="f_temp">
                                                </div>
                                                <div class="form-group">
                                                    <label>Weight</label>
                                                    <input class="form-control" placeholder="e.g. 50 kg" id="m_weight" name="f_weight">
                                                </div>
                                                <div class="form-group">
                                                    <label>Name of attending Officer</label>
                                                    <input class="form-control" placeholder="Juan Dela Cruz" id="m_attendingofficer" name="f_attendingofficer">
                                                </div>
                                                <div class="form-group">
                                                    <label>Nature of Visit</label>
                                                     <input class="form-control" placeholder="Enter here" name="f_natureofvisit" id="m_natureofvisit">
                                                </div>
                                                
                                                <div>
                                                    <label> For Referral Transaction Only</label>
                                                </div>
                                                <div class="form-group">
                                                    <label>Referred from</label>
                                                    <input class="form-control" placeholder="Enter here" id="m_referredfrom" name="f_referredfrom">
                                                </div>
                                                <div class="form-group">
                                                    <label>Referred to</label>
                                                    <input class="form-control" placeholder="Enter here" id="m_referredto" name="f_referredto">
                                                </div>
                                                <div class="form-group">
                                                    <label>Reason for referral</label>
                                                    <textarea class="form-control" rows="3" id="m_reasonofref" name="f_reasonofref"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Referred by</label>
                                                    <input class="form-control" placeholder="Juan Dela Cruz" id="m_referredby" name="f_referredby">
                                                </div>
                                                <div class="form-group">
                                                    <label>Chronic Disease</label>
                                                    <input class="form-control" id="m_chronic" name="f_chronic">
                                                </div>
                                                <div class="form-group">
                                                    <label>Chief Complaints</label>
                                                    <textarea class="form-control" rows="5" id="m_chiefcomplaints" name="f_chiefcomplaints"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Name of Health Care Provider</label>
                                                    <textarea class="form-control" rows="3" id="m_healthcare" name="f_healthcare"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Performed Laboratory Test</label>
                                                    <textarea class="form-control" rows="3" id="m_labtest" name="f_labtest"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Diagnosis</label>
                                                    <textarea class="form-control" rows="5" id="m_diagnosis" name="f_diagnosis"></textarea>
                                                </div>
                                                <div class="form-group">
                                                            <label>Medication/Treatment</label>
                                                            <textarea class="form-control" rows="5" id="m_medication" name="f_medication"></textarea>
                                                </div>
                                                <div class="form-group">
                                                            <label>Laboratory Findings/Impression</label>
                                                            <textarea class="form-control" rows="5" id="m_labfindings" name="f_labfindings"></textarea>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="updatebutton" class="btn btn-primary">Save changes</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            
                                        </div>
                                    </form>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                             <!--modaldelete-->

                                <div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        <form role='form' method='post'>
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="myModalLabel">Delete user</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete this Record?</p>
                                                <p class="text-danger">This action cannot be undone.</p>
                                                <input class="form-control" type="hidden" name="iddelete" id="m_iddelete" >
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                <button type="submit" name="deletebutton" class="btn btn-danger">Archive</button>
                                            </div>
                                        </form>
                                        </div>
                                        <!-- /.modal-content -->
                                    
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                      
                                

        </div>
        <!--End Content-->
    </div>


</div>
<!--End Container-->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!--<script src="http://code.jquery.com/jquery.js"></script>-->
<script src="plugins/jquery/jquery-2.1.0.min.js"></script>
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="plugins/bootstrap/bootstrap.min.js"></script>
<script src="plugins/justified-gallery/jquery.justifiedgallery.min.js"></script>
<script src="plugins/tinymce/tinymce.min.js"></script>
<script src="plugins/tinymce/jquery.tinymce.min.js"></script>
<!-- All functions for this theme + document.ready processing -->
<script src="js/devoops.js"></script>
<script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
</body>
<script type="text/javascript">
$(document).ready(function() {
    // Load TimePicker plugin and callback all time and date pickers
    LoadTimePickerScript(AllTimePickers);
    // Create jQuery-UI tabs
    $("#tabs").tabs();
    // Sortable for elements
    $(".sort").sortable({
        items: "div.col-sm-2",
        appendTo: 'div.box-content'
    });
    // Droppable for example of trash
    $(".drop div.col-sm-2").draggable({containment: '.dropbox' });
    $('#trash').droppable({
        drop: function(event, ui) {
            $(ui.draggable).remove();
        }
    });
    var icons = {
        header: "ui-icon-circle-arrow-e",
        activeHeader: "ui-icon-circle-arrow-s"
    };
    // Make accordion feature of jQuery-UI
    $("#accordion").accordion({icons: icons });
    // Create UI spinner
    $("#ui-spinner").spinner();
    // Add Drag-n-Drop to boxes
    WinMove();
});
</script>
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
    TestTable1();
    TestTable2();
    TestTable3();
    LoadSelect2Script(MakeSelect2);
}
function MakeSelect2(){
    $('select').select2();
    $('.dataTables_filter').each(function(){
        $(this).find('label input[type=text]').attr('placeholder', 'Search');
    });
}
$(document).ready(function() {
    // Load Datatables and run plugin on tables 
    LoadDataTablesScripts(AllTables);
    // Add Drag-n-Drop feature
    WinMove();
});
</script>

 <script type="text/javascript">


    $(document).ready(function(){
        $('.edit_data').click(function(){
            var itrID = $(this).attr("id");

            $.ajax({
                url:"updateITRquery.php",
                method:"post",
                data:{itrID:itrID},
                dataType:"json",
                success:function(data){
                    $('#m_itrid').val(itrID);
                    $('#m_serialno').val(data.family_serial_no);
                    $('#m_lname').val(data.lname);
                    $('#m_fname').val(data.fname);
                    $('#m_mname').val(data.mname);
                    $('#m_suffix').val(data.suffix);
                    $('#m_age').val(data.age);
                    $('#m_address').val(data.city);
                    $('#m_modeoftransact').val(data.mode_transaction);
                    $('#m_dateofconsult').val(data.date_consultation);
                    $('#m_consulttime').val(data.time_consultation);
                    $('#m_bloodpressure').val(data.blood_pressure);
                    $('#m_temp').val(data.temperature);
                    $('#m_height').val(data.height);
                    $('#m_weight').val(data.weight);
                    $('#m_attendingofficer').val(data.name_of_attending);
                    $('#m_referredfrom').val(data.referred_from);
                    $('#m_referredto').val(data.referred_to);
                    $('#m_reasonofref').val(data.reason_of_referral);
                    $('#m_referredby').val(data.referred_by);
                    $('#m_natureofvisit').val(data.nature_of_visit);
                    $('#m_chiefcomplaints').val(data.chief_complaints);
                    $('#m_diagnosis').val(data.diagnosis);
                    $('#m_medication').val(data.medication);
                    $('#m_labfindings').val(data.lab_findings);
                    $('#m_healthcare').val(data.name_health_careprovider);
                    $('#m_labtest').val(data.performed_lab_test);
                    $('#m_chronic').val(data.chronic_disease);
                    $('#editModal').appendTo('body').modal("show");

                }
            })


            
        });

    $('.btndelete').click(function(){
            var serialnumber = $(this).attr("id");

            $('#m_iddelete').val(serialnumber);
            $('#deletemodal').appendTo('body').modal('show');
        });
    });

     </script>



</body>
</html>
