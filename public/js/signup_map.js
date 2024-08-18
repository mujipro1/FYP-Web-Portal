var map, drawControl, drawnItems;
var deras = [];
var usedIndices = []; // Track used indices
var colors = ["#FF5733", "#33FF57", "#3357FF", "#F33FFF", "#FF335B"]; // Unique colors for polygons
var drawingModeActive = false; // Flag to track if drawing mode is active
var autoPanInterval; // Variable to hold the auto-pan interval
var existingLabels = {}; // Store existing labels by layer ID

form = document.getElementById("map-form");
deraDetailsinput = document.getElementById("deraDetails");

// Function to initialize the map with user's location
function initializeMap(lat, lng) {
    map = L.map("map").setView([lat, lng], 13);

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

    satelliteLayer.addTo(map);

    var baseLayers = {
        Satellite: satelliteLayer,
        OpenStreetMap: animatedLayer,
        "Google Hybrid": googleHybrid,
        "Google Terrain": googleTerrain,
    };

    L.control.layers(baseLayers).addTo(map);

    var searchControl = new L.esri.Controls.Geosearch().addTo(map);
    var results = new L.LayerGroup().addTo(map);

    searchControl.on("results", function (data) {
        results.clearLayers();
        for (var i = data.results.length - 1; i >= 0; i--) {
            results.addLayer(L.marker(data.results[i].latlng));
        }
    });

    drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    drawControl = new L.Control.Draw({
        edit: {
            featureGroup: drawnItems,
        },
        draw: {
            polyline: false,
            polygon: {
                allowIntersection: false,
                showArea: true,
                color: "#000",
            },
            rectangle: false,
            circle: false,
            marker: false,
            circlemarker: false,
        },
    });

    map.addControl(drawControl);

    map.on(L.Draw.Event.DRAWSTART, function () {
        drawingModeActive = true;
        startAutoPan();
    });

    map.on(L.Draw.Event.CREATED, onPolygonChange);
    map.on(L.Draw.Event.EDITED, function (e) {
        e.layers.eachLayer(onPolygonChange);
    });

    map.on(L.Draw.Event.DELETED, onPolygonDeleted); // Listen for deletions

    map.on(L.Draw.Event.DRAWSTOP, function () {
        drawingModeActive = false;
        stopAutoPan();
    });

    enableAutoPan();
}

// Function to get the next available index for a new Dera
function getNextDeraIndex() {
    for (var i = 1; i <= deras.length + 1; i++) {
        if (!usedIndices.includes(i)) {
            return i;
        }
    }
    return deras.length + 1;
}

function onPolygonChange(e) {
    var layer = e.layer;
    

    if (!e.layer) {
        layer = e;
    }

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
            // Update existing Dera
            deras[deraIndex] = layer;
        } else {
            // Check if the max limit of Deras has been reached
            if (has_deras === '1' && deras.length >= no_of_deras) {
                alert('You cannot create more than ' + no_of_deras + ' Deras.');
                drawnItems.removeLayer(layer);
                return;
            }

            // Add new Dera
            deras.push(layer);
            usedIndices.push(getNextDeraIndex());

            if (has_deras === '1') {
                // Handle labeling for new Dera
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

        if (has_deras === '1') {
            // Calculate and log the area for Deras with labels
            var areaInAcres = L.GeometryUtil.geodesicArea(polygonCoordinates[0]) / 4046.86;
            console.log('Dera ' + (deraIndex + 1) + ' updated:', polygonCoordinates, 'Area: ' + areaInAcres.toFixed(2) + ' acres');
        } else {
            // Calculate and log the area for generic bounding boxes
            var areaInAcres = L.GeometryUtil.geodesicArea(polygonCoordinates[0]) / 4046.86;
            console.log('Bounding Box updated:', polygonCoordinates, 'Area: ' + areaInAcres.toFixed(2) + ' acres');
        }
    } else {
        console.error('Layer does not have _latlngs property:', layer);
    }
}


// Function to handle polygon deletion
function onPolygonDeleted(e) {
    e.layers.eachLayer(function (layer) {
        var deraIndex = deras.findIndex(
            (d) => d._leaflet_id === layer._leaflet_id
        );
        if (deraIndex > -1) {
            deras.splice(deraIndex, 1);
            if (existingLabels[layer._leaflet_id]) {
                map.removeLayer(existingLabels[layer._leaflet_id]);
                delete existingLabels[layer._leaflet_id];
            }
            usedIndices.splice(deraIndex, 1);
        }
    });
}

// Auto-pan functionality
function enableAutoPan() {
    var panThreshold = 50; // Distance from edge to start panning
    var panSpeed = 10; // Speed of panning

    var lastMousePosition = { x: null, y: null };

    window.startAutoPan = function () {
        autoPanInterval = setInterval(function () {
            if (
                drawingModeActive &&
                lastMousePosition.x !== null &&
                lastMousePosition.y !== null
            ) {
                var mouseX = lastMousePosition.x;
                var mouseY = lastMousePosition.y;
                var mapWidth = map.getSize().x;
                var mapHeight = map.getSize().y;

                if (mouseX < panThreshold) {
                    map.panBy([-panSpeed, 0]);
                } else if (mouseX > mapWidth - panThreshold) {
                    map.panBy([panSpeed, 0]);
                }
                if (mouseY < panThreshold) {
                    map.panBy([0, -panSpeed]);
                } else if (mouseY > mapHeight - panThreshold) {
                    map.panBy([0, panSpeed]);
                }
            }
        }, 100);
    };

    window.stopAutoPan = function () {
        clearInterval(autoPanInterval);
    };

    map.on("mousemove", function (e) {
        lastMousePosition.x = e.originalEvent.clientX;
        lastMousePosition.y = e.originalEvent.clientY;
    });

    map.on("mouseleave", function () {
        lastMousePosition.x = null;
        lastMousePosition.y = null;
        stopAutoPan();
    });

    document
        .getElementById("draw-button")
        .addEventListener("click", function () {
            drawControl._toolbars.draw._modes.polygon.handler.enable();
        });

    document
        .getElementById("save-button")
        .addEventListener("click", function () {
            var deraData = deras.map(function (layer, index) {
                return {
                    name: has_deras === "1" ? "Dera " + (index + 1) : null,
                    coordinates: layer._latlngs[0],
                    acres:
                        L.GeometryUtil.geodesicArea(layer._latlngs[0]) /
                        4046.86,
                };
            });
            deraDetailsinput.value = JSON.stringify(deraData);
            form.submit();
        });
}

// Function to get user's current location and initialize the map
function getCurrentLocationAndInitializeMap() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                initializeMap(lat, lng);
            },
            function () {
                // Fallback location if user denies geolocation permission
                var fallbackLat = 33.6844;
                var fallbackLng = 73.0479;
                initializeMap(fallbackLat, fallbackLng);
            }
        );
    } else {
        // Fallback if geolocation is not supported
        var fallbackLat = 33.6844;
        var fallbackLng = 73.0479;
        initializeMap(fallbackLat, fallbackLng);
    }
}

// Trigger the map initialization
getCurrentLocationAndInitializeMap();
