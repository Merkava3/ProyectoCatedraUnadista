import { 
    UrlUserInsert, 
    UrlUserAll, 
    UrlUserDelete, 
    UrlUserGetId, 
    UrlUserUpdate,
    UrlUserLogin, 
    UrlUserSesion,
    UrlContentUpdate,
    UrlContentLoad,
    UrlQuestionInsert,
    UrlQuestionAll,
    UrlQuestionDelete,
    UrlQuestionGetId,
    Urljson,
    UrlQuestionUpdate,
    UrlAnswerJson,
    UrlAnswerInsert,
    UrlChatGpt4IA,
    UrlSms,
    UrlUserLogout
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
        let datos = {
            identificacion: "123456789",
            nombre: "Juan",
            apellido: "Perez",
            genero: "Masculino",
            correo: "juanperez@gmail.com",
            tipo_usuario: "Estudiante",
            pws: "123456789"
            }
       
    
        const response = await fetch(UrlUserInsert, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ datos })
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
        //console.log("Datos obtenidos para la actualización:", list.collection);
        try {          

            const response = await fetch(UrlUserUpdate, {
                method: 'POST', 
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                     id_usuario: 2, 
                        indentificacion: "1100678901", 
                        nombre: "valentina", 
                        apellido: "silva", 
                        correo: "lennzoferrari@hotmail.com", 
                        genero: "Masculino",
                        tipo_usuario: "Estudiante",
                        pws:"Dimichev3." })
            });
            const result = await response.json();            
            if(result.success){
                console.log(result.message);
                //logout();
            }
            
        } catch (error) {
            console.error('Hubo un problema con la solicitud Fetch:', error);
        }
    } else {
        console.log("No se encontraron datos para la actualización", list);
    }
}
async function logout(){
    try {
        const response = await fetch(UrlUserLogout, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'logout' })
        });
        
        const result = await response.json();
        
        if (result.success) {
            console.log(result.message);
            // Redirigir al usuario a la página de inicio de sesión u otra página
            //window.location.href = '../pages/login.html';
            console.log("Salimos")
        } else {
            console.log('Error al cerrar sesión:', result.message);
        }
        
    } catch (error) {
        console.error('Error en la solicitud de logout:', error);
    }


}
async function login() {    
    try {
        const response = await fetch(UrlUserLogin, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ correo: "lszondas@gmail.com", pws: "Dimichev3." })
        });
        
        // Asegurarse de que la respuesta se parsea como JSON
        const result = await response.json();         
        
        if (result.success) {
            console.log("Bienvenido");
            //window.location.href = result.pages;
            console.log(result.pages);            
            update();    
        } else {
            console.log(result.message);
            console.log('Inicio de sesión fallido. Verifique sus credenciales.');
        }
        
    } catch (error) {
        console.error('Error en la solicitud:', error);
        alert('Ocurrió un error durante el inicio de sesión.');
    }

    logout();
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

async function InsertQuestionData(){    
    try {
        const result = await fetch(Urljson);
        const resultText = await result.text();
        examen(resultText)
        
    } catch (error) {
        console.error("Error en la consulta:", error);
        
    }
}

async function examen(data){    
    try {
        const result = await fetch(UrlQuestionInsert, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ titulo:"Examen de Historia", descripcion:"Examen para evaluar conocimientos básicos de historia" ,contenido: data, id_tutor: 91})

        });
        const resultText = await result.text();
        console.log(resultText);
        
    } catch (error) {
        console.error("Error en la consulta:", error);
        
    }


}

async function allQuestions(){
    try {
        const result = await fetch(UrlQuestionAll, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });
        const resultText = await result.json();
        console.log(resultText);
        
    } catch (error) {
        console.log(error);
        
    }
}

async function DeleteQuestion(){
    try {
        const result = await fetch(UrlQuestionDelete, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({id_examen:2})
        });
        const resultText = await result.text();
        console.log(resultText);
    } catch (error) {
        console.log(error);
        
    }
}

async function SearchQuestion(){
    try {
        const result = await fetch(UrlQuestionGetId,{
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({id_examen45})
        })
        const response = await result.json();
        console.log(response);
        
    } catch (error) {
        console.log(error);
        
    }
}

const buscar = (data, id, cambios) => {
    for (let pregunta of data) {
        if (pregunta.id_pregunta === id) {
            console.log(pregunta.texto_pregunta);

            // Permitir al usuario ingresar un nuevo texto para la pregunta
            let nuevoTextoPregunta = prompt("Ingrese el nuevo texto para la pregunta: ", pregunta.texto_pregunta);
            cambios.pregunta = {
                id_pregunta: pregunta.id_pregunta,
                texto_pregunta: nuevoTextoPregunta
            };

            // Actualizar el texto de la pregunta en el objeto original
            pregunta.texto_pregunta = nuevoTextoPregunta;

            Question(pregunta.respuestas, cambios);
            break;
        }
    }
}

