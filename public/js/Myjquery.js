import Help from './help.js';

export default class Myjquery{
    static async getJSON(url, callback) {
        try {
          const response = await fetch(url);
          const data = await response.json();
          callback(data);
        } catch (error) {
          console.error('Error fetching JSON:', error);
        }
      }
      static async getId(ElementId, baseUrl, callback) {
        
        try {
            let element = document.getElementById(ElementId);
            element.addEventListener('submit', async function(event) {
              event.preventDefault();
      
              let formData = new FormData(element);
              let postData = {};
              formData.forEach((value, key) => {
                postData[key] = value;
              });
              let url = `${baseUrl}/${postData.name}`;    
              
      
              const response = await fetch(url);
              //console.log(response)         
                
              if(response.ok === false && response.status === 400) {
                callback({success: false, message: "Este digimon no existe"})

              }else{
                const responseData = await response.json();
                callback({success: true, data : Help.organizeData(responseData)});

              }
      
              
            });
          } catch (error) {
            console.error('Error:', error);
          }
        }
       
      }
      



    
  

    //Myjquery.getJSON('https://digimon-api.vercel.app/api/digimon/level/mega' , function (data) {
        //console.log(data);
      //});

     Myjquery.getId("form",'https://digimon-api.vercel.app/api/digimon/name', function (data) {
     // Verificar si la consulta fue exitosa
     if (data.success) {
        // Aquí dentro del callback, manejas los datos recibidos y creas tu div
        let existingCard = document.getElementById("card");

        // Si existe un div con id "card", eliminarlo
        if (existingCard) {
            existingCard.remove();
        }

        // Crear el nuevo div
        let card = document.createElement("div");
        card.setAttribute("id", "card");
        

        // Crear la imagen y establecer el atributo src
        let img = document.createElement("img");
        img.setAttribute("src", data.data.img);
        img.setAttribute("alt", data.data.name); // Añadir un atributo alt para accesibilidad
        card.appendChild(img); // Agregar la imagen al div

        // Ejemplo: Mostrar el nombre del Digimon obtenido
        card.innerHTML += `<h3>${data.data.name}</h3>`; // Agregar el nombre del Digimon al contenido existente

        // Agregar el div al cuerpo del documento o al contenedor deseado
        document.body.appendChild(card);

    } else {
        // Manejar el caso en que la consulta no fue exitosa
        alert(data.message); // Mostrar el mensaje de error al usuario
    }


     }) 
    