<!-- Header -->
<?php $this->load->view('template/header.php'); ?>
<!-- End Header -->

<!-- Body -->
<div class="container custom-body">
	<div class="row">
	<div style="padding-top:20px"></div>
	<div class="container" style="width:40%">
		<ul class="nav nav-pills nav-justified">
			<li class="active"><a data-toggle="tab" href="#owned_events">Owned</a></li>
			<li><a data-toggle="tab" href="#joined_events">Joined</a></li>
		</ul>
	</div>
	<div class="col-md-10 col-md-offset-1 well" id="view_events">
		<div style="padding-top:20px"></div>
		<div class="col-md-12 well" id="event_layout">
			<div class="col-md-1 col-sm-1 col-lg-1 well"></div>
			<div class="col-md-10 col-sm-10 col-lg-10 well">
				<div class="tab-content">
					<div id="owned_events" class="tab-pane fade in active">
						<?php
							if ($ownedevents == NULL)
							{
								echo '<h4 id="create_event_link" style="padding-top:30px">No events created. Create an event? <a href="createEvent/index">click here</a></h4>';
							}
							else {								
								make_tiles("owned", count($ownedevents), $ownedevents);	
							}
						?>
					</div>
					<div id="joined_events" class="tab-pane fade">
						<?php
							if ($memberedevents == NULL)
							{
								echo '<h4 id="search_event_link" style="padding-top:30px">No events pending. Search for events to attend: <a href="';
								echo base_url('index.php/Event/search');
								echo '">click here</a></h4>';
							}
							else
							{
								make_tiles("membered", count($memberedevents), $memberedevents);
							}
						?>
					</div>
				</div>		
			</div>
			<div class="col-md-1 col-sm-1 col-lg-1 well"></div>
	    </div>
	</div>
</div>
<!-- End Body -->


<?php

function make_tiles($set, $size, $events)
{	
	if ($set == "owned")
	{
		for ($x = 0; $x < $size; $x++) {
			$truncatedDesc = strlen($events[$x]->event_description) > 17 ? substr($events[$x]->event_description, 0, 17).'...' : $events[$x]->event_description."<br><br>";
			$truncatedTitle = strlen($events[$x]->event_title) > 26 ? substr($events[$x]->event_title, 0, 26).'...' : $events[$x]->event_title;
			echo "<div class='col-md-3 card'>";
			echo "<a id='".$events[$x]->event_id."' href='".base_url()."index.php/Event/display/".$events[$x]->event_id."'><img class='img-responsive' src='";
			echo base_url()."uploads/".$events[$x]->event_picture."' alt='".$events[$x]->event_title."'></a>";
			echo "<div class='card-block'>";
			echo "<h5 data-toggle='tooltip' data-placement='top' title='".$events[$x]->event_title."' class='card-title' id='card-title' >".$truncatedTitle."</h5>";
			echo "<p  data-toggle='tooltip' data-placement='top' title='".$events[$x]->event_description."' class='card-text'>".$truncatedDesc."</p>
			<a class='btn btn-primary waves-effect waves-light' href='".base_url()."index.php/Event/display/".$events[$x]->event_id."'>See More</a>
			<p class='card-text'><small class='text-muted'></small></p></div></div>";
		}
	}
	else if ($set == "membered")
	{
		for ($x = 0; $x < $size; $x++) {
			$truncatedDesc = strlen($events[$x]->event_description) > 17 ? substr($events[$x]->event_description, 0, 17).'...' : $events[$x]->event_description."<br><br>";
			$truncatedTitle = strlen($events[$x]->event_title) > 26 ? substr($events[$x]->event_title, 0, 26).'...' : $events[$x]->event_title;
			echo "<div class='col-md-3 card'>";
			echo "<a id='".$events[$x]->event_id."' href='".base_url()."index.php/Event/display/".$events[$x]->event_id."'><img class='img-responsive' src='";
			echo base_url()."uploads/".$events[$x]->event_picture."' alt='".$events[$x]->event_title."'></a>";
			echo "<div class='card-block'>";
			echo "<h5 data-toggle='tooltip' data-placement='top' title='".$events[$x]->event_title."' class='card-title' id='card-title' >".$truncatedTitle."</h5>";
			echo "<p  data-toggle='tooltip' data-placement='top' title='".$events[$x]->event_description."' class='card-text'>".$truncatedDesc."</p>
			<a class='btn btn-primary waves-effect waves-light' href='".base_url()."index.php/Event/display/".$events[$x]->event_id."'>See More</a>
			<p class='card-text'><small class='text-muted'></small></p></div></div>";
		}
	}
	else{
		return;
	}
}

?>
<!-- Footer -->
<?php $this->load->view('template/footer.php'); ?>
<!-- End Footer -->
