CREATE DATABASE correspondencia;

USE correspondencia;

-- CREANDO TABLAS

CREATE TABLE areas(
    id_area INT NOT NULL,
    nombre_area VARCHAR(30),
    descripcion_area VARCHAR(100)
);

ALTER TABLE areas
    ADD PRIMARY KEY (id_area);

CREATE TABLE roles(
    id_rol INT NOT NULL,
    nombre_rol VARCHAR(30)
);

ALTER TABLE roles
    ADD PRIMARY KEY (id_rol);

CREATE TABLE usuarios(
    ci INT NOT NULL,
    nombre_usuario VARCHAR(30),
    apellido_paterno VARCHAR(15),
    apellido_materno VARCHAR(15),
    username VARCHAR(30),
    contrasena VARCHAR(30),
    correo VARCHAR(30),
    id_area_usuario INT NOT NULL,
    id_rol_usuario INT NOT NULL,
    FOREIGN KEY (id_area_usuario) REFERENCES areas(id_area),
    FOREIGN KEY (id_rol_usuario) REFERENCES roles(id_rol)
);

ALTER TABLE usuarios
    ADD PRIMARY KEY (ci);

CREATE TABLE tipo_documento(
    id_tipo_documento INT NOT NULL,
    nombre_documento VARCHAR(30),
    descripcion_documento VARCHAR(100)
);

ALTER TABLE tipo_documento
    ADD PRIMARY KEY (id_tipo_documento);

CREATE TABLE tipo_procedimiento(
    id_tipo_procedimiento INT NOT NULL,
    nombre_tipo_procedimiento VARCHAR(30),
    descripcion_tipo_procedimiento VARCHAR(100)
);

ALTER TABLE tipo_procedimiento
    ADD PRIMARY KEY (id_tipo_procedimiento);

CREATE TABLE procedimiento(
    id_procedimiento INT NOT NULL,
    codigo_hoja_ruta INT NOT NULL,
    fecha_creada DATE,
    solicitante VARCHAR(100),
    descripcion_solicitud VARCHAR(200),
    id_area_creada INT NOT NULL,
    id_tipo_procedimiento_realizado INT NOT NULL,
    fecha_subido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    gestion INT NOT NULL DEFAULT 2022,
    FOREIGN KEY (id_area_creada) REFERENCES areas(id_area),
    FOREIGN KEY (id_tipo_procedimiento_realizado) REFERENCES tipo_procedimiento(id_tipo_procedimiento)
);

ALTER TABLE procedimiento
    ADD PRIMARY KEY (id_procedimiento);

ALTER TABLE procedimiento
    MODIFY id_procedimiento INT NOT NULL AUTO_INCREMENT;


-- ALTERNATIVA A FLUJO DE PROCEDIMIENTOS

CREATE TABLE estados_flujos(
    id_estados_flujos INT NOT NULL,
    descripcion_estados_flujos VARCHAR(20)
);

ALTER TABLE estados_flujos
    ADD PRIMARY KEY (id_estados_flujos);

CREATE TABLE flujo_procedimiento(
    id_flujo INT NOT NULL,
    id_procedimiento_flujo INT NOT NULL,
    id_area_procedencia INT NOT NULL,
    id_area_destino INT NOT NULL,
    id_usuario_envia INT NOT NULL,
    observaciones TEXT,
    fecha_subido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado_rev INT NOT NULL DEFAULT 1,
    id_flujo_padre INT DEFAULT 0,
    FOREIGN KEY (id_procedimiento_flujo) REFERENCES procedimiento(id_procedimiento),
    FOREIGN KEY (id_area_procedencia) REFERENCES areas(id_area),
    FOREIGN KEY (id_area_destino) REFERENCES areas(id_area),
    FOREIGN KEY (id_usuario_envia) REFERENCES usuarios(ci),
    FOREIGN KEY (estado_rev) REFERENCES estados_flujos(id_estados_flujos)
   -- FOREIGN KEY (id_flujo_padre) REFERENCES flujo_procedimiento(id_flujo)
);

-- FIN DE LA ALTERNATIVA

ALTER TABLE flujo_procedimiento
    ADD PRIMARY KEY (id_flujo);

ALTER TABLE flujo_procedimiento
    MODIFY id_flujo INT NOT NULL AUTO_INCREMENT;

CREATE TABLE documento_subido(
    id_documento_subido INT NOT NULL,
    nombre_archivo VARCHAR(100),
    directorio VARCHAR(300),
    fecha_subido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_usuario_subido INT NOT NULL,
    id_procedimiento_subido INT NOT NULL,
    id_flujo_subido INT NOT NULL,
    FOREIGN KEY (id_flujo_subido) REFERENCES flujo_procedimiento(id_flujo),
    FOREIGN KEY (id_usuario_subido) REFERENCES usuarios(ci),
    FOREIGN KEY (id_procedimiento_subido) REFERENCES procedimiento(id_procedimiento)
);

ALTER TABLE documento_subido
    ADD PRIMARY KEY (id_documento_subido);

