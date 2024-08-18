var map, drawControl, drawnItems;
var deras = [];
var usedIndices = []; // Track used indices
var colors = ['#FF5733', '#33FF57', '#3357FF', '#F33FFF', '#FF335B']; // Unique colors for polygons
var existingLabels = {}; // Store existing labels by layer ID
var deraCount = 0;

form = document.getElementById('map-form');
deraDetailsinput = document.getElementById('deraDetails');

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
            polygon: has_deras === 1 ? {
                allowIntersection: false,
                showArea: true,
                color: '#000',
                maxPolygons: no_of_deras - deras.length // Limit number of polygons based on Deras
            } : {
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
            var deraIndex = deras.findIndex(d => d._leaflet_id === layer._leaflet_id);
            if (deraIndex > -1) {
                deras.splice(deraIndex, 1);
                if (existingLabels[layer._leaflet_id]) {
                    map.removeLayer(existingLabels[layer._leaflet_id]);
                    delete existingLabels[layer._leaflet_id];
                }
                usedIndices.splice(deraIndex, 1);

                // Renumber the remaining deras
                updateDeraLabels();
            }
        });
    });

    if (has_deras === 1) {
        // Load existing deras from mapdata
        mapdata.forEach((data, index) => {
            var layer = L.polygon(data.coordinates, {
                color: colors[index % colors.length]
            }).addTo(drawnItems);

            if (data.name == null) {
                data.name = 'Farm';
            }

            deras.push(layer);

            var label = L.tooltip({
                permanent: true,
                direction: 'center',
                className: 'label'
            })
            .setLatLng(layer.getBounds().getCenter())
            .setContent(data.name)
            .addTo(map);

            existingLabels[layer._leaflet_id] = label;
            usedIndices.push(index + 1);
        });

        // Fit the map to the bounding boxes
        if (deras.length > 0) {
            var group = new L.featureGroup(deras);
            map.fitBounds(group.getBounds(), { padding: [20, 20] });
        }
    }
    document
    .getElementById("draw-button")
    .addEventListener("click", function () {
        drawControl._toolbars.draw._modes.polygon.handler.enable();
    });

    document.getElementById('save-button').addEventListener('click', function () {
        var deraData = deras.map(function (layer, index) {
            return {
                name: has_deras === 1 ? 'Dera ' + (index + 1) : '',
                coordinates: layer.getLatLngs()[0]
            };
        });
        deraDetailsinput.value = JSON.stringify(deraData);
        form.submit();
    });
}

// Helper function to update labels after deletion
function updateDeraLabels() {
    deras.forEach((layer, index) => {
        var center = layer.getBounds().getCenter();
        if (has_deras === 1) {

            var deraName = 'Dera ' + (index + 1);
        }
        else{
            var deraName = 'Farm'
        }
        if (existingLabels[layer._leaflet_id]) {
            existingLabels[layer._leaflet_id].setLatLng(center).setContent(deraName);
        } else {
            var label = L.tooltip({
                permanent: true,
                direction: 'center',
                className: 'label'
            })
                .setLatLng(center)
                .setContent(deraName)
                .addTo(map);

            existingLabels[layer._leaflet_id] = label;
        }
    });
}

function onPolygonChange(e) {
    var layer = e.layer || e;

    if (layer._latlngs) {
        var polygonCoordinates = layer._latlngs;
        var color = colors[deras.length % colors.length];
        layer.setStyle({ color: color });

        if (!drawnItems.hasLayer(layer)) {
            drawnItems.addLayer(layer);
        }

        // Check if the layer is already in the deras array
        var deraIndex = deras.findIndex(d => d._leaflet_id === layer._leaflet_id);
        if (deraIndex > -1) {
            deras[deraIndex] = layer; // Update existing dera
        } else {
            // Add new Dera
            if (has_deras === 1 && deras.length >= no_of_deras) {
                alert('You cannot create more than ' + no_of_deras + ' Deras.');
                drawnItems.removeLayer(layer);
                return;
            }
            deras.push(layer);
            usedIndices.push(deras.length); // Keep track of the dera index

            if (has_deras === 1) {
                var deraName = 'Dera ' + usedIndices[deras.indexOf(layer)];
                var center = layer.getBounds().getCenter();
                var label = L.tooltip({
                    permanent: true,
                    direction: 'center',
                    className: 'label'
                })
                    .setLatLng(center)
                    .setContent(deraName)
                    .addTo(map);

                existingLabels[layer._leaflet_id] = label;
            }
        }

        if (has_deras === 1) {
            var areaInAcres = L.GeometryUtil.geodesicArea(polygonCoordinates[0]) / 4046.86;
            console.log('Dera ' + (deraIndex + 1) + ' updated:', polygonCoordinates, 'Area: ' + areaInAcres.toFixed(2) + ' acres');
        } else {
            var areaInAcres = L.GeometryUtil.geodesicArea(polygonCoordinates[0]) / 4046.86;
            console.log('Bounding Box updated:', polygonCoordinates, 'Area: ' + areaInAcres.toFixed(2) + ' acres');
        }
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
