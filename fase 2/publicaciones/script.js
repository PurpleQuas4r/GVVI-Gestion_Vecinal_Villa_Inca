// Menú Lateral - Alternar Submenús
const sidebarMenuItems = document.querySelectorAll('#sidebar ul li a.menu-item');

sidebarMenuItems.forEach(item => {
    item.addEventListener('click', function(event) {
        const parent = this.parentElement;

        // Solo prevenir el comportamiento si el enlace tiene un submenú
        if (parent.querySelector('ul')) {
            event.preventDefault();
        }

        // Cerrar todos los otros menús abiertos
        document.querySelectorAll('#sidebar ul li').forEach(li => {
            if (li !== parent) {
                li.classList.remove('active');
            }
        });

        // Alternar el submenú actual
        parent.classList.toggle('active');
    });
});

// Modal para Editar Usuario
const editUserModal = document.getElementById("editUserModal");
const closeBtn = document.querySelector(".close-btn");
const cancelBtn = document.querySelector(".cancel-btn");
const editConfirmBtn = document.getElementById("editConfirmBtn");

// Abre el modal de edición y carga datos del usuario
document.querySelectorAll(".edit-btn").forEach(button => {
    button.addEventListener("click", () => {
        // Obtener datos del usuario desde los atributos data
        const userId = button.getAttribute("data-id");
        const nombre = button.getAttribute("data-nombre");
        const direccion = button.getAttribute("data-direccion");
        const telefono = button.getAttribute("data-telefono");
        const correo = button.getAttribute("data-correo");
        const rol = button.getAttribute("data-rol");

        // Asignar datos a los valores del formulario en el modal
        document.getElementById("editUserId").value = userId;
        document.getElementById("editName").value = nombre;
        document.getElementById("editAddress").value = direccion;
        document.getElementById("editPhone").value = telefono;
        document.getElementById("editEmail").value = correo;
        document.getElementById("editRole").value = rol;

        // Mostrar el modal
        editUserModal.style.display = "flex";
    });
});

// Evento para confirmar edición
editConfirmBtn.addEventListener("click", () => {
    // Obtener los datos del formulario
    const user_id = document.getElementById("editUserId").value;
    const nombre = document.getElementById("editName").value;
    const direccion = document.getElementById("editAddress").value;
    const telefono = document.getElementById("editPhone").value;
    const correo = document.getElementById("editEmail").value;
    const rol = document.getElementById("editRole").value;

    // Realizar una petición AJAX para actualizar los datos
    fetch("update_user.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
            user_id: user_id,
            nombre: nombre,
            direccion: direccion,
            telefono: telefono,
            correo: correo,
            rol: rol,
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert(data.message); // Muestra un mensaje de éxito
            location.reload();   // Recarga la página para ver los cambios
        } else {
            alert(data.message); // Muestra el mensaje de error
        }
    })
    .catch(error => console.error("Error:", error));
});

// Cierra el modal de edición al hacer clic en cerrar o cancelar
closeBtn.onclick = cancelBtn.onclick = function() {
    editUserModal.style.display = "none";
};

// Modal para Eliminar Usuario
const deleteUserModal = document.getElementById("deleteUserModal");
const deleteConfirmBtn = document.querySelector(".delete-confirm-btn");
const deleteConfirmInput = document.getElementById("deleteConfirmInput");
const deleteUserName = document.getElementById("deleteUserName");
const deleteUserAddress = document.getElementById("deleteUserAddress");

// Variable para almacenar el ID del usuario a eliminar
let userIdToDelete = null;

// Abre el modal de eliminación al hacer clic en el botón de eliminar
document.querySelectorAll(".delete-btn").forEach(button => {
    button.addEventListener("click", () => {
        // Obtener los datos del usuario desde los atributos data
        const nombre = button.getAttribute("data-nombre");
        const direccion = button.getAttribute("data-direccion");
        userIdToDelete = button.getAttribute("data-id"); // Captura el ID del usuario

        console.log("ID del usuario capturado para eliminar:", userIdToDelete);  // Verifica si se captura el ID correctamente
        console.log("Nombre del usuario capturado:", nombre);  // Verifica el nombre del usuario
        console.log("Dirección del usuario capturada:", direccion);  // Verifica la dirección del usuario

        // Mostrar los datos en el modal
        if (deleteUserName && deleteUserAddress) {
            deleteUserName.textContent = nombre;
            deleteUserAddress.textContent = direccion;
        }

        // Limpiar el campo de entrada y deshabilitar el botón de confirmación
        if (deleteConfirmInput && deleteConfirmBtn) {
            deleteConfirmInput.value = "";
            deleteConfirmBtn.disabled = true;
        }

        // Mostrar el modal
        deleteUserModal.style.display = "flex";
    });
});


// Habilita el botón de confirmación solo si se escribe "BORRAR" en mayúsculas
if (deleteConfirmInput) {
    deleteConfirmInput.addEventListener("input", () => {
        console.log("Valor del campo:", deleteConfirmInput.value);  // Verifica el valor del input
        if (deleteConfirmBtn) {  // Verifica que el botón exista
            deleteConfirmBtn.disabled = deleteConfirmInput.value !== "BORRAR";
            if (!deleteConfirmBtn.disabled) {
                console.log("Botón Confirmar habilitado");  // Confirma que el botón se habilita
            }
        }
    });
}

// Confirmar y enviar solicitud para eliminar usuario
if (deleteConfirmBtn) {
    deleteConfirmBtn.addEventListener("click", () => {
        console.log("Botón de Confirmar presionado");  // Verifica si esto aparece en la consola
        console.log("ID de usuario a eliminar:", userIdToDelete);  // Verifica si el ID es correcto
        if (userIdToDelete) {
            fetch("delete_user.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams({ user_id: userIdToDelete })
            })
            .then(response => response.json())
            .then(data => {
                console.log("Respuesta del servidor:", data);  // Verifica la respuesta del servidor
                if (data.status === "success") {
                    alert(data.message);  // Muestra un mensaje de éxito
                    location.reload();    // Recarga la página para actualizar los datos
                } else {
                    alert(data.message);  // Muestra el mensaje de error
                }
            })
            .catch(error => console.error("Error:", error));
        }
    });
}

// Cierra el modal de eliminación al hacer clic en "Cancelar"
const cancelBtnDelete = document.querySelector(".cancel-btn-delete");
if (cancelBtnDelete) {
    cancelBtnDelete.onclick = () => {
        deleteUserModal.style.display = "none";
        userIdToDelete = null; // Reinicia la variable para evitar valores residuales
    };
}


// Cierra los modales al hacer clic fuera de ellos
window.onclick = function(event) {
    if (event.target == deleteUserModal) {
        deleteUserModal.style.display = "none";
    }
};