ALTER TABLE documento_subido
    MODIFY id_documento_subido INT NOT NULL AUTO_INCREMENT;



CREATE TABLE modificaciones(
    id_modificacion INT NOT NULL,
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    descripcion_modificacion TEXT,
    id_estado_modificacion INT NOT NULL DEFAULT 5,
    id_flujo_modificacion INT NOT NULL,
    id_usuario_modificacion INT NOT NULL,
    FOREIGN KEY (id_flujo_modificacion) REFERENCES flujo_procedimiento(id_flujo),
    FOREIGN KEY (id_usuario_modificacion) REFERENCES usuarios(ci),
    FOREIGN KEY (id_estado_modificacion) REFERENCES estados_flujos(id_estados_flujos)
);

ALTER TABLE modificaciones
    ADD PRIMARY KEY (id_modificacion);

ALTER TABLE modificaciones
    MODIFY id_modificacion INT NOT NULL AUTO_INCREMENT;





-- SELECT * FROM flujo_procedimiento WHERE id_area_procedencia!=2 AND id_area_destino=2;



-- SELECT * FROM flujo_procedimiento WHERE id_area_procedencia!=2 AND id_area_destino=2;

-- CREACION DE VISTA PARA VARIAS AREAS ENVIADAS

CREATE VIEW `procedencia_documentos` AS
SELECT flujo_procedimiento.id_flujo AS id_flujo_prc, procedimiento.codigo_hoja_ruta, tipo_procedimiento.nombre_tipo_procedimiento, procedimiento.solicitante, areas.nombre_area, usuarios.nombre_usuario, flujo_procedimiento.id_area_procedencia, flujo_procedimiento.id_area_destino, procedimiento.descripcion_solicitud, flujo_procedimiento.fecha_subido, procedimiento.gestion, flujo_procedimiento.id_flujo_padre
FROM flujo_procedimiento INNER JOIN procedimiento ON flujo_procedimiento.id_procedimiento_flujo = procedimiento.id_procedimiento
INNER JOIN areas ON flujo_procedimiento.id_area_procedencia = areas.id_area
INNER JOIN tipo_procedimiento ON procedimiento.id_tipo_procedimiento_realizado = tipo_procedimiento.id_tipo_procedimiento
INNER JOIN usuarios ON flujo_procedimiento.id_usuario_envia = usuarios.ci
WHERE flujo_procedimiento.estado_rev = 1;

-- FIN CREACION DE PRUEBA



CREATE VIEW `flujos_procedimientos` AS
SELECT MAX(id_flujo) AS id_flujo_prc, id_procedimiento_flujo, id_area_procedencia, id_area_destino, id_usuario_envia
FROM flujo_procedimiento
GROUP BY id_procedimiento_flujo DESC;

CREATE VIEW `documentos_procedencia`
	AS SELECT flujos_procedimientos.id_flujo_prc, procedimiento.`codigo_hoja_ruta`, tipo_procedimiento.`nombre_tipo_procedimiento`, procedimiento.`solicitante`, areas.`nombre_area`, usuarios.`nombre_usuario`, flujo_procedimiento.id_area_procedencia, flujo_procedimiento.id_area_destino, procedimiento.descripcion_solicitud, flujo_procedimiento.fecha_subido
    FROM `flujos_procedimientos` INNER JOIN `flujo_procedimiento` ON flujo_procedimiento.id_flujo = flujos_procedimientos.id_flujo_prc
    INNER JOIN `procedimiento` ON flujo_procedimiento.id_procedimiento_flujo = procedimiento.id_procedimiento
    INNER JOIN `areas` ON flujo_procedimiento.id_area_procedencia = areas.id_area
    INNER JOIN `tipo_procedimiento` ON procedimiento.id_tipo_procedimiento_realizado = tipo_procedimiento.id_tipo_procedimiento
    INNER JOIN `usuarios` ON flujo_procedimiento.id_usuario_envia = usuarios.ci;

CREATE VIEW `flujo_actual`
    AS SELECT documentos_procedencia.id_flujo_prc, documentos_procedencia.codigo_hoja_ruta, documentos_procedencia.nombre_tipo_procedimiento, documentos_procedencia.solicitante, documentos_procedencia.nombre_area AS area_procedencia, documentos_procedencia.nombre_usuario,areas.nombre_area AS area_destino, documentos_procedencia.fecha_subido, documentos_procedencia.id_area_procedencia, documentos_procedencia.id_area_destino
    FROM `documentos_procedencia` INNER JOIN `areas` ON documentos_procedencia.id_area_destino = areas.id_area;


/*
id_flujo_prc	
codigo_hoja_ruta	
nombre_tipo_procedimiento	
solicitante	
nombre_area	
nombre_usuario	
id_area_procedencia	
id_area_destino	
descripcion_solicitud*/





