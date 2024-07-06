// Definimos la función para cargar y mostrar las imágenes
export async function cargarYMostrarImagenes() {
    try {
        // Fetch API para obtener el archivo JSON que contiene los datos de las imágenes
        const respuesta = await fetch('./data/img.json');
        
        // Parseamos la respuesta como JSON para obtener los datos de las imágenes
        const datos = await respuesta.json();
        
        // Seleccionamos el contenedor donde se mostrarán las imágenes
        const contenedorImagenes = document.getElementById('fotos');

        // Iteramos sobre los datos de las imágenes
        datos.forEach(({ url, nombre, icon, texto }) => {
            // Creamos el contenedor principal de la imagen
            const imagenPort = crearElemento('div', 'imagen-port');
            // Creamos la etiqueta <img> para la imagen principal
            const img = crearElemento('img');
            // Creamos el contenedor para el efecto hover
            const hoverGaleria = crearElemento('div', 'hover-galeria');
            // Creamos la etiqueta <img> para el icono del efecto hover
            const imgIcon = crearElemento('img');
            // Creamos la etiqueta <p> para su mensaje
            const TitleP = crearElemento('p');


            // Establecemos la fuente y el texto alternativo de la imagen principal
            img.src = url;
            img.alt = nombre;
            // Establecemos la fuente del icono del efecto hover
            imgIcon.src = icon;
            TitleP.textContent = texto;

            // Añadimos el icono del efecto hover al contenedor del efecto hover
            hoverGaleria.appendChild(imgIcon);
            hoverGaleria.appendChild(TitleP);
            // Añadimos la imagen principal al contenedor principal de la imagen
            imagenPort.appendChild(img);
            // Añadimos el contenedor del efecto hover al contenedor principal de la imagen
            imagenPort.appendChild(hoverGaleria);
            // Añadimos el contenedor principal de la imagen al contenedor de imágenes en el DOM
            contenedorImagenes.appendChild(imagenPort);
        });

    } catch (error) {
        // En caso de error al cargar o parsear el archivo JSON, mostramos un mensaje de error en la consola
        console.error('Error al cargar el archivo JSON:', error);
    }
}

// Función auxiliar para crear elementos HTML con la etiqueta y la clase especificadas
function crearElemento(tagName, className) {
    // Creamos el elemento HTML con la etiqueta especificada
    const elemento = document.createElement(tagName);
    // Si se especifica una clase, la añadimos al elemento
    if (className) {
        elemento.classList.add(className);
    }
    // Devolvemos el elemento creado
    return elemento;
}

// Añadimos un evento que se ejecutará cuando el DOM esté completamente cargado,
// en este caso, llamamos a la función cargarYMostrarImagenes para cargar y mostrar las imágenes
document.addEventListener('DOMContentLoaded', cargarYMostrarImagenes);
