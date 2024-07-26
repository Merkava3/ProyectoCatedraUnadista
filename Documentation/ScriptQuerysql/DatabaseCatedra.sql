use catedra;
select * from sesion;
SELECT COUNT(*) AS total_estudiantes FROM usuario WHERE tipo_usuario = 'Estudiante';
select * from usuario;
create table usuario(
id_usuario int auto_increment primary key not null,
identifacion varchar(45) not null,
nombre_usuario varchar(45) not null,
apellido_usuario varchar(45) not null,
correo varchar(80) not null,
/* imagen blob not null, */
pws varchar(16) not null,
genero enum("Masculino","Femenino") not null,
tipo_usuario enum("Tutor","Estudiante") 
);
SHOW TRIGGERS FROM catedra LIKE 'respuesta_estudiante';
select * from sesion;
select * from usuario where  tipo_usuario = 'Estudiante' and 'lisa@gmail.com' ;

select * from programa;
drop table sesion;
select * from sesion;
create table sesion(
id_sesion int auto_increment primary key null,
fecha_sesion TIMESTAMP DEFAULT CURRENT_TIMESTAMP not null,
estado tinyint not null,
sesion_usuario int not null,
foreign key(sesion_usuario) references usuario(id_usuario)
);
select * from contenido;
create table contenido (
id_contenido int auto_increment null primary key,
contenido_sobre json not null,
nuestro_servicio json not null,
portafolio json not null,
id_usuario_contenido int not null,
foreign key(id_usuario_contenido) references usuario(id_usuario)
);
CREATE TABLE examen (
    id_examen INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    contenido JSON NOT NULL,
    id_tutor INT NOT NULL,
    FOREIGN KEY (id_tutor) REFERENCES usuario(id_usuario)
);
select * from respuesta_estudiante;

CREATE TABLE respuesta_estudiante (
    id_respuesta_estudiante INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    id_estudiante INT NOT NULL,
    id_examen INT NOT NULL,
    respuestas JSON NOT NULL,
    nota DECIMAL(4, 2) NOT NULL,
    FOREIGN KEY (id_estudiante) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_examen) REFERENCES examen(id_examen)
);


select * from respuesta_estudiante;


alter table contenido
add control TINYINT NOT NULL DEFAULT 1,
add unique(control);
select * from contenido;
select * from usuario;
INSERT INTO contenido (contenido_sobre, nuestro_servicio, portafolio, id_usuario_contenido)
VALUES ('{}', '{}', '{}', 91);
/* Evita que elimina el unico dato de la tabla contenido*/
DELIMITER $$
CREATE TRIGGER prevent_delete
BEFORE DELETE ON contenido
FOR EACH ROW
BEGIN
    DECLARE total_records INT;

    -- Contar el número de registros en la tabla contenido
    SELECT COUNT(*) INTO total_records FROM contenido;

    -- Si solo hay un registro, impedir la eliminación
    IF total_records = 1 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No se puede eliminar el único registro de la tabla contenido';
    END IF;
END$$

DELIMITER ;
/* end Evita que elimina el unico dato de la tabla contenido*/

/* ------------------------------- examen tutor ---------------------------------------------- */

DELIMITER //
CREATE TRIGGER validar_tutor_antes_insert_examen
BEFORE INSERT ON examen
FOR EACH ROW
BEGIN
    DECLARE tipo_usuario_actual ENUM('Tutor', 'Estudiante');
    
    -- Obtener el tipo de usuario del tutor que intenta crear el examen
    SELECT tipo_usuario INTO tipo_usuario_actual
    FROM usuario
    WHERE id_usuario = NEW.id_tutor;
    
    -- Verificar si el usuario es un tutor
    IF tipo_usuario_actual != 'Tutor' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Solo los tutores pueden crear exámenes';
    END IF;
END //
DELIMITER ;
select * from examen;
INSERT INTO examen (titulo, descripcion, contenido, id_tutor)VALUES ('Examen de Historia', 'Examen para evaluar conocimientos básicos de historia', '{"preguntas":[{"id_pregunta":1,"texto_pregunta":"¿Quién descubrió América?","puntaje":5,"correct_answer":"Cristóbal Colón"}]}', 91);


/* ------------------------------- end examen tutor-------------------------------------------------*/

