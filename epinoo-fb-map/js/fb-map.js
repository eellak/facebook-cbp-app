function fb_map_initialise() {
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: new google.maps.LatLng(userLat, userLong),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    for (var i = 0; i < plainLocations.length; i++) {
        var icon = '';
        var lat = plainLocations[i][0];
        var lng = plainLocations[i][1];
        var title = plainLocations[i][2] + "";

        if (i == 0) {
            icon = 'http://www.clker.com/cliparts/b/7/6/5/1308001441853739087google maps pin.svg.thumb.png';
        }
        else {
            icon = 'http://www.clker.com/cliparts/c/9/m/4/B/d/google-maps-grey-marker-w-shadow-th.png';
        }

        latlng = new google.maps.LatLng(lat, lng);

        var marker = new google.maps.Marker({
            position: latlng,
            map: map,
            icon: icon,
            title: title
        });

        var infowindow = new google.maps.InfoWindow()
        google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){
            return function() {
                infowindow.setContent(content);
                infowindow.open(map,marker);
            };
        })(marker,title,infowindow));
    }
};



$(window).load(function () {
    fb_map_initialise();
});