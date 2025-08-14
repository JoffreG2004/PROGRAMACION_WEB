<?php
// conexion a base de datos con conexion/db.php
require_once 'conexion/db.php';

//consultar los departamentos de la base de datos 
$sqlDepartamentos = "SELECT * FROM departamentos";
$stmtDepartamentos = $pdo->prepare($sqlDepartamentos);
$stmtDepartamentos->execute();
$departamentos = $stmtDepartamentos->fetchAll(PDO::FETCH_ASSOC);

//consultar las carreras de la base de datos 
$sqlCarreras = "SELECT * FROM carreras";
$stmtCarreras = $pdo->prepare($sqlCarreras);
$stmtCarreras->execute();
$carreras = $stmtCarreras->fetchAll(PDO::FETCH_ASSOC);

//consultar los profesores de la base de datos 
$sqlProfesores = "SELECT * FROM profesor";
$stmtProfesores = $pdo->prepare($sqlProfesores);
$stmtProfesores->execute();
$profesores = $stmtProfesores->fetchAll(PDO::FETCH_ASSOC);

//consultar los estudiantes de la base de datos
$sqlEstudiantes = "SELECT * FROM estudiantes";
$stmtEstudiantes = $pdo->prepare($sqlEstudiantes);
$stmtEstudiantes->execute();
$estudiantes = $stmtEstudiantes->fetchAll(PDO::FETCH_ASSOC);
//consultar las materias de la base de datos
$sqlMaterias = "SELECT * FROM materias";
$stmtMaterias = $pdo->prepare($sqlMaterias);
$stmtMaterias->execute();
$materias = $stmtMaterias->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Instituto Superior de Estudios Modernos</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="public/css/style.css">
</head>