select contenido_sobre,nuestro_servicio,portafolio from contenido WHERE id_usuario_contenido;

/* ---------------------------------------------------------------------------- */

DELIMITER //

CREATE TRIGGER validar_estudiante_antes_insert_respuesta
BEFORE INSERT ON respuesta_estudiante
FOR EACH ROW
BEGIN
    DECLARE tipo_usuario_actual ENUM('Tutor', 'Estudiante');
    
    -- Obtener el tipo de usuario del estudiante que intenta insertar la respuesta
    SELECT tipo_usuario INTO tipo_usuario_actual
    FROM usuario
    WHERE id_usuario = NEW.id_estudiante;
    
    -- Verificar si el usuario es un estudiante
    IF tipo_usuario_actual != 'Estudiante' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Solo los estudiantes pueden responder a un examen';
    END IF;
    
    -- Verificar que el examen exista y esté disponible para responder
    IF NOT EXISTS (
        SELECT 1 FROM examen WHERE id_examen = NEW.id_examen
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El examen especificado no existe o no está disponible';
    END IF;
END //

DELIMITER ;

/* -----------------------------------------------------------------------------------*/

UPDATE contenido 
SET 
    contenido_sobre = '{"holas":"hola"}', 
    nuestro_servicio = '{"holas":"hola"}', 
    portafolio = '{"holas":"hola"}' 
WHERE id_usuario_contenido = 91;

UPDATE contenido SET contenido_sobre = ?, nuestro_servicio = ?, portafolio = ? WHERE id_usuario_contenido = ?;
/* -----------------------------------------------------------------------------------------------------------*/
create table programa(
id_programa int   not null primary key auto_increment,
nombre_programa varchar(45) not null,
fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
programa_usuario int not null,
foreign key(programa_usuario) references usuario (id_usuario)
)
select * from usuario;
select * from sesion;
SELECT 
    u.id_usuario,   
    u.nombre,
    u.apellido,
    u.correo,   
    s.fecha_sesion,
    s.estado
FROM 
    usuario AS u
INNER JOIN 
    sesion AS s
ON 
    u.id_usuario = s.sesion_usuario
WHERE 
    u.tipo_usuario = 'Estudiante' AND s.estado = 1;
    
/* -------------------------------------------------------------------------------------------------------------------------------------------*/
SELECT 
    s.estado,
    COUNT(u.id_usuario) AS cantidad_estudiantes
FROM 
    usuario AS u
INNER JOIN 
    sesion AS s
ON 
    u.id_usuario = s.sesion_usuario
WHERE 
    u.tipo_usuario = 'Estudiante'
GROUP BY 
    s.estado;
/*---------------------------------------------------------------------------------------------------------------------------------------*/


SELECT 
    CASE 
        WHEN s.estado = 1 THEN 'Conectados'
        ELSE 'No Conectados'
    END AS estado_conexion,
    COUNT(u.id_usuario) AS cantidad_estudiantes
FROM 
    usuario AS u
INNER JOIN 
    sesion AS s
ON 
    u.id_usuario = s.sesion_usuario
WHERE 
    u.tipo_usuario = 'Estudiante'
GROUP BY 
    s.estado;

select u.id_usuario ,u.indentificacion, u.nombre, u.apellido, u.correo, s.fecha_sesion, s.estado from usuario AS u inner join sesion AS s on u.id_usuario = s.sesion_usuario where  u.tipo_usuario = 'Estudiante';
select * from usuario;
SELECT 
    u.id_usuario,
    u.indentificacion,
    u.nombre AS nombre,
    u.apellido AS apellido,
    u.correo,
    DATE_FORMAT(s.fecha_sesion, '%M %d %Y %r') AS fecha,
    s.estado
FROM 
    usuario AS u
INNER JOIN 
    (SELECT 
         sesion_usuario, 
         MAX(fecha_sesion) AS fecha_sesion
     FROM 
         sesion
     GROUP BY 
         sesion_usuario
    ) AS s_max 
ON 
    u.id_usuario = s_max.sesion_usuario
INNER JOIN 
    sesion AS s 
ON 
    s.sesion_usuario = s_max.sesion_usuario AND s.fecha_sesion = s_max.fecha_sesion
WHERE 
    u.tipo_usuario = 'Estudiante'


