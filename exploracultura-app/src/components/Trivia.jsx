import React, { useState, useEffect } from 'react';

const triviaData = [
  {
    question: "¿Cuál de estos edificios es famoso por los murales de José Clemente Orozco?",
    options: ["Catedral de Guadalajara", "Instituto Cultural Cabañas", "Teatro Degollado", "Rotonda de los Jaliscienses Ilustres"],
    answer: "Instituto Cultural Cabañas",
    feedback: "El Instituto Cultural Cabañas alberga una impresionante colección de murales de Orozco, declarados Patrimonio de la Humanidad."
  },
  {
    question: "¿En qué municipio se encuentra el Parián, conocido por su ambiente festivo y mariachi?",
    options: ["Guadalajara", "Zapopan", "Tlajomulco", "Tlaquepaque"],
    answer: "Tlaquepaque",
    feedback: "El Parián de Tlaquepaque es un lugar emblemático para disfrutar de la música de mariachi y la gastronomía local."
  },
  {
    question: "¿Qué bebida espirituosa es originaria de Jalisco y se produce a partir del agave azul?",
    options: ["Mezcal", "Raicilla", "Tequila", "Pulque"],
    answer: "Tequila",
    feedback: "El Tequila es la bebida más famosa de México, con denominación de origen en Jalisco y otras regiones específicas."
  }
];

const Trivia = () => {
  const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
  const [selectedOption, setSelectedOption] = useState(null);
  const [isAnswered, setIsAnswered] = useState(false);
  const [showFeedback, setShowFeedback] = useState(false);

  const currentQuestion = triviaData[currentQuestionIndex];

  const handleOptionClick = (option) => {
    if (isAnswered) return; // No permitir cambiar la respuesta una vez seleccionada

    setSelectedOption(option);
    setIsAnswered(true);
    setShowFeedback(true);

    // Opcional: pasar a la siguiente pregunta después de un tiempo
    // setTimeout(() => {
    //   setShowFeedback(false);
    //   setIsAnswered(false);
    //   setSelectedOption(null);
    //   setCurrentQuestionIndex((prevIndex) => (prevIndex + 1) % triviaData.length);
    // }, 3000); // 3 segundos para leer el feedback
  };

  const getButtonClass = (option) => {
    if (!isAnswered) return "";
    if (option === currentQuestion.answer) return "correct";
    if (option === selectedOption && option !== currentQuestion.answer) return "incorrect";
    return "";
  };

  const nextQuestion = () => {
    setShowFeedback(false);
    setIsAnswered(false);
    setSelectedOption(null);
    setCurrentQuestionIndex((prevIndex) => (prevIndex + 1) % triviaData.length);
  }

  return (
    <div className="trivia-component">
      <h2>Trivia Cultural Jalisciense</h2>
      <p>{currentQuestion.question}</p>
      <div className="trivia-options">
        {currentQuestion.options.map((option, index) => (
          <button
            key={index}
            onClick={() => handleOptionClick(option)}
            className={getButtonClass(option)}
            disabled={isAnswered}
          >
            {option}
          </button>
        ))}
      </div>
      {showFeedback && (
        <div className="trivia-feedback">
          <p>
            {selectedOption === currentQuestion.answer ? "¡Correcto!" : "Incorrecto."}
          </p>
          <p>{currentQuestion.feedback}</p>
          <button onClick={nextQuestion} style={{marginTop: "10px", backgroundColor: "#8c5a3b"}}>
            Siguiente Pregunta
          </button>
        </div>
      )}
    </div>
  );
};

export default Trivia;
