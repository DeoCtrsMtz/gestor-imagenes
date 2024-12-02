// Mostrar un mensaje de confirmación antes de registrar un administrador
document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.querySelector('form[action][method="POST"]');
    const roleSelect = document.querySelector('select[name="role"]');

    if (registerForm && roleSelect) {
        registerForm.addEventListener('submit', (e) => {
            const selectedRole = roleSelect.value;
            if (selectedRole === 'admin') {
                const confirmAdmin = confirm("¿Estás seguro de que quieres registrar un administrador?");
                if (!confirmAdmin) {
                    e.preventDefault(); // Cancela el envío del formulario si el usuario cancela
                }
            }
        });
    }
});
