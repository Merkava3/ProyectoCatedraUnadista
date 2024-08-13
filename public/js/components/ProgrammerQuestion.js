let preguntasData = {};
const maxPreguntas = 8;
let draggedElement = null;

async function allQuestions() {
    try {
        const response = await fetch("../data/Question.json", {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });
        const data = await response.json();
        build(data.preguntas);
    } catch (error) {
        console.log(error);
    }
}

function build(preguntas) {
    preguntasData = {}; // Reiniciar preguntasData
    const contenedor = document.getElementById("Questions");
    contenedor.innerHTML = "";

    preguntas.forEach((pregunta, preguntaIndex) => {
        preguntasData[preguntaIndex] = pregunta;
    });

    mostrarPreguntas();
}

function mostrarPreguntas() {
    const contenedor = document.getElementById("Questions");
    const preguntas = Object.values(preguntasData).filter(pregunta => !pregunta.eliminada);

    // Limpiar las preguntas actuales
    const preguntasDivs = contenedor.querySelectorAll('.pregunta');
    preguntasDivs.forEach(div => div.remove());

    preguntas.forEach((pregunta, preguntaIndex) => {
        const preguntaDiv = crearPreguntaElement(pregunta, preguntaIndex);
        contenedor.appendChild(preguntaDiv); // Usar appendChild para mantener el orden
    });

    // Agregar botones al final después de mostrar todas las preguntas
    agregarBotones(contenedor);
}

function crearPreguntaElement(pregunta, preguntaIndex) {
    const preguntaDiv = document.createElement("div");
    preguntaDiv.className = "pregunta";

    const preguntaLabel = document.createElement("label");
    preguntaLabel.textContent = `Pregunta ${preguntaIndex + 1}:`;
    preguntaDiv.appendChild(preguntaLabel);

    const preguntaInput = document.createElement("input");
    preguntaInput.type = "text";
    preguntaInput.value = pregunta.texto_pregunta;
    preguntaInput.addEventListener("input", (e) => {
        preguntasData[preguntaIndex].texto_pregunta = e.target.value;
    });
    preguntaDiv.appendChild(preguntaInput);

    const tipoPreguntaLabel = document.createElement("label");
    tipoPreguntaLabel.textContent = "Tipo de Pregunta:";
    preguntaDiv.appendChild(tipoPreguntaLabel);

    const tipoPreguntaSelect = document.createElement("select");
    const optionMultipleChoice = document.createElement("option");
    optionMultipleChoice.value = "multiple_choice";
    optionMultipleChoice.text = "Multiple Choice";
    const optionTrueFalse = document.createElement("option");
    optionTrueFalse.value = "true_false";
    optionTrueFalse.text = "True/False";
    tipoPreguntaSelect.add(optionMultipleChoice);
    tipoPreguntaSelect.add(optionTrueFalse);
    tipoPreguntaSelect.value = pregunta.tipo_pregunta;
    tipoPreguntaSelect.addEventListener("change", (e) => {
        preguntasData[preguntaIndex].tipo_pregunta = e.target.value;
        handleTipoPreguntaChange(tipoPreguntaSelect, respuestasDiv, preguntaIndex, preguntaDiv);
    });
    preguntaDiv.appendChild(tipoPreguntaSelect);

    const puntajeLabel = document.createElement("label");
    puntajeLabel.textContent = "Puntaje:";
    preguntaDiv.appendChild(puntajeLabel);

    const puntajeInput = document.createElement("input");
    puntajeInput.type = "number";
    puntajeInput.value = pregunta.puntajea || 5;
    puntajeInput.addEventListener("input", (e) => {
        preguntasData[preguntaIndex].puntajea = e.target.value;
    });
    preguntaDiv.appendChild(puntajeInput);

    const respuestasDiv = document.createElement("div");
    respuestasDiv.className = "respuestas";
    respuestasDiv.dataset.preguntaIndex = preguntaIndex; // Agregar el índice de la pregunta al dataset

    pregunta.respuestas.forEach((respuesta, respuestaIndex) => {
        const respuestaDiv = crearRespuestaElement(respuesta, preguntaIndex, respuestaIndex);
        respuestasDiv.appendChild(respuestaDiv);
    });

    if (pregunta.tipo_pregunta === "multiple_choice") {
        const addRespuestaBtn = document.createElement("button");
        addRespuestaBtn.textContent = "Agregar Respuesta";
        addRespuestaBtn.className = "add-respuesta";
        addRespuestaBtn.addEventListener("click", () => addRespuesta(respuestasDiv, preguntaIndex));
        preguntaDiv.appendChild(addRespuestaBtn);
    }

    const deletePreguntaBtn = document.createElement("button");
    deletePreguntaBtn.textContent = "Eliminar Pregunta";
    deletePreguntaBtn.className = "delete-pregunta";
    deletePreguntaBtn.addEventListener("click", () => eliminarPregunta(preguntaIndex));
    preguntaDiv.appendChild(deletePreguntaBtn);

    preguntaDiv.appendChild(respuestasDiv);
    return preguntaDiv;
}

