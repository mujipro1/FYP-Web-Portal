var map, drawControl, drawnItems;
var polygons = []; // Array to store polygons
var colors = ['#FF5733', '#33FF57', '#3357FF', '#F33FFF', '#FF335B']; // Unique colors for polygons
var existingLabels = {}; // Store existing labels by layer ID

function initializeMap(lat, lng) {
    map = L.map('map').setView([lat, lng], 13);

    var animatedLayer = L.tileLayer(
        "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
        {
            maxZoom: 19,
        }
    );

    var satelliteLayer = L.tileLayer(
        "http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}",
        {
            maxZoom: 20,
            subdomains: ["mt0", "mt1", "mt2", "mt3"],
        }
    );

    var googleHybrid = L.tileLayer(
        "http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}",
        {
            maxZoom: 20,
            subdomains: ["mt0", "mt1", "mt2", "mt3"],
        }
    );

    var googleTerrain = L.tileLayer(
        "http://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}",
        {
            maxZoom: 20,
            subdomains: ["mt0", "mt1", "mt2", "mt3"],
        }
    );

    googleHybrid.addTo(map);

    var baseLayers = {
        Satellite: satelliteLayer,
        OpenStreetMap: animatedLayer,
        "Google Hybrid": googleHybrid,
        "Google Terrain": googleTerrain,
    };

    L.control.layers(baseLayers).addTo(map);

    drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    drawControl = new L.Control.Draw({
        edit: {
            featureGroup: drawnItems
        },
        draw: {
            polygon: {
                allowIntersection: false,
                showArea: true,
                color: '#000'
            },
            polyline: false,
            rectangle: false,
            circle: false,
            marker: false,
            circlemarker: false,
        }
    });

    map.addControl(drawControl);
    map.on(L.Draw.Event.CREATED, onPolygonChange);
    map.on(L.Draw.Event.EDITED, function (e) {
        e.layers.eachLayer(onPolygonChange);
    });
    map.on(L.Draw.Event.DELETED, function (e) {
        e.layers.eachLayer(function (layer) {
            var polygonIndex = polygons.findIndex(p => p._leaflet_id === layer._leaflet_id);
            if (polygonIndex > -1) {
                polygons.splice(polygonIndex, 1);
                if (existingLabels[layer._leaflet_id]) {
                    map.removeLayer(existingLabels[layer._leaflet_id]);
                    delete existingLabels[layer._leaflet_id];
                }
            }
        });
        var polygonData = polygons.map(function (layer) {
            return {
                name: cropname,
                coordinates: layer.getLatLngs()[0]
            };
        });
        document.getElementById('polygonData').value = JSON.stringify(polygonData);
    });

    document.getElementById("draw-button").addEventListener("click", function () {
        drawControl._toolbars.draw._modes.polygon.handler.enable();
    });

}

function onPolygonChange(e) {
    var layer = e.layer || e;

    if (layer._latlngs) {
        var polygonCoordinates = layer._latlngs;
        var color = colors[polygons.length % colors.length];
        layer.setStyle({ color: color });

        if (!drawnItems.hasLayer(layer)) {
            drawnItems.addLayer(layer);
        }

        // Check if the layer is already in the polygons array
        var polygonIndex = polygons.findIndex(p => p._leaflet_id === layer._leaflet_id);
        if (polygonIndex > -1) {
            polygons[polygonIndex] = layer; // Update existing polygon
        } else {
            polygons.push(layer); // Add new polygon

            var center = layer.getBounds().getCenter();
            var label = L.tooltip({
                permanent: true,
                direction: 'center',
                className: 'label'
            })
                .setLatLng(center)
                .setContent(cropname)
                .addTo(map);

            existingLabels[layer._leaflet_id] = label;
        }

        var areaInAcres = L.GeometryUtil.geodesicArea(polygonCoordinates[0]) / 4046.86;
        console.log('Polygon updated:', polygonCoordinates, 'Area: ' + areaInAcres.toFixed(2) + ' acres');

        var polygonData = polygons.map(function (layer) {
            return {
                name: cropname,
                coordinates: layer.getLatLngs()[0]
            };
        });
        document.getElementById('polygonData').value = JSON.stringify(polygonData);
    }
}

function getCurrentLocationAndInitializeMap() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            initializeMap(position.coords.latitude, position.coords.longitude);
        }, function () {
            initializeMap(40.7128, -74.0060); // Default location (New York City)
        });
    } else {
        initializeMap(40.7128, -74.0060);
    }
}

getCurrentLocationAndInitializeMap();
