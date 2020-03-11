/**
 * This class represents a group holding an editable route and it's markers
 */
class NCEditable extends L.LayerGroup {

    constructor(coords, options) {
        const t = [new NCEditableRoute(coords, options)];
        super(t);
        this._route = t[0];
        this._index = options['index'];

        this._route.addTo(this);
        this._options = options;
        this._markers = Array();
        if (!options.pointIcon)
            this._options.pointIcon = L.Icon.Default;
        if (!options.newPointIcon)
            this._options.newPointIcon = L.Icon.Default;
        this._placeMarkers();
        const that = this;
        this._route.on('click', function (e) {
            const clickCoordinate = e.latlng;

            //Find Closest coordinate in route
            let closestDistance = 99999999;
            let closestCoordinateIndex;
            for (let i = 0; i < this._latlngs.length; i++) {
                const newDistance = that._distance(clickCoordinate.lat, clickCoordinate.lng, this._latlngs[i].lat, this._latlngs[i].lng);
                if (newDistance < closestDistance) {
                    closestDistance = newDistance;
                    closestCoordinateIndex = i;
                }
            }
            //Find Position in markers array
            let indexInArray = 0;
            for (let i = 0; i < that._markers.length; i++) {
                if (that._markers[i]._latLngIndex > closestCoordinateIndex) {
                    indexInArray = i;
                    break;
                }
            }
            //Create Marker insert it and fix indexes
            const marker = that._createMarker(clickCoordinate, indexInArray, closestCoordinateIndex);
            that._markers.splice(indexInArray, 0, marker);
            for (let i = 0; i < that._markers.length; i++) {
                that._markers[i]._indexInArray = i;
                that._markers[i].setTooltip();
            }
        });
    }

    /*
    true : Save only the route
    false: Saves the route and the markers
     */
    toGeoJson(simple) {
        let geoJSON;
        if (simple) {
            geoJSON = this._route.toGeoJSON();
        } else {
            geoJSON = this.toGeoJSON();
        }
        console.log("kml : ", tokml(geoJSON));
    }

    addTo(t) {
        super.addTo(t);
        map.fitBounds(this._route.getBounds())
    }

    /**
     *
     * @param marker Marker handling the event
     * @param point1 Previous marker's position
     * @param point2 Next marker's position
     * @private
     */
    _setupDragLines(marker, point1, point2) {
        let line1 = null;
        let line2 = null;
        if (point1) line1 = L.polyline([marker.getLatLng(), point1], {dasharray: "5,1", weight: 1})
            .addTo(map);
        if (point2) line2 = L.polyline([marker.getLatLng(), point2], {dasharray: "5,1", weight: 1})
            .addTo(map);

        const moveHandler = function (event) {
            if (line1)
                line1.setLatLngs([event.latlng, point1]);
            if (line2)
                line2.setLatLngs([event.latlng, point2]);
        };

        const stopHandler = function (event) {
            if (map) {
                map.off('mousemove', moveHandler);
                marker.off('dragend', stopHandler);
                if (line1) map.removeLayer(line1);
                if (line2) map.removeLayer(line2);
                if (event.target != map) {
                    map.fire('click', event);
                }
            }
        };

        map.on('mousemove', moveHandler);
        marker.on('dragend', stopHandler);

        // map.once('click', function(){
        //     console.log("DRAG START");
        //     lastLayerModified = that._index;
        //     that._previous = that;
        // });
        marker.once('click', stopHandler);
        if (line1) line1.once('click', stopHandler);
        if (line2) line2.once('click', stopHandler);
    }

    // _saveCurrent(){
    //     console.log("Saving current");
    //     this._previousRoute = this._route;
    //     this._previousMarkers = this._markers;
    //     lastLayerModified = this._index;
    // }

