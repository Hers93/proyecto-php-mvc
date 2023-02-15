
CREATE DATABASE Inventario;

USE Inventario;

create table productos (
    id_producto  int AUTO_INCREMENT,
    sku varchar(250),
    descripcion varchar(500),
    marca varchar(250),
    color varchar(250),
    precio float,
    CONSTRAINT pk_idProducto_producto PRIMARY KEY (id_producto));

create table almancen_tipo(
    id_almacen_tipo int AUTO_INCREMENT,
    nombre_almacen varchar(255),
    constraint pk_id_almance_tipo_almacen primary key (id_almacen_tipo));

create table almacen(
    id_almacen int AUTO_INCREMENT,
    nombre_almacen varchar(255),
    localizacion varchar(255),
    responsable varchar(255),
    id_tipo_almacen int,
    constraint pk_id_almacen_almacen primary key(id_almacen),
    constraint fk_id_tipo_almacen foreign key (id_tipo_almacen) references almancen_tipo (id_almacen_tipo)
    );

create table existencias (
	id_existencia int auto_increment,
    id_producto int,
    id_almacen int,
    existencia float,
    constraint pk_id_existentias primary key (id_existencia),
    constraint fk_id_producto foreign key (id_producto) references productos(id_producto),
    constraint fk_id_almacen foreign key (id_almacen) references almacen (id_almacen)
);

/** CATALOGO*/
insert into almancen_tipo (nombre_almacen) values ('Virtual');
insert into almancen_tipo (nombre_almacen) values ('Fisico');
/** INSERT PRODUCTOS*/
insert into productos (sku, descripcion, marca, color, precio) values ('3045PRO','BOTE DE PINTURA','COMEX','AZUL',40.00);
insert into productos (sku, descripcion, marca, color, precio) values ('30DFFEG','BOTE DE ACEITE MOVIL','MOVIL','GRIS',50.00);
insert into productos (sku, descripcion, marca, color, precio) values ('3042DFD','BOTE DE ACEITE REPSOL','REPSOL','NEGRO',50.00);
insert into productos (sku, descripcion, marca, color, precio) values ('30DSF45','BOTE DE ACEITE REPSOL PREMIUM','REPSOL','NEGRP',90.00);
insert into productos (sku, descripcion, marca, color, precio) values ('3FSDF4T','BOTE DE ACEITE SUELTO','SM','NEGRO',20.00);
insert into productos (sku, descripcion, marca, color, precio) values ('30DSF44',' BUJIAS','PREMIUM','GRIS',20.00);
insert into productos (sku, descripcion, marca, color, precio) values ('3DSFASD','BANDA','BANDA','CAFE',80.00);
insert into productos (sku, descripcion, marca, color, precio) values ('30FDWSR','ANTICONGELANTE ','COMEX','AZUL',90.00);
insert into productos (sku, descripcion, marca, color, precio) values ('30324DW','ANTICONGELANTE MAX','COMEX','AZUL',130.00);
insert into productos (sku, descripcion, marca, color, precio) values ('3042134','JUEGO DE LLAVES','TRUPER','NARANJA',234.00);
insert into productos (sku, descripcion, marca, color, precio) values ('30FSDF4','BOTE DE ACEITE MOVIL MAX','COMEX','ROJO',560.00);

/** INSERT ALMACEN FISICO*/
insert into almacen (nombre_almacen, localizacion, responsable, id_tipo_almacen) values ('ZONA CENTRO', 'PALO ALTO #134 COL. LOS MANANTIALES','ROCIO GASCA',1);
insert into almacen (nombre_almacen, localizacion, responsable, id_tipo_almacen) values ('ZONA SUR', 'JACARANDAS #34 COL. GIRASOLES','PEDRO PAZ',1);
insert into almacen (nombre_almacen, localizacion, responsable, id_tipo_almacen) values ('ZONA ESTE', 'LOS PINOS #3 COL. AGUA AZUL','FERNANDO GASCA',1);
insert into almacen (nombre_almacen, localizacion, responsable, id_tipo_almacen) values ('ZONA OESTE', 'ROSARIO #98 COL. JITOMATES','ELIZABETH CRUZ',1);
insert into almacen (nombre_almacen, localizacion, responsable, id_tipo_almacen) values ('ZONA NORTE', 'AV.PRINCIPAL #123 COL. LOS AZUFRES','JUAN PEREZ',1);

/** INSERT ALMACEN VIRTUAL*/
insert into almacen (nombre_almacen, localizacion, responsable, id_tipo_almacen) values ('ZONA CENTRO', 'PALO ALTO #134 COL. LOS MANANTIALES','ROCIO GASCA',2);
insert into almacen (nombre_almacen, localizacion, responsable, id_tipo_almacen) values ('ZONA SUR', 'JACARANDAS #34 COL. GIRASOLES','PEDRO PAZ',2);
insert into almacen (nombre_almacen, localizacion, responsable, id_tipo_almacen) values ('ZONA ESTE', 'LOS PINOS #3 COL. AGUA AZUL','FERNANDO GASCA',2);
insert into almacen (nombre_almacen, localizacion, responsable, id_tipo_almacen) values ('ZONA OESTE', 'ROSARIO #98 COL. JITOMATES','ELIZABETH CRUZ',2);
insert into almacen (nombre_almacen, localizacion, responsable, id_tipo_almacen) values ('ZONA NORTE', 'AV.PRINCIPAL #123 COL. LOS AZUFRES','JUAN PEREZ',2);
/** INSERT EXISTENCIAS*/
insert into existencias(id_almacen,id_producto,existencia) values(1,1,2000);
insert into existencias(id_almacen,id_producto,existencia) values(6,1,200);