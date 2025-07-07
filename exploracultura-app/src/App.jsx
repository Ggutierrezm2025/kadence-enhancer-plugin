import React, { useState, useEffect } from 'react';
import './main.css'; // Asegúrate que la ruta sea correcta
import WelcomeScreen from './components/WelcomeScreen';
import SitioCard from './components/SitioCard'; // Para la vista Home
import Mapa from './components/Mapa';
import Trivia from './components/Trivia'; // Opcional, para demostración

// Datos de ejemplo para los sitios culturales
const sitesData = [
  {
    id: 1,
    nombre: "Catedral de Guadalajara",
    descripcion: "Emblema arquitectónico de la ciudad con sus torres neogóticas.",
    imagen: "https://upload.wikimedia.org/wikipedia/commons/thumb/3/38/Catedral_Metropolitana_de_Guadalajara_Enrique_V%C3%A1zquez_Aldana.jpg/1200px-Catedral_Metropolitana_de_Guadalajara_Enrique_V%C3%A1zquez_Aldana.jpg", // URL de imagen de ejemplo
    audio: "audio/catedral.mp3", // Ruta simulada
    coordenadas: [20.6770, -103.3470]
  },
  {
    id: 2,
    nombre: "Instituto Cultural Cabañas",
    descripcion: "Hospicio histórico con murales de José Clemente Orozco, Patrimonio de la Humanidad.",
    imagen: "https://upload.wikimedia.org/wikipedia/commons/thumb/a/ab/Hospicio_Caba%C3%B1as_01.JPG/1280px-Hospicio_Caba%C3%B1as_01.JPG", // URL de imagen de ejemplo
    audio: "audio/cabanas.mp3",
    coordenadas: [20.6775, -103.3375]
  },
  {
    id: 3,
    nombre: "Centro Cultural El Refugio (Tlaquepaque)",
    descripcion: "Antiguo hospital y convento, hoy centro de exposiciones y eventos culturales.",
    imagen: "https://upload.wikimedia.org/wikipedia/commons/thumb/3/3f/Centro_Cultural_El_Refugio%2C_Tlaquepaque%2C_Jalisco%2C_M%C3%A9xico%2C_2021_02.jpg/1024px-Centro_Cultural_El_Refugio%2C_Tlaquepaque%2C_Jalisco%2C_M%C3%A9xico%2C_2021_02.jpg",
    audio: "audio/refugio.mp3",
    coordenadas: [20.6420, -103.3100] // Tlaquepaque
  },
    {
    id: 4,
    nombre: "Parroquia de San Pedro Apóstol (Tlaquepaque)",
    descripcion: "Iglesia icónica en el corazón de Tlaquepaque, de estilo barroco y neoclásico.",
    imagen: "https://upload.wikimedia.org/wikipedia/commons/thumb/c/c0/Parroquia_de_San_Pedro_Ap%C3%B3stol%2C_Tlaquepaque%2C_Jalisco.JPG/1024px-Parroquia_de_San_Pedro_Ap%C3%B3stol%2C_Tlaquepaque%2C_Jalisco.JPG",
    audio: "audio/sanpedro_tlaq.mp3",
    coordenadas: [20.6414, -103.3095] // Tlaquepaque
  },
  {
    id: 5,
    nombre: "Santuario de los Mártires (Tlajomulco)",
    descripcion: "Monumento religioso moderno de gran escala en el Cerro del Tesoro.",
    // Nota: Tlajomulco es grande, esta es una ubicación general de un punto de interés.
    // Se necesitarían coordenadas más precisas para sitios específicos de Tlajomulco.
    // Usaremos una imagen genérica para Tlajomulco por ahora.
    imagen: "https://upload.wikimedia.org/wikipedia/commons/thumb/8/82/Santuario_de_los_M%C3%A1rtires_Mexicanos_Gdl.JPG/1024px-Santuario_de_los_M%C3%A1rtires_Mexicanos_Gdl.JPG",
    audio: "audio/martires_tlajo.mp3",
    coordenadas: [20.5900, -103.4000] // Coordenadas aproximadas para Tlajomulco (Santuario)
  }
];

