import React, { useCallback, useState } from 'react';
import { GoogleMap, LoadScript, Marker, Rectangle } from '@react-google-maps/api';

const center = {
  lat: 37.7749, // Example coordinates (San Francisco)
  lng: -122.4194,
};

const options = {
  fillColor: "lightblue",
  fillOpacity: 0.1,
  strokeColor: "blue",
  strokeOpacity: 0.8,
  strokeWeight: 2,
  clickable: false,
  draggable: false,
  editable: false,
  visible: true,
};

const boundingBoxCoordinates = {
  north: 37.785,
  south: 37.765,
  east: -122.405,
  west: -122.435,
};

const FarmMap = () => {
  const [bounds, setBounds] = useState(boundingBoxCoordinates);

  const onLoad = useCallback(map => {
    const bounds = new window.google.maps.LatLngBounds(center);
    map.fitBounds(bounds);
  }, []);

  return (
    <LoadScript googleMapsApiKey="YOUR_GOOGLE_MAPS_API_KEY">
      <div className="map-container">
        <GoogleMap
          mapContainerClassName="map-iframe"
          center={center}
          zoom={14}
          onLoad={onLoad}
        >
          <Marker position={center} />
          <Rectangle bounds={bounds} options={options} />
        </GoogleMap>
      </div>
    </LoadScript>
  );
};

export default FarmMap;