function agregarBotones(contenedor) {
    let botonesDiv = contenedor.querySelector('.botones');
    if (!botonesDiv) {
        const addPreguntaBtn = document.createElement("button");
        addPreguntaBtn.textContent = "Agregar Pregunta";
        addPreguntaBtn.setAttribute("class", "agregarpregunta")
        addPreguntaBtn.addEventListener("click", () => addPregunta());

        const guardarBtn = document.createElement("button");
        guardarBtn.setAttribute("class", "guardar")
        guardarBtn.textContent = "Guardar";
        guardarBtn.addEventListener("click", () => {
            console.log(preguntasData);
        });

        botonesDiv = document.createElement("div");
        botonesDiv.className = "botones";
        botonesDiv.appendChild(addPreguntaBtn);
        botonesDiv.appendChild(guardarBtn);
        contenedor.appendChild(botonesDiv);
    } else {
        contenedor.appendChild(botonesDiv);
    }
}

function handleTipoPreguntaChange(tipoPreguntaSelect, respuestasDiv, preguntaIndex, preguntaDiv) {
    respuestasDiv.innerHTML = "";
    if (tipoPreguntaSelect.value === "true_false") {
        preguntasData[preguntaIndex].respuestas = [
            { id_respuesta: 1, texto_respuesta: "Verdadero", es_correcta: true },
            { id_respuesta: 2, texto_respuesta: "Falso", es_correcta: false }
        ];

        const respuestaDiv1 = crearRespuestaElement(preguntasData[preguntaIndex].respuestas[0], preguntaIndex, 0);
        const respuestaDiv2 = crearRespuestaElement(preguntasData[preguntaIndex].respuestas[1], preguntaIndex, 1);
        respuestasDiv.appendChild(respuestaDiv1);
        respuestasDiv.appendChild(respuestaDiv2);

        // Desactivar o eliminar el botón "Agregar Respuesta" para preguntas de tipo true_false
        const addRespuestaBtn = preguntaDiv.querySelector(".add-respuesta");
        if (addRespuestaBtn) {
            addRespuestaBtn.remove();
        }
    } else {
        preguntasData[preguntaIndex].respuestas = [];
        
        // Crear y agregar el botón "Agregar Respuesta" solo para preguntas que no sean de tipo true_false
        const addRespuestaBtn = document.createElement("button");
        addRespuestaBtn.textContent = "Agregar Respuesta";
        addRespuestaBtn.className = "add-respuesta";
        addRespuestaBtn.addEventListener("click", () => addRespuesta(respuestasDiv, preguntaIndex));
        preguntaDiv.appendChild(addRespuestaBtn);
    }
}