// Componente para simular iconos (puedes reemplazar con SVGs reales o una librería)
const Icon = ({ name }) => {
  // En una app real, usarías <svg> o <img>
  return <span style={{ fontSize: '20px', marginBottom: '4px' }}>{name.substring(0,2).toUpperCase()}</span>;
};


function App() {
  const [currentView, setCurrentView] = useState('welcome'); // welcome, home, map, routes, profile
  const [showProximityAlert, setShowProximityAlert] = useState(false);
  const [alertMessage, setAlertMessage] = useState('');

  // Simulación de detección de proximidad
  useEffect(() => {
    if (currentView === 'home' || currentView === 'map') {
      const timer = setTimeout(() => {
        // Simular estar cerca de un sitio aleatorio después de un tiempo
        const randomSite = sitesData[Math.floor(Math.random() * sitesData.length)];
        setAlertMessage(`¡Estás cerca de ${randomSite.nombre}!`);
        setShowProximityAlert(true);
        setTimeout(() => setShowProximityAlert(false), 4000); // Ocultar después de 4 segundos
      }, 8000); // Mostrar alerta después de 8 segundos en Home o Mapa

      return () => clearTimeout(timer);
    }
  }, [currentView]);

  const navigateTo = (view) => {
    setCurrentView(view);
  };

  const handleExplore = () => {
    navigateTo('home');
  };

  const renderView = () => {
    switch (currentView) {
      case 'welcome':
        return <WelcomeScreen onExplore={handleExplore} backgroundImage="https://images.unsplash.com/photo-1581428982951-94e344157900?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1932&q=80" />; // URL de imagen de fondo de ejemplo
      case 'home':
        return (
          <div className="home-view">
            <h2>Sitios Culturales Destacados</h2>
            <div className="sites-grid">
              {sitesData.map(site => (
                <SitioCard
                  key={site.id}
                  nombre={site.nombre}
                  descripcion={site.descripcion}
                  imagen={site.imagen}
                  audio={site.audio}
                />
              ))}
            </div>
            <Trivia /> {/* Trivia como parte de la vista Home para demostración */}
          </div>
        );
      case 'map':
        return <Mapa sites={sitesData} />;
      case 'routes': // Placeholder
        return <div style={{padding: "20px"}}><h2>Rutas Culturales</h2><p>Próximamente...</p></div>;
      case 'profile': // Placeholder
        return <div style={{padding: "20px"}}><h2>Mi Perfil</h2><p>Próximamente...</p></div>;
      default:
        return <WelcomeScreen onExplore={handleExplore} backgroundImage="https://images.unsplash.com/photo-1581428982951-94e344157900?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1932&q=80" />;
    }
  };

  return (
    <>
      {showProximityAlert && (
        <div className={`proximity-alert ${showProximityAlert ? 'show' : ''}`}>
          {alertMessage}
        </div>
      )}
      <div className="app-content">
        {renderView()}
      </div>
      {currentView !== 'welcome' && (
        <nav className="nav-bar">
          <button
            className={`nav-button ${currentView === 'home' ? 'active' : ''}`}
            onClick={() => navigateTo('home')}
          >
            <Icon name="Inicio" /> {/* Reemplazar con SVG real */}
            Inicio
          </button>
          <button
            className={`nav-button ${currentView === 'map' ? 'active' : ''}`}
            onClick={() => navigateTo('map')}
          >
            <Icon name="Mapa" /> {/* Reemplazar con SVG real */}
            Mapa
          </button>
          <button
            className={`nav-button ${currentView === 'routes' ? 'active' : ''}`}
            onClick={() => navigateTo('routes')}
          >
            <Icon name="Rutas" /> {/* Reemplazar con SVG real */}
            Rutas
          </button>
          <button
            className={`nav-button ${currentView === 'profile' ? 'active' : ''}`}
            onClick={() => navigateTo('profile')}
          >
            <Icon name="Perfil" /> {/* Reemplazar con SVG real */}
            Perfil
          </button>
        </nav>
      )}
    </>
  );
}

export default App;