CREATE VIEW `documentos_destino`
	AS SELECT flujo_procedimiento.`id_flujo` AS id_flujo_prc, procedimiento.`codigo_hoja_ruta`, tipo_procedimiento.`nombre_tipo_procedimiento`, procedimiento.`solicitante`, areas.`nombre_area`, usuarios.`nombre_usuario`, flujo_procedimiento.id_area_procedencia, flujo_procedimiento.id_area_destino, procedimiento.descripcion_solicitud, flujo_procedimiento.fecha_subido, flujo_procedimiento.id_flujo_padre, procedimiento.gestion
    FROM `flujo_procedimiento` INNER JOIN `procedimiento` ON flujo_procedimiento.id_procedimiento_flujo = procedimiento.id_procedimiento
    INNER JOIN `areas` ON flujo_procedimiento.id_area_destino = areas.id_area
    INNER JOIN `usuarios` ON flujo_procedimiento.id_usuario_envia = usuarios.ci
    INNER JOIN `tipo_procedimiento` ON procedimiento.id_tipo_procedimiento_realizado = tipo_procedimiento.id_tipo_procedimiento WHERE flujo_procedimiento.estado_rev!=3 AND flujo_procedimiento.estado_rev!=4 AND id_area_procedencia!=id_area_destino;


CREATE VIEW `documentos_completados`
	AS SELECT flujo_procedimiento.`id_flujo` AS id_flujo_prc, procedimiento.`codigo_hoja_ruta`, tipo_procedimiento.`nombre_tipo_procedimiento`, procedimiento.`solicitante`, areas.`nombre_area`, usuarios.`nombre_usuario`, flujo_procedimiento.id_area_procedencia, flujo_procedimiento.id_area_destino, procedimiento.descripcion_solicitud, flujo_procedimiento.fecha_subido, flujo_procedimiento.id_flujo_padre, procedimiento.gestion
    FROM `flujo_procedimiento` INNER JOIN `procedimiento` ON flujo_procedimiento.id_procedimiento_flujo = procedimiento.id_procedimiento
    INNER JOIN `areas` ON flujo_procedimiento.id_area_procedencia = areas.id_area
    INNER JOIN `usuarios` ON flujo_procedimiento.id_usuario_envia = usuarios.ci
    INNER JOIN `tipo_procedimiento` ON procedimiento.id_tipo_procedimiento_realizado = tipo_procedimiento.id_tipo_procedimiento WHERE flujo_procedimiento.estado_rev=3 AND flujo_procedimiento.id_area_destino=100;

CREATE VIEW `documentos_terminados`
	AS SELECT flujo_procedimiento.`id_flujo` AS id_flujo_prc, procedimiento.`codigo_hoja_ruta`, tipo_procedimiento.`nombre_tipo_procedimiento`, procedimiento.`solicitante`, areas.`nombre_area`, usuarios.`nombre_usuario`, flujo_procedimiento.id_area_procedencia, flujo_procedimiento.id_area_destino, procedimiento.descripcion_solicitud, flujo_procedimiento.fecha_subido, flujo_procedimiento.id_flujo_padre, procedimiento.gestion
    FROM `flujo_procedimiento` INNER JOIN `procedimiento` ON flujo_procedimiento.id_procedimiento_flujo = procedimiento.id_procedimiento
    INNER JOIN `areas` ON flujo_procedimiento.id_area_procedencia = areas.id_area
    INNER JOIN `usuarios` ON flujo_procedimiento.id_usuario_envia = usuarios.ci
    INNER JOIN `tipo_procedimiento` ON procedimiento.id_tipo_procedimiento_realizado = tipo_procedimiento.id_tipo_procedimiento WHERE flujo_procedimiento.estado_rev=4 AND flujo_procedimiento.id_area_destino=100;

CREATE VIEW documentos_modificacion
    AS SELECT modificaciones.id_modificacion, modificaciones.fecha_solicitud, modificaciones.descripcion_modificacion, modificaciones.id_estado_modificacion, modificaciones.id_flujo_modificacion, flujo_procedimiento.id_procedimiento_flujo, usuarios.nombre_usuario, areas.nombre_area, procedimiento.codigo_hoja_ruta, procedimiento.gestion, procedimiento.solicitante, tipo_procedimiento.nombre_tipo_procedimiento, procedimiento.descripcion_solicitud
    FROM modificaciones
    INNER JOIN flujo_procedimiento ON modificaciones.id_flujo_modificacion=flujo_procedimiento.id_flujo
    INNER JOIN procedimiento ON flujo_procedimiento.id_procedimiento_flujo=procedimiento.id_procedimiento
    INNER JOIN tipo_procedimiento ON procedimiento.id_tipo_procedimiento_realizado=tipo_procedimiento.id_tipo_procedimiento
    INNER JOIN usuarios ON modificaciones.id_usuario_modificacion=usuarios.ci
    INNER JOIN areas ON usuarios.id_area_usuario=areas.id_area;




/*
id_flujo_prc	
codigo_hoja_ruta	
nombre_tipo_procedimiento	
solicitante	
nombre_area	
nombre_usuario	
id_area_procedencia	
id_area_destino	
descripcion_solicitud*/