function crearRespuestaElement(respuesta, preguntaIndex, respuestaIndex) {
    const respuestaDiv = document.createElement("div");
    respuestaDiv.className = "respuesta";
    respuestaDiv.draggable = true;
    respuestaDiv.addEventListener("dragstart", dragStart);
    respuestaDiv.addEventListener("dragover", dragOver);
    respuestaDiv.addEventListener("drop", drop);

    const respuestaRadio = document.createElement("input");
    respuestaRadio.type = "radio";
    respuestaRadio.name = `pregunta-${preguntaIndex}`;
    respuestaRadio.checked = respuesta.es_correcta;
    respuestaRadio.addEventListener("change", () => {
        preguntasData[preguntaIndex].respuestas.forEach(r => r.es_correcta = false);
        preguntasData[preguntaIndex].respuestas[respuestaIndex].es_correcta = true;
    });
    respuestaDiv.appendChild(respuestaRadio);

    const respuestaLabel = document.createElement("label");
    respuestaLabel.textContent = `Respuesta ${respuestaIndex + 1}:`;
    respuestaDiv.appendChild(respuestaLabel);

    const respuestaInput = document.createElement("input");
    respuestaInput.type = "text";
    respuestaInput.value = respuesta.texto_respuesta;
    respuestaInput.addEventListener("input", (e) => {
        preguntasData[preguntaIndex].respuestas[respuestaIndex].texto_respuesta = e.target.value;
    });
    respuestaDiv.appendChild(respuestaInput);

    return respuestaDiv;
}

function addRespuesta(respuestasDiv, preguntaIndex) {
    const numRespuestas = preguntasData[preguntaIndex].respuestas.length;
    if (numRespuestas >= 2 && preguntasData[preguntaIndex].tipo_pregunta === "true_false") {
        alert("Solo se permiten 2 respuestas para preguntas de tipo True/False.");
        return;
    }

    const nuevaRespuesta = {
        id_respuesta: numRespuestas + 1,
        texto_respuesta: "",
        es_correcta: false
    };

    preguntasData[preguntaIndex].respuestas.push(nuevaRespuesta);
    const respuestaDiv = crearRespuestaElement(nuevaRespuesta, preguntaIndex, numRespuestas);
    respuestasDiv.appendChild(respuestaDiv);
}

function addPregunta() {
    const numPreguntas = Object.keys(preguntasData).length;
    if (numPreguntas >= maxPreguntas) {
        alert("No se pueden agregar más de 8 preguntas.");
        return;
    }

    const nuevaPregunta = {
        id_pregunta: numPreguntas + 1,
        texto_pregunta: "",
        tipo_pregunta: "multiple_choice",
        respuestas: [
            { id_respuesta: 1, texto_respuesta: "", es_correcta: false }
        ],
        eliminada: false,
        puntajea: 5
    };

    preguntasData[numPreguntas] = nuevaPregunta;
    mostrarPreguntas();
}

function eliminarPregunta(preguntaIndex) {
    preguntasData[preguntaIndex].eliminada = true;
    mostrarPreguntas();
}

function dragStart(event) {
    draggedElement = event.currentTarget;
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('text/html', draggedElement.outerHTML);
    draggedElement.classList.add('dragging');
}

function dragOver(event) {
    event.preventDefault();
    event.dataTransfer.dropEffect = 'move';
}

function drop(event) {
    event.preventDefault();
    const target = event.currentTarget;
    const parent = target.parentElement;
    if (draggedElement !== target) {
        const draggedIndex = Array.from(parent.children).indexOf(draggedElement);
        const targetIndex = Array.from(parent.children).indexOf(target);
        if (draggedIndex > targetIndex) {
            parent.insertBefore(draggedElement, target);
        } else {
            parent.insertBefore(draggedElement, target.nextSibling);
        }
        updateRespuestasOrder(parent, draggedElement.parentElement.dataset.preguntaIndex);
    }
    draggedElement.classList.remove('dragging');
    draggedElement = null;
}

function updateRespuestasOrder(parent, preguntaIndex) {
    const respuestas = parent.querySelectorAll('.respuesta');
    respuestas.forEach((respuesta, index) => {
        preguntasData[preguntaIndex].respuestas[index].id_respuesta = index + 1;
    });
}

export { allQuestions, preguntasData };

