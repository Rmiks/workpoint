$('.js-gmap-field').each(function (_key, _map) {
    var $container = $(_map);
    var $addressInput = $container.find("#address");
    var $addressLatInput = $container.find("#address_lat");
    var $addressLngInput = $container.find("#address_lng");
    var lat;
    var lng;
    var geocoder;
    var map;
    var marker;

    function initialize(lat, lng) {
        lat = (lat != undefined) ? lat : 56.9619428;
        lng = (lng != undefined) ? lng : 24.134509;

        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(lat, lng);
        var myOptions = {
            zoom: 7,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: false
        }

        map = new google.maps.Map($container.find("#map_canvas")[0], myOptions);

        var myLatlng = new google.maps.LatLng(lat, lng);

        marker = new google.maps.Marker({
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP,
            position: myLatlng
        });

        google.maps.event.addListener(marker, 'dragend', function () {
            changeCoordinates(marker.position.lat(), marker.position.lng());
        });

        google.maps.event.addListener(map, 'click', function (event) {
            marker.setMap(null);

            var position = event.latLng;

            marker = new google.maps.Marker({
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP,
                position: position
            });

            changeCoordinates(position.lat(), position.lng());

            google.maps.event.addListener(marker, 'dragend', function () {
                changeCoordinates(marker.position.lat(), marker.position.lng());
            });
            ;
        });

        $('#address_lat, #address_lng').on('change', function (e) {
            var latLng = new google.maps.LatLng($addressLatInput.val(), $addressLngInput.val());
            marker.setMap(null);
            map.setCenter(latLng);
            marker = new google.maps.Marker({
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP,
                position: latLng
            });
        });
    }

    // get addresss by latLng
    function codeLatLng() {
        var lat = parseFloat($addressLatInput.value);
        var lng = parseFloat($addressLngInput.value);
        var latlng = new google.maps.LatLng(lat, lng);
        geocoder.geocode({'latLng': latlng}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                console.log(results);
                if (results[1]) {
                    $addressInput(results[1].formatted_address);
                }
            } else {
                alert("Geocoder failed due to: " + status);
            }
        });
    }

    function codeAddress() {
        var address = $addressInput.val();
        geocoder.geocode({'address': address}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                marker.setMap(null);

                changeCoordinates(results[0].geometry.location.lat(), results[0].geometry.location.lng());

                map.setCenter(results[0].geometry.location);
                marker = new google.maps.Marker({
                    map: map,
                    draggable: true,
                    animation: google.maps.Animation.DROP,
                    position: results[0].geometry.location
                });
                google.maps.event.addListener(marker, 'dragend', function () {
                    changeCoordinates(marker.position.lat(), marker.position.lng());
                });

            } else {
                alert("Geocode was not successful for the following reason: " + status);
            }
        });
    }

    function clearCoordinates() {
        $addressInput.val('');
        changeCoordinates('', '');
        marker.setMap(null);
    }

    function changeCoordinates(lat, lng) {
        $addressLatInput.val(lat);
        $addressLngInput.val(lng);
    }

    $container.find('.js-gmap-code-address').on('click', function (e) {
        codeAddress();
    });

    $container.find('.js-gmap-clear-coordinates').on('click', function (e) {
        clearCoordinates();
    });

    $container.find('.js-gmap-address-input').on('keypress', function (e) {
        if (e.keyCode == 13) {
            codeAddress();
            return false;
        }
    });

    initialize($container.data('lat'), $container.data('lng'));
    changeCoordinates($container.data('lat'), $container.data('lng'));
});