document.getElementById("togglePassword").addEventListener("click", function() {
    var passwordField = document.getElementById('passwordField');
    var isPasswordVisible = passwordField.type === "text";
    
    if (isPasswordVisible) {
        passwordField.type = "password"; // Ocultar la contraseña
    } else {
        passwordField.type = "text"; // Mostrar la contraseña
    }
    
    // Opcional: Cambiar el icono del ojo según el estado
    // this.src = isPasswordVisible ? 'path_to_closed_eye_icon.png' : 'path_to_open_eye_icon.png';
});
