document.addEventListener('DOMContentLoaded', () => {
    const specialistTypeSelect = document.getElementById('specialist-type');
    const specialistsList = document.getElementById('specialists-list');
    const specialists = specialistsList.getElementsByClassName('specialist');

    function displaySpecialists(type) {
        for (let specialist of specialists) {
            if (specialist.classList.contains(type)) {
                specialist.classList.add('active');
            } else {
                specialist.classList.remove('active');
            }
        }
    }

    specialistTypeSelect.addEventListener('change', () => {
        const selectedType = specialistTypeSelect.value;
        displaySpecialists(selectedType);
    });

    // Mostrar por defecto los especialistas generales
    displaySpecialists('general');
});

document.getElementById('registroForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita el envío del formulario hasta que la validación sea correcta

    const cedulaInput = document.getElementById('cedula').value;
    const errorMessage = document.getElementById('error-message');

    // Expresión regular para validar el formato de la cédula (por ejemplo, 8-990-145)
    const cedulaRegex = /^\d-\d{3}-\d{3}$/;

    if (!cedulaRegex.test(cedulaInput)) {
        errorMessage.style.display = 'block';
    } else {
        errorMessage.style.display = 'none';
        // Aquí puedes agregar la lógica para enviar el formulario si la validación es correcta
        alert('Cédula ingresada correctamente');
        // Si quieres enviar el formulario después de la validación exitosa, puedes descomentar la siguiente línea:
        // event.target.submit();
    }
});



