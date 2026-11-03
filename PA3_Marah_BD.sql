-- ============================================================
-- PROYECTO: MARAH - Sitio web para la venta de kefir
-- PA3 - Programacion Web Basica | ISIL | Periodo 2026-20
-- Motor: MySQL 8.0 | Motor de almacenamiento: InnoDB
-- Profesor: LUIGGUI ENRIQUE YNGA BECERRA
-- Alumno: ADRIAN MOISES BASURCO CASTRO
-- ============================================================

-- Ejecutar este script en MySQL Workbench o un cliente compatible.
-- IMPORTANTE: si ya existe una base de datos llamada marah_db,
-- se eliminara y se creara nuevamente desde cero.

DROP DATABASE IF EXISTS marah_db;
CREATE DATABASE marah_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE marah_db;

-- 1. TABLA: categorias
-- Almacena las categorias de los productos de Marah.

CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(60) NOT NULL,
    descripcion VARCHAR(255),
    activo TINYINT(1) NOT NULL DEFAULT 1,
    CONSTRAINT uq_categorias_nombre UNIQUE (nombre),
    CONSTRAINT chk_categorias_activo CHECK (activo IN (0, 1))
) ENGINE = InnoDB;

-- 2. TABLA: productos
-- Almacena las presentaciones, precios y stock del catalogo.

CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    id_categoria INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255),
    presentacion VARCHAR(50) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_productos_categorias
        FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT chk_productos_precio CHECK (precio > 0),
    CONSTRAINT chk_productos_stock CHECK (stock >= 0),
    CONSTRAINT chk_productos_activo CHECK (activo IN (0, 1))
) ENGINE = InnoDB;

CREATE INDEX idx_productos_categoria ON productos(id_categoria);
CREATE INDEX idx_productos_nombre ON productos(nombre);

-- 3. TABLA: clientes
-- Almacena los datos de las personas que realizan pedidos.

CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(80) NOT NULL,
    apellidos VARCHAR(80) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    correo VARCHAR(120),
    direccion VARCHAR(180),
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_clientes_telefono UNIQUE (telefono),
    CONSTRAINT uq_clientes_correo UNIQUE (correo)
) ENGINE = InnoDB;

-- 4. TABLA: pedidos
-- Registra los pedidos realizados por los clientes.

CREATE TABLE pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    fecha_pedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    direccion_entrega VARCHAR(180) NOT NULL,
    estado ENUM(
        'Pendiente',
        'Confirmado',
        'Preparando',
        'Enviado',
        'Entregado',
        'Cancelado'
    ) NOT NULL DEFAULT 'Pendiente',
    total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    observaciones VARCHAR(255),
    CONSTRAINT fk_pedidos_clientes
        FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT chk_pedidos_total CHECK (total >= 0)
) ENGINE = InnoDB;

CREATE INDEX idx_pedidos_cliente ON pedidos(id_cliente);
CREATE INDEX idx_pedidos_fecha ON pedidos(fecha_pedido);

-- 5. TABLA: detalle_pedido
-- Registra los productos, cantidades y precios de cada pedido.

CREATE TABLE detalle_pedido (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_detalle_pedidos
        FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_detalle_productos
        FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT uq_detalle_pedido_producto UNIQUE (id_pedido, id_producto),
    CONSTRAINT chk_detalle_cantidad CHECK (cantidad > 0),
    CONSTRAINT chk_detalle_precio CHECK (precio_unitario > 0),
    CONSTRAINT chk_detalle_subtotal CHECK (subtotal > 0)
) ENGINE = InnoDB;

CREATE INDEX idx_detalle_producto ON detalle_pedido(id_producto);

-- 6. TABLA: contactos
-- Almacena los mensajes enviados desde el formulario de contacto.

