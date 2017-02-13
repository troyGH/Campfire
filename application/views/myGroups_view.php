<!-- Header -->
<?php $this->load->view('template/header.php'); ?>
<!-- End Header -->

<!-- Body -->
<div class="container custom-body">
	<div class="row">
	<div style="padding-top:20px"></div>
	<div class="container" style="width:40%">
		<ul class="nav nav-pills nav-justified">
		<li id="owned_groupsTab" class="active"><a data-toggle="tab" href="#owned_groups">Owned</a></li>
		<li id="joined_groupsTab"><a data-toggle="tab" href="#joined_groups">Joined</a></li>
		</ul>
	</div>
	<div class="col-md-10 col-md-offset-1 well" id="view_groups">
		<div style="padding-top:20px"></div>
		<div class="col-md-12 well" id="group_view_layout">
			<div class="col-md-1 col-sm-1 col-lg-1 well"></div>
			<div class="col-md-10 col-sm-10 col-lg-10 well">
				<div class="card-group">
				<div class="tab-content">
					<div id="owned_groups" class="tab-pane fade in active">
						<?php

							if ($ownedgroups == NULL)
							{
								echo '<h4 style="padding-top:30px">No groups owned. Create a group? <a href="createGroup/index">click here</a></h4>';
							}
							else {
								make_tiles("owned", count($ownedgroups), $ownedgroups);
							}
						?>
					</div>
					<div id="joined_groups" class="tab-pane fade">
						<?php
							if ($memberedgroups == NULL)
							{
								echo '<h4 style="padding-top:30px">No groups joined. Search for a group to join: <a href="';
								echo base_url('index.php/Group/search');
								echo '">click here</a></h4>';
							}
							else
							{
								make_tiles("membered", count($memberedgroups), $memberedgroups);
							}
						?>
					</div>
					</div>
				</div>
			</div>
			<div class="col-md-1 col-sm-1 col-lg-1 well"></div>
	    </div>
	</div>
	</div>
</div>
<!-- End Body -->

<?php

function make_tiles($set, $size, $groups)
{
	
	if ($set == "owned")
	{
		for ($x = 0; $x < $size; $x++) {
			$truncatedDesc = strlen($groups[$x]->org_description) > 17 ? substr($groups[$x]->org_description, 0, 17).'...' : $groups[$x]->org_description."<br><br>";
			$truncatedTitle = strlen($groups[$x]->org_title) > 26 ? substr($groups[$x]->org_title, 0, 26).'...' : $groups[$x]->org_title;
			echo "<div class='col-md-3 card'>";
			echo "<a id='".$groups[$x]->org_id."' href='".base_url()."index.php/Group/display/".$groups[$x]->org_id."'><img class='img-responsive' src='";
			echo base_url()."uploads/".$groups[$x]->org_picture."' alt='".$groups[$x]->org_title."'></a>";
			echo "<div class='card-block'>";
			echo "<h5 data-toggle='tooltip' data-placement='top' title='".$groups[$x]->org_title."' class='card-title' id='card-title' >".$truncatedTitle."</h5>";
			echo "<p  data-toggle='tooltip' data-placement='top' title='".$groups[$x]->org_description."' class='card-text'>".$truncatedDesc."</p>
			<a class='btn btn-primary waves-effect waves-light' href='".base_url()."index.php/Group/display/".$groups[$x]->org_id."'>See More</a>
			<p class='card-text'><small class='text-muted'></small></p></div></div>";
		}
	}
	else if ($set == "membered")
	{
		for ($x = 0; $x < $size; $x++) {
			$truncatedDesc = strlen($groups[$x]->org_description) > 17 ? substr($groups[$x]->org_description, 0, 17).'...' : $groups[$x]->org_description."<br><br>";
			$truncatedTitle = strlen($groups[$x]->org_title) > 26 ? substr($groups[$x]->org_title, 0, 26).'...' : $groups[$x]->org_title;
			echo "<div class='col-md-3 card'>";
			echo "<a id='".$groups[$x]->org_id."' href='".base_url()."index.php/Group/display/".$groups[$x]->org_id."'><img class='img-responsive' src='";
			echo base_url()."uploads/".$groups[$x]->org_picture."' alt='".$groups[$x]->org_title."'></a>";
			echo "<div class='card-block'>";
			echo "<h5 data-toggle='tooltip' data-placement='top' title='".$groups[$x]->org_title."' class='card-title' id='card-title' >".$truncatedTitle."</h5>";
			echo "<p  data-toggle='tooltip' data-placement='top' title='".$groups[$x]->org_description."' class='card-text'>".$truncatedDesc."</p>
			<a class='btn btn-primary waves-effect waves-light' href='".base_url()."index.php/Group/display/".$groups[$x]->org_id."'>See More</a>
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
