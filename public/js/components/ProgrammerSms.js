import {UrlSms} from '../const.js'

async function sms(event) {
    event.preventDefault();
    let form = document.getElementById("FormSms");    
    let number = '57' + form.telefono.value;
    let msg = form.msg.value;
    
    
    try {
        const response = await fetch(UrlSms, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ send: msg, number: number })
        });

        const result = await response.json();
        console.log(result);           
        if (result.status === '1x000') {
            alert('Mensaje enviado a ' + number);
            form.reset();
        } else {
            alert('Error al enviar', result.status);
        }

    } catch (error) {
        console.log(error);
    }
       
}
window.sms = sms;
