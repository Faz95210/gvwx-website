class NCEditableRoute extends L.Polyline {

    constructor(t, e) {
        super(t, e);
        this._coords = t;
        this._options = e;
    }

    // toGeoJson(){
    //     return super.toGeoJSON();
    // }

    mergeWithNewRoutes(newCoordinates, startCoordsIndex, endCoordsIndex) {
        let newCoords = Array();
        if (startCoordsIndex >= 0) {
            for (let i = 0; i < startCoordsIndex; i++) {
                newCoords[newCoords.length] = this._coords[i];
            }
            if (startCoordsIndex == 0) {
                newCoords[newCoords.length] = this._coords[0];
            }
        }
        for (let i = 0; i < newCoordinates.length; i++) {
            newCoords[newCoords.length] = newCoordinates[i];
        }
        if (endCoordsIndex >= 0) {
            for (let i = endCoordsIndex; i < this._coords.length; i++) {
                newCoords[newCoords.length] = this._coords[i];
            }
        }
        this._coords = newCoords;
        // return newCoords;
        super.setLatLngs(newCoords);
    }

}