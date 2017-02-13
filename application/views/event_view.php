<div class="modal fade" id="bulletinModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Enter Bulletin Message</h4>
      </div>
	    <div class="modal-body">
	    	<?php $attributes = array("name" => "bulletinform");
			echo form_open("event/display/".$info['event_id'], $attributes);?>
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
				      	 <img id="eventPicture" height="300" width="300" src="<?php echo base_url().'uploads/'.$info['event_picture']?>" alt="...">
				  	</div>
			  		<div class="col-md-8">
						<div class="row">
							<div class=" col-md-12 panel panel-default">
								<div class="panel-body text-left" id="eventTitleText"><h4><strong><?php echo $info['event_title'];?></strong>
								<?php 
								if ($status == 'owner') {
									echo '<a class="btn btn-info btn-sm pull-right" id="editEventButton" href="'.base_url().'index.php/EditEvent/index/'.$info['event_id'].'">Edit Event</a>';
								} else if ($status == 'member') {
									echo '<a class="btn btn-info btn-sm pull-right" id="leaveEventButton" href="'.base_url().'index.php/Event/leave_event/'.$info['event_id'].'">Withdraw</a>';
								} else  if ($status == 'nonmember'){
									echo '<a class="btn btn-info btn-sm pull-right" id="joinEventButton" href="'.base_url().'index.php/Event/join_event/'.$info['event_id'].'">RSVP</a>';
								} else {
									echo '<a class="btn btn-info btn-sm pull-right" id="joinEventButton" href="'.base_url().'index.php/Login">Login to Join</a>';
								}
								?></h4></div>
								<table class="table text-left">
									<tr>
										<td id="eventOwnerName">Owner: <?php echo $info['user_fname'].' '.$info['user_lname'];?></td>
										<td id="eventOwnerEmail">Email: <?php echo $info['user_email']?></td>
									</tr>
								</table>
							</div>
						</div>
						<div class="row" style="padding: 1em 0em">
							<div class="panel panel-default">
								<div class="panel-heading"><h5 class="panel-title text-left">Description</h5></div>
								<div class="panel-body text-left" id="eventDescriptionText">
									<?php echo $info['event_description'];?>
							</div>
						</div>
						</div>
					</div>
				</div>
				<div class="row top-buffer">
					<div class="panel panel-default">
						<table class="table table-responsive">
							<?php
								$t = strtotime($info['event_begin_datetime']);
								$e = strtotime($info['event_end_datetime']);

								echo '<tr class="row">';
								echo '<td> Location: </td><td colspan="3">'.$info['address_one'].' '.$info['address_two'].'&nbsp;&nbsp;&nbsp;&nbsp;'
    									.$info['city'].', '.$info['state'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$info['zipcode'].'</td>';
								echo '</tr>';
								echo '<tr class="row">';
								echo '<td> Start Time: </td><td colspan="">'.date('l, F d, Y H:i A', $t).'</td>';
								echo '<td> End Time: </td><td colspan="">'.date('l, F d, Y H:i A', $e).'</td>';
									
								echo '</tr>';
								echo '<tr class="row">';
								echo '<td colspan="4"><div id="map-canvas" style="width:98%;height:200px;margin:10px;"></div></td>';
								echo '</tr>';
							?>
						</table>
					</div>
				</div>
				
				<div class="row">
				<div class="panel panel-default">
								<div class="panel-heading">
									<div class="row">
										<div class="col-md-10 col-md-offset-1"><h5 class="panel-title">Attendees (<?php echo count($members)?>)</h5></div>
										<div class="col-md-1"></div>
										<div class="col-md-1">
											<button  type="button" class="btn btn-default btn-sm" data-toggle="collapse" data-target="#member-table"  
													aria-expanded="false" aria-controls="member-table">
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
									<button  type="button" class="btn btn-default btn-sm" data-toggle="collapse" data-target="#bulletin-table">
							  			<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
									</button>
								</div>
							</div>
						</div>
						<div class="panel-body collapse" id="bulletin-table">
							<table class="table table-responsive text-left">
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
				<?php echo $this->session->flashdata('msg'); ?>
			</div>
		</div>
	</div>

<!-- End Body -->

<!-- Footer -->
<?php $this->load->view('template/footer.php'); ?>
<!-- End Footer -->

<script>
var lat = <?php echo $info['geolat']?>;
var lng = <?php echo $info['geolng']?>;

function toggler(divId) {
    $("#" + divId).toggle();
}

function initialize() {
    var mapOptions = {
        center: new google.maps.LatLng(lat, lng),
        zoom: 15,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        scrollwheel: false,
        draggable: false,
        panControl: true,
        zoomControl: true,
        mapTypeControl: true,
        scaleControl: true,
        streetViewControl: false,
        overviewMapControl: true,
        rotateControl: true,
    };
    
    var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
    var marker = new google.maps.Marker({
        position: {lat: lat, lng: lng},
        map: map,
        title: "<?php echo $info['event_title']?>"
      });
}
google.maps.event.addDomListener(window, 'load', initialize);
</script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA-6CzpsxPQPdiOV_3M0QhATgjyTqO7JQE&libraries=places&callback=initialize">
 </script>
