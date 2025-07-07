import React from 'react';

const WelcomeScreen = ({ onExplore, backgroundImage }) => {
  const style = {
    backgroundImage: `linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url(${backgroundImage})`,
  };

  return (
    <div className="welcome-screen" style={style}>
      <h1>ExploraCultura Jalisco</h1>
      <p style={{fontSize: "1.2rem", margin: "0 20px 30px", maxWidth: "600px", textShadow: "1px 1px 2px rgba(0,0,0,0.7)"}}>
        Descubre la riqueza cultural e histórica de Jalisco a través de una experiencia interactiva.
      </p>
      <button className="explore-button" onClick={onExplore}>
        Explorar Sitios
      </button>
    </div>
  );
};

export default WelcomeScreen;
