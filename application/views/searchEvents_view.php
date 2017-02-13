<!-- Header -->
<?php $this->load->view('template/header.php'); ?>
<!-- End Header -->

<!-- Body -->
<div class="container custom-body">


  <nav class="navbar navbar-default" id="inputGroup">
          <div class="nav nav-justified navbar-nav">
              <form class="navbar-form navbar-search" role="search">
                  <div class="input-group">
                      <input id="pac-input" type="search" class="form-control" placeholder="City, State, or Zip">
                      <div>
                        <label id="filter-label" for="select-tag">Filter:</label>
                        <select id="select-tag">
                          <option id="all-opt" data-filter="All">All</option>
                          <option id="movies-opt" data-filter="Movies">Movies</option>
                          <option id="education-opt" data-filter="Education">Education</option>
                          <option id="sports-opt" data-filter="Sports">Sports</option>
                          <option id="food-opt" data-filter="Food">Food</option>
                          <option id="coffee-opt" data-filter="Coffee">Coffee</option>
                        </select>
                      </div>
                  </div>
              </form>
          </div>
      </nav>


  <div id="map"></div>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA-6CzpsxPQPdiOV_3M0QhATgjyTqO7JQE&libraries=places&callback=initMap" async defer></script>
</div> <!-- /container -->
<script>
$(document).ready(function(){
	$('#select-tag').on('change', function() {
    var value = $(this).find(':selected');
    filterMarkers(value.val());
	});
});
</script>
<!-- Footer -->
<?php $this->load->view('template/footer.php'); ?>
<!-- End Footer -->