const Question = (respuestas, cambios) => {
    let idRespuesta = parseInt(prompt("Ingrese el id de la respuesta: "), 10); // Convertir a número
    for (let respuesta of respuestas) {
        if (respuesta.id_respuesta === idRespuesta) {
            console.log("Respuesta encontrada:", respuesta.texto_respuesta);
            console.log("Es correcta:", respuesta.es_correcta);

            // Permitir al usuario ingresar un nuevo texto para la respuesta
            let nuevoTextoRespuesta = prompt("Ingrese el nuevo texto para la respuesta: ", respuesta.texto_respuesta);
            let nuevaEsCorrecta = confirm("¿Es esta respuesta correcta?");

            cambios.respuesta = {
                id_respuesta: respuesta.id_respuesta,
                texto_respuesta: nuevoTextoRespuesta,
                es_correcta: nuevaEsCorrecta
            };

            // Actualizar el texto y la corrección de la respuesta en el objeto original
            respuesta.texto_respuesta = nuevoTextoRespuesta;
            respuesta.es_correcta = nuevaEsCorrecta;

            return respuesta;
        }
    }
    console.log("Respuesta no encontrada");
    return null;
}

async function UpdateQuestions() {
    try {
        const response = await fetch(UrlQuestionGetId, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_examen: 4 })
        });
        const result = await response.json();
        if (!result.success) {
            console.log(result.message);
        } else {
            let data = JSON.parse(result.data.contenido).preguntas; // Asegúrate de acceder correctamente al array de preguntas
           
            let update = prompt("Ingrese código de la pregunta: ");
            let updateId = parseInt(update);  // Convierte update a un número
            let cambios = {};  // Definir el objeto cambios
            buscar(data, updateId, cambios);
            console.log(cambios);

            // Crear un diccionario de cambios para enviar a la API
            let updatedData = {
                id_examen: 4,
                contenido: JSON.stringify({ preguntas: data })
            };

            // Enviar los cambios a la API
            const updateResponse = await fetch(UrlQuestionUpdate, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(updatedData)
            });

            const updateResult = await updateResponse.text();
            console.log(updateResult);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}


async function RequestAnswer(){   
        try {
            const reponse = await fetch(UrlAnswerJson);
            const result = await reponse.text()
            //console.log(result)
            InsertAnswer(result);
            
        } catch (error) {
            console.log(error)
            
        }

}

async function InsertAnswer(data){    
   try {
    const response = await fetch(UrlAnswerInsert, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({id_estudiante:2,id_examen: 5, data:data})
            });
            const result = await response.text();
            console.log(result);
    
   } catch (error) {
     console.log(error)
    
   }
}
async function ChatGptSend() {
    let msg = prompt('Envia mensaje a chat GPT');
    try {
        const response = await fetch(UrlChatGpt4IA, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ send: msg })
        });

        const result = await response.text(); // Cambiar a `.json()` para manejar JSON
        console.log(result);

        if (result.success) {
            console.log('Respuesta de ChatGPT:', result.data);
        } else {
            console.error('Error:', result.message);
        }
        
    } catch (error) {
        console.error('Error de red:', error);
    }
}

async function Sms() {
    let msg = prompt('Envia mensaje a sms: ');
    let num = prompt('Ingrese numero de telefono: ');
    let number = '57' + num;

    try {
        const response = await fetch(UrlSms, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ send: msg, number: number })
        });

        const result = await response.json();
        if (result.status === '1x000') {
            console.log('Mensaje enviado a ' + number);
        } else {
            console.log('Error al enviar', result.status);
        }

    } catch (error) {
        console.log(error);
    }
}




// ---------------------  usuario ----------------------------
//crear_usuario();
//GetAll();
//GetId();
//Delete();
//update();
//getIdUpdate();
// --------------------- end usuario ----------------------------

// --------------------- login usuario ----------------------------
login();
// --------------------- end login usuario ----------------------


// --------------------------- contenido -------------------------
//InsertContent();
//load();
// ---------------------------end contenido -------------------------


// --------------------------- examen -------------------------

//InsertQuestionData()
//allQuestions();
//DeleteQuestion();
//SearchQuestion();
//UpdateQuestions();

// -------------------- end examen -------------------------------


// ------------------- respuesta estudiante ------------------------
//RequestAnswer();
// ------------------- end respuesta estudiante ----------------------

//--------------------------------IA--------------------------------------
//ChatGptSend();
//------------------------------End IA--------------------------------------

//--------------------------------SMS---------------------------------------
//Sms()
//---------------------------- End SMS---------------------------------------


