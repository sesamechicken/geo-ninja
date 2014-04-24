// Utility funcs

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
};


// Distance calculator (future)
function calculateDistance(lat1, lng1, lat2, lng2) {
  var R = 6371; // km
  var dLat = (lat2 - lat1).toRad();
  var dLon = (lon2 - lon1).toRad(); 
  var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
          Math.cos(lat1.toRad()) * Math.cos(lat2.toRad()) * 
          Math.sin(dLon / 2) * Math.sin(dLon / 2); 
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)); 
  var d = R * c;
  return d;
}
Number.prototype.toRad = function() {
  return this * Math.PI / 180;
}

// Error handler
function errorHandler(err) {
  if(err.code == 1) {
    alert("Error: Access is denied!");
  }else if( err.code == 2) {
    alert("Error: Position is unavailable!");
  }
}

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