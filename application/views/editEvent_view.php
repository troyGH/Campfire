<!-- Header -->
<?php $this->load->view('template/header.php'); ?>
<!-- End Header -->

<!-- Body -->
<div class="container custom-body">
	<div class="row">
		<div class="col-md-4 col-md-offset-4 well">
			<?php $attributes = array("name" => "editeventform", 'onsubmit' => 'return validateAddress()');
			echo form_open_multipart("editEvent/index", $attributes);?>
			<legend>Edit Event</legend>
			<?php echo $this->session->flashdata('msg'); ?>
			
			<div class="form-group row">
				<div class="col-sm-6">
					<label for="eventTitle">Title</label>
					<input id="eventTitle" class="form-control" name="eventTitle" placeholder="Event Title" type="text" value="<?php echo set_value('eventTitle', $oldEventData['event_title']); ?>" />
					<span id="eventTitle_error" class="text-danger"><?php echo form_error('eventTitle'); ?></span>
				</div>
				<div class="col-sm-6">
					<label> Hosted by
					<h5><?php echo $uname; ?>.</h5></label>
				</div>
			</div>
			<div class="form-group" id="has-error">
				<label for="address-input">Address</label>
				<input id="address-input" type="text" class="form-control" placeholder="Address" required>
				<span id="eventAddress_error" class="text-danger"></span>
				<input type="hidden" id="address1" name="address1" value="" />
				<input type="hidden" id="zip" name="zipcode" value="" />
			</div>
			<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA-6CzpsxPQPdiOV_3M0QhATgjyTqO7JQE&libraries=places&callback=initAutoComplete" async defer></script>
			<div class="form-group row">
				<div class="col-sm-6">
					<label for="startTime">Event Start</label>
					<div class="input-group date" id="startDate">
						<input id="startTime" type="datetime" class="form-control" name="startTime" placeholder="Event Start" value="<?php echo set_value('startTime', date('m/d/Y H:i', strtotime($oldEventData['event_begin_datetime']))); ?>" />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
					<span id="startTime_error" class="text-danger"><?php echo form_error('startTime'); ?></span>
				</div>
				<div class="col-sm-6">
					<label for="endTime">Event End</label>
					<div class="input-group date" id="endDate">
						<input id="endTime" type="datetime" class="form-control" name="endTime" placeholder="Event End" value="<?php echo set_value('endTime', date('m/d/Y H:i', strtotime($oldEventData['event_end_datetime']))); ?>" />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
					<span id="endTime_error" class="text-danger"><?php echo form_error('endTime'); ?></span>
				</div>
			</div>
			<div class="form-group">
			<div class="form-event">
				<label for="tag">Choose Tag</label>
				<select class="form-control" name="tag" id="eventTag">
					<?php
					foreach($tag_list as $row) {
						echo "<option value=$row" .($row == $oldTag ? ' selected="selected"' : '') . ">$row</option>"; 
					}
					?>
				</select>
			</div>
			<div class="form-group">
				<label for="eventGroup">Associate the Event with a Group</label>
				<select id="eventGroup" class="form-control" name="eventGroup">
					<?php
					if (isset($oldGroup) && $oldGroup != NULL) {
						echo '<option>This event does not involve a group</option>';
						foreach($group_list as $row) {
							echo "<option value=$row" .($row == $oldGroup ? ' selected="selected"' : '') . ">$row</option>";
						}
					} else {
						echo '<option>This event does not involve a group</option>';
						foreach($group_list as $row) {
							echo '<option>'.$row.'</option>';
						}
					}
					?>
				</select>
			</div>
			<div class="form-event">
				<label for="description">Event Description</label>
				<textarea class="form-control" rows="5" name="description" id="eventDescription" value="<?php echo set_value('description'); ?>"><?php echo (isset($oldEventData['event_description']) ? $oldEventData['event_description'] : ''); ?></textarea>
				<span class="text-danger"><?php echo form_error('description'); ?></span>
			</div>
			<div class="form-event">
				<label for="imageUpload">Upload an Image</label>
				<input class="file" name="imageUpload" id="imageUpload" type="file" />
				<span class="text-danger"><?php echo form_error('imageUpload'); ?></span>
			</div>
			<div class="form-event">
				<button name="submit" id="bSubmit" type="submit" class="btn btn-info">Update</button>
				<button name="cancel" id="bCancel" type="button" class="btn btn-info" onclick="location.href='<?php echo base_url();?>index.php/home/index'">Cancel</button>
				<?php if(isset($oldEventData['event_id'])) : ?>
					<a href="<?php echo base_url();?>index.php/editEvent/deleteEvent/<?php echo $oldEventData['event_id'];?>"
						onclick="return confirm('Are you sure you want to delete the event: <?php echo $oldEventData['event_title'];?>?');">
						<button name="delete" id="bDelete" type="button" class="btn btn-danger">Delete Event</button>
				<?php endif; ?>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
<!-- End Body -->
<script>
$(function () {
    $('#startDate').datetimepicker({
    startDate,
    format: 'mm/dd/yyyy h:i',
    minuteStep: 15,
    autoclose: true,
    });

    $('#endDate').datetimepicker({
    format: 'mm/dd/yyyy h:i',
    minuteStep: 15,
    autoclose: true
    });

    $("#startDate").on("dp.change",function (e) {
        $('#endDate').data("DateTimePicker").setMinDate(e.date);
    });
    $("#endDate").on("dp.change",function (e) {
        $('#startDate').data("DateTimePicker").setMaxDate(e.date);
    });
});
</script>
<!-- Footer -->
<?php $this->load->view('template/footer.php'); ?>
<!-- End Footer -->
