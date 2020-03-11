let map;
let layerControl;

initMap();

function initMap() {
    const editableLayers = new L.FeatureGroup();

    // let lastLayerModified = -1;
    map = L.map('mapId', {minZoom: 0, maxZoom: 18}).setView([48.864716, 2.349014], 13);
    map.addLayer(L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        minZoom: 0,
        maxZoom: 18,
        attribution: 'Copyright',
    }));
    const drawnItems = new L.FeatureGroup();

    map.addLayer(drawnItems);
    map.addLayer(editableLayers);
    layerControl = L.control.layers({}, {});
}
