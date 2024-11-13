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
