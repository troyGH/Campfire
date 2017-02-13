<div class="modal fade" id="bulletinModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Enter Bulletin Message</h4>
      </div>
	    <div class="modal-body">
	    	<?php $attributes = array("name" => "bulletinform");
			echo form_open("group/display/".$info['org_id'], $attributes);?>
	    	<textarea id="bulletinDescription" class="form-control" rows="5" name="bulletinDescription" ></textarea>
				<span id="bulletinDescription_error" class="text-danger"><?php echo form_error('description'); ?></span>
      	</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" name="submit" class="btn btn-primary" value="Submit Message"></input>
        <?php echo form_close(); ?>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Header -->
<?php $this->load->view('template/header.php'); ?>
<!-- End Header -->

<!-- Body -->
	<div class="container custom-body" style="color: black">

		<div class="row">
			<div class="col-md-8 col-md-offset-2 well">
				<div class="row">
					<div class="col-md-4">
				      	<img id="groupPicture" height="300" width="300" src="<?php echo base_url().'uploads/'.$info['org_picture']?>" alt="...">
				  	</div>
			  		<div class="col-md-8">
						<div class="row">
							<div class=" col-md-12 panel panel-default">
								<div class="panel-body text-left" id="groupTitleText"><h4><strong><?php echo $info['org_title'];?></strong>
								<?php 
								if ($status == 'owner') {
									echo '<a class="btn btn-info btn-sm pull-right" id="editGroupButton" href="'.base_url().'index.php/EditGroup/index/'.$info['org_id'].'">Edit Group</a>';
								} else if ($status == 'member') {
									echo '<a class="btn btn-info btn-sm pull-right" id="leaveGroupButton" href="'.base_url().'index.php/Group/leave_group/'.$info['org_id'].'">Leave</a>';
								} else  if ($status == 'nonmember'){
									echo '<a class="btn btn-info btn-sm pull-right" id="joinGroupButton" href="'.base_url().'index.php/Group/join_group/'.$info['org_id'].'">Join</a>';
								} else {
									echo '<a class="btn btn-info btn-sm pull-right" id="joinGroupButton" href="'.base_url().'index.php/Login">Login to Join</a>';
								}
								?></h4></div>
								<table class="table">
									<tr>
										<td id="groupOwnerName"><?php echo $info['user_fname'].' '.$info['user_lname'];?></td>
										<td id="groupOwnerEmail"><?php echo $info['user_email']?></td>
									</tr>
								</table>
							</div>
						</div>
						<div class="row text-left">
							<div class="panel panel-default">
								<div class="panel-heading"><h5 class="panel-title">Description</h5></div>
								<div class="panel-body" id="groupDescriptionText">
									<?php echo $info['org_description'];?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row top-buffer">
					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="row">
								<div class="col-md-8 col-md-offset-2"><h5 class="panel-title">Members (<?php echo count($members)?>)</h5></div>
								<div class="col-md-1"></div>
								<div class="col-md-1">
									<button  type="button" class="btn btn-default btn-sm" data-toggle="collapse" data-target="#member-table">
							  			<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
									</button>
								</div>
							</div>
						</div>
						<div class="pre-scrollable collapse" id="member-table">
							<table class="table">
								<?php 
									$count = 0;
									foreach ($members as $row) {
										if ($count % 4 == 0) {
											echo '<tr>';
										}
										echo '<td style=\'width:25%\'>'.$row['user_fname'].' '.substr($row['user_lname'], 0,1).'.</td>';
										$count++;
			  							if ($count % 4 == 4) {
											echo '</tr>';
										}
									}
									while ($count % 4 != 0) {
										echo '<td style=\'width:25%\'> </td>';
										$count++;
									}
									echo '</tr>';
								?>
							</table>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="row">
								<div class="col-md-8 col-md-offset-2"><h5 class="panel-title">Bulletin Board</h5></div>
								<div class="col-md-1">
								<?php if ($status == 'owner') {
									echo '<button id="bulletinButton" class="btn btn-info btn-sm pull-right" data-toggle="modal" data-target="#bulletinModal">Add Message</button>';
								}?>
								</div>
								<div class="col-md-1">
									<button  type="button" class="btn btn-default pull-right btn-sm" data-toggle="collapse" data-target="#bulletin-table">
							  			<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
									</button>
								</div>
							</div>
						</div>
						<div class="panel-body collapse" id="bulletin-table">
							<table class="table table-responsive">
							<?php 
								foreach ($bulletins as $row) {
									$t = strtotime($row['bulletin_datetime']);
									echo '<tr class="row">';
									echo '<td class="col-md-2">'.$row['user_fname'].' '.substr($row['user_lname'], 0,1).'.</td>';
									echo '<td class="col-md-2">'.date('n/j/Y H:i A', $t).'</td>';
									echo '<td class="col-md-8"><p style="font-size: 14px;">'.$row['bulletin_message'].'</p></td>';
									echo '</tr>';
								}
							?>
							</table>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="row">
								<div class="col-md-8 col-md-offset-2"><h5 class="panel-title">Event List</h5></div>
								<div class="col-md-1">
								<?php if ($status == 'owner') {
									echo '<a class="btn btn-info btn-sm pull-right" id="createEventButton" href="'.base_url().'index.php/createEvent/index/'.$info['org_id'].'">Create Event</a>';
								}?>									
								</div>
								<div class="col-md-1">
									<button  type="button" class="btn btn-default pull-right btn-sm" data-toggle="collapse" data-target="#event-table">
							  			<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
									</button>
								</div>
							</div>
						</div>
						<div class="panel-body collapse in" id="event-table">
							<table id="event-table" class="table table-responsive">
							<?php 
								foreach ($events as $row) {
									echo '<tr class="row">';
									echo '<td class="col-md-2"><a href="'.base_url().'index.php/Event/display/'.$row['event_id'].'" class="thumbnail">
										      <img src="'.base_url().'uploads/'.$row['event_picture'].'" alt="...">
										    </a></td>';
									echo '<td class="col-md-10"><p><h5><strong>'.$row['event_title'].'</strong></h5></p>';
									$t = strtotime($row['event_begin_datetime']);
									$e = strtotime($row['event_end_datetime']);
									echo '<p><h6>'.date('l, F d, Y H:i A', $t).'</h6></p>';
									echo '<p><h6>'.$row['event_description'].'</h6></p></td>';
									echo '</tr>';
								}
							?>
							</table>
						</div>
					</div>
				</div>
				<?php echo $this->session->flashdata('msg'); ?>
			</div>
		</div>
	</div>

<!-- End Body -->

<!-- Footer -->
<?php $this->load->view('template/footer.php'); ?>
<!-- End Footer -->

<script>
$('#myModal').on('shown.bs.modal', function () {
	  $('#myInput').focus()
	})

function toggler(divId) {
    $("#" + divId).toggle();
}
</script>
