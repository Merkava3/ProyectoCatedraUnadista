import  {UrlUserInsert, 
        UrlUserAll, 
        UrlUserDelete, 
        UrlUserGetId, 
        UrlUserUpdate,
        UrlUserLogin, 
        UrlUserSesion,
        UrlContentInsert } from "./const.js";
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
        const response = await  fetch(UrlUserAll, {
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
            //console.log("Texto de respuesta para depuración:", resultText);// error
            console.error('Error parsing JSON:', e);            
            throw new Error('Invalid JSON received from server');
        }

        //console.log(result.success);
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
        } if (result.success === false) {
            console.log(result.message);         

        }else{
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
            
            list = {"successfulness":true, id_usuario:id_usuario , "collection":result.data};
            
        } else {
            list = {"successfulness":false, "collection" : null};            
        }
        return list;

        
    } catch (error) {
        console.error(error)
        
    }



} 
async function update(){
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
                body: JSON.stringify({id_usuario,indentificacion, nombre, apellido, genero, correo, tipo_usuario, pws })
            });
            const resultText = await response.text();
            console.log("Texto de respuesta:", resultText);
            
        } catch (error) {
            
        }
    }else{
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

    async function UserSesion(){
       
        try {
            const response = await fetch(UrlUserSesion,{
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify()
            });
            const result = await response.text();
            console.log(result);
        } catch (error) {
            console.error("error en la consulta : ", error)
            
        }
    }
    /* -------------------------------------- End usuario ----------------------------- */

async function InsertContent(){
    let sobre_curso = prompt("ingrese contenido del curso : ")
    try {
        const response = await  fetch(UrlContentInsert, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({sobre_curso})

        });
        const resultText = await response.text();
        console.log("Texto de respuesta:", resultText);    
        
    } catch (error) {
        console.error("error en la consulta : ", error)
        
    }

}





//crear_usuario(); // arreglar insert
//update();
//GetAll();
//GetId();
//Delete();
//getIdUpdate();
//update();
//login();
//UserSesion();
InsertContent();




