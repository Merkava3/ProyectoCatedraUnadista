import { 
    UrlUserInsert, 
    UrlUserAll, 
    UrlUserDelete, 
    UrlUserGetId, 
    UrlUserUpdate,
    UrlUserLogin, 
    UrlUserSesion,
    UrlContentUpdate,
    UrlContentLoad 
} from "./const.js";

/* -------------------------------------- usuario ----------------------------- */
async function crear_usuario() {
    let indentificacion = prompt("Ingrese identificacion :");
    let nombre = prompt("Ingrese nombre : ");
    let apellido = prompt("Ingrese Apellido : ");
    let genero = prompt("Ingrese genero : ");
    let correo = prompt("Ingrese el Correo : ");
    let tipo_usuario = prompt("Ingrese tipo de usuario ");
    let pws = prompt("Ingrese la contraseña : ");

    try {
        const response = await fetch(UrlUserInsert, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ indentificacion, nombre, apellido, genero, correo, tipo_usuario, pws })
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const resultText = await response.text();  // Primero obtenemos el texto de la respuesta
        console.log("Texto de respuesta:", resultText);  // Lo mostramos en la consola para depuración

        // Verificamos si la respuesta es JSON válida antes de parsear
        let result;
        try {
            result = JSON.parse(resultText);
        } catch (e) {
            console.log(e);
            console.error('Error parsing JSON:', e);
            throw new Error('Invalid JSON received from server');
        }

        console.log(result);

        if (result.success) {
            console.log('Usuario creado exitosamente:', result.message);
        } else {
            console.error('Error al crear el usuario:', result.message);
            if (result.errors) {
                console.error('Errores de validación:', result.errors);
            }
        }

        // Verifica los datos recibidos
        if (result.data) {
            console.log('Datos recibidos del servidor:', result.data);
        } else {
            console.warn('No se recibieron datos del servidor.');
        }

    } catch (error) {
        console.log(error);
        console.error('Hubo un problema con la solicitud Fetch:', error);
    }
}

async function GetAll(){    
    try {
        const response = await fetch(UrlUserAll, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        });
        const resultText = await response.text();
        console.log("Texto de respuesta:", resultText);      
    } catch (error) {
        console.error('Hubo un problema con la solicitud Fetch:', error);
    }
}

async function Delete() {
    let indentificacion = prompt("Ingrese identificacion para eliminar : ");
    try {
        const response = await fetch(UrlUserDelete, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ indentificacion })
        });

        const resultText = await response.text();
        console.log("Texto de respuesta:", resultText);

        let result;
        try {
            result = JSON.parse(resultText);
        } catch (e) {
            console.error('Error parsing JSON:', e);
            throw new Error('Invalid JSON received from server');
        }

        if (result.success) {
            console.log('Eliminación exitosa:', result.message);
        } else {
            console.log('Error en la eliminación:', result.message);
        }

    } catch (error) {
        console.error('Hubo un problema con la solicitud Fetch:', error);
    }
}

async function GetId() {
    let indentificacion = prompt("Ingrese identificacion para obtener: ");
    try {
        const response = await fetch(UrlUserGetId, {
            method: 'POST', // Cambia el método a POST
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ indentificacion })
        });

        const resultText = await response.text();
        console.log("Texto de respuesta:", resultText);

        let result;
        try {
            result = JSON.parse(resultText);
        } catch (e) {
            console.error('Error parsing JSON:', e);
            console.log("Texto de respuesta para depuración:", resultText);
            throw new Error('Invalid JSON received from server');
        }

        if (result.success) {
            console.log('Datos recibidos:', result.data);
        } else {
            console.log('Error al obtener datos:', result.message);
        }

    } catch (error) {
        console.error('Hubo un problema con la solicitud Fetch:', error);
    }
}

async function getIdUpdate(){
    let list = {};   
    let id_usuario = prompt("Ingrese el id : ");
    try {
        const response = await fetch(UrlUserGetId, {
            method: 'POST', // Cambia el método a POST
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_usuario })
        });

        const result = await response.json();       
        if (result.success) {
            list = {"successfulness": true, id_usuario: id_usuario, "collection": result.data};
        } else {
            list = {"successfulness": false, "collection" : null};            
        }
        return list;

    } catch (error) {
        console.error(error);
    }
} 

