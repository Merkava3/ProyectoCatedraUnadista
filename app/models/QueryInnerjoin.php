<?php
$UserQuerySesion = "select fecha_sesion, estado, usuario.indentificacion, usuario.nombre, usuario.apellido, usuario.correo, usuario.tipo_usuario from sesion inner join usuario on sesion.sesion_usuario = usuario.id_usuario where usuario.tipo_usuario = 'Tutor'";
?>