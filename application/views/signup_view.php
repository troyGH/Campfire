<!-- Header -->
<?php $this->load->view('template/header.php'); ?>
<!-- End Header -->

<!-- Body -->
<div class="container custom-body">
	<div class="row">
		<div class="col-md-4 col-md-offset-4 well">
			<?php $attributes = array("name" => "signupform");
			echo form_open("signup/index", $attributes);?>
			<legend>Signup</legend>
			<?php echo $this->session->flashdata('msg'); ?>
			<div class="form-group">
				<label for="name">First Name</label>
				<input id = 'fname' class="form-control" name="fname" placeholder="First Name" type="text" value="<?php echo set_value('fname'); ?>" />
				<span id = 'fname_error' class="text-danger"><?php echo form_error('fname'); ?></span>
			</div>
			<div class="form-group">
				<label for="name">Last Name</label>
				<input id = 'lname' class="form-control" name="lname" placeholder="Last Name" type="text" value="<?php echo set_value('lname'); ?>" />
				<span id = 'lname_error' class="text-danger"><?php echo form_error('lname'); ?></span>
			</div>
			<div class="form-group">
				<label for="email">Email</label>
				<input id = 'email' class="form-control" name="email" placeholder="Email" type="text" value="<?php echo set_value('email'); ?>" />
				<span id = 'email_error' class="text-danger"><?php echo form_error('email'); ?></span>
			</div>
			<div class="form-group">
				<label for="subject">Password</label>
				<input id = 'password' class="form-control" name="password" placeholder="Password" type="password" />
				<span id = 'password_error' class="text-danger"><?php echo form_error('password'); ?></span>
			</div>
			<div class="form-group">
				<label for="subject">Confirm Password</label>
				<input id = 'confirm' class="form-control" name="cpassword" placeholder="Confirm Password" type="password" />
				<span id = 'confirm_error' class="text-danger"><?php echo form_error('cpassword'); ?></span>
			</div>
			<div class="form-group">
				<button id = 'bSignup' name="submit" type="submit" class="btn btn-primary">Signup</button>
				<button id = 'bClear'  name="cancel" type="reset" class="btn btn-default">Clear</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
	<div class="row">
		<div id = 'already_registered' class="col-md-4 col-md-offset-4 text-center">
		Already Registered? <a href="<?php echo base_url(); ?>index.php/login">Login Here</a>
		</div>
	</div>
</div>
<!-- End Body -->

<!-- Footer -->
<?php $this->load->view('template/footer.php'); ?>
<!-- End Footer -->
