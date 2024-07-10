use catedra;
select * from usuario;
create table usuario(
id_usuario int auto_increment primary key not null,
identifacion varchar(45) not null,
nombre_usuario varchar(45) not null,
apellido_usuario varchar(45) not null,
genero enum("Masculino","Femenino") not null,
correo varchar(80) not null,
/* imagen blob not null, */
tipo_usuario enum("Tutor", "Estudiante"),
contraseña varchar(16) not null
);
create table sesion(
id_sesion int auto_increment primary key null,
fecha_sesion datetime not null,
estado tinyint not null,
sesion_usuario int not null,
foreign key(sesion_usuario) references usuario(id_usuario)
);
create table contenido (
id_contenido int auto_increment null primary key,
contenido_sobre json not null,
nuestro_servicio json not null,
portafolio json not null,
id_usuario_contenido int not null,
foreign key(id_usuario_contenido) references usuario(id_usuario)
);


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
select contenido_sobre,nuestro_servicio,portafolio from contenido WHERE id_usuario_contenido;



UPDATE contenido 
SET 
    contenido_sobre = '{"holas":"hola"}', 
    nuestro_servicio = '{"holas":"hola"}', 
    portafolio = '{"holas":"hola"}' 
WHERE id_usuario_contenido = 91;

UPDATE contenido SET contenido_sobre = ?, nuestro_servicio = ?, portafolio = ? WHERE id_usuario_contenido = ?
