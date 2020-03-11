// function previous(){
//     console.log(layerControl._layers[lastLayerModified].layer._previousMarkers);
//     // console.log(layerControl._layers[lastLayerModified].layer._previousRoute);
//     console.log(layerControl._layers[lastLayerModified].layer._markers);
//     // layerControl._layers[lastLayerModified].layer = layerControl._layers[lastLayerModified].layer._previous;
// }

function parseKML(fileList) {
    if (fileList.length > 0) {
        for (let i = 0; i < fileList.length; i++) {
            const file = fileList[i];
            const reader = new FileReader();
            reader.onload = function (event) {
                handleKMLContent(event.target.result)
            };
            reader.readAsText(file);
        }
    } else {
        alert("Pick a file");
    }
}

function handleKMLContent(content) {
    const parser = new DOMParser();
    const rawKml = parser.parseFromString(content, 'text/xml');
    const kml = new L.KML(rawKml);
    console.log(kml);
    //#TODO Get group name from server.
    const group = new NCEditable(kml.latLngs, {
        color: kml._options['color'],
        opacity: kml._options['opacity'],
        smoothFactor: 2,
        bubblingMouseEvents: false,
        clickable: true,
        pointsBetweenMarkers: 100,
        assetPath: '##RESOURCEPATH##',
        weight: kml._options['weight'],
        index: layerControl._layers.length
    });
    group.addTo(map);
    layerControl.addOverlay(group, kml._documentName);
    layerControl.addTo(map);
}
