
window.addEventListener('scroll', function() {
    // Obtenemos la posición actual del scroll
    let scrollPosition = window.scrollY;
   // console.log(scrollPosition)
    // Definimos la posición a partir de la cual queremos activar la animación
    //let activationPoint = 1522; // Por ejemplo, a 200 píxeles de scroll hacia abajo
  
    // Función para animar un elemento
    function animateElement(elementId, animationClass, activationPoint) {
      let elemento = document.getElementById(elementId);
  
      // Si el usuario ha hecho scroll más allá de la posición de activación
      if (scrollPosition > activationPoint) {
        // Establecemos la opacidad en 1 para asegurarnos de que el elemento esté visible
        elemento.style.opacity = '1';
        // Agregamos la clase de animación al elemento
        elemento.classList.add(animationClass);
        elemento.style.setProperty('--animate-duration', '1s');
      }
    }
  
    //animate__fadeInUp 1185
    animateElement("fotos", "animate__fadeInUp", 1185)
    animateElement("animeted-text", "animate__fadeInDown", 204)
    animateElement("img-about-animeted", "animate__fadeInLeftBig",204)
    // Llamar a la función para animar el primer elemento
    animateElement('Tutor_one_animated', 'animate__fadeInLeft',1522);
    // Llamar a la función para animar el segundo elemento
    animateElement('Tutor_two_animated', 'animate__fadeInRight',1522);
  });