import { UrlContentLoad } from '../const.js';


async function load() {   

    try {
        const response = await fetch(UrlContentLoad, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_usuario_contenido: 91 })
        });

        const resultText = await response.text();
        const result = JSON.parse(resultText);      

        if (result.success) {
            const data = result.data;

            // Recorrer cada elemento en data
            data.forEach(item => {
                // Parsear contenido_sobre y portafolio
                const contenidoSobre = JSON.parse(item.contenido_sobre);
                const nuestro_servicio = JSON.parse(item.nuestro_servicio);              

                // Asignar valores a los párrafos correspondientes
                document.getElementById('parrafo1').textContent = contenidoSobre.contenido_one;
                document.getElementById('parrafo2').textContent = contenidoSobre.contenido_two;
                servicies(nuestro_servicio)
            });
          
        } else {
            console.error("Error en la respuesta:", result.message);
        }
    } catch (error) {
        console.error("Error en la consulta:", error);
    }
}

async function servicies(nuestro_servicio){   
    try {
        const respuesta = await fetch('./data/services.json');        
        const datos = await respuesta.json();        
         const updatedata =  actualizarServicios(datos,nuestro_servicio)

        const contenServices = document.getElementById("services");

        updatedata.map(content =>{
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

        }).forEach(element => contenServices.appendChild(element));  
    } catch (error) {
        console.log(error)
        
    }

   
}

function actualizarServicios(services, nuestro_servicio) {    
    services[0].texto = nuestro_servicio.servicio_one;
    services[1].texto = nuestro_servicio.servicio_two;
    services[2].texto = nuestro_servicio.servicio_three;
    return services;
}

load();
