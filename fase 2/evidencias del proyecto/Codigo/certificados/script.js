const menuItems = document.querySelectorAll('#sidebar ul li a.menu-item');

menuItems.forEach(item => {
    item.addEventListener('click', function(event) {
        const parent = this.parentElement;
        // Verifica si el enlace debe redirigir (si tiene un submenú o no)
        if (!this.classList.contains('no-prevent')) {
            event.preventDefault(); // Prevenir comportamiento predeterminado de enlace solo para ciertos casos
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

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('certificateModal');
    const emitCertificateButton = document.querySelector('.action-btn');
    const closeModalButton = document.querySelector('.close-modal-btn');
    const modalContent = document.querySelector('.modal-content');

    // Mostrar el modal al hacer clic en "Emitir Certificado"
    if (emitCertificateButton) {
        emitCertificateButton.addEventListener('click', (event) => {
            console.log('Emitir Certificado clicked');
            modal.classList.add('show');
        });
    }

    // Cerrar el modal al hacer clic en el botón de cierre
    if (closeModalButton) {
        closeModalButton.addEventListener('click', () => {
            console.log('Close button clicked');
            modal.classList.remove('show');
        });
    }

    // Cerrar el modal al hacer clic fuera del contenido del modal
    modal.addEventListener('click', (event) => {
        if (event.target === modal) { // Verifica que el clic sea en el fondo del modal
            console.log('Clicked outside modal');
            modal.classList.remove('show');
        }
    });
    
});


