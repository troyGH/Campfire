<!-- Header -->
<?php $this->load->view('template/header.php'); ?>
<!-- End Header -->

<!-- Body -->
<div class="container custom-body">
	<div class="row">
		<div class="col-md-4 col-md-offset-4 well">
			<?php $attributes = array("name" => "creategroupform");
			echo form_open_multipart("createGroup/index", $attributes);?>
			<legend>Create Group</legend>
			<div class="form-group">
				<label for="groupName">Group Name</label>
				<input id="groupName" class="form-control" name="groupName" placeholder="Group Name" type="text" value="<?php echo set_value('groupName'); ?>" />
				<span id="groupName_error" class="text-danger"><?php echo form_error('groupName'); ?></span>
			</div>
			<div class="form-group">
				<label for="zip">Group Zip Code</label>
				<input id="groupZip" class="form-control" name="zip" placeholder="Group Zip Code" type="text" value="<?php echo set_value('zip'); ?>" />
				<span id="groupZip_error" class="text-danger"><?php echo form_error('zip'); ?></span>
			</div>
			<div class="form-group">
				<label for="tag">Choose Tag</label>
				<select id="groupTag" class="form-control" name="tag">
					<?php
					foreach($tag_list as $row) {
						echo '<option>'.$row.'</option>';
					}
					?>
				</select>
			</div>
			<div class="form-group">
				<label for="description">Group Description</label>
				<textarea id="groupDescription" class="form-control" rows="5" name="description" value="<?php echo set_value('description'); ?>"><?php echo (isset($description) ? $description : ''); ?></textarea>
				<span id="groupDescription_error" class="text-danger"><?php echo form_error('description'); ?></span>
			</div>
			<div class="form-group">
				<label for="imageUpload">Upload an Image</label>
				<input id="groupImage" class="file" name="imageUpload" type="file" />
				<span id="groupimage_error" class="text-danger"><?php echo form_error('imageUpload'); ?></span>
			</div>
			<div class="form-group">
				<button id="bSubmit" name="submit" type="submit" class="btn btn-primary">Create Group</button>
				<button id="bCancel" type="button" class="btn btn-default" onclick="location.href='<?php echo base_url();?>index.php/home/index'">Cancel</button>
			</div>
			<?php echo form_close(); ?>
			<?php echo $this->session->flashdata('msg'); ?>
		</div>
	</div>
</div>
<!-- End Body -->

<!-- Footer -->
<?php $this->load->view('template/footer.php'); ?>
<!-- End Footer -->
