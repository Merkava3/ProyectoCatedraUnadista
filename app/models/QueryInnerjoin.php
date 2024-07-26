<?php
$UserQuerySesion = "select fecha_sesion, estado, usuario.indentificacion, usuario.nombre, usuario.apellido, usuario.correo, usuario.tipo_usuario from sesion inner join usuario on sesion.sesion_usuario = usuario.id_usuario where usuario.tipo_usuario = 'Tutor'";
$Querycontenido = "select contenido_sobre,nuestro_servicio,portafolio from contenido WHERE id_usuario_contenido";
$QueryExamen = "select id_examen, contenido from examen";
$querySession = "UPDATE sesion SET estado = FALSE WHERE sesion_usuario = ? AND estado = TRUE";
$insertSesion = "INSERT INTO sesion (fecha_sesion, estado, sesion_usuario) VALUES (NOW(), TRUE,?)";
$QueryCountUser = "SELECT COUNT(*) AS total_estudiantes FROM usuario WHERE tipo_usuario = 'Estudiante'";
$allStudents = "select * from usuario where  tipo_usuario = 'Estudiante'";
$Online_Count_student = "SELECT 
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
";
$QueryTableData = "SELECT 
    u.id_usuario,
    u.indentificacion,
    u.nombre AS nombre,
    u.apellido AS apellido,
    u.correo,
    DATE_FORMAT(s.fecha_sesion, '%M %d %Y %r') AS fecha_sesion,
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
";
?>