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
	<title>Login | Campfire</title>
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url("img/favicon.ico"); ?>">
	<!--link local CSS files  :  note that javascript is linked at the bottom of page-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/bootstrap.css"); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/bootstrap.min.css"); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/bootstrap-theme.css"); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/bootstrap-theme.min.css"); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/custom.css"); ?>">
</head>
<body>
	<nav class="navbar navbar-inverse" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="<?php echo base_url(); ?>index.php/home"><img src="<?php echo base_url("img/Campfire-logo.png"); ?>"></a>
				<a class="navbar-brand" href="<?php echo base_url(); ?>index.php/home">Campfire</a>
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<div class="collapse navbar-collapse" id="navbar1">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="<?php echo base_url(); ?>index.php/about">About</a></li>
					<?php if ($this->session->userdata('login')){ ?>
					<li><p class="navbar-text">Hello <?php echo $this->session->userdata('uname'); ?></p></li>
					<li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle">Account <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="<?php echo base_url(); ?>index.php/editProfile">Edit Profile</a></li>
							<li><a href="<?php echo base_url(); ?>index.php/myGroups">My Groups</a></li>
							<li><a href="<?php echo base_url(); ?>index.php/myEvents">My Events</a></li>
							<li class="divider"></li>
							<li><a href="<?php echo base_url(); ?>index.php/home/logout">Log Out</a></li>
						</ul>
					</li>
					<?php } else { ?>
					<li><a href="<?php echo base_url(); ?>index.php/login">Login</a></li>
					<li><a href="<?php echo base_url(); ?>index.php/signup">Signup</a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</nav>
	<br/>
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4 well">
			<?php $attributes = array("name" => "loginform");
				echo form_open("login/index", $attributes);?>
				<legend>Login</legend>
				<div class="form-group">
					<label for="name">Email</label>
					<input class="form-control" name="email" placeholder="Enter Email" type="text" value="<?php echo set_value('email'); ?>" />
					<span class="text-danger"><?php echo form_error('email'); ?></span>
				</div>
				<div class="form-group">
					<label for="name">Password</label>
					<input class="form-control" name="password" placeholder="Password" type="password" value="<?php echo set_value('password'); ?>" />
					<span class="text-danger"><?php echo form_error('password'); ?></span>
				</div>
				<div class="form-group">
					<button name="submit" type="submit" class="btn btn-info">Login</button>
					<button name="cancel" type="reset" class="btn btn-info">Cancel</button>
				</div>
			<?php echo form_close(); ?>
			<?php echo $this->session->flashdata('msg'); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4 col-md-offset-4 text-center">	
			New User? <a href="<?php echo base_url(); ?>index.php/signup">Sign Up Here</a>
			</div>
		</div>
	</div>
	<footer class="container-fluid text-center">
		<p><p>&copy; SJSU CMPE165 Fall 2016 Project. All rights reserved.</p>
	</footer>
	<script type="text/javascript" src="<?php echo base_url("assets/js/jquery-1.10.2.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.min.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("assets/js/custom.js"); ?>"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
</body>
</html>
