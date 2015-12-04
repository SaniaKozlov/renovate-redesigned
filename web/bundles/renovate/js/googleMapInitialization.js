function initialize() {
	  var mapCanvas = document.getElementById('map-canvas');
	  var mapOptions = {
		      center: new google.maps.LatLng(48.61700, 22.29498),
		      zoom: 15,
		      mapTypeId: google.maps.MapTypeId.ROADMAP,
		      mapMaker: true,
		      scrollwheel: false
		    }
	  var map = new google.maps.Map(mapCanvas, mapOptions);
	  
	  var contentString = "<div style='width:145px;'><h4>ТОВ \"RENOVATE\"</h4></br><p>вул.Швабська 26</p></div>";
	  
	  var infowindow = new google.maps.InfoWindow({
	      content: contentString
	  });
	  
	  var marker = new google.maps.Marker({
	      position: new google.maps.LatLng(48.61700, 22.29498),
	      map: map
	  });
	  
	  infowindow.open(map,marker);
	  google.maps.event.addListener(marker, 'click', function() {
		    infowindow.open(map,marker);
	  });
  }
google.maps.event.addDomListener(window, 'load', initialize);