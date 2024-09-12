
var map;

function initializeMap() {
    // Initialize the map without setting a specific center or zoom level
    map = L.map('map');

    // Define tile layers
    var animatedLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    });

    var satelliteLayer = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });

    var googleHybrid = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });

    var googleTerrain = L.tileLayer('http://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });

    // Add the default layer to the map
    if (map_var == 1){
        googleHybrid.addTo(map);
    }
    googleHybrid.addTo(map);

    // Base layers object for the layer control
    var baseLayers = {
        "Satellite": satelliteLayer,
        "OpenStreetMap": animatedLayer,
        "Google Hybrid": googleHybrid,
        "Google Terrain": googleTerrain
    };

    // Add layer control to the map
    L.control.layers(baseLayers).addTo(map);

    // Create a LatLngBounds object to track the bounds of all polygons
    var bounds = L.latLngBounds();

    // Draw each Dera on the map
    mapdata.forEach(function (dera) {
        var latlngs = dera.coordinates.map(function (coord) {
            return [coord.lat, coord.lng];
        });

        var polygon = L.polygon(latlngs, {
            color: '#ffffff', // Default polygon color
        }).addTo(map);

        // Extend the bounds to include this polygon
        bounds.extend(polygon.getBounds());

        if (dera.name  == null){
            dera.name = 'Farm';
        }
        // Add a label for each Dera
        var center = polygon.getBounds().getCenter();
        if (map_var == 1){
            L.tooltip({
                permanent: true,
                direction: 'center',
                className: 'label'
            })
            .setLatLng(center)
            .setContent(dera.name)
            .addTo(map);
        }
        else{
            console.log(dera)
            L.tooltip({
                permanent: true,
                direction: 'center',
                className: 'label'
            })
            .setLatLng(center)
            .setContent(dera.name + ' (' + Math.round(dera.acres, 2) + ' acres)' )
            .addTo(map);
        }
    });

    // Fit the map to the bounds of all the polygons
    map.fitBounds(bounds);
}

// Call the function to initialize the map
initializeMap();
