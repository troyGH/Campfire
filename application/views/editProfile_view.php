<!-- Header -->
<?php $this->load->view('template/header.php'); ?>
<!-- End Header -->

<!-- Body -->
	<div class="container custom-body">
		<div class="row">
			<div class="col-md-4 col-md-offset-4 well">
				<?php $attributes = array("name" => "editprofileform");
				echo form_open("editProfile/index", $attributes);?>
				<legend>EditProfile</legend>

				<div class="form-group">
					<label for="name">First Name</label>
					<input class="form-control" name="fname" placeholder="First Name" type="text" value="<?php echo set_value('fname', $user_data['user_fname']); ?>" />
					<span class="text-danger"><?php echo form_error('fname'); ?></span>
				</div>
				<div class="form-group">
					<label for="name">Last Name</label>
					<input class="form-control" name="lname" placeholder="Last Name" type="text" value="<?php echo set_value('lname', $user_data['user_lname']); ?>" />
					<span class="text-danger"><?php echo form_error('lname'); ?></span>
				</div>
				<div class="form-group">
					<label for="email">Email</label>
					<input class="form-control" name="email" placeholder="Email" type="text" value="<?php echo set_value('email', $user_data['user_email']); ?>" />
					<span class="text-danger"><?php echo form_error('email'); ?></span>
				</div>
				<div class="form-group">
					<label for="subject">Password</label>
					<input class="form-control" name="password" placeholder="Password" type="password" />
					<span class="text-danger"><?php echo form_error('password'); ?></span>
				</div>
				<div class="form-group">
					<label for="subject">Confirm Password</label>
					<input class="form-control" name="cpassword" placeholder="Confirm Password" type="password" />
					<span class="text-danger"><?php echo form_error('cpassword'); ?></span>
				</div>
				<div class="form-group">
					<button name="update" type="submit" value="update" class="btn btn-primary">Update</button>
					<button name="cancel" type="button" class="btn btn-default" onclick="location.href='<?php echo base_url();?>index.php/home'">Cancel</button>
					<button name="delete" type="button" value="delete" class="btn btn-danger pull-right" onclick="confirmDelete()">Delete Account</button>
					<?php echo form_close(); ?>
				</div>

				<?php echo $this->session->flashdata('msg'); ?>
			</div>
		</div>
	</div>
<!-- End Body -->

<!-- Footer -->
<?php $this->load->view('template/footer.php'); ?>
<!-- End Footer -->
