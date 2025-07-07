document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const questions = document.querySelectorAll('.question');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const resultSection = document.getElementById('autoestima-result');
    const retakeBtn = document.getElementById('retakeTestBtn');
    const form = document.getElementById('autoestimaTestForm');
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
            const questionIndex = parseInt(questionId.substring(3)) - 1;
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

        calculateAutoestimaScore();
        resultSection.style.display = 'block';
        resultSection.scrollIntoView({ behavior: 'smooth' });
    }

    function calculateAutoestimaScore() {
        const totalScore = answers.reduce((sum, answer) => sum + (answer || 0), 0);

        document.getElementById('autoestima-score').textContent = totalScore;
        const resultText = document.getElementById('autoestima-interpretation');
        const recommendationList = document.getElementById('recommendation-list');

        let level, color, icon, message, recommendations;

        if (totalScore >= 25) {
            level = "Autoestima muy alta";
            color = "var(--success-color)";
            icon = "fas fa-star";
            message = "¡Excelente! Tu puntaje indica que tienes una autoestima muy saludable.";
            recommendations = [
                "Mantén tus hábitos positivos",
                "Ayuda a otros a mejorar su autoestima",
                "Sigue practicando el auto-reconocimiento",
                "Establece nuevos desafíos personales",
                "Celebra tus logros regularmente"
            ];
        } else if (totalScore >= 20) {
            level = "Autoestima alta";
            color = "var(--success-color)";
            icon = "fas fa-thumbs-up";
            message = "Tienes una buena autoestima. Podrías fortalecerla aún más en algunos aspectos.";
            recommendations = [
                "Practica la autocompasión",
                "Identifica y desafía pensamientos negativos",
                "Rodéate de personas positivas",
                "Establece metas alcanzables",
                "Reconoce tus logros diarios"
            ];
        } else if (totalScore >= 15) {
            level = "Autoestima moderada";
            color = "var(--warning-color)";
            icon = "fas fa-info-circle";
            message = "Tu autoestima es moderada. A veces dudas de ti mismo/a.";
            recommendations = [
                "Practica afirmaciones positivas",
                "Identifica tus cualidades",
                "Evita compararte con otros",
                "Participa en actividades gratificantes",
                "Considera apoyo profesional si lo necesitas"
            ];
        } else if (totalScore >= 10) {
            level = "Autoestima baja";
            color = "var(--danger-color)";
            icon = "fas fa-exclamation-triangle";
            message = "Tu autoestima es baja. Trabaja en fortalecer tu confianza y amor propio.";
            recommendations = [
                "Busca apoyo profesional",
                "Practica el autocuidado diario",
                "Cuestiona tus pensamientos negativos",
                "Rodéate de apoyo positivo",
                "Celebra tus pequeños logros"
            ];
        } else {
            level = "Autoestima muy baja";
            color = "var(--danger-color)";
            icon = "fas fa-heartbeat";
            message = "Tu autoestima es muy baja. Sería importante buscar ayuda profesional.";
            recommendations = [
                "Consulta con un psicólogo",
                "Empieza con ejercicios de autoaceptación",
                "Evita el aislamiento",
                "Practica técnicas de relajación",
                "Considera terapia cognitivo-conductual"
            ];
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
