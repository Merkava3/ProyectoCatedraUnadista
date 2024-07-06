const Accerder = () => {
   let form = document.getElementById("form");   
   if (form.uname.value === "catedra" && form.psw.value === "123" ) {   
    window.location.replace("SibeBarMenu.html");
    return false;    
   }
    
}