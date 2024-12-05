function Recuperar(event){
    event.preventDefault();
    let form = document.getElementById("form")
    let datos = new FormData(form);
    let data = Object.fromEntries(datos)
    console.log(data);
}
window.Recuperar = Recuperar;