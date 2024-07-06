// Función asincrónica para cargar los servicios desde el archivo JSON
// Se utiliza la función fetch() de JavaScript, que permite realizar solicitudes HTTP
export async function loadServices(){
    try {
        // Obtener la respuesta del archivo JSON mediante fetch
        const respuesta = await fetch('./data/services.json');
        // Convertir la respuesta a formato JSON
        const datos = await respuesta.json();
        // Obtener el contenedor donde se mostrarán los servicios
        const contenServices = document.getElementById("services");

        // Mapear los datos para crear los elementos HTML de cada servicio
        datos.map(content => {
            // Desestructurar el objeto 'content' para obtener sus propiedades
            const { url, name, texto } = content;
             // Crear un elemento div para cada servicio
            const content_servicio_ind = document.createElement("div");
            // Agregar la clase "servicio-ind" al div del servicio
            content_servicio_ind.classList.add("servicio-ind");
            // Insertar HTML dentro del div del servicio con las propiedades del servicio
            content_servicio_ind.innerHTML = `
                <img src="${url}">
                <h3>${name}</h3>
                <p>${texto}</p>
            `;
            // Retornar el elemento div del servicio creado
            return content_servicio_ind;
             // Por cada servicio creado, añadirlo al contenedor de servicios
        }).forEach(element => contenServices.appendChild(element));
        
    } catch (error) {
        // Manejar errores en caso de fallo al cargar el archivo JSON
        console.error('Error al cargar el archivo JSON:', error);       
    }
}
// Esperar a que se cargue el DOM para ejecutar la función loadServices
document.addEventListener('DOMContentLoaded', loadServices);