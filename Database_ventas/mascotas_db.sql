-- crear base de datos si no existe
create database if not exists mascotas_db
  default character set utf8mb4
  collate utf8mb4_general_ci;

use mascotas_db;

/*!40101 set @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 set @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 set @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 set NAMES utf8mb4 */;
/*!40103 set @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 set TIME_ZONE='+00:00' */;
/*!40014 set @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 set @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 set @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 set @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- ------------------------------------------------------
-- tabla clientes
-- ------------------------------------------------------

drop table if exists clientes;
/*!40101 set @saved_cs_client = @@character_set_client */;
/*!50503 set character_set_client = utf8mb4 */;
create table clientes (
  id_cliente int not null auto_increment,
  nombre     varchar(100) not null,
  apellido   varchar(100) not null,
  email      varchar(100) default null,
  telefono   varchar(15)  default null,
  primary key (id_cliente),
  unique key email (email)
) engine=innodb auto_increment=3
  default charset=utf8mb4
  collate=utf8mb4_general_ci;
/*!40101 set character_set_client = @saved_cs_client */;

lock tables clientes write;
/*!40000 alter table clientes disable keys */;
insert into clientes
  (id_cliente, nombre, apellido, email, telefono)
values
  (1,'Ana','Gomez','ana@correo.com','987654321'),
  (2,'Luis','Torres','luis@correo.com','987123456');
/*!40000 alter table clientes enable keys */;
unlock tables;

-- ------------------------------------------------------
-- tabla productos
-- ------------------------------------------------------

drop table if exists productos;
/*!40101 set @saved_cs_client = @@character_set_client */;
/*!50503 set character_set_client = utf8mb4 */;
create table productos (
  id_producto     int not null auto_increment,
  nombre_producto varchar(100) not null,
  precio          decimal(10,2) not null,
  stock           int default 0,
  primary key (id_producto)
) engine=innodb auto_increment=4
  default charset=utf8mb4
  collate=utf8mb4_general_ci;
/*!40101 set character_set_client = @saved_cs_client */;

lock tables productos write;
/*!40000 alter table productos disable keys */;
insert into productos
  (id_producto, nombre_producto, precio, stock)
values
  (1,'Comida para Perro 2kg',45.50,50),
  (2,'Juguete Hueso Goma',15.00,30),
  (3,'Arena para Gato 5kg',70.00,40);
/*!40000 alter table productos enable keys */;
unlock tables;

-- ------------------------------------------------------
-- tabla ventas
-- ------------------------------------------------------

drop table if exists ventas;
/*!40101 set @saved_cs_client = @@character_set_client */;
/*!50503 set character_set_client = utf8mb4 */;
create table ventas (
  id_venta   int not null auto_increment,
  id_cliente int not null,
  fecha      datetime default current_timestamp,
  total      decimal(10,2) not null,
  primary key (id_venta),
  key id_cliente (id_cliente),
  constraint ventas_ibfk_1 foreign key (id_cliente)
    references clientes (id_cliente)
) engine=innodb auto_increment=3
  default charset=utf8mb4
  collate=utf8mb4_general_ci;
/*!40101 set character_set_client = @saved_cs_client */;

lock tables ventas write;
/*!40000 alter table ventas disable keys */;
insert into ventas
  (id_venta, id_cliente, fecha, total)
values
  (1,1,'2025-11-15 14:12:41',60.50),
  (2,2,'2025-11-15 14:12:41',70.00);
/*!40000 alter table ventas enable keys */;
unlock tables;

-- ------------------------------------------------------
-- tabla detalle_venta
-- ------------------------------------------------------

drop table if exists detalle_venta;
/*!40101 set @saved_cs_client = @@character_set_client */;
/*!50503 set character_set_client = utf8mb4 */;
create table detalle_venta (
  id_detalle      int not null auto_increment,
  id_venta        int not null,
  id_producto     int not null,
  cantidad        int not null,
  precio_unitario decimal(10,2) not null,
  primary key (id_detalle),
  key id_venta (id_venta),
  key id_producto (id_producto),
  constraint detalle_venta_ibfk_1 foreign key (id_venta)
    references ventas (id_venta),
  constraint detalle_venta_ibfk_2 foreign key (id_producto)
    references productos (id_producto)
) engine=innodb auto_increment=4
  default charset=utf8mb4
  collate=utf8mb4_general_ci;
/*!40101 set character_set_client = @saved_cs_client */;

lock tables detalle_venta write;
/*!40000 alter table detalle_venta disable keys */;
insert into detalle_venta
  (id_detalle, id_venta, id_producto, cantidad, precio_unitario)
values
  (1,1,1,1,45.50),
  (2,1,2,1,15.00),
  (3,2,3,1,70.00);
/*!40000 alter table detalle_venta enable keys */;
unlock tables;

-- fin
/*!40103 set TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 set SQL_MODE=@OLD_SQL_MODE */;
/*!40014 set FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 set UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 set CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 set CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 set COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 set SQL_NOTES=@OLD_SQL_NOTES */;

-- ------------------------------------------------------
-- tabla usuarios
-- ------------------------------------------------------

create table if not exists usuarios (
  id_usuario int not null auto_increment primary key,
  nombre     varchar(100) not null,
  usuario    varchar(50)  not null unique,
  password   varchar(255) not null,
  rol        enum('admin','empleado') not null default 'empleado'
) engine=innodb
  default charset=utf8mb4
  collate=utf8mb4_general_ci;

insert into usuarios (nombre, usuario, password, rol) values
('administrador general', 'admin', md5('admin123'), 'admin'),
('vendedor caja 1', 'empleado1', md5('empleado123'), 'empleado');


-- ------------------------------------------------------
-- tabla recordatorios
-- ------------------------------------------------------

create table if not exists recordatorios (
  id_recordatorio   int not null auto_increment primary key,
  id_cliente        int not null,
  fecha_programada  datetime not null,
  motivo            varchar(150) not null,
  canal             enum('email','sms','whatsapp') not null default 'email',
  estado            enum('pendiente','enviado','cancelado') not null default 'pendiente',
  creado_en         datetime not null default current_timestamp,
  constraint fk_recordatorios_cliente
    foreign key (id_cliente) references clientes(id_cliente)
) engine=innodb
  default charset=utf8mb4
  collate=utf8mb4_general_ci;

select * from clientes;
Select * from recordatorios;

Select * from productos;