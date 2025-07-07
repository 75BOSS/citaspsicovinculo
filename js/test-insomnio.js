 document.addEventListener('DOMContentLoaded', function() {
  // Variables del test
  const questions = document.querySelectorAll('.question');
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
  const submitBtn = document.getElementById('submitBtn');
  const progressBar = document.getElementById('progressBar');
  const progressText = document.getElementById('progressText');
  const testForm = document.getElementById('insomnioTestForm');
  const resultSection = document.getElementById('insomnio-result');
  const scoreDisplay = document.getElementById('insomnio-score');
  const interpretationDisplay = document.getElementById('insomnio-interpretation');
  const recommendationList = document.getElementById('recommendation-list');
  const retakeBtn = document.getElementById('retakeTestBtn');
  const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
  const mainNav = document.querySelector('.main-nav');
  
  let currentQuestion = 0;
  const totalQuestions = questions.length;
  
  // Mostrar la primera pregunta
  showQuestion(currentQuestion);
  updateProgress();
  
  // Event listeners para botones de navegación
  nextBtn.addEventListener('click', nextQuestion);
  prevBtn.addEventListener('click', prevQuestion);
  submitBtn.addEventListener('click', showResults);
  retakeBtn.addEventListener('click', resetTest);
  if(mobileMenuBtn) {
    mobileMenuBtn.addEventListener('click', toggleMobileMenu);
  }
  
  // Event listener para opciones seleccionadas
  document.querySelectorAll('.option-box').forEach(box => {
    box.addEventListener('click', function() {
      const radioInput = this.querySelector('input[type="radio"]');
      radioInput.checked = true;
      
      // Remover selección de todas las opciones de esta pregunta
      const questionDiv = this.closest('.question');
      questionDiv.querySelectorAll('.option-box').forEach(opt => {
        opt.classList.remove('selected');
      });
      
      // Seleccionar la opción actual
      this.classList.add('selected');
    });
  });
  
  // Función para mostrar/ocultar menú en móviles
  function toggleMobileMenu() {
    mainNav.classList.toggle('active');
    mobileMenuBtn.innerHTML = mainNav.classList.contains('active') ? 
        '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
  }
  
  // Función para mostrar una pregunta específica sin desplazamiento vertical excesivo
  function showQuestion(index) {
    questions.forEach((question, i) => {
      question.classList.remove('active');
      if (i === index) {
        question.classList.add('active');
      }
    });
    
    // Actualizar estado de los botones
    prevBtn.disabled = index === 0;
    nextBtn.style.display = index === totalQuestions - 1 ? 'none' : 'inline-flex';
    submitBtn.style.display = index === totalQuestions - 1 ? 'inline-flex' : 'none';
    
    // Scroll ligero, para evitar que la página baje mucho
    // Scroll solo si pregunta no visible en viewport
    const rect = questions[index].getBoundingClientRect();
    if (rect.top < 0 || rect.top > window.innerHeight * 0.2) {
      window.scrollBy({
        top: rect.top - window.innerHeight * 0.1,
        behavior: 'smooth'
      });
    }
  }
  
  // Función para avanzar a la siguiente pregunta
  function nextQuestion() {
    // Validar que se haya respondido antes de avanzar
    const selected = questions[currentQuestion].querySelector('input[type="radio"]:checked');
    if (!selected) {
      alert('Por favor, selecciona una opción para continuar.');
      return;
    }
    
    if (currentQuestion < totalQuestions - 1) {
      currentQuestion++;
      showQuestion(currentQuestion);
      updateProgress();
    }
  }
  
  // Función para retroceder a la pregunta anterior
  function prevQuestion() {
    if (currentQuestion > 0) {
      currentQuestion--;
      showQuestion(currentQuestion);
      updateProgress();
    }
  }
  
  // Función para actualizar la barra de progreso
  function updateProgress() {
    const progress = ((currentQuestion + 1) / totalQuestions) * 100;
    progressBar.style.width = `${progress}%`;
    progressText.textContent = `${currentQuestion + 1}/${totalQuestions}`;
  }
  
  // Función para calcular y mostrar los resultados
  function showResults() {
    // Validar que la última pregunta esté respondida
    const selected = questions[currentQuestion].querySelector('input[type="radio"]:checked');
    if (!selected) {
      alert('Por favor, selecciona una opción para continuar.');
      return;
    }

    // Calcular puntaje total
    let totalScore = 0;
    for (let i = 1; i <= totalQuestions; i++) {
      const selectedOption = document.querySelector(`input[name="iq${i}"]:checked`);
      if (selectedOption) {
        totalScore += parseInt(selectedOption.value);
      }
    }
    
    // Mostrar sección de resultados
    testForm.style.display = 'none';
    resultSection.style.display = 'block';
    scoreDisplay.textContent = totalScore;
    
    // Determinar interpretación según el puntaje
    let interpretation = '';
    let recommendations = [];
    
    if (totalScore >= 22) {
      interpretation = `
        <div class="result-level">
          <i class="fas fa-exclamation-triangle" style="color: var(--danger-color);"></i>
          <span>Insomnio clínicamente significativo (grave)</span>
        </div>
        <p>Tus respuestas indican un problema de insomnio severo que probablemente está afectando significativamente tu calidad de vida. Se recomienda consultar con un especialista en sueño para una evaluación completa y tratamiento adecuado.</p>
      `;
      
      recommendations = [
        "Consulta con un especialista en trastornos del sueño lo antes posible.",
        "Considera llevar un diario de sueño para registrar tus patrones.",
        "Evita el consumo de cafeína y alcohol, especialmente en horas de la tarde/noche.",
        "Establece una rutina regular para acostarte y levantarte.",
        "Crea un ambiente óptimo para dormir (oscuridad, temperatura adecuada, sin dispositivos electrónicos)."
      ];
    } else if (totalScore >= 15) {
      interpretation = `
        <div class="result-level">
          <i class="fas fa-exclamation-circle" style="color: var(--warning-color);"></i>
          <span>Insomnio moderado</span>
        </div>
        <p>Experimentas dificultades considerables con el sueño que pueden estar afectando tu funcionamiento diario. Sería beneficioso buscar consejo profesional para mejorar tu calidad de sueño y prevenir que el problema empeore.</p>
      `;
      
      recommendations = [
        "Considera programar una consulta con un profesional de la salud.",
        "Practica técnicas de relajación antes de acostarte.",
        "Limita el tiempo en la cama solo para dormir (evita trabajar o ver TV en la cama).",
        "Evita siestas largas durante el día.",
        "Establece un ritual relajante antes de dormir (lectura, música suave, etc.)."
      ];
    } else if (totalScore >= 8) {
      interpretation = `
        <div class="result-level">
          <i class="fas fa-info-circle" style="color: var(--secondary-color);"></i>
          <span>Insomnio subclínico (leve)</span>
        </div>
        <p>Tienes algunas dificultades con el sueño que podrían mejorar con cambios en la higiene del sueño. Monitorea tus síntomas y considera implementar mejores hábitos de sueño para prevenir que el problema progrese.</p>
      `;
      
      recommendations = [
        "Mejora tus hábitos de higiene del sueño.",
        "Mantén un horario regular para dormir, incluso los fines de semana.",
        "Evita comidas pesadas y ejercicio intenso antes de acostarte.",
        "Limita la exposición a pantallas al menos 1 hora antes de dormir.",
        "Considera técnicas de manejo del estrés si la ansiedad afecta tu sueño."
      ];
    } else {
      interpretation = `
        <div class="result-level">
          <i class="fas fa-check-circle" style="color: var(--success-color);"></i>
          <span>Sin insomnio clínicamente significativo</span>
        </div>
        <p>Tus respuestas no indican problemas significativos de insomnio. Sigue manteniendo buenos hábitos de sueño para preservar tu salud y bienestar.</p>
      `;
      
      recommendations = [
        "Mantén tus buenos hábitos de sueño actuales.",
        "Continúa con una rutina regular de sueño.",
        "Practica actividades relajantes antes de dormir.",
        "Mantén tu ambiente de sueño cómodo y libre de distracciones.",
        "Monitorea ocasionalmente tu calidad de sueño."
      ];
    }
    
    interpretationDisplay.innerHTML = interpretation;
    
    // Mostrar recomendaciones
    recommendationList.innerHTML = '';
    recommendations.forEach(recommendation => {
      const li = document.createElement('li');
      li.textContent = recommendation;
      recommendationList.appendChild(li);
    });
    
    // Desplazarse a la sección de resultados
    resultSection.scrollIntoView({ behavior: 'smooth' });
  }
  
  // Función para reiniciar el test
  function resetTest() {
    currentQuestion = 0;
    
    // Limpiar selecciones
    document.querySelectorAll('input[type="radio"]').forEach(input => {
      input.checked = false;
    });
    
    document.querySelectorAll('.option-box').forEach(box => {
      box.classList.remove('selected');
    });
    
    // Mostrar el formulario y ocultar resultados
    testForm.style.display = 'block';
    resultSection.style.display = 'none';
    
    showQuestion(currentQuestion);
    updateProgress();
    
    // Scroll al inicio del test
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
  
  // Prevenir envío real del formulario
  testForm.addEventListener('submit', function(e) {
    e.preventDefault();
    showResults();
  });
});
