CREATE TABLE carreras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    departamento_id INT NOT NULL,
    codigo VARCHAR(10) UNIQUE NOT NULL,
    nombre VARCHAR(100) UNIQUE NOT NULL,
    duracion_semestres INT NOT NULL,
    FOREIGN KEY (departamento_id) REFERENCES departamentos (id)
);

CREATE TABLE profesor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    departamento_id INT NOT NULL,
    FOREIGN KEY (departamento_id) REFERENCES departamentos (id)
);

CREATE TABLE departamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE materias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nrc VARCHAR(5) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    creditos INT NOT NULL,
    departamento_id INT NOT NULL,
    profesor_id INT,
    FOREIGN KEY (departamento_id) REFERENCES departamentos (id),
    FOREIGN KEY (profesor_id) REFERENCES profesor (id)
);

CREATE TABLE estudiantes (
    id VARCHAR(20) PRIMARY KEY,
    ci VARCHAR(10) UNIQUE NOT NULL,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    carrera_id INT NOT NULL,
    FOREIGN KEY (carrera_id) REFERENCES carreras (id)
);

-- TABLA: ADMINISTRADORES
CREATE TABLE administradores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso TIMESTAMP NULL
);

CREATE TABLE notas (
    id int AUTO_INCREMENT PRIMARY KEY,
    estudiante_id VARCHAR(20) NOT NULL,
    materia_id int NOT NULL,
    n1 decimal(5, 2) NOT NULL,
    n2 decimal(5, 2) NOT NULL,
    n3 decimal(5, 2) NOT NULL,
    promedio decimal(5, 2) NOT NULL,
    FOREIGN KEY (estudiante_id) REFERENCES estudiantes (id) ON DELETE CASCADE,
    FOREIGN KEY (materia_id) REFERENCES materias (id) ON DELETE CASCADE
);

SELECT departamentos.nombre AS departamento_nombre, carreras.codigo, carreras.nombre, carreras.duracion_semestres
FROM carreras
    JOIN departamentos ON carreras.departamento_id = departamentos.id;

-- JOIN: PROFESORES CON DEPARTAMENTOS
SELECT
    p.id AS profesor_id,
    p.nombre,
    p.apellido,
    p.email,
    d.nombre AS departamento_nombre
FROM profesor p
    JOIN departamentos d ON p.departamento_id = d.id;

-- JOIN: MATERIAS CON PROFESORES Y DEPARTAMENTOS
SELECT
    m.nrc,
    m.nombre AS materia_nombre,
    m.creditos,
    p.nombre AS profesor_nombre,
    p.apellido AS profesor_apellido,
    d.nombre AS departamento_nombre
FROM
    materias m
    LEFT JOIN profesor p ON m.profesor_id = p.id
    LEFT JOIN departamentos d ON m.departamento_id = d.id;

-- CONSULTA: PROFESORES POR CARRERA (basado en el departamento de la carrera)
-- Esta consulta muestra todos los profesores que pueden dar clases en una carrera específica
SELECT
    c.nombre AS carrera_nombre,
    c.codigo AS carrera_codigo,
    d.nombre AS departamento_nombre,
    p.id AS profesor_id,
    p.nombre AS profesor_nombre,
    p.apellido AS profesor_apellido,
    p.email AS profesor_email
FROM
    carreras c
    JOIN departamentos d ON c.departamento_id = d.id
    JOIN profesor p ON p.departamento_id = d.id
WHERE
    c.nombre = 'Software' -- Cambiar por la carrera deseada
ORDER BY p.apellido, p.nombre;

-- CONSULTA: PROFESORES DISPONIBLES PARA UNA CARRERA ESPECÍFICA (por ID)
-- Usar esta consulta en el formulario para cargar profesores según la carrera seleccionada
SELECT p.id, CONCAT(p.nombre, ' ', p.apellido) AS nombre_completo, p.email
FROM
    profesor p
    JOIN departamentos d ON p.departamento_id = d.id
    JOIN carreras c ON c.departamento_id = d.id
WHERE
    c.id = ? -- Parámetro para el ID de la carrera
ORDER BY p.apellido, p.nombre;