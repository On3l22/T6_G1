-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS clinica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE clinica;

-- Tabla: pacientes
CREATE TABLE pacientes (
    cedula VARCHAR(20) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    edad INT NOT NULL,
    sexo ENUM('Masculino', 'Femenino') NOT NULL,
    provincia VARCHAR(100),
    distrito VARCHAR(100),
    ciudad VARCHAR(100),
    direccion TEXT,
    telefono_movil VARCHAR(20),
    telefono_casa VARCHAR(20)
);

CREATE TABLE citas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(20) NOT NULL,
    tipo_medico ENUM('General', 'Especializado') NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    metodo_pago ENUM('Efectivo', 'Tarjeta de crédito', 'Tarjeta de débito') NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    FOREIGN KEY (cedula) REFERENCES pacientes(cedula)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


-- Tabla: examenes
CREATE TABLE examenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(6,2) NOT NULL,
    tipo ENUM('Urológico', 'Ginecológico') NOT NULL
);

-- Tabla intermedia: cita_examen
CREATE TABLE cita_examen (
    cita_id INT NOT NULL,
    examen_id INT NOT NULL,
    PRIMARY KEY (cita_id, examen_id),
    FOREIGN KEY (cita_id) REFERENCES citas(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (examen_id) REFERENCES examenes(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
