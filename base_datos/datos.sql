USE correspondencia;

INSERT INTO `areas`(`id_area`,`nombre_area`,`descripcion_area`) VALUES (1,'Secretaria','DESCRIPCION'),
(2,'contabilidad','DESCRIPCION'),
(3,'juridica','DESCRIPCION'),
(4,'auditoria','DESCRIPCION'),
(5,'sistemas','DESCRIPCION'),
(6,'cartera','DESCRIPCION'),
(100,'TERMINADO','DESCRIPCION');

INSERT INTO `roles`(`id_rol`,`nombre_rol`) VALUES (1,'Administrador'),
(2,'USUARIO_NORMAL');

INSERT INTO `usuarios`(`ci`,`nombre_usuario`,`apellido_paterno`,`apellido_materno`,`username`,`contrasena`,`correo`,`id_area_usuario`,`id_rol_usuario`) VALUES (12863500,'CARLOS','YUJRA','CHAMBI','elcarlosardilla','Ardillita1*','elcarlosardilla@gmail.com',1,1),
(12863501,'MICHELLE','VARGAS','FLORES','elmichelleardilla','Ardillita1*','elmichelleardilla@gmail.com',2,2),
(12863502,'KEVIN','URUCHI','COARITE','elkevinardilla','Ardillita1*','elkevinardilla@gmail.com',3,2),
(12863503,'JUAN','APAZA','GUTIERREZ','eljuanardilla','Ardillita1*','eljuanardilla@gmail.com',4,2),
(12863504,'BORIS','SOTO','CHAMBI','elborisardilla','Ardillita1*','elborisardilla@gmail.com',5,2),
(12863505,'ARACELY','PLATA','CHOQUE','elaracelyardilla','Ardillita1*','elaracelyardilla@gmail.com',6,2),
(12863506,'KARLA','YUJRA','CHAMBI','elkarlaardilla','Ardillita1*','elkarlaardilla@gmail.com',1,2),
(12863507,'ADMIN','ADMIN','ADMIN','admin','Ardillita1*','admin@gmail.com',100,1);

INSERT INTO `tipo_procedimiento`(`id_tipo_procedimiento`,`nombre_tipo_procedimiento`,`descripcion_tipo_procedimiento`) VALUES (1,'PRESTAMO','SOLICITUD DE PRESTAMO'),
(2,'RETIRO','SOLICITUD DE RETIRO'),
(3,'CREDITO','SOLICITUD DE CREDITO');

INSERT INTO `estados_flujos`(`id_estados_flujos`,`descripcion_estados_flujos`) VALUES (1,'Pendiente'),
(2,'Completado'),
(3,'Cerrado');

INSERT INTO `procedimiento`(`codigo_hoja_ruta`,`fecha_creada`,`solicitante`,`descripcion_solicitud`,`id_area_creada`,`id_tipo_procedimiento_realizado`) VALUES (0,'','','',1,1);