async function update() {
    const list = await getIdUpdate(); // Espera a que getIdUpdate termine y obtiene su resultado
   
    if(list.successfulness){        
        console.log("Datos obtenidos para la actualización:", list.collection);
        try {
            let id_usuario = list.id_usuario;       
            let indentificacion = prompt("Ingrese identificacion :");
            let nombre = prompt("Ingrese nombre : ");
            let apellido = prompt("Ingrese Apellido : ");
            let genero = prompt("Ingrese genero : ");
            let correo = prompt("Ingrese el Correo : ");
            let tipo_usuario = prompt("Ingrese tipo de usuario ");
            let pws = prompt("Ingrese la contraseña : ");

            const response = await fetch(UrlUserUpdate, {
                method: 'PUT', // Cambia el método a POST
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({id_usuario, indentificacion, nombre, apellido, genero, correo, tipo_usuario, pws })
            });
            const resultText = await response.text();
            console.log("Texto de respuesta:", resultText);
            
        } catch (error) {
            console.error('Hubo un problema con la solicitud Fetch:', error);
        }
    } else {
        console.log("No se encontraron datos para la actualización", list);
    }
}

async function login() {
    let correo = prompt("Ingrese el correo : ");
    let pws = prompt("Ingrese la contraseña : ");
    try {
        const response = await fetch(UrlUserLogin, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ correo, pws })
        });
        const result = await response.text();
        console.log(result);
        /*
        if (result.success) {
            console.log("Bienvenido", result);
            window.location.href = result.pages; // Redirige a la página principal o a la página correspondiente
        } else {
            console.log(result.message);
            alert('Inicio de sesión fallido. Verifique sus credenciales.');
        }
        */
    } catch (error) {
        console.error('Error en la solicitud:', error);
        alert('Ocurrió un error durante el inicio de sesión.');
    }
}

async function UserSesion() {
    try {
        const response = await fetch(UrlUserSesion, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify()
        });
        const result = await response.text();
        console.log(result);
    } catch (error) {
        console.error("error en la consulta : ", error);
    }
}
/* -------------------------------------- End usuario ----------------------------- */

async function InsertContent() {
    let contenido_sobre = {};
    let nuestro_servicio = {};
    let portafolio = {valor:"hola", acto:"mundo"};
    
    let contenido_one = prompt("ingrese contenido 1 del curso : ");
    let contenido_two = prompt("ingrese contenido 2 del curso : ");
    let contenido_three = prompt("ingrese contenido 3 del curso : ");
    let servicio_one = prompt("ingrese servicio 1 : ");
    let servicio_two = prompt("ingrese servicio 2 del cur");
    let servicio_three = prompt("ingrese servicio 3 del curso : ");
    contenido_sobre = {contenido_one, contenido_two, contenido_three}
    nuestro_servicio = {servicio_one, servicio_two, servicio_three}
    try {
        const response = await fetch(UrlContentUpdate, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({contenido_sobre, nuestro_servicio, portafolio, id_usuario_contenido: 91})
        });
        const resultText = await response.text();
        console.log("Texto de respuesta:", resultText);    
        
    } catch (error) {
        console.error("error en la consulta : ", error);
    }
}

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
                const portafolio = JSON.parse(item.portafolio);

                console.log("Contenido sobre:", contenidoSobre);
                console.log("Portafolio:", portafolio);

                // Recorrer contenido_sobre
                for (const key in contenidoSobre) {
                    if (contenidoSobre.hasOwnProperty(key)) {
                        console.log(`${key}: ${contenidoSobre[key]}`);
                    }
                }

                // Recorrer portafolio
                for (const key in portafolio) {
                    if (portafolio.hasOwnProperty(key)) {
                        console.log(`${key}: ${portafolio[key]}`);
                    }
                }
            });
        } else {
            console.error("Error en la respuesta:", result.message);
        }
    } catch (error) {
        console.error("Error en la consulta:", error);
    }
}



// ---------------------  usuario ----------------------------
//crear_usuario(); // arreglar insert
//update();
//GetAll();
//GetId();
//Delete();
//getIdUpdate();
//update();
//login();
//UserSesion();
// --------------------- end usuario ----------------------------

// --------------------------- contenido -------------------------
//InsertContent();
load();
// ---------------------------end contenido -------------------------



