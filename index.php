<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
ini_set('memory_limit', '1024M');

// Start session
session_start();

// Check if username and password are set in the POST request
if(isset($_POST['username']) && isset($_POST['password'])) {
    // LDAP server details
    $ldapserver = '10.24.0.127';
    $ldapuser = 'cn=Admin,dc=ldap,dc=iitm,dc=ac,dc=in';
    $ldappass = '00o00opio0+$0';
    $ldaptree = 'DC=ldap,DC=iitm,DC=ac,DC=in';

    // Get username and password from POST data
    $ldapuname = trim($_POST['username']);
    $ldappwd = trim($_POST['password']);

    // Connect to LDAP server
    $ldapconn = ldap_connect($ldapserver) or die('Could not connect to LDAP server.');
    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

    if ($ldapconn) {
        // Bind to LDAP server
        $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die('Error trying to bind: ' . ldap_error($ldapconn));

        if ($ldapbind) {
            // Organizational Units (OUs) where users are allowed to login
            $dn[] = 'cn=ccprj05,ou=cc,ou=project,ou=employee,dc=ldap,dc=iitm,dc=ac,dc=in';
            $dn[] = 'ou=employee,dc=ldap,dc=iitm,dc=ac,dc=in';
            $dn[] = 'ou=People,dc=ldap,dc=iitm,dc=ac,dc=in';

            // LDAP filter to search for any user with a common name
            $filter = "(cn=*)";

            // Search LDAP server
            $result = ldap_search($ldapconn, $ldaptree, $filter);
            $entries = ldap_get_entries($ldapconn, $result);

            $search = false;
            // Check if user is found in allowed Organizational Units
            foreach ($entries as $entry) {
                if (in_array($entry['dn'], $dn)) {
                    $search = true;
                    break;
                }
            }

            if ($search) {
                // Assuming these attributes are available in LDAP entries
                $email = $entry["mail"][0];
                $title = $entry["title"][0];
                $employeeid = $entry["employeeid"][0];
                $department = $entry["department"][0];

                // Store user information in session variables
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['dis'] = $dis;
                $_SESSION['email'] = $email;
                $_SESSION['title'] = $title;
                $_SESSION['employeeid'] = $employeeid;
                $_SESSION['department'] = $department;

                // Redirect to registrationform.php
                header("Location: registrationform.php");
                exit;
            } else {
                // User not found in allowed Organizational Units
                $message = "User not found in allowed Organizational Units.";
                echo "<script type='text/javascript'>alert('$message');</script>";
            }
        } else {
            // LDAP Bind failed
            $message = "LDAP Bind failed.";
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
    } else {
        // Could not connect to LDAP server
        $message = "Could not connect to LDAP server.";
        echo "<script type='text/javascript'>alert('$message');</script>";
    }
}
?>
<!-- HTML and CSS styles go here -->

<style>
@import "font-awesome.min.css";
@import "font-awesome-ie7.min.css";
/* Space out content a bit */
body {
  padding-top: 20px;
  padding-bottom: 20px;
}

.container{
width: 1135px !important;
}
/* Everything but the jumbotron gets side spacing for mobile first views */
.header,
.marketing,
.footer {
  padding-right: 15px;
  padding-left: 15px;
}

/* Custom page header */
.header {
  border-bottom: 1px solid #e5e5e5;
}
/* Make the masthead heading the same height as the navigation */
.header h3 {
  padding-bottom: 19px;
  margin-top: 0;
  margin-bottom: 0;
  line-height: 40px;
}

/* Custom page footer */
.footer {
  padding-top: 19px;
  color: #777;
  border-top: 1px solid #e5e5e5;
}

/* Customize container */
@media (min-width: 768px) {
  .container {
/*    max-width: 730px;*/
  }
}
.container-narrow > hr {
  margin: 30px 0;
}

/* Main marketing message and sign up button */
.jumbotron {
  text-align: center;
  border-bottom: 1px solid #e5e5e5;
}
.jumbotron .btn {
  padding: 14px 24px;
  font-size: 21px;
}

/* Supporting marketing content */
.marketing {
  margin: 40px 0;
}
.marketing p + h4 {
  margin-top: 28px;
}

/* Responsive: Portrait tablets and up */
@media screen and (min-width: 768px) {
  /* Remove the padding we set earlier */
  .header,
  .marketing,
  .footer {
    padding-right: 0;
    padding-left: 0;
  }
  /* Space out the masthead */
  .header {
    margin-bottom: 30px;
  }
  /* Remove the bottom border on the jumbotron for visual effect */
  .jumbotron {
    border-bottom: 0;
  }
}
.dropdown-menu{
background-color: none !important;
border: 0px solid #ccc !important;
border: 0px solid rgba(0,0,0,.15) !important;
-webkit-box-shadow: 0 0px 0px rgba(0,0,0,.175) !important;
box-shadow: 0 px 0px rgba(0,0,0,.175) !important;
}
</style>

<link href="bootstrap.min.css" rel="stylesheet" id="bootstrap.min.css">

<!--  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>-->

<!--icon-->
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->

<script src="jquery.min.js"></script>
<script src="jquery.validate.min.js"></script>

<!--<div class="container" style="background:linear-gradient(121deg, #9fb8ad 0%, #1fc8db 51%, #2cb5e8 65%)">
<div class="col-lg-12 well" style="background:linear-gradient(121deg, #9fb8ad 0%, #1fc8db 51%, #2cb5e8 65%);border:none !important;">-->

<div class="container" style="background:linear-gradient(#1fc8db 51%, #2cb5e8 65%)">
<div class="col-lg-12 well" style="background:none;border:none !important;">

<div class="col-sm-12" style="border:0px solid red;">

<div class="col-sm-2" style="border:0px solid red;height:15%;margin-left:70px;">
<img src="iitlogo.png" style="width:130%;height:90%;">
</div>

<div class="col-sm-8" style="border:0px solid red;height:15%;color:white;margin-top:1.3%;">
<h3>Antivirus for IITM Faculty and Staff</h3>
</div>
<!--<center><img src="saaranglogo.png"></center><br>-->

</div>


<div class="row">

<div class="col-sm-8" style="border:0px solid red;margin-left:15%;margin-right:15%;background:white;padding:20px;">
<form method="post" action="" style="">
<div class="row">
<br><div class="col-sm-6 form-group" style="margin-left:15%;margin-right:15%;margin-bottom:3%;">
		<input type="text" name="username" class="form-control" placeholder="IITM ADS Username " style="width:140% !important;height:40px !important;" value="" required/>
	</div>
</div>

<div class="row">
	<div class="col-sm-6 form-group" style="margin-left:15%;margin-right:15%;margin-bottom:5%;">
	<input type="password" name="password" class="form-control" placeholder="Password " style="width:140% !important;height:40px !important;" value="" required/>
        </div>
</div>

<div class="row">
<div class="col-sm-6 form-group" style="margin-left:15%;margin-right:15%;margin-bottom:5%;font-size:14px;">
Note: Login using your <strong>IITM LDAP Username </strong>
</div>
</div>

<center><input type="submit" class="btn btn-lg btn-info" value="Login" style="width:22%;border-radius:25px;font-weight:bold;background:linear-gradient(121deg, #9fb8ad 0%, #1fc8db 51%, #2cb5e8 65%);"></center>
</form>

</div>

</div>
</div>
<center><font style="color:white;font-size:17px;">Copyright © 2020 All rights reserved | Developed and Maintained by <a href="https://eservices.iitm.ac.in" style="color:white;">Eservices,IITM</a></font></center>
<br>
</div>

<?php
session_start();
$_SESSION['usernamee'] =$_POST['username'];
$_SESSION['dis'] =$dis;
$_SESSION['mail'] =$email;
$_SESSION['title'] =$title;
$_SESSION['employeeid'] =$employeeid;
$_SESSION['department']=$department;
?>

