<!DOCTYPE html>
<html lang='en'>
<META NAME="Author" CONTENT="Peter Curtis, Tyler Jones, Troy Nguyen, Marshall Cargle, Luis Otero, Jorge Aguiniga, Stephen Piazza, Jatinder Verma">
<META NAME="Date" CONTENT="September 1, 2016">
<META NAME="Copyright" CONTENT="SJSU CMPE165 Fall 2016 Project. All rights reserved.">
<META NAME="Robots" CONTENT="all">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Campfire | <?php $ci =& get_instance(); echo ucfirst($ci->uri->segment(1)); ?></title>
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url("assets/img/favicon.png"); ?>">
	<!--link local CSS files  :  note that javascript is linked at the bottom of page-->

	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
	<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/animate.css/3.1.1/animate.min.css" />
	<link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/mdb.css"); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/mdb.min.css"); ?>">
		<!-- Pete -->
	<link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/smalot-bootstrap-datetimepicker/2.3.11/css/bootstrap-datetimepicker.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/styles.css"); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/custom.css"); ?>">


	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
	<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.js"></script>
	<script type="text/javascript" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<!-- Pete -->
	<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js"></script>
	<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>

	<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.4.0/lang/en-gb.js"></script>

	<script type="text/javascript" src="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.0.4/js/bootstrap-transition.js"></script>
	<!-- <script type="text/javascript" src="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.0.4/js/bootstrap-collapse.js"></script> -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/smalot-bootstrap-datetimepicker/2.3.11/js/bootstrap-datetimepicker.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url("assets/js/bootbox.min.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("assets/js/custom.js"); ?>"></script>
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>

</head>
<body data-spy="scroll" data-target=".navbar" data-offset="70">

  <?php
  //Due to a flickering issue on page load, authenticated navbar and normal navbar would be stored in seprate files
  if( $this->session->userdata('login') ){
     $this->load->view('template/auth_nav.php');
  }
  else{
    $this->load->view('template/nav.php');
  }
   ?>
