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
)