CREATE TABLE contactos (
    id_contacto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(120) NOT NULL,
    telefono VARCHAR(20),
    asunto VARCHAR(120) NOT NULL,
    mensaje TEXT NOT NULL,
    fecha_envio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('Nuevo', 'En revision', 'Atendido')
        NOT NULL DEFAULT 'Nuevo'
) ENGINE = InnoDB;

CREATE INDEX idx_contactos_fecha ON contactos(fecha_envio);
CREATE INDEX idx_contactos_estado ON contactos(estado);

-- 7. DATOS DE PRUEBA
-- Se insertan como maximo 10 registros por tabla.

-- Categorias: 3 registros
INSERT INTO categorias (nombre, descripcion) VALUES
('Bebidas', 'Kefir de leche listo para consumir.'),
('Sabores', 'Kefir de leche con diferentes sabores.'),
('Cultivo', 'Granos de kefir para preparar en casa.');

-- Productos: 5 registros
INSERT INTO productos
(id_categoria, nombre, descripcion, presentacion, precio, stock) VALUES
(1, 'Kefir natural', 'Kefir de leche de sabor natural.', '500 ml', 15.00, 12),
(1, 'Kefir natural familiar', 'Presentacion familiar de kefir natural.', '1 litro', 28.00, 10),
(2, 'Kefir de fresa', 'Kefir de leche con sabor a fresa.', '500 ml', 20.00, 8),
(2, 'Kefir de arandanos', 'Kefir de leche con sabor a arandanos.', '500 ml', 22.00, 7),
(3, 'Granos de kefir', 'Granos de cultivo para preparar kefir en casa.', '20 gramos', 18.00, 15);

-- Clientes: 4 registros
INSERT INTO clientes
(nombres, apellidos, telefono, correo, direccion) VALUES
('Lucia', 'Ramirez Soto', '999111222', 'lucia.ramirez@email.com', 'Miraflores, Lima'),
('Carlos', 'Mendoza Ruiz', '999222333', 'carlos.mendoza@email.com', 'Surco, Lima'),
('Valeria', 'Torres Diaz', '999333444', 'valeria.torres@email.com', 'San Borja, Lima'),
('Jorge', 'Paredes Leon', '999444555', 'jorge.paredes@email.com', 'San Miguel, Lima');

-- Pedidos: 2 registros de prueba
INSERT INTO pedidos
(id_cliente, fecha_pedido, direccion_entrega, estado, total, observaciones) VALUES
(1, '2026-10-20 10:30:00', 'Miraflores, Lima', 'Entregado', 50.00, 'Entrega realizada correctamente.'),
(2, '2026-10-21 15:00:00', 'Surco, Lima', 'Preparando', 28.00, 'Coordinar horario de entrega.');

-- Detalles: 3 registros de prueba
INSERT INTO detalle_pedido
(id_pedido, id_producto, cantidad, precio_unitario, subtotal) VALUES
(1, 1, 2, 15.00, 30.00),
(1, 3, 1, 20.00, 20.00),
(2, 2, 1, 28.00, 28.00);

-- Contactos: 2 registros de prueba
INSERT INTO contactos
(nombre, correo, telefono, asunto, mensaje, estado) VALUES
('Ana Flores', 'ana.flores@email.com', '988111222',
 'Consulta sobre el kefir', 'Deseo conocer los beneficios y la conservacion del producto.', 'Nuevo'),
('Pedro Salas', 'pedro.salas@email.com', '988222333',
 'Disponibilidad', 'Quisiera saber si tienen granos de kefir disponibles.', 'Atendido');

-- 8. PROCEDIMIENTO ALMACENADO
-- Registra un pedido de un producto, valida el stock,
-- guarda el detalle, descuenta las unidades y calcula el total.

DELIMITER //

