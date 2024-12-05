import {UrlUserLogin} from './const.js';

async function Acceder(event){
   event.preventDefault();
   let form = document.getElementById("form");   
   let formData = new FormData(form);
   const data = Object.fromEntries(formData);
   try {
      const response = await fetch(UrlUserLogin, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data)
      });      
     
      const result = await response.json();  
      
      if (result.success) {
         alert("Bienvenido"); 
         form.reset();            
          window.location.href = result.pages;          
      } else {
          alert(result.message);
          alert('Inicio de sesión fallido. Verifique sus credenciales.');
      }
      
  } catch (error) {
      console.error('Error en la solicitud:', error);
      alert('Ocurrió un error durante el inicio de sesión.');
  }
  

}

window.Acceder = Acceder;



