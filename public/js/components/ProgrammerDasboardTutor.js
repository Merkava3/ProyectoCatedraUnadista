import { UrlCount } from "../const.js";

async function getcount() {
    try {
        const response = await fetch(UrlCount, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });
        const data = await response.json();
        console.log(data);

        //http://localhost/ProyectoCatedraUnadista/public/pages/SibeBarMenu.html

        // Extrae el valor de total_usuarios del objeto data
        if (data.success && Array.isArray(data.data) && data.data.length > 0) {
            const total_estudiantes = data.data[0].total_estudiantes;

            // Actualiza el contenido del elemento con id "count"
            const countElement = document.getElementById("count");
            if (countElement) {
                countElement.textContent = total_estudiantes;
            }
        }
    } catch (error) {
        console.log(error);
    }
}

// Llama a la función getcount cuando el documento esté completamente cargado
document.addEventListener('DOMContentLoaded', getcount);

