<?php
$UserQuerySesion = "select fecha_sesion, estado, usuario.indentificacion, usuario.nombre, usuario.apellido, usuario.correo, usuario.tipo_usuario from sesion inner join usuario on sesion.sesion_usuario = usuario.id_usuario where usuario.tipo_usuario = 'Tutor'";
$Querycontenido = "select contenido_sobre,nuestro_servicio,portafolio from contenido WHERE id_usuario_contenido";
$QueryExamen = "select id_examen, contenido from examen";
$querySession = "UPDATE sesion SET estado = FALSE WHERE sesion_usuario = ? AND estado = TRUE";
$insertSesion = "INSERT INTO sesion (fecha_sesion, estado, sesion_usuario) VALUES (NOW(), TRUE,?)";
?>