document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const questions = document.querySelectorAll('.question');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const resultSection = document.getElementById('ansiedad-result');
    const retakeBtn = document.getElementById('retakeTestBtn');
    const form = document.getElementById('ansiedadTestForm');
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const mainNav = document.querySelector('.main-nav');

    // Variables de estado
    let currentQuestion = 0;
    const totalQuestions = questions.length;
    const answers = Array(totalQuestions).fill(null);

    // Inicialización
    showQuestion(currentQuestion);
    updateProgress();

    // Event Listeners
    nextBtn.addEventListener('click', goToNextQuestion);
    prevBtn.addEventListener('click', goToPrevQuestion);
    submitBtn.addEventListener('click', showResults);
    retakeBtn.addEventListener('click', retakeTest);
    mobileMenuBtn.addEventListener('click', toggleMobileMenu);
    form.addEventListener('change', handleOptionSelection);

    // Funciones
    function showQuestion(index) {
        if (index < 0 || index >= totalQuestions) return;

        questions.forEach((question, i) => {
            question.classList.toggle('active', i === index);
        });

        prevBtn.disabled = index === 0;
        const isLastQuestion = index === totalQuestions - 1;
        nextBtn.style.display = isLastQuestion ? 'none' : 'inline-block';
        submitBtn.style.display = isLastQuestion ? 'inline-block' : 'none';

        nextBtn.disabled = answers[index] === null;

        highlightSelectedOption(index);
    }

    function goToNextQuestion() {
        if (currentQuestion < totalQuestions - 1) {
            currentQuestion++;
            showQuestion(currentQuestion);
            updateProgress();
        }
    }

    function goToPrevQuestion() {
        if (currentQuestion > 0) {
            currentQuestion--;
            showQuestion(currentQuestion);
            updateProgress();
        }
    }

    function updateProgress() {
        const progress = ((currentQuestion + 1) / totalQuestions) * 100;
        progressBar.style.width = `${progress}%`;
        progressText.textContent = `${currentQuestion + 1}/${totalQuestions}`;
    }

    function handleOptionSelection(e) {
        if (e.target.matches('input[type="radio"]')) {
            const questionId = e.target.name;
            const questionIndex = parseInt(questionId.substring(1)) - 1;
            const value = parseInt(e.target.value);

            answers[questionIndex] = value;

            if (questionIndex === currentQuestion) {
                nextBtn.disabled = false;
            }

            highlightSelectedOption(questionIndex);
        }
    }

    function highlightSelectedOption(questionIndex) {
        const question = questions[questionIndex];
        const selectedValue = answers[questionIndex];

        question.querySelectorAll('.option-item').forEach(item => {
            item.classList.remove('selected');
        });

        if (selectedValue !== null) {
            const selectedRadio = question.querySelector(`input[value="${selectedValue}"]`);
            if (selectedRadio) {
                selectedRadio.closest('.option-item').classList.add('selected');
            }
        }
    }

    function showResults() {
        if (answers.some(answer => answer === null)) {
            alert('Por favor responde todas las preguntas antes de ver los resultados.');
            return;
        }

        calculateAnsiedadScore();
        resultSection.style.display = 'block';
        resultSection.scrollIntoView({ behavior: 'smooth' });
    }

    function calculateAnsiedadScore() {
        const totalScore = answers.reduce((sum, answer) => sum + (answer || 0), 0);

        document.getElementById('ansiedad-score').textContent = totalScore;
        const resultText = document.getElementById('ansiedad-interpretation');
        const recommendationList = document.getElementById('recommendation-list');

        let level, color, icon, message, recommendations;

        if (totalScore <= 4) {
            level = "Ansiedad mínima o ausente";
            color = "var(--success-color)";
            icon = "fas fa-smile";
            message = "Tus resultados sugieren que no presentas síntomas significativos de ansiedad. Sin embargo, si tienes alguna preocupación, no dudes en consultar con un profesional.";
            recommendations = [
                "Mantén hábitos de vida saludables",
                "Practica técnicas de relajación regularmente",
                "Mantén una rutina de sueño consistente",
                "Realiza actividad física moderada",
                "Limita el consumo de cafeína"
            ];
        } else if (totalScore <= 9) {
            level = "Ansiedad leve";
            color = "var(--warning-color)";
            icon = "fas fa-meh";
            message = "Tus resultados sugieren que podrías estar experimentando síntomas leves de ansiedad. Es recomendable monitorear estos síntomas y considerar técnicas de manejo del estrés.";
            recommendations = [
                "Practica respiración profunda diariamente",
                "Establece tiempos específicos para preocupaciones",
                "Reduce el consumo de estimulantes",
                "Prueba técnicas de mindfulness",
                "Considera hablar con un profesional si persiste"
            ];
        } else if (totalScore <= 14) {
            level = "Ansiedad moderada";
            color = "var(--warning-color)";
            icon = "fas fa-frown";
            message = "Tus resultados sugieren que podrías estar experimentando síntomas moderados de ansiedad. Estos pueden estar afectando tu vida diaria. Sería recomendable buscar apoyo profesional.";
            recommendations = [
                "Consulta con un profesional de salud mental",
                "Practica ejercicio físico regular",
                "Establece una rutina de sueño consistente",
                "Considera terapia cognitivo-conductual",
                "Evita el aislamiento social"
            ];
        } else if (totalScore <= 19) {
            level = "Ansiedad severa";
            color = "var(--danger-color)";
            icon = "fas fa-exclamation-triangle";
            message = "Tus resultados sugieren que podrías estar experimentando síntomas severos de ansiedad. Estos probablemente están afectando significativamente tu vida diaria. Te recomendamos buscar ayuda profesional lo antes posible.";
            recommendations = [
                "Busca ayuda profesional urgentemente",
                "Habla con tu médico sobre opciones de tratamiento",
                "Informa a seres queridos sobre cómo te sientes",
                "Evita el consumo de alcohol y drogas",
                "Practica técnicas de grounding durante crisis"
            ];
        } else {
            level = "Ansiedad muy severa";
            color = "var(--danger-color)";
            icon = "fas fa-heartbeat";
            message = "Tus resultados sugieren que podrías estar experimentando síntomas muy severos de ansiedad. Por favor busca ayuda profesional inmediatamente. El tratamiento adecuado puede hacer una gran diferencia.";
            recommendations = [
                "Consulta con un profesional inmediatamente",
                "Considera opciones de terapia intensiva",
                "Crea un plan de seguridad con tu terapeuta",
                "Identifica y evita desencadenantes",
                "Sigue estrictamente el plan de tratamiento"
            ];
        }

        // Mensaje adicional para puntajes altos en preguntas clave
        if (answers[6] >= 2 || answers[7] >= 2) { // Preguntas sobre miedo intenso o palpitaciones
            message += "<p class='urgent-message' style='color: var(--danger-color); margin-top: 15px; font-weight: bold;'><i class='fas fa-exclamation-circle'></i> Tus respuestas indican síntomas físicos significativos de ansiedad. Considera una evaluación médica para descartar otras condiciones.</p>";
        }

        resultText.innerHTML = `
            <div class="result-level" style="color: ${color}">
                <i class="${icon}"></i> ${level}
            </div>
            <p>${message}</p>
        `;

        recommendationList.innerHTML = recommendations.map(rec => `<li>${rec}</li>`).join('');
    }

    function retakeTest() {
        currentQuestion = 0;
        answers.fill(null);
        form.reset();

        document.querySelectorAll('.option-item').forEach(item => {
            item.classList.remove('selected');
        });

        showQuestion(currentQuestion);
        updateProgress();
        resultSection.style.display = 'none';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function toggleMobileMenu() {
        mainNav.classList.toggle('active');
    }

    // Navegación con teclado
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowRight' && !nextBtn.disabled) {
            if (nextBtn.style.display !== 'none') {
                goToNextQuestion();
            } else if (submitBtn.style.display !== 'none') {
                showResults();
            }
        } else if (e.key === 'ArrowLeft' && !prevBtn.disabled) {
            goToPrevQuestion();
        }
    });
});