USE marah_db;

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    estado BOOLEAN NOT NULL DEFAULT TRUE,
    fecha_registro DATETIME NOT NULL DEFAULT NOW()
) ENGINE = InnoDB;

DELIMITER //

CREATE PROCEDURE sp_validar_usuario(
    IN p_correo VARCHAR(100)
)
BEGIN
    SELECT id_usuario, nombre, correo, password
    FROM usuarios
    WHERE correo = p_correo
      AND estado = TRUE;
END //

DELIMITER ;

-- Para crear un usuario administrador:
-- 1. Generar el hash:  php -r "echo password_hash('tu_clave', PASSWORD_DEFAULT);"
-- 2. INSERT INTO usuarios (nombre, correo, password) VALUES ('Nombre', 'correo@ejemplo.com', 'HASH');

