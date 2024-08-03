let sidebar = document.querySelector(".sidebar");
let closeBtn = document.querySelector("#btn");
let searchBtn = document.querySelector(".bx-search");

closeBtn.addEventListener("click", ()=>{
  sidebar.classList.toggle("open");
  menuBtnChange();//calling the function(optional)
});

searchBtn.addEventListener("click", ()=>{ // Sidebar open when you click on the search iocn
  sidebar.classList.toggle("open");
  menuBtnChange(); //calling the function(optional)
});



// following are the code to change sidebar button(optional)
function menuBtnChange() {
 if(sidebar.classList.contains("open")){
   closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");//replacing the iocns class
 }else {
   closeBtn.classList.replace("bx-menu-alt-right","bx-menu");//replacing the iocns class
 }
}

async function loadContent(url, callback) {
  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    const content = await response.text();
    const contentDiv = document.getElementById("content");
    contentDiv.innerHTML = content;

    if (callback) {
      callback();
    }

  } catch (error) {
    console.error('Error loading content:', error);
  }
}

document.addEventListener('DOMContentLoaded', () => loadContent('inicio.html'));

document.getElementById("question-link").addEventListener("click", () => {
  loadContent("loadQuestion.html", allQuestions);
});

// Importar la función allQuestions del módulo ProgrammerQuestion.js
import { allQuestions } from './ProgrammerQuestion.js';