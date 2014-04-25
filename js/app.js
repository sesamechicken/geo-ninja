
var watchID;
var geoLoc;
var map;
var mapOptions;
var tracking_marker;
var sts_msg;

var my_marker;
var my_latlng;
var my_lat;
var my_lng;
var my_acc;
var username;

var tracker_lat = "";
var tracker_lng = "";
var tracker_user = "";
var tracker_id;
var tracker_int;

var poll_interval = 5000;

function app_init(){

  tracker_id = getQueryVariable('id');
/*
  // Hardcode for testing
  tracker_lat = 48.858093;
  tracker_lng = 2.294694;
  tracker_user = 'sesamechicken';
*/

  // IF TRACKER, OTHERWISE MY POSITION
  if(tracker_id > 0){
    // Grab the actual most-recent data per session ID
    $.get('ajax/poll_tracker.php', {session_key: tracker_id}, function(data){
      // success      
      var init_tracker_obj = jQuery.parseJSON(data);

      $('#tracker_name').text(init_tracker_obj.username);

      var tracker_latlng = new google.maps.LatLng(init_tracker_obj.lat, init_tracker_obj.lng);
      mapOptions = {
        zoom: 16,
        center: tracker_latlng
      };

      map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

      tracking_marker = new google.maps.Marker({
        position: tracker_latlng,
        title: init_tracker_obj.username,
        icon: 'http://project107.net/geo-ninja/img/marker.png'
      });
      tracking_marker.setMap(map);

      tracker_int = setInterval(function(){
        pollTracker(tracker_id);
      }, poll_interval);

      // Hide form elements and show nothing but sts div
      $('#identity-frm').hide();
      $('#share-frm').hide();
      $('#sts').html('Waiting for more data...').slideDown();
    });
  }
  else{
    // MY POSITION - NO TRACKER
    console.log('tracker = false');
    var options = {
      enableHighAccuracy: true,
      timeout: 5000,
      maximumAge: 10000
    };
    navigator.geolocation.getCurrentPosition(showCurrentUserPosition, errorHandler, options);
  }
}
// END app_init

// Called from app_init if nobody to track
function showCurrentUserPosition(pos){
  my_lat = pos.coords.latitude;
  my_lng = pos.coords.longitude;
  my_acc = pos.coords.accuracy;

  my_latlng = new google.maps.LatLng(my_lat, my_lng);
  mapOptions = {
      zoom: 16,
      center: my_latlng
   };

    map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

    my_marker = new google.maps.Marker({
      position: my_latlng,
      title: 'My location (approx)'
    });
    my_marker.setMap(map);
}

// For polling the tracker's beacon from the db
function pollTracker (tracker_id) {
  var salt = Math.floor(Math.random()*999999999999999);
  var beacon_icon = '<div class="beacon-icon"></div>';

  $.get('ajax/poll_tracker.php?'+ salt, {session_key: tracker_id}, function(data){
    // success
    console.log(data);
    var tracker_obj = jQuery.parseJSON(data);
    moveMarker(tracking_marker, true, tracker_obj.lat, tracker_obj.lng);
    var accuracy = tracker_obj.accuracy;

    if(accuracy >= 1000){
     sts_msg = 'Accuracy  is awful. (' + accuracy +'m)';
    }
    else{
      sts_msg = 'Accuracy ~' + accuracy +'m';  
    }

    sts_msg += ". <em>Last update @ " + tracker_obj.timestamp + "</em>" + beacon_icon;

    $('#sts').html(sts_msg).show();
    $('.beacon-icon').show().fadeOut();   
  });  
}

// Fired after share button clicked
function shareLocation(){
  // Get email to send to
  var recip = $('#recip').val();
  console.log(recip);

  $('#share-frm').slideUp();
  $('#sts').addClass('success').html('Invite sent!').slideDown(200).delay(2000).slideUp(200);
  // Send first set of datapoints to server
  $.post('ajax/share_location.php', {lat: my_lat, lng: my_lng, user: username, recip: recip, acc: my_acc}, function(data){
    // on success, fire the watcher which will trigger the updater
    // console.log(data);
    obj = jQuery.parseJSON(data);
    // console.log(obj);
    getLocationUpdate();
  });
}

// Main function to init watchPosition
function getLocationUpdate(){
   if(navigator.geolocation){
      // timeout at 60000 milliseconds (60 seconds)
      var options = {
        timeout: 60000,
        maximumAge: 5000
      };
      geoLoc = navigator.geolocation;
      watchID = geoLoc.watchPosition(sendLocation, errorHandler, options);
   }else{
      alert("Sorry, browser does not support geolocation!");
   }
}

// Sending out my tracked position to server
function sendLocation(position){
  // position obj holds the key...
  var latitude = position.coords.latitude;
  var longitude = position.coords.longitude;
  var accuracy = position.coords.accuracy;

  if(accuracy >= 1000){
    sts_msg = 'Position sent ok. Accuracy is awful. (' + accuracy +'m)';
  }
  else{
    sts_msg = 'Position sent ok. Accuracy ~' + accuracy +'m';  
  }
  // Keep updating the db with my coords
  $.post('ajax/post_coords.php', {lat: latitude, lng: longitude, session_key: obj.session_key, user: username, accuracy: accuracy}, function(){
    // on success
    $('#sts').removeClass("success").html(sts_msg).slideDown(200).delay(5000).slideUp(100);
    moveMarker(my_marker, true, latitude, longitude);
  });

}


// Utility funcs

// Get query string vars
function getQueryVariable(variable){
  var query = window.location.search.substring(1);
  var vars = query.split("&");
  for (var i=0;i<vars.length;i++) {
         var pair = vars[i].split("=");
         if(pair[0] == variable){return pair[1];}
  }
  return(false);
}

// Setting name on form
function setName(){
  $('#identity-frm').slideUp(200);
  $('#share-frm').slideDown(200);
  username = $('#username').val();
  $('#tracker_name').html(username + ' (you)');
}

// Moving marker and panning to positon
function moveMarker(marker, pan, lat, lng) {
  marker.setPosition( new google.maps.LatLng( lat, lng) );
  if(pan){
    map.panTo( new google.maps.LatLng( lat, lng) );
  }
  console.log('moved to: lat = ' + lat + " / lng = " + lng);
}

// Error handler
function errorHandler(err) {
  if(err.code == 1) {
    alert("Error: Access is denied!");
  }else if( err.code == 2) {
    alert("Error: Position is unavailable!");
  }
}

