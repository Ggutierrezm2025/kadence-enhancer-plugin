import React from 'react';

const SitioCard = ({ nombre, descripcion, imagen, audio }) => {
  const handlePlayAudio = () => {
    // En una implementación real, aquí se manejaría la reproducción del audio.
    // Por ejemplo, usando el API de Audio de HTML5:
    // const audioElement = new Audio(audio);
    // audioElement.play();
    alert(`Reproduciendo audio para: ${nombre} (simulado)`);
  };

  return (
    <div className="site-card">
      <img src={imagen} alt={`Imagen de ${nombre}`} />
      <div className="site-card-content">
        <h3>{nombre}</h3>
        <p>{descripcion}</p>
        <div className="site-card-controls">
          {audio && (
            <button className="audio-button" onClick={handlePlayAudio}>
              <span role="img" aria-label="Play audio">🎧</span> Escuchar Audio
            </button>
          )}
          {/* Podrían ir más controles aquí, como "Ver en mapa" o "Más info" */}
        </div>
      </div>
    </div>
  );
};

export default SitioCard;