    _createMarker(latlng, indexInArray, latLngIndex) {
        // const marker = new NCEditMarker(latlng, {});
        const marker = new NCEditMarker(latlng, {
            bubblingMouseEvents: false,
            draggable: true,
            zIndexOffset: 1500,
            riseOnHover: true,
            riseOffset: 500,
            keyboard: true,
            icon: new L.Icon({iconUrl: 'images/marker.png', iconSize: [11, 11], iconAnchor: [6, 6]})
        }, indexInArray, latLngIndex);
        const that = this;

        marker.on('click', function (event) {
            if (event.originalEvent.altKey) {
                // that.toGeoJson(true);
                marker.remove();
                that._markers.splice(marker._indexInArray, 1);
                that._arrangeMarkers();
            }
        });

        marker.on('dragstart', function (event) {
            // that._saveCurrent();
            const pointNo = marker._indexInArray;
            const previousPoint = pointNo > 0 ? that._markers[pointNo - 1].getLatLng() : null;
            const nextPoint = pointNo < that._markers.length - 1 ? that._markers[pointNo + 1].getLatLng() : null;
            that._setupDragLines(marker, previousPoint, nextPoint);
            // that._hideAll(marker);
        });

        marker.on('dragend', function (event) {
            // var marker = event.target;
            const pointNo = marker._indexInArray;
            setTimeout(function () {
                //Get Waypoints for routing
                const previousIndex = pointNo > 0 ? that._markers[pointNo - 1]._latLngIndex : -1;
                const nextIndex = pointNo < that._markers.length - 1 ? that._markers[pointNo + 1]._latLngIndex : -1;
                let waypoints = [];
                if (previousIndex >= 0) {
                    waypoints[waypoints.length] = that._route._coords[previousIndex]
                }
                waypoints[waypoints.length] = marker._latlng;
                if (nextIndex >= 0) {
                    waypoints[waypoints.length] = that._route._coords[nextIndex]
                }

                that._enableDisableMarker(false);
                const route = L.Routing.control({'waypoints': waypoints, /*serviceUrl:'http://54.72.39.19:5000/route/v1', suppressDemoServerWarning: true*/});
                route.route({
                    callback: function (err, routes) {
                        if (!err) {
                            that._route.mergeWithNewRoutes(routes[0].coordinates, previousIndex, nextIndex);
                            that._markers = that._arrangeMarkers();
                            that._enableDisableMarker(true);
                        } else {
                            console.log('Routing Error', err);
                        }
                    }
                });
            }, 25);
        });
        marker.addTo(this);
        return marker;
    }

    _enableDisableMarker(flag) {
        for (let i = 0; i < this._markers.length; i++) {
            this._markers[i].clickable = flag;
        }
    }

    _placeMarkers() {
        this._markers = [];
        for (let i = 0; i < this._route._coords.length; i++) {
            if (i === 0
                || (i > this._options['pointsBetweenMarkers'] && i % (this._options['pointsBetweenMarkers']) === 0 && i < this._route._coords.length - (this._options['pointsBetweenMarkers']))
                || i === this._route._coords.length - 1) {
                this._markers.push(this._createMarker(this._route._coords[i], this._markers.length, i));
            }
        }
    }

    _arrangeMarkers() {
        let return_value = [];
        for (let j = 0; j < this._markers.length; j++) {
            const marker = this._markers[j];
            marker._indexInArray = j;
            //If Marker isn't at the right place anymore fix it
            if (marker._latlng !== this._route._coords[marker._latLngIndex]) {
                //Is Marker's location still on the route
                let found = false;
                for (let i = 0; i < this._route._coords.length && !found; i++) {
                    if (marker._latlng === this._route._coords[i]) {
                        found = true;
                    }
                }
                //It is not so move it to the closest place on the route
                if (!found) {
                    let closestCoordinateIndex = 0;
                    let closestDistance = 9999999;
                    for (let i = 0; i < this._route._coords.length; i++) {
                        const latlng = this._route._coords[i];
                        const currentDistance = this._distance(marker._latlng.lat, marker._latlng.lng, latlng.lat, latlng.lng);
                        if (closestDistance > currentDistance) {
                            closestDistance = currentDistance;
                            closestCoordinateIndex = i;
                        }
                    }
                    marker._latlng = this._route._coords[closestCoordinateIndex];
                    marker._latLngIndex = closestCoordinateIndex;
                    // this._arrangeMarkers();
                }

            }
            marker.setTooltip();
            return_value[j] = marker;
        }
        return return_value;
    }

    _distance(lat1, lon1, lat2, lon2, unit) {
        if ((lat1 == lat2) && (lon1 == lon2)) {
            return 0;
        } else {
            var radlat1 = Math.PI * lat1 / 180;
            var radlat2 = Math.PI * lat2 / 180;
            var theta = lon1 - lon2;
            var radtheta = Math.PI * theta / 180;
            var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
            if (dist > 1) {
                dist = 1;
            }
            dist = Math.acos(dist);
            dist = dist * 180 / Math.PI;
            dist = dist * 60 * 1.1515;
            if (unit == "K") {
                dist = dist * 1.609344
            }
            if (unit == "N") {
                dist = dist * 0.8684
            }
            return dist;
        }
    }

}

