import React from 'react';
import { MapContainer, TileLayer, Marker, Popup } from 'react-leaflet';
import 'leaflet/dist/leaflet.css';

// Arreglo para un problema común con los íconos de los marcadores en Leaflet con Webpack/React
import L from 'leaflet';
delete L.Icon.Default.prototype._getIconUrl;

L.Icon.Default.mergeOptions({
  iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
  iconUrl: require('leaflet/dist/images/marker-icon.png'),
  shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});


const Mapa = ({ sites }) => {
  // Coordenadas del centro inicial del mapa (Guadalajara Centro)
  const initialPosition = [20.6760, -103.3472];

  // Filtrar sitios para incluir solo los de Guadalajara, Tlajomulco y Tlaquepaque
  // Esta es una simplificación. En una app real, los datos podrían tener una propiedad 'municipio'.
  // Aquí asumimos que los sitios pasados ya están pre-filtrados o todos son relevantes.
  // Para este ejemplo, usaremos todos los sitios que tengan coordenadas.
  const relevantSites = sites.filter(site => site.coordenadas && site.coordenadas.length === 2);

  return (
    <div className="map-view">
      <MapContainer center={initialPosition} zoom={13} scrollWheelZoom={true} className="leaflet-container">
        <TileLayer
          attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
          url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
        />
        {relevantSites.map(site => (
          <Marker key={site.id} position={site.coordenadas}>
            <Popup>
              <strong>{site.nombre}</strong>
              <br />
              {site.descripcion.substring(0, 50)}...
              {/* Se podría agregar un botón para "Ver más" o navegar al sitio */}
            </Popup>
          </Marker>
        ))}
      </MapContainer>
    </div>
  );
};

export default Mapa;