<body>
    <!-- Elementos flotantes de fondo -->
    <div class="floating-elements">
        <div class="floating-element" style="left: 10%; animation-delay: 0s;">⚙️</div>
        <div class="floating-element" style="left: 20%; animation-delay: 2s;">🔧</div>
        <div class="floating-element" style="left: 30%; animation-delay: 4s;">📊</div>
        <div class="floating-element" style="left: 40%; animation-delay: 6s;">🗃️</div>
        <div class="floating-element" style="left: 50%; animation-delay: 8s;">🏫</div>
        <div class="floating-element" style="left: 60%; animation-delay: 1s;">👥</div>
        <div class="floating-element" style="left: 70%; animation-delay: 3s;">📝</div>
        <div class="floating-element" style="left: 80%; animation-delay: 5s;">🎯</div>
        <div class="floating-element" style="left: 90%; animation-delay: 7s;">🔐</div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.html">
                <i class="bi bi-mortarboard-fill me-2"></i>
                ISEM
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">
                            <i class="bi bi-house"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#panel">
                            <i class="bi bi-gear"></i> Panel Admin
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#gestion">
                            <i class="bi bi-kanban"></i> Gestión
                        </a>
                    </li>
                </ul>

                <!-- Botones de la derecha -->
                <div class="d-flex align-items-center">
                    <!-- Botón Modo Oscuro -->
                    <button id="darkModeBtn" class="btn btn-outline-light btn-sm me-3" title="Cambiar modo">
                        <i class="bi bi-moon-fill" id="darkModeIcon"></i>
                        <span id="darkModeText" class="d-none d-md-inline ms-1">Modo Oscuro</span>
                    </button>

                    <!-- Botón de Salir -->
                    <a href="index.html" class="btn btn-danger btn-sm">
                        <i class="bi bi-box-arrow-left"></i>
                        <span class="d-none d-md-inline ms-1">Salir</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header Principal -->
    <header class="main-header" id="panel">
        <div class="container">
            <div class="logo-container text-center fade-in-up">
                <div class="logo-icon">⚙️</div>
                <h1 class="institute-name">Panel de Administración</h1>
                <p class="institute-subtitle">
                    <i class="bi bi-shield-lock-fill"></i>
                    Sistema de Gestión Académica - Área Administrativa
                </p>
                <div class="mt-3">
                    <span class="badge bg-warning text-dark me-2">
                        <i class="bi bi-exclamation-triangle"></i> Acceso Restringido
                    </span>
                    <span class="badge bg-success text-light me-2">
                        <i class="bi bi-person-check"></i> Administrador
                    </span>
                    <span class="badge bg-info text-light">
                        <i class="bi bi-clock"></i> Sesión Activa
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Sección de Gestión -->
    <section class="selection-container" id="gestion">
        <div class="container">
            <h2 class="section-title fade-in-up">
                <i class="bi bi-kanban-fill"></i>
                ¿Qué deseas gestionar?
            </h2>

            <div class="user-cards">
                <!-- Tarjeta Estudiantes -->
                <div class="user-card fade-in-up delay-1">
                    <div class="user-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h3 class="user-title">
                        <i class="bi bi-mortarboard"></i>
                        Estudiantes
                    </h3>
                    <p class="user-description">
                        Gestiona información de estudiantes, inscripciones,
                        datos personales y estado académico.
                    </p>
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="bi bi-check-circle-fill text-success"></i> Crear/Editar estudiantes<br>
                            <i class="bi bi-check-circle-fill text-success"></i> Gestionar inscripciones<br>
                            <i class="bi bi-check-circle-fill text-success"></i> Reportes académicos
                        </small>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="app/listar_estudiantes.php" class="btn-custom">
                            <i class="bi bi-list"></i>
                            Gestionar Estudiantes
                        </a>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearEstudianteModal">
                            <i class="bi bi-plus-circle"></i>
                            Crear Estudiante
                        </button>
                    </div>
                </div>

                <!-- Tarjeta Profesores -->
                <div class="user-card fade-in-up delay-2">
                    <div class="user-icon">
                        <i class="bi bi-person-workspace"></i>
                    </div>
                    <h3 class="user-title">
                        <i class="bi bi-person-badge"></i>
                        Profesores
                    </h3>
                    <p class="user-description">
                        Administra el personal docente, asignación de materias
                        y información profesional.
                    </p>
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="bi bi-check-circle-fill text-success"></i> Crear/Editar profesores<br>
                            <i class="bi bi-check-circle-fill text-success"></i> Asignar materias<br>
                            <i class="bi bi-check-circle-fill text-success"></i> Gestionar horarios
                        </small>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="app/listar_profesores.php" class="btn-custom btn-secondary">
                            <i class="bi bi-list"></i>
                            Gestionar Profesores
                        </a>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearProfesorModal">
                            <i class="bi bi-plus-circle"></i>
                            Crear Profesor
                        </button>
                    </div>
                </div>

                <!-- Tarjeta Carreras -->
                <div class="user-card fade-in-up delay-3">
                    <div class="user-icon">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                    <h3 class="user-title">
                        <i class="bi bi-book"></i>
                        Carreras
                    </h3>
                    <p class="user-description">
                        Configura carreras académicas, planes de estudio
                        y estructura curricular.
                    </p>
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="bi bi-check-circle-fill text-success"></i> Crear/Editar carreras<br>
                            <i class="bi bi-check-circle-fill text-success"></i> Gestionar materias<br>
                            <i class="bi bi-check-circle-fill text-success"></i> Plan de estudios
                        </small>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="app/listar_carreras.php" class="btn-custom">
                            <i class="bi bi-list"></i>
                            Gestionar Carreras
                        </a>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearCarreraModal">
                            <i class="bi bi-plus-circle"></i>
                            Crear Carrera
                        </button>
                    </div>
                </div>

                <!-- Tarjeta Departamentos -->
                <div class="user-card fade-in-up delay-4">
                    <div class="user-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <h3 class="user-title">
                        <i class="bi bi-diagram-3"></i>
                        Departamentos
                    </h3>
                    <p class="user-description">
                        Organiza la estructura departamental del instituto
                        y asignación de carreras.
                    </p>
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="bi bi-check-circle-fill text-success"></i> Crear/Editar departamentos<br>
                            <i class="bi bi-check-circle-fill text-success"></i> Asignar carreras<br>
                            <i class="bi bi-check-circle-fill text-success"></i> Estructura organizacional
                        </small>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="app/listar_departamento.php" class="btn-custom btn-secondary">
                            <i class="bi bi-list"></i>
                            Gestionar Departamentos
                        </a>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearDepartamentoModal">
                            <i class="bi bi-plus-circle"></i>
                            Crear Departamento
                        </button>
                    </div>
                </div>

                <!-- Tarjeta Materias -->
                <div class="user-card fade-in-up delay-5">
                    <div class="user-icon">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <h3 class="user-title">
                        <i class="bi bi-book-half"></i>
                        Materias
                    </h3>
                    <p class="user-description">
                        Gestiona las materias académicas, asignación de profesores
                        y créditos académicos.
                    </p>
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="bi bi-check-circle-fill text-success"></i> Crear/Editar materias<br>
                            <i class="bi bi-check-circle-fill text-success"></i> Asignar profesores<br>
                            <i class="bi bi-check-circle-fill text-success"></i> Gestionar créditos
                        </small>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="app/listar_materias.php" class="btn-custom btn-warning">
                            <i class="bi bi-list"></i>
                            Gestionar Materias
                        </a>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearMateriaModal">
                            <i class="bi bi-plus-circle"></i>
                            Crear Materia
                        </button>
                    </div>
                </div>





                <!-- Modales de Creación -->
                <!-- Modal Crear Estudiante -->
                <div class="modal fade" id="crearEstudianteModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title">
                                    <i class="bi bi-person-plus me-2"></i>
                                    Crear Nuevo Estudiante
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="crearEstudianteForm">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="idestudiante" class="form-label">ID Estudiante</label>
                                            <input type="text" class="form-control" id="idestudiante" name="id" required>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="cedulaEstudiante" class="form-label">Cédula Estudiante</label>
                                            <input type="text" class="form-control" id="cedulaEstudiante" name="ci" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nombreEstudiante" class="form-label">Nombres</label>
                                            <input type="text" class="form-control" id="nombreEstudiante" name="nombre" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="apellidoEstudiante" class="form-label">Apellidos</label>
                                            <input type="text" class="form-control" id="apellidoEstudiante" name="apellido" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="carreraEstudiante" class="form-label">Carrera</label>
                                            <select class="form-select" id="carreraEstudiante" name="carrera_id" required>
                                                <option value="">Seleccionar carrera...</option>
                                                <?php foreach ($carreras as $carrera) {  ?>
                                                    <option value="<?php echo $carrera['id']; ?>"><?php echo $carrera['nombre']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Datos auto-generados:</strong> El usuario, contraseña y email se generarán automáticamente basados en el nombre y apellido.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-save"></i> Guardar Estudiante
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
















                <!-- Modal Crear Profesor -->
                <div class="modal fade" id="crearProfesorModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title">
                                    <i class="bi bi-person-plus me-2"></i>
                                    Crear Nuevo Profesor
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="crearProfesorForm">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nombreProfesor" class="form-label">Nombres</label>
                                            <input type="text" class="form-control" id="nombreProfesor" name="nombre" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="apellidoProfesor" class="form-label">Apellidos</label>
                                            <input type="text" class="form-control" id="apellidoProfesor" name="apellido" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="departamentoProfesor" class="form-label">Departamento</label>
                                            <select class="form-control" id="departamentoProfesor" name="departamento_id" required>
                                                <option value="">Seleccionar departamento...</option>
                                                <?php foreach ($departamentos as $departamento) {  ?>
                                                    <option value="<?php echo $departamento['id']; ?>"><?php echo $departamento['nombre']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Datos auto-generados:</strong> El usuario y contraseña se generarán automáticamente basados en el nombre y apellido.
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-save"></i> Guardar Profesor
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>


                <script>
                    document.getElementById('crearProfesorForm').addEventListener('submit', function(e) {
                        e.preventDefault();

                        const form = this;
                        const formData = new FormData(form);

                        // Mostrar loading
                        Swal.fire({
                            title: 'Procesando solicitud...',
                            text: 'Por favor espere',
                            icon: 'info',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        fetch('app/guardar_profesor.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(function(response) {
                                return response.json();
                            })
                            .then(function(data) {
                                if (data.success) {
                                    // Mostrar datos del profesor creado
                                    Swal.fire({
                                        title: 'Profesor creado exitosamente',
                                        html: `
                                        <div class="text-start">
                                            <p><strong>DATOS GENERADOS</strong></p>
                                            <p><strong>Nombre:</strong> ${data.profesor.nombre} ${data.profesor.apellido}</p>
                                            <p><strong>Email:</strong> ${data.profesor.email}</p>
                                            <p><strong>Usuario generado:</strong> <span class="text-primary fw-bold">${data.profesor.usuario}</span></p>
                                            <p><strong>Contraseña generada:</strong> <span class="text-primary fw-bold">${data.profesor.contrasena}</span></p>
                                            <small class="text-muted">Guarde estos datos de acceso</small>
                                        </div>
                                    `,
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 6000
                                    }).then(() => {
                                        location.reload(); // Recargar la página para actualizar todos los selects
                                    });
                                    form.reset();
                                    const modal = bootstrap.Modal.getInstance(document.getElementById('crearProfesorModal'));
                                    modal.hide();
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: data.message,
                                        icon: 'error',
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                                }
                            })
                            .catch(function(error) {
                                console.error('Error:', error);
                                Swal.fire({
                                    title: 'Error de conexión',
                                    text: 'Verifica tu conexión a internet',
                                    icon: 'error',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            });
                    });

                    // JavaScript para el formulario de estudiantes
                    document.getElementById('crearEstudianteForm').addEventListener('submit', function(e) {
                        e.preventDefault();

                        const form = this;
                        const formData = new FormData(form);

                        // Mostrar loading
                        Swal.fire({
                            title: 'Procesando solicitud...',
                            text: 'Por favor espere',
                            icon: 'info',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        fetch('app/guardar_estudiante.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(function(response) {
                                return response.json();
                            })
                            .then(function(data) {
                                if (data.success) {
                                    // Mostrar datos generados
                                    Swal.fire({
                                        title: 'Estudiante creado exitosamente',
                                        html: `
                                        <div class="text-start">
                                            <p><strong>DATOS GENERADOS</strong> </p>
                                            <p><strong>Email:</strong> ${data.estudiante.email}</p>
                                            <p><strong>Usuario generado:</strong> <span class="text-primary fw-bold">${data.estudiante.usuario}</span></p>
                                            <p><strong>Contraseña generada:</strong> <span class="text-primary fw-bold">${data.estudiante.contrasena}</span></p>
                                            <small class="text-muted">Guarde estos datos de acceso</small>
                                        </div>
                                    `,
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 6000
                                    }).then(() => {
                                        location.reload(); // Recargar la página para actualizar todos los selects
                                    });
                                    form.reset();
                                    const modal = bootstrap.Modal.getInstance(document.getElementById('crearEstudianteModal'));
                                    modal.hide();
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: data.message,
                                        icon: 'error',
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                                }
                            })
                            .catch(function(error) {
                                console.error('Error:', error);
                                Swal.fire({
                                    title: 'Error de conexión',
                                    text: 'Verifica tu conexión a internet',
                                    icon: 'error',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            });
                    });
                </script>











                <!-- Modal Crear Carrera -->
                <div class="modal fade" id="crearCarreraModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title">
                                    <i class="bi bi-journal-plus me-2"></i>
                                    Crear Nueva Carrera
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="crearCarreraForm">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="nombre" class="form-label">Nombre de la Carrera</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="codigo" class="form-label">Código</label>
                                            <input type="text" class="form-control" id="codigo" name="codigo" required>
                                        </div>
                                        <label for="duracion" class="form-label">Duración (semestres)</label>
                                        <input type="number" class="form-control" id="duracion" name="duracion" min="1" max="12"
                                            required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="departamento" class="form-label">Departamento</label>
                                        <select class="form-select" id="departamento" name="departamento" required>
                                            <option value="">Seleccionar departamento...</option>
                                            <?php foreach ($departamentos as $departamento) {  ?>
                                                <option value="<?php echo $departamento['id']; ?>"><?php echo $departamento['nombre']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save"></i> Guardar Carrera
                                </button>
                            </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <script>
                document.getElementById('crearCarreraForm').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const form = this;
                    const formData = new FormData(form);

                    // Mostrar loading
                    Swal.fire({
                        title: 'Procesando solicitud...',
                        text: 'Por favor espere',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('app/guardar_carrera.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(function(response) {
                            return response.json();
                        })
                        .then(function(data) {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Carrera creada exitosamente',
                                    html: `
                                        <div class="text-start">
                                   
                                        </div>
                                    `,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 4000
                                }).then(() => {
                                    location.reload(); // Recargar la página para actualizar todos los selects
                                });
                                form.reset();
                                const modal = bootstrap.Modal.getInstance(document.getElementById('crearCarreraModal'));
                                modal.hide();
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: data.message,
                                    icon: 'error',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            }
                        })
                        .catch(function(error) {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error de conexión',
                                text: 'Verifica tu conexión a internet',
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        });
                });
            </script>















            <!-- Modal Crear Departamento -->
            <div class="modal fade" id="crearDepartamentoModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-building-add me-2"></i>
                                Crear Nuevo Departamento
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form id="crearDepartamentoForm">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="Departamento" class="form-label">NOMBRE DEL DEPARTAMENTO</label>
                                        <input type="text" class="form-control" id="Departamento" name="nombre" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save"></i> Guardar Departamento
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                document.getElementById('crearDepartamentoForm').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const form = this;
                    const formData = new FormData(form);

                    // Mostrar loading
                    Swal.fire({
                        title: 'Procesando solicitud...',
                        text: 'Por favor espere',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('app/guardar_departamento.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(function(response) {
                            return response.json();
                        })
                        .then(function(data) {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Departamento creado exitosamente',
                                    html: `
                                        <div class="text-start">
                                          
                                        </div>
                                    `,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 4000
                                }).then(() => {
                                    location.reload(); // Recargar la página para actualizar todos los selects
                                });
                                form.reset();
                                const modal = bootstrap.Modal.getInstance(document.getElementById('crearDepartamentoModal'));
                                modal.hide();
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: data.message,
                                    icon: 'error',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            }
                        })
                        .catch(function(error) {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error de conexión',
                                text: 'Verifica tu conexión a internet',
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        });
                });
            </script>




































            <!-- Modal Crear Materia -->
            <div class="modal fade" id="crearMateriaModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-journal-plus me-2"></i>
                                Crear Nueva Materia
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="crearMateriaForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nrcMateria" class="form-label">NRC</label>
                                        <input type="text" class="form-control" id="nrcMateria" name="nrc" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="nombreMateria" class="form-label">Nombre de la Materia</label>
                                        <input type="text" class="form-control" id="nombreMateria" name="nombre" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="creditosMateria" class="form-label">Créditos</label>
                                        <input type="number" class="form-control" id="creditosMateria" name="creditos" min="1" max="10" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="departamentoMateria" class="form-label">Departamento</label>
                                        <select class="form-select" id="departamentoMateria" name="departamento_id" required>
                                            // que vuelva al option seleccionar departamento cuando se abra el modal

                                            <option value="" selected>Seleccionar Departamento...</option>
                                            <?php foreach ($departamentos as $departamento) { ?>
                                                <option value="<?php echo $departamento['id']; ?>"><?php echo $departamento['nombre']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="profesorMateria" class="form-label">Profesor</label>
                                        <select class="form-select" id="profesorMateria" name="profesor_id" required>
                                            <option value="">Primero seleccione un departamento...</option>
                                            //gestion de profesor por departamento

                                            <?php foreach ($profesores as $profesor) { ?>
                                                <option value="<?php echo $profesor['id']; ?>"><?php echo $profesor['nombre_completo']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-save"></i> Guardar Materia
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.getElementById('crearMateriaModal').addEventListener('shown.bs.modal', function() {
                    document.getElementById('departamentoMateria').value = '';
                    document.getElementById('profesorMateria').innerHTML = '<option value="">Primero seleccione un departamento...</option>';
                });
                // JavaScript para el formulario de materias
                document.getElementById('crearMateriaForm').addEventListener('submit', function(e) {
                    e.preventDefault();



                    const form = this;
                    const formData = new FormData(form);

                    // Mostrar loading
                    Swal.fire({
                        title: 'Procesando solicitud...',
                        text: 'Por favor espere',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('app/guardar_materia.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(function(response) {
                            return response.json();
                        })
                        .then(function(data) {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Materia creada exitosamente',
                                    html: `
                                        <div class="text-start">
                                        </div>
                                    `,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 4000
                                }).then(() => {
                                    location.reload(); // Recargar la página para actualizar todos los selects
                                });
                                form.reset();
                                const modal = bootstrap.Modal.getInstance(document.getElementById('crearMateriaModal'));
                                modal.hide();
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: data.message,
                                    icon: 'error',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            }
                        })
                        .catch(function(error) {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error de conexión',
                                text: 'Verifica tu conexión a internet',
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        });
                });

                // Cargar profesores cuando se selecciona un departamento
                document.getElementById('departamentoMateria').addEventListener('change', function() {
                    const departamentoId = this.value;
                    const profesorSelect = document.getElementById('profesorMateria');

                    // Limpiar el select de profesores
                    profesorSelect.innerHTML = '<option value="">Cargando profesores...</option>';

                    if (departamentoId) {
                        fetch(`app/obtener_profesores_por_departamento.php?departamento_id=${departamentoId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    profesorSelect.innerHTML = '<option value="">Sin asignar...</option>';
                                    data.profesores.forEach(profesor => {
                                        const option = document.createElement('option');
                                        option.value = profesor.id;
                                        option.textContent = profesor.nombre_completo;
                                        profesorSelect.appendChild(option);
                                    });
                                } else {
                                    profesorSelect.innerHTML = '<option value="">Error al cargar profesores</option>';
                                    console.error('Error:', data.error);
                                }
                            })
                            .catch(error => {
                                profesorSelect.innerHTML = '<option value="">Error al cargar profesores</option>';
                                console.error('Error:', error);
                            });
                    } else {
                        // Si no hay departamento seleccionado
                        profesorSelect.innerHTML = '<option value="">Primero seleccione un departamento...</option>';
                    }
                });
            </script>











            <!-- Footer -->
            <footer class="main-footer">
                <div class="container">
                    <div class="footer-content">
                        <div class="footer-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <div>
                            <p class="mb-0">
                                © 2025 Instituto Superior de Estudios Modernos -
                                <i class="bi bi-heart-fill text-success"></i>
                                Panel de Administración
                            </p>
                            <small class="text-muted">
                                <i class="bi bi-shield-lock"></i>
                                Área Restringida |
                                <i class="bi bi-person-gear"></i>
                                Administrador: Ing. Joffre Gómez
                            </small>
                        </div>
                    </div>
                </div>
            </footer>

            <!-- Bootstrap JS -->
            <script src="public/bootstrap/js/bootstrap.bundle.min.js"></script>

            <!-- Script personalizado -->
            <script>
                // Reutilizar el código del modo oscuro del index
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }
                    });
                }, observerOptions);

                document.querySelectorAll('.fade-in-up').forEach(el => {
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(30px)';
                    el.style.transition = 'all 0.6s ease-out';
                    observer.observe(el);
                });

                // Funcionalidad Modo Oscuro
                const darkModeBtn = document.getElementById('darkModeBtn');
                const darkModeIcon = document.getElementById('darkModeIcon');
                const darkModeText = document.getElementById('darkModeText');
                const body = document.body;

                const currentMode = localStorage.getItem('darkMode');
                if (currentMode === 'enabled') {
                    enableDarkMode();
                }

                darkModeBtn.addEventListener('click', () => {
                    if (body.classList.contains('dark-mode')) {
                        disableDarkMode();
                    } else {
                        enableDarkMode();
                    }
                });

                function enableDarkMode() {
                    body.classList.add('dark-mode');
                    darkModeIcon.classList.remove('bi-moon-fill');
                    darkModeIcon.classList.add('bi-sun-fill');
                    darkModeText.textContent = 'Modo Claro';
                    localStorage.setItem('darkMode', 'enabled');
                }

                function disableDarkMode() {
                    body.classList.remove('dark-mode');
                    darkModeIcon.classList.remove('bi-sun-fill');
                    darkModeIcon.classList.add('bi-moon-fill');
                    darkModeText.textContent = 'Modo Oscuro';
                    localStorage.setItem('darkMode', 'disabled');
                }

                // Navegación suave
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function(e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            const offsetTop = target.offsetTop - 80;
                            window.scrollTo({
                                top: offsetTop,
                                behavior: 'smooth'
                            });
                        }
                    });
                });

                // Cambiar navbar al hacer scroll
                window.addEventListener('scroll', function() {
                    const navbar = document.querySelector('.navbar');
                    if (window.scrollY > 50) {
                        navbar.classList.add('navbar-scrolled');
                    } else {
                        navbar.classList.remove('navbar-scrolled');
                    }
                });
            </script>
</body>

</html>