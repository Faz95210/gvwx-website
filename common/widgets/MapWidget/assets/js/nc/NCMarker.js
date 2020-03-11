class NCEditMarker extends L.Marker {

    constructor(latlng, options, indexInMarkerArray, latLngIndex) {
        super(latlng, options);
        this._indexInArray = indexInMarkerArray;
        this._latLngIndex = latLngIndex;
        this._latlng = latlng;
        // this.on('click', this.onClick);
        // this.on('dragstart', this.onDragStart);
        // this.on('dragend', this.onDragEnd);
        // this.on('call', this.onCall);
        this.setTooltip();
    }

    setTooltip() {
        this.bindPopup("Marker " + this._indexInArray + " number " + this._latLngIndex + " in coords<br> lat :" + this._latlng.lat + " lng : " + this._latlng.lng);
        this.on('mouseover', function (e) {
            if (e.originalEvent.altKey) {
                return;
            }
            this.openPopup();
        });
        this.on('mouseout', function (e) {
            this.closePopup();
        });
    }

    //
    // onClick(event){
    //     if(event.ctrlKey){
    //         this.remove();
    //     }
    // }
    //
    // onDragStart(event){
    // }
    //
    // onDragEnd(event){
    // }

    remove() {
        super.remove();
    }
}