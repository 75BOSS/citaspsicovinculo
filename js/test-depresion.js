document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const questions = document.querySelectorAll('.question');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const resultSection = document.getElementById('depresion-result');
    const retakeBtn = document.getElementById('retakeTestBtn');
    const form = document.getElementById('depresionTestForm');
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

        calculateDepresionScore();
        resultSection.style.display = 'block';
        resultSection.scrollIntoView({ behavior: 'smooth' });
    }

    function calculateDepresionScore() {
        const totalScore = answers.reduce((sum, answer) => sum + (answer || 0), 0);

        document.getElementById('depresion-score').textContent = totalScore;
        const resultText = document.getElementById('depresion-interpretation');
        const recommendationList = document.getElementById('recommendation-list');

        let level, color, icon, message, recommendations;

        if (totalScore <= 4) {
            level = "Depresión mínima o ausente";
            color = "var(--success-color)";
            icon = "fas fa-smile";
            message = "Tus resultados sugieren que no presentas síntomas significativos de depresión. Sin embargo, si tienes alguna preocupación sobre tu estado de ánimo, no dudes en consultar con un profesional.";
            recommendations = [
                "Mantén hábitos saludables de sueño y alimentación",
                "Practica actividad física regularmente",
                "Mantén conexiones sociales positivas",
                "Dedica tiempo a actividades que disfrutes",
                "Practica técnicas de manejo del estrés"
            ];
        } else if (totalScore <= 9) {
            level = "Depresión leve";
            color = "var(--warning-color)";
            icon = "fas fa-meh";
            message = "Tus resultados sugieren que podrías estar experimentando síntomas leves de depresión. Es importante estar atento a estos síntomas y considerar buscar apoyo si persisten o empeoran.";
            recommendations = [
                "Considera hablar con alguien de confianza sobre cómo te sientes",
                "Establece una rutina diaria estructurada",
                "Practica técnicas de relajación o meditación",
                "Limita el consumo de alcohol y cafeína",
                "Si los síntomas persisten, considera consultar con un profesional"
            ];
        } else if (totalScore <= 14) {
            level = "Depresión moderada";
            color = "var(--warning-color)";
            icon = "fas fa-frown";
            message = "Tus resultados sugieren que podrías estar experimentando síntomas moderados de depresión. Estos síntomas pueden estar afectando tu vida diaria. Sería recomendable buscar apoyo profesional.";
            recommendations = [
                "Busca ayuda de un profesional de salud mental",
                "Habla con tu médico de cabecera sobre cómo te sientes",
                "Evita aislarte socialmente",
                "Establece metas pequeñas y alcanzables",
                "Considera terapia cognitivo-conductual"
            ];
        } else if (totalScore <= 19) {
            level = "Depresión moderadamente grave";
            color = "var(--danger-color)";
            icon = "fas fa-exclamation-triangle";
            message = "Tus resultados sugieren que podrías estar experimentando síntomas significativos de depresión. Estos síntomas probablemente están afectando tu vida diaria. Te recomendamos buscar ayuda profesional lo antes posible.";
            recommendations = [
                "Consulta con un profesional de salud mental urgentemente",
                "Habla con tu médico sobre opciones de tratamiento",
                "No intentes manejar esto solo/a - busca apoyo",
                "Considera informar a seres queridos sobre cómo te sientes",
                "Elimina el acceso a medios de autolesión si es necesario"
            ];
        } else {
            level = "Depresión grave";
            color = "var(--danger-color)";
            icon = "fas fa-heartbeat";
            message = "Tus resultados sugieren que podrías estar experimentando síntomas graves de depresión. Estos síntomas están afectando significativamente tu vida. Por favor busca ayuda profesional inmediatamente. No estás solo/a y hay ayuda disponible.";
            recommendations = [
                "Busca ayuda profesional inmediatamente",
                "Contacta a un servicio de emergencias si tienes pensamientos suicidas",
                "Informa a alguien de confianza sobre cómo te sientes",
                "Considera hospitalización si el riesgo es alto",
                "Sigue estrictamente el plan de tratamiento profesional"
            ];
        }

        // Mensaje adicional para puntajes altos en la pregunta 9 (pensamientos suicidas)
        if (answers[8] >= 2) { // Si respondió "Más de la mitad" o "Casi todos los días" en la pregunta 9
            message += "<p class='urgent-message' style='color: var(--danger-color); margin-top: 15px; font-weight: bold;'><i class='fas fa-exclamation-circle'></i> Tus respuestas indican que has tenido pensamientos sobre lastimarte o estar mejor muerto/a. Por favor busca ayuda profesional inmediatamente. No estás solo/a y hay personas que pueden ayudarte.</p>";
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