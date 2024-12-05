import { UrlCount,UrlOnlineUser,UrlTableData  } from "../const.js";

async function getcount() {
    try {
        const response = await fetch(UrlCount, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });
        const data = await response.json();
        //console.log(data);

        //http://localhost/ProyectoCatedraUnadista/public/pages/SibeBarMenu.html

        // Extrae el valor de total_usuarios del objeto data
        if (data.success && Array.isArray(data.data) && data.data.length > 0) {
            const total_estudiantes = data.data[0].total_estudiantes;

            // Actualiza el contenido del elemento con id "count"
            const countElement = document.getElementById("count");
            if (countElement) {
                countElement.textContent = total_estudiantes;
            }
        }
    } catch (error) {
        console.log(error);
    }
}

async function OnlineSession(){
    try {
        const response = await fetch(UrlOnlineUser, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });
        const result = await response.json();
        //console.log(result);

        if (result.success && Array.isArray(result.data) && result.data.length > 0) {
            const cantidad_estudiantes = result.data[0].cantidad_estudiantes;
            // Actualiza el contenido del elemento con id "SessionCoun"
            const sessionCounElement = document.getElementById("NotSession");
            if (sessionCounElement) {
                sessionCounElement.textContent = cantidad_estudiantes;
            }
        }

        if (result.success && Array.isArray(result.data) && result.data.length > 0) {
            const cantidad_estudiantes = result.data[1].cantidad_estudiantes;
            // Actualiza el contenido del elemento con id "SessionCoun"
            const sessionCounElement = document.getElementById("SessionCoun");
            if (sessionCounElement) {
                sessionCounElement.textContent = cantidad_estudiantes;
            }         
        }
        
    } catch (error) {
        console.log(error);
        
    }
    
}



async function TableData(){
    try {
        const response = await fetch(UrlTableData , {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
             });
        const result = await response.json();
        //console.log(result);
        if(result.success && Array.isArray(result.data)) {
            data = result.data;
         if (data.length > 15) {
            RenderTable(currentPage);
            renderPagination();
            
         }else{
            renderTableWithoutPagination();

         }            
        }     
        
    } catch (error) {
        console.log(error);
        
    }
}

const rowsPerPage = 2;
let currentPage = 1;
let data = [];

function renderTableWithoutPagination() {
    const tbody = document.querySelector('#TableInfo tbody');
    tbody.innerHTML = '';

    data.forEach(item => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${item.indentificacion}</td>
            <td>${item.nombre}</td>
            <td>${item.apellido}</td>
            <td>${item.correo}</td>
            <td>${item.fecha_sesion}</td>
            <td><span class="sesion ${item.estado === 1 ? 'green' : 'red'}"></span></td>
        `;
        tbody.appendChild(tr);
    });
}

function renderPagination() {
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';
    const pageCount = Math.ceil(data.length / rowsPerPage);

    for (let i = 1; i <= pageCount; i++) {
        const button = document.createElement('button');
        button.textContent = i;
        if (i === currentPage) {
            button.classList.add('active');
        }
        button.addEventListener('click', () => {
            currentPage = i;
            renderTable(currentPage);
            renderPagination();
        });
        pagination.appendChild(button);
    }
}




// Llama a la función getcount cuando el documento esté completamente cargado
document.addEventListener('DOMContentLoaded', () => {
    getcount()
    OnlineSession();
    TableData();
    
    //setInterval(OnlineSession, 3000)
});




