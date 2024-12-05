import {UrlUserInsert} from './const.js'

async function Registrar(event){
    event.preventDefault();// Previene el envío del formulario y la redirección    
    let form = document.getElementById("form");
    let formData = new FormData(form);
    const data = Object.fromEntries(formData);    

    try {
        
        const response = await fetch(UrlUserInsert, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
             });
        const result = await response.json();        
       if(result.success){
        form.reset();
        alert(result.message);       
       }else{
        let errorMessage = result.message;
            if (result.errors) {
                // Acceder a los errores específicos y concatenarlos en un mensaje
                errorMessage += "\n";
                Object.keys(result.errors).forEach((key) => {
                    errorMessage += `${key}: ${result.errors[key]}\n`;
                });
            }
            alert(errorMessage);    
       }

    } catch (error) {
        console.log(error);
        
    }

}

window.Registrar = Registrar;