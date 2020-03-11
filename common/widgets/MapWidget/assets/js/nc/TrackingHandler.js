let lastId = 0;
let count = 1;
let device_id = -1;

const distanceBetweenPoints = 0.2;
let lastLatLng = [lat => 9500, lng => 9500];

//Create a group to hold all the markers.
let layerGroup = L.featureGroup([]);

function initTrackingMap(rtTrackings, btoa, last_tracking) {
    if (last_tracking) {
        lastId = last_tracking;
    }
    for (let i = 0; i < rtTrackings.length; i++) {
        const tracking = rtTrackings[i];
        let distance = -1;
        let className = "rt-tracking-RegularMarker";
        //Put fitting class name, if regular only display if further than ${distanceBetweenPoints}
        if (i === 0) {
            className = "rt-tracking-FirstMarker"
        } else if (i === rtTrackings.length - 1) {
            className = "rt-tracking-LastMarker";
        } else {
            distance = calculateDistance(tracking.lat, tracking.lng, lastLatLng.lat, lastLatLng.lng)
        }
        if (distance == -1 || distance > distanceBetweenPoints) {
            lastLatLng = {lat: tracking.lat, lng: tracking.lng};
            addMarkerToMap(tracking.lat, tracking.lng, className, tracking.timestamp)
        }
    }

    if (rtTrackings.length > 0) {
        map.addLayer(layerGroup);
        map.fitBounds(layerGroup.getBounds());
    }
    setInterval(function () {
        console.log('poll');
        let url = 'index.php?r=rttracking/getlist';
        if (device_id !== -1) {
            url += '&Device_id=' + device_id;
        }
        if (lastId > 0) {
            url += '&RtTrackingId=' + lastId;
        }
        $.ajax({
            success: onNewRtTrackingReceived,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", "Basic " + btoa);
            },
            url: url,
            type: "GET"
        });
    }, 6000);
}

function onNewRtTrackingReceived(data) {
    console.log(data['data']);
    if (data['data'].length <= 0) return;
    for (let i = 0; i < data['data'].length; i++) {
        lastId = data['data'][i].id;
        if (i === data['data'].length - 1 || calculateDistance(lastLatLng.lat, lastLatLng.lng, data['data'][i].lat, data['data'][i].lng) > distanceBetweenPoints) {
            lastLatLng = {'lat': data['data'][i].lat, 'lng': data['data'][i].lng};
            const el = $('.rt-tracking-LastMarker');
            // el.bindPopup("Vu à " + formattedTime);

            el.addClass('rt-tracking-RegularMarker');
            el.removeClass('rt-tracking-LastMarker');
            addMarkerToMap(data['data'][i].lat, data['data'][i].lng, 'rt-tracking-LastMarker', data['data'][i].timestamp)
        }
    }
    map.addLayer(layerGroup);
    map.fitBounds(layerGroup.getBounds());
}

function addMarkerToMap(lat, lng, klass, timestamp) {
    const newMarker = L.marker(L.latLng(lat, lng, 0), {
        icon: new L.DivIcon({
            className: "myMarker " + klass,
            iconAnchor: [0, 24],
            labelAnchor: [-6, 0],
            popupAnchor: [0, -36],
            html: '<span class="myMarker ' + klass + '">' + (count++) + '</span>'
        })
    });
    newMarker.addTo(layerGroup);
    const date = new Date(timestamp * 1000);
    // Hours part from the timestamp
    const hours = date.getHours();
    // Minutes part from the timestamp
    const minutes = "0" + date.getMinutes();
    // Seconds part from the timestamp
    const seconds = "0" + date.getSeconds();
    lastLatLng = {lat: lat, lng: lng};
    // Will display time in 10:30:23 format
    const formattedTime = hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
    if (klass === 'rt-tracking-LastMarker') {
        newMarker.bindPopup("Dernier tracker <br> Vu à " + formattedTime);
    } else if (klass === 'rt-tracking-FirstMarker') {
        newMarker.bindPopup("Début de la course. <br> Vu à " + formattedTime);
    } else {
        newMarker.bindPopup("Vu à " + formattedTime);
    }
    newMarker.on('mouseover', function (e) {
        if (e.originalEvent.altKey) {
            return;
        }
        newMarker.openPopup();
    });
    newMarker.on('mouseout', function (e) {
        newMarker.closePopup();
    });
    return newMarker;
}


function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // km (change this constant to get miles)
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    const d = R * c;
    return d;
}

function getCookie(name) {
    var value = "; " + document.cookie;
    console.log(value);
    var parts = value.split("; " + name + "=");
    console.log(value);
    if (parts.length == 2) return parts.pop().split(";").shift();
}
