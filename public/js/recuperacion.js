import {UrlRecovery} from './const.js'

async function Recuperar(event){
    event.preventDefault();
    let form = document.getElementById("form")
    let datos = new FormData(form);
    let data = Object.fromEntries(datos)
   
    
    try {
        const response = await fetch(UrlRecovery, {
            method: 'POST',
            headers:  { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)


        })
        const result = await response.text();
        console.log(result);
        
    } catch (error) {
        console.log(error);
        
    }
}

window.Recuperar = Recuperar;