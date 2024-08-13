// Constants
const MAX_PREGUNTAS = 8;

// Utility functions
const createElement = (type, className, attributes = {}, eventListeners = []) => {
    const element = document.createElement(type);
    if (className) element.className = className;
    Object.keys(attributes).forEach(attr => element.setAttribute(attr, attributes[attr]));
    eventListeners.forEach(({ type, handler }) => element.addEventListener(type, handler));
    return element;
};

// Classes
class Respuesta {
    constructor(respuestaData, preguntaIndex, respuestaIndex) {
        this.respuestaData = respuestaData;
        this.preguntaIndex = preguntaIndex;
        this.respuestaIndex = respuestaIndex;
    }

    createElement() {
        const respuestaDiv = createElement('div', 'respuesta', { draggable: true }, [
            { type: 'dragstart', handler: dragStart },
            { type: 'dragover', handler: dragOver },
            { type: 'drop', handler: drop }
        ]);

        const respuestaRadio = createElement('input', '', { type: 'radio', name: `pregunta-${this.preguntaIndex}` });
        respuestaRadio.checked = this.respuestaData.es_correcta;
        respuestaRadio.addEventListener('change', () => {
            preguntasData[this.preguntaIndex].respuestas.forEach(r => r.es_correcta = false);
            preguntasData[this.preguntaIndex].respuestas[this.respuestaIndex].es_correcta = true;
        });
        respuestaDiv.appendChild(respuestaRadio);

        const respuestaLabel = createElement('label');
        respuestaLabel.textContent = `Respuesta ${this.respuestaIndex + 1}:`;
        respuestaDiv.appendChild(respuestaLabel);

        const respuestaInput = createElement('input', '', { type: 'text', value: this.respuestaData.texto_respuesta });
        respuestaInput.addEventListener('input', (e) => {
            preguntasData[this.preguntaIndex].respuestas[this.respuestaIndex].texto_respuesta = e.target.value;
        });
        respuestaDiv.appendChild(respuestaInput);

        return respuestaDiv;
    }
}

class Pregunta {
    constructor(preguntaData, preguntaIndex) {
        this.preguntaData = preguntaData;
        this.preguntaIndex = preguntaIndex;
    }

    createElement() {
        const preguntaDiv = createElement('div', 'pregunta');

        const preguntaLabel = createElement('label');
        preguntaLabel.textContent = `Pregunta ${this.preguntaIndex + 1}:`;
        preguntaDiv.appendChild(preguntaLabel);

        const preguntaInput = createElement('input', '', { type: 'text', value: this.preguntaData.texto_pregunta });
        preguntaInput.addEventListener('input', (e) => {
            preguntasData[this.preguntaIndex].texto_pregunta = e.target.value;
        });
        preguntaDiv.appendChild(preguntaInput);

        const tipoPreguntaSelect = createElement('select');
        const optionMultipleChoice = createElement('option', '', { value: 'multiple_choice' });
        optionMultipleChoice.textContent = 'Multiple Choice';
        tipoPreguntaSelect.appendChild(optionMultipleChoice);
        const optionTrueFalse = createElement('option', '', { value: 'true_false' });
        optionTrueFalse.textContent = 'True/False';
        tipoPreguntaSelect.appendChild(optionTrueFalse);
        tipoPreguntaSelect.value = this.preguntaData.tipo_pregunta;
        tipoPreguntaSelect.addEventListener('change', (e) => {
            this.preguntaData.tipo_pregunta = e.target.value;
            handleTipoPreguntaChange(tipoPreguntaSelect, preguntaDiv);
        });
        preguntaDiv.appendChild(tipoPreguntaSelect);

        const puntajeInput = createElement('input', '', { type: 'number', value: this.preguntaData.puntajea || 5 });
        puntajeInput.addEventListener('input', (e) => {
            this.preguntaData.puntajea = e.target.value;
        });
        preguntaDiv.appendChild(puntajeInput);

        const respuestasDiv = createElement('div', 'respuestas', { 'data-pregunta-index': this.preguntaIndex });

        this.preguntaData.respuestas.forEach((respuesta, respuestaIndex) => {
            const respuestaElement = new Respuesta(respuesta, this.preguntaIndex, respuestaIndex).createElement();
            respuestasDiv.appendChild(respuestaElement);
        });

        if (this.preguntaData.tipo_pregunta === 'multiple_choice') {
            const addRespuestaBtn = createElement('button', 'add-respuesta');
            addRespuestaBtn.textContent = 'Agregar Respuesta';
            addRespuestaBtn.addEventListener('click', () => addRespuesta(respuestasDiv, this.preguntaIndex));
            preguntaDiv.appendChild(addRespuestaBtn);
        }

        const deletePreguntaBtn = createElement('button', 'delete-pregunta');
        deletePreguntaBtn.textContent = 'Eliminar Pregunta';
        deletePreguntaBtn.addEventListener('click', () => eliminarPregunta(this.preguntaIndex));
        preguntaDiv.appendChild(deletePreguntaBtn);

        preguntaDiv.appendChild(respuestasDiv);
        return preguntaDiv;
    }
}

// Main Functions
async function allQuestions() {
    try {
        const response = await fetch("../data/Question.json");
        const data = await response.json();
        build(data.preguntas);
    } catch (error) {
        console.error(error);
    }
}

function build(preguntas) {
    preguntasData = {};
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

    const preguntasDivs = contenedor.querySelectorAll('.pregunta');
    preguntasDivs.forEach(div => div.remove());

    preguntas.forEach((pregunta, preguntaIndex) => {
        const preguntaElement = new Pregunta(pregunta, preguntaIndex).createElement();
        contenedor.appendChild(preguntaElement);
    });

    agregarBotones(contenedor);
}

function handleTipoPreguntaChange(tipoPreguntaSelect, preguntaDiv) {
    const preguntaIndex = preguntaDiv.querySelector('.respuestas').dataset.preguntaIndex;
    const respuestasDiv = preguntaDiv.querySelector('.respuestas');
    respuestasDiv.innerHTML = "";

    if (tipoPreguntaSelect.value === 'true_false') {
        preguntasData[preguntaIndex].respuestas = [
            { id_respuesta: 1, texto_respuesta: "Verdadero", es_correcta: true },
            { id_respuesta: 2, texto_respuesta: "Falso", es_correcta: false }
        ];
        preguntasData[preguntaIndex].respuestas.forEach((respuesta, index) => {
            const respuestaElement = new Respuesta(respuesta, preguntaIndex, index).createElement();
            respuestasDiv.appendChild(respuestaElement);
        });
        const addRespuestaBtn = preguntaDiv.querySelector(".add-respuesta");
        if (addRespuestaBtn) addRespuestaBtn.remove();
    } else {
        preguntasData[preguntaIndex].respuestas = [];
        const addRespuestaBtn = createElement('button', 'add-respuesta');
        addRespuestaBtn.textContent = 'Agregar Respuesta';
        addRespuestaBtn.addEventListener('click', () => addRespuesta(respuestasDiv, preguntaIndex));
        preguntaDiv.appendChild(addRespuestaBtn);
    }
}

function agregarBotones(contenedor) {
    let botonesDiv = contenedor.querySelector('.botones');
    if (!botonesDiv) {
        const addPreguntaBtn = createElement('button', 'agregar-pregunta');
        addPreguntaBtn.textContent = 'Agregar Pregunta';
        addPreguntaBtn.addEventListener('click', addPregunta);

        const guardarBtn = createElement('button', 'guardar');
        guardarBtn.textContent = 'Guardar';
        guardarBtn.addEventListener('click', () => console.log(preguntasData));

        botonesDiv = createElement('div', 'botones');
        botonesDiv.appendChild(addPreguntaBtn);
        botonesDiv.appendChild(guardarBtn);
        contenedor.appendChild(botonesDiv);
    } else {
        contenedor.appendChild(botonesDiv);
    }
}

function addRespuesta(respuestasDiv, preguntaIndex) {
    if (preguntasData[preguntaIndex].tipo_pregunta === 'true_false' && preguntasData[preguntaIndex].respuestas.length >= 2) {
        alert("Solo se permiten 2 respuestas para preguntas de tipo True/False.");
        return;
    }

    const nuevaRespuesta = { id_respuesta: preguntasData[preguntaIndex].respuestas.length + 1, texto_respuesta: "", es_correcta: false };
    preguntasData[preguntaIndex].respuestas.push(nuevaRespuesta);
    const respuestaElement = new Respuesta(nuevaRespuesta, preguntaIndex, preguntasData[preguntaIndex].respuestas.length - 1).createElement();
    respuestasDiv.appendChild(respuestaElement);
}

function addPregunta() {
    if (Object.keys(preguntasData).length >= MAX_PREGUNTAS) {
        alert("Se alcanzó el límite máximo de preguntas.");
        return;
    }

    const nuevaPregunta = { id_pregunta: Object.keys(preguntasData).length + 1, texto_pregunta: "", tipo_pregunta: "multiple_choice", puntajea: 5, respuestas: [] };
    preguntasData[Object.keys(preguntasData).length] = nuevaPregunta;
    mostrarPreguntas();
}

function eliminarPregunta(preguntaIndex) {
    if (confirm("¿Está seguro de que desea eliminar esta pregunta?")) {
        delete preguntasData[preguntaIndex];
        mostrarPreguntas();
    }
}

// Drag and Drop Handlers
function dragStart(e) {
    e.dataTransfer.setData('text/plain', e.target.dataset.preguntaIndex);
}

function dragOver(e) {
    e.preventDefault();
}

function drop(e) {
    e.preventDefault();
    const fromIndex = e.dataTransfer.getData('text/plain');
    const toIndex = e.target.closest('.pregunta').dataset.preguntaIndex;
    if (fromIndex === toIndex) return;
    
    const movedPregunta = preguntasData[fromIndex];
    delete preguntasData[fromIndex];
    preguntasData[toIndex] = movedPregunta;
    mostrarPreguntas();
}

// Initialize
let preguntasData = {};
document.addEventListener('DOMContentLoaded', allQuestions);
