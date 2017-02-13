<!-- Header -->
<?php $this->load->view('template/header.php'); ?>
<!-- End Header -->
<!-- Body -->

<div class="container custom-body">
	<?php $attributes = array("class" => "form-wrapper cf", "method" => "get");
				echo form_open("group/search", $attributes); ?>
				<div class="form-group">
					<input type="text" class="form-control" id="groupQuery" name="groupQuery" placeholder="Enter a city or zip..." required>
					<button type="submit" id="searchButton">Search </button>
				</div>
	<?php echo form_close(); ?>

	    <div class="col-md-10 col-md-offset-1 well" id="search-group-well">
						<?php
							if(isset($groups)) {
								echo "<h5>".count($groups)." Group(s) Found:</h5>";
							}
							else{
								if($this->input->get('groupQuery')){
									echo "<h5>0 Groups Found</h5>";
								}
								else{
									if(isset($random)){
										echo "<h5>Here are some random groups:</h5>";
									}
								}
							} ?>
							<div class="col-md-2 col-sm-2 col-lg-2 well" style="min-height: 100px;">
								<div class="form-group">
								  <label for="select-tag">Filter:</label>
									<?php	$options = array('All'=> 'All','Movies' => 'Movies','Education' => 'Education','Sports' => 'Sports','Food' => 'Food','Coffee'=> 'Coffee');
									$extra = 'class="form-control" id="select-tag" method="POST"';
									echo form_dropdown('tag', $options, 'large', $extra);?>
								</div>
							</div>
							<div class="col-md-10 col-sm-10 col-lg-10 well">
								<div class="card-group">
							<?php
								if(isset($groups)) {
										displayTiles($groups);
								}else{
										if(isset($random)){
											displayTiles($random);

										}
									}
									?>
									<div class="col-md-12 col-sm-12 col-lg-12">
										<?php $ci =& get_instance(); echo $ci->pagination->create_links(); ?>
									</div>
							</div>
						</div>
	    </div>
</div>
<!-- End Body -->

<!-- Footer -->
<?php $this->load->view('template/footer.php'); ?>
<!-- End Footer -->

<?php

function displayTiles($groups){
	$ci =& get_instance();
	$per_page = $ci->input->get('per_page');
	$index = 12 * $per_page;
	$max = $index + 12;
	if($max > count($groups)-1){
		$max = count($groups); //in case of out of range error
	}
	for ($x = $index; $x < $max; $x++) {
		$truncatedDesc = strlen($groups[$x]['org_description']) > 17 ? substr($groups[$x]['org_description'], 0, 17).'...' : $groups[$x]['org_description']."<br><br>";
		$truncatedTitle = strlen($groups[$x]['org_title']) > 26 ? substr($groups[$x]['org_title'], 0, 26).'...' : $groups[$x]['org_title'];
		echo "<div class='col-md-3 card filter ".$groups[$x]['tag_title']."'>";
		echo "<a id='".$groups[$x]['org_id']."' href='".base_url()."index.php/Group/display/".$groups[$x]['org_id']."'><img class='img-responsive' src='";
		echo base_url()."uploads/".$groups[$x]['org_picture']."' alt='".$groups[$x]['org_title']."'></a>";
		echo "<div class='card-block'>";
		echo "<h5 data-toggle='tooltip' data-placement='top' title='".$groups[$x]['org_title']."' class='card-title' id='card-title' >".$truncatedTitle."</h5>";
		echo "<p  data-toggle='tooltip' data-placement='top' title='".$groups[$x]['org_description']."' class='card-text'>".$truncatedDesc."</p>
		<a class='btn btn-primary waves-effect waves-light' href='".base_url()."index.php/Group/display/".$groups[$x]['org_id']."'>See More</a>
		<p class='card-text'><small class='text-muted'>Members:".$groups[$x]['members_count']."</small></p></div></div>";
	}
}

?>

<script>
$(document).ready(function(){
	$('#select-tag').on('change', function() {
    var value = $(this).find(':selected');
		if(value.val() == "All")
		{
				$('.filter').show('1000');
		}
		else
		{
				$(".filter").not('.'+value.val()).hide('3000');
				$('.filter').filter('.'+value.val()).show('3000');
		}
	});

});
</script>
