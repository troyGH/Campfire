//Google Map Stuff
var infowindows = [];
var markers = [];
var map;
function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: 37.3352, lng: -121.8811},
    zoom: 5,
    clickableIcons: false
  });
  var input = document.getElementById('pac-input');
  var autocomplete = new google.maps.places.Autocomplete(input);

  // Try HTML5 geolocation.
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
    };
    map.setCenter(pos);
    map.setZoom(11);
    $.ajax({
        type: "POST",
        url: "http://localhost/Campfire/Event/search_nearby",
        data: {current_lat: position.coords.latitude, current_lng: position.coords.longitude, dist: 10},
        success: function(data){
            var result = $.parseJSON(data);
            displayEvents(result);
        }
    });
  });
  }

  autocomplete.bindTo('bounds', map);
  autocomplete.setTypes(['(regions)']);
  var inputGroup = document.getElementById('inputGroup');


  map.controls[google.maps.ControlPosition.TOP_CENTER].push(inputGroup);

  autocomplete.addListener('place_changed', function() {
    removeMarkers();
    removeInfoWindows();
    var place = autocomplete.getPlace();
    if (!place.geometry) {
      return;
    }
    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(11);
    }

    $.ajax({
        type: "POST",
        url: "http://localhost/Campfire/Event/search_nearby",
        data: {current_lat: place.geometry.location.lat, current_lng: place.geometry.location.lng, dist: 10},
        success: function(data){
            var result = $.parseJSON(data);
            displayEvents(result);
        }
    });

  });
}
function displayEvents(events){
  events.forEach(function(event){
    var infowindow = new google.maps.InfoWindow();
    var marker = new google.maps.Marker({
      map: map,
    });

    marker.addListener('click', function() {
      removeInfoWindows();
      infowindow.open(map, marker);
    });

    marker.setPosition(new google.maps.LatLng(event['geolat'], event['geolng']));
    marker.setVisible(true);
    var url = 'http://localhost/Campfire/Event/display/' + event['event_id'];
    var truncatedDesc = event['event_description'].length > 150? event['event_description'].substring(150, 0) + "..." : event['event_description'];
    var content =
    '<h4>'+ event['event_title']+ '</h4></div>' +
    '<h5>Description:</h5>' + truncatedDesc +
    '<h5>Date/Time:</h5> ' + new Date(event['event_begin_datetime'])  + '<br> to <br>' +  new Date(event['event_end_datetime'])  +
    '<h5>Tag:</h5> '   +  event['tag_title']  +
    "<h5>Attendees:</h5> "  +  event['attendee_count'] +
     '<br><a class="btn btn-primary" href="'+ url +'">See More </a>';

    infowindow.setContent(content);

    infowindows.push({window:infowindow, tag:event['tag_title']});
    markers.push({marker:marker, tag:event['tag_title']});
  });

}

function removeMarkers(){
  markers.forEach(function(marker){
    marker['marker'].setMap(null);
  });
  markers=[];
}

function removeInfoWindows(){
  infowindows.forEach(function(iw){
    iw['window'].close();
  });
}

function filterMarkers(tag){
  infowindows.forEach(function(iw){
    iw['window'].close();
  });
  if(tag === 'All'){
    markers.forEach(function(marker){
      marker['marker'].setVisible(true);
    });
  }else{
    markers.forEach(function(marker){
    if(marker['tag'].toLowerCase() !== tag.toLowerCase()){
      marker['marker'].setVisible(false);
    }else{
        marker['marker'].setVisible(true);
    }
    });
  }
}

function confirmDelete() {
	bootbox.confirm({
	    message: "Are you sure you want to delete your account? This can not be undone.",
	    buttons: {
	        confirm: {
	            label: 'Delete',
	            className: 'btn-danger'
	        },
	        cancel: {
	            label: 'Cancel',
	            className: 'btn'
	        }
	    },
	    callback: function (result) {
	        if (result) {
	            $.ajax({
	                url: 'editProfile/deleteUser',
	                type: 'POST'
	            });
	            $('.well').append('<div class="alert alert-success text-center">You have Successfully Deleted your account! You will be redirected shortly.</div>');
	            var delay = 3000;
	            setTimeout(function() {
	            	location.href='home/index';
	            }, delay);


	        }
	    }
	});
}

/*
function initialize() {

var input = document.getElementById('searchTextField');
var autocomplete = new google.maps.places.Autocomplete(input);
}
google.maps.event.addDomListener(window, 'load', initialize);
*/

// end of custom.js


// $(function () {
//         $('#startDate').datetimepicker();
//         $('#endDate').datetimepicker({
//             useCurrent: false //Important! See issue #1075
//         });
//         $("#startDate").on("dp.change", function (e) {
//             $('#endDate').data("DateTimePicker").minDate(e.date);
//         });
//         $("#endDate").on("dp.change", function (e) {
//             $('#startDate').data("DateTimePicker").maxDate(e.date);
//         });
//     });

// function testJS() {
//   jQuery('#eventDateTimeStart').datetimepicker();
//   jQuery('#eventDateTimeEnd').datetimepicker();
//   jQuery("#eventDateTimeStart").on("dp.change",function (e) {
//         jQuery('#eventDateTimeEnd').data("DateTimePicker").setMinDate(e.date);
//   });
//   jQuery("#eventDateTimeEnd").on("dp.change",function (e) {
//         jQuery('#eventDateTimeStart').data("DateTimePicker").setMaxDate(e.date);
//   });
// };
var place;
function initAutoComplete(){
	var input = document.getElementById('address-input');
	var autocomplete = new google.maps.places.Autocomplete(input);
	autocomplete.setTypes(['address']);

  google.maps.event.addListener(autocomplete, 'place_changed', function () {
            place = autocomplete.getPlace();
            if(typeof place.geometry !== 'undefined'){
              $('#eventAddress_error').html('');
            }
        });
}
function validateAddress(){
  if(typeof place.geometry === 'undefined'){
    $('#eventAddress_error').html('Error. Please select a dropdown address.');
    return false;
  }else{
    var street = "";
    var address = "";
    var zip = "";
    place.address_components.forEach(function(component){
      if(component.types[0] === "postal_code"){
        zip = component.short_name;
      }
      else if(component.types[0] === "street_number"){
        street = component.long_name;
      }
      else if(component.types[0] === "route"){
        address = component.long_name;
      }
    });
    if(street === ""){
      $('#address-input').val("");
      $('#eventAddress_error').html('Error. Please enter valid street number.');
      return false;
    }else{
      $('input#address1').val(street + " " + address);
      $('input#zip').val(zip);
    }
    return true;
  }
}