CREATE PROCEDURE sp_registrar_pedido (
    IN p_id_cliente INT,
    IN p_id_producto INT,
    IN p_cantidad INT,
    IN p_direccion_entrega VARCHAR(180),
    IN p_observaciones VARCHAR(255),
    OUT p_id_pedido INT
)
BEGIN
    DECLARE v_stock INT;
    DECLARE v_precio DECIMAL(10,2);
    DECLARE v_subtotal DECIMAL(10,2);
    DECLARE v_existe_cliente INT DEFAULT 0;
    DECLARE v_existe_producto INT DEFAULT 0;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    -- Validar que la cantidad sea positiva.
    IF p_cantidad IS NULL OR p_cantidad <= 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'La cantidad debe ser mayor que cero.';
    END IF;

    -- Validar que el cliente exista.
    SELECT COUNT(*) INTO v_existe_cliente
    FROM clientes
    WHERE id_cliente = p_id_cliente;

    IF v_existe_cliente = 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'El cliente indicado no existe.';
    END IF;

    -- Validar que el producto exista y este activo.
    SELECT COUNT(*) INTO v_existe_producto
    FROM productos
    WHERE id_producto = p_id_producto
      AND activo = 1;

    IF v_existe_producto = 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'El producto no existe o esta inactivo.';
    END IF;

    -- Consultar y bloquear el registro del producto para evitar
    -- que otra transaccion descuente el mismo stock simultaneamente.
    SELECT stock, precio
    INTO v_stock, v_precio
    FROM productos
    WHERE id_producto = p_id_producto
      AND activo = 1
    FOR UPDATE;

    IF v_stock < p_cantidad THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Stock insuficiente para completar el pedido.';
    END IF;

    SET v_subtotal = v_precio * p_cantidad;

    -- Crear el pedido.
    INSERT INTO pedidos
    (id_cliente, direccion_entrega, estado, total, observaciones)
    VALUES
    (p_id_cliente, p_direccion_entrega, 'Pendiente', v_subtotal, p_observaciones);

    SET p_id_pedido = LAST_INSERT_ID();

    -- Registrar el producto solicitado.
    INSERT INTO detalle_pedido
    (id_pedido, id_producto, cantidad, precio_unitario, subtotal)
    VALUES
    (p_id_pedido, p_id_producto, p_cantidad, v_precio, v_subtotal);

    -- Descontar el stock del producto.
    UPDATE productos
    SET stock = stock - p_cantidad
    WHERE id_producto = p_id_producto;

    COMMIT;
END //

DELIMITER ;

-- 9. PRUEBA DEL PROCEDIMIENTO ALMACENADO
-- Registra un pedido de prueba para el cliente 3.
-- El stock del producto 2 se reduce en una unidad.

CALL sp_registrar_pedido(
    3,
    2,
    1,
    'San Borja, Lima',
    'Pedido registrado mediante el procedimiento almacenado.',
    @nuevo_pedido
);

SELECT @nuevo_pedido AS id_pedido_generado;

-- 10. CONSULTAS DE VERIFICACION

-- Ver el catalogo con su categoria.
SELECT
    p.id_producto,
    p.nombre AS producto,
    c.nombre AS categoria,
    p.presentacion,
    p.precio,
    p.stock,
    p.activo
FROM productos p
INNER JOIN categorias c ON p.id_categoria = c.id_categoria
ORDER BY c.nombre, p.nombre;

-- Ver los pedidos con los datos del cliente.
SELECT
    pe.id_pedido,
    CONCAT(cl.nombres, ' ', cl.apellidos) AS cliente,
    pe.fecha_pedido,
    pe.estado,
    pe.total
FROM pedidos pe
INNER JOIN clientes cl ON pe.id_cliente = cl.id_cliente
ORDER BY pe.fecha_pedido DESC;

-- Ver el detalle de los pedidos.
SELECT
    dp.id_pedido,
    pr.nombre AS producto,
    dp.cantidad,
    dp.precio_unitario,
    dp.subtotal
FROM detalle_pedido dp
INNER JOIN productos pr ON dp.id_producto = pr.id_producto
ORDER BY dp.id_pedido;

