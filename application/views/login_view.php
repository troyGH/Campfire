<!-- Header -->
<?php $this->load->view('template/header.php'); ?>
<!-- End Header -->

<!-- Body -->
<div class="container custom-body">
	<div class="row">
		<div class="col-md-4 col-md-offset-4 well">
		<?php $attributes = array("name" => "loginform");
			echo form_open("login/index", $attributes);?>
			<legend>Login</legend>
			<div class="form-group">
				<label for="name">Email</label>
				<input id = 'email' class="form-control" name="email" placeholder="Enter Email" type="text" value="<?php echo set_value('email'); ?>" />
				<span id = 'email_error' class="text-danger"><?php echo form_error('email'); ?></span>
			</div>
			<div class="form-group">
				<label for="name">Password</label>
				<input id = 'password' class="form-control" name="password" placeholder="Password" type="password" value="<?php echo set_value('password'); ?>" />
				<span id = 'password_error' class="text-danger"><?php echo form_error('password'); ?></span>
			</div>
			<div class="form-group">
				<button id = 'bSubmit' name="submit" type="submit" class="btn btn-primary">Login</button>
				<button id = 'bReset' name="cancel" type="reset" class="btn btn-default">Clear</button>
			</div>
		<?php echo form_close(); ?>
		<?php echo $this->session->flashdata('msg'); ?>
		</div>
	</div>
	<div class="row">
		<div id = 'new_user' class="col-md-4 col-md-offset-4 text-center">
		New User? <a href="<?php echo base_url(); ?>index.php/signup">Sign Up Here</a>
		</div>
	</div>
</div>
<!-- End Body -->

<!-- Footer -->
<?php $this->load->view('template/footer.php'); ?>
<!-- End Footer -->
