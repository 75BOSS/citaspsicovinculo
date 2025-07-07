document.addEventListener("DOMContentLoaded", function() {
    // Variables
    const formulario_login = document.querySelector(".formulario__login");
    const formulario_register = document.querySelector(".formulario__register");
    const contenedor_login_register = document.querySelector(".contenedor__login-register");
    const caja_trasera_login = document.querySelector(".caja__trasera-login");
    const caja_trasera_register = document.querySelector(".caja__trasera-register");
    const contenedor_todo = document.querySelector(".contenedor__todo");

    // Función para alternar formularios
    function toggleForm(showLogin) {
        const isDesktop = window.innerWidth > 850;
        const isRegisterActive = contenedor_todo.classList.contains('active-register');
        
        if (isDesktop) {
            // Desktop
            contenedor_login_register.style.left = showLogin ? "0px" : "calc(100% - 400px)";
            caja_trasera_login.style.opacity = showLogin ? "0" : "1";
            caja_trasera_register.style.opacity = showLogin ? "1" : "0";
            caja_trasera_login.style.display = caja_trasera_register.style.display = "block";
        } else {
            // Mobile
            contenedor_login_register.style.left = "0px";
            caja_trasera_login.style.display = showLogin ? "none" : "block";
            caja_trasera_register.style.display = showLogin ? "block" : "none";
        }
        
        // Alternar formularios
        const activeForm = showLogin ? formulario_login : formulario_register;
        const inactiveForm = showLogin ? formulario_register : formulario_login;
        
        activeForm.style = "display: block; opacity: 1; visibility: visible; pointer-events: auto;";
        inactiveForm.style = "display: none; opacity: 0; visibility: hidden; pointer-events: none;";
        
        // Manejar clase
        contenedor_todo.classList.toggle('active-register', !showLogin);
    }

    // Funciones principales
    const iniciarSesion = () => toggleForm(true);
    const register = () => toggleForm(false);
    
    // Función para ajustar según ancho de pantalla
    function anchoPage() {
        const isRegisterActive = contenedor_todo.classList.contains('active-register');
        toggleForm(!isRegisterActive);
    }

    // Event listeners
    document.getElementById("btn__iniciar-sesion").addEventListener("click", iniciarSesion);
    document.getElementById("btn__registrarse").addEventListener("click", register);
    
    // Estado inicial
    anchoPage();
});