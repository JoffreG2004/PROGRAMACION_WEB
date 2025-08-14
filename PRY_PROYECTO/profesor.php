<?php
session_start();

// Inicializar variables para el profesor
$nombreProfesor = 'Profesor';
$idProfesor = null;

// Verificar si se recibió información por POST (fetch)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['nombre'])) {
        $_SESSION['nombre_profesor'] = $input['nombre'];
        $nombreProfesor = $input['nombre'];

        if (isset($input['id'])) {
            $_SESSION['id_profesor'] = $input['id'];
            $idProfesor = $input['id'];
        }
    }
} elseif (isset($_SESSION['nombre_profesor'])) {
    // Si ya existe en la sesión, usarlo
    $nombreProfesor = $_SESSION['nombre_profesor'];
    $idProfesor = $_SESSION['id_profesor'] ?? null;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="public/css/style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <title>
        GESTION DE PROFESORES
    </title>
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
            <a class="navbar-brand" href="#">
                <i class="bi bi-mortarboard-fill me-2"></i>
                ISEM
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#inicio">
                            <i class="bi bi-house"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#notas">
                            <i class="bi bi-pencil"></i> Registrar Notas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto">
                            <i class="bi bi-telephone"></i> Contacto
                        </a>
                    </li>
                </ul>

                <!-- Botones de la derecha -->
                <div class="d-flex align-items-center">
                    <!-- Botón Volver -->
                    <button class="btn btn-success btn-sm me-3" onclick="volverInicio()">
                        <i class="bi bi-arrow-left"></i>
                        <span class="d-none d-md-inline ms-1">Volver</span>
                    </button>

                    <!-- Botón Modo Oscuro -->
                    <button id="darkModeBtn" class="btn btn-outline-light btn-sm me-3" title="Cambiar modo">
                        <i class="bi bi-moon-fill" id="darkModeIcon"></i>
                        <span id="darkModeText" class="d-none d-md-inline ms-1">Modo Oscuro</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <br>

    <!-- Header Principal -->
    <header class="main-header" id="inicio">
        <div class="container">
            <div class="logo-container text-center fade-in-up">
                <div class="logo-icon">🏫</div>
                <h1 class="institute-name">Instituto Superior de Estudios Modernos</h1>
                <h1 class="institute-name">Bienvenido <?php echo htmlspecialchars($nombreProfesor); ?></h1>
                <p class="institute-subtitle">
                    <i class="bi bi-mortarboard-fill"></i>
                    Sistema de Gestión Académica Digital
                </p>
                <div class="mt-3">
                    <span class="badge bg-light text-dark me-2">
                        <i class="bi bi-shield-check"></i> Seguro
                    </span>
                    <span class="badge bg-light text-dark me-2">
                        <i class="bi bi-lightning"></i> Rápido
                    </span>
                    <span class="badge bg-light text-dark">
                        <i class="bi bi-heart"></i> Confiable
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Sección de Registro de Notas -->
    <section class="selection-container" id="notas">
        <div class="container">
            <h2 class="section-title fade-in-up">
                <i class="bi bi-pencil-square"></i>
                Registro de Notas
            </h2>

            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-clipboard-check"></i>
                                Registrar Calificaciones
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="formNotas">
                                <!-- Selección de Materia -->
                                <div class="mb-3">
                                    <label for="selectMateria" class="form-label">
                                        <i class="bi bi-book"></i> Seleccionar Materia
                                    </label>
                                    <select class="form-select" id="selectMateria" required>
                                        <option value="">Cargando materias...</option>
                                    </select>
                                </div>

                                <!-- Selección de Estudiante -->
                                <div class="mb-3">
                                    <label for="selectEstudiante" class="form-label">
                                        <i class="bi bi-person"></i> Seleccionar Estudiante
                                    </label>
                                    <select class="form-select" id="selectEstudiante" required>
                                        <option value="">Primero seleccione una materia</option>
                                    </select>
                                </div>

                                <!-- Notas -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="nota1" class="form-label">
                                                <i class="bi bi-1-circle"></i> Primer Parcial
                                            </label>
                                            <input type="number" class="form-control" id="nota1"
                                                min="0" max="20" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="nota2" class="form-label">
                                                <i class="bi bi-2-circle"></i> Segundo Parcial
                                            </label>
                                            <input type="number" class="form-control" id="nota2"
                                                min="0" max="20" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="nota3" class="form-label">
                                                <i class="bi bi-3-circle"></i> Tercer Parcial
                                            </label>
                                            <input type="number" class="form-control" id="nota3"
                                                min="0" max="20" step="0.01" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Promedio -->
                                <div class="mb-3">
                                    <label for="promedio" class="form-label">
                                        <i class="bi bi-calculator"></i> Promedio Final
                                    </label>
                                    <input type="number" class="form-control" id="promedio" readonly>
                                </div>

                                <!-- Botones -->
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-secondary me-md-2" id="btnLimpiar">
                                        <i class="bi bi-arrow-clockwise"></i> Limpiar
                                    </button>
                                    <button type="button" class="btn btn-outline-warning me-md-2 d-none" id="btnCancelarEdicion">
                                        <i class="bi bi-x-circle"></i> Cancelar Edición
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-save"></i> Registrar Nota
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Notas Registradas -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-table"></i>
                                Notas Registradas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="tablaNotas">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Estudiante</th>
                                            <th>Materia</th>
                                            <th>Parcial 1</th>
                                            <th>Parcial 2</th>
                                            <th>Parcial 3</th>
                                            <th>Promedio</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Las notas se cargarán aquí -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal de Login Administrador -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-shield-lock me-2"></i>
                        Acceso de Administrador
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="loginError" class="alert alert-danger d-none">
                        <i class="bi bi-exclamation-triangle"></i>
                        Usuario o contraseña incorrectos
                    </div>

                    <form id="loginForm">
                        <div class="mb-3">
                            <label for="adminUsuario" class="form-label">
                                <i class="bi bi-person"></i> Usuario
                            </label>
                            <input type="text" class="form-control" id="adminUsuario" required
                                placeholder="Ingrese su usuario">
                        </div>

                        <div class="mb-3">
                            <label for="adminPassword" class="form-label">
                                <i class="bi bi-lock"></i> Contraseña
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="adminPassword" required
                                    placeholder="Ingrese su contraseña">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordModal()">
                                    <i class="bi bi-eye" id="toggleIconModal"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button id="btn-login" class="btn btn-success">
                                <i class="bi bi-box-arrow-in-right"></i>
                                Ingresar al Panel
                            </button>
                        </div>
                    </form>

                    <hr>
                    <div class="text-center">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i>
                            Acceso solo para personal autorizado
                        </small>
                    </div>

                    <!-- Información de seguridad -->
                    <div class="mt-3 p-3 bg-light rounded">
                        <small class="text-muted">
                            <i class="bi bi-shield-check"></i>
                            <strong>Sistema Seguro:</strong> Las credenciales se verifican contra la base de datos.<br>
                            <i class="bi bi-database-lock"></i>
                            <strong>Cifrado:</strong> Todas las contraseñas están protegidas con hash.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <footer class="main-footer" id="contacto">
        <div class="container">
            <div class="footer-content">
                <div class="footer-icon">
                    <i class="bi bi-building"></i>
                </div>
                <div>
                    <p class="mb-0">
                        © 2025 Instituto Superior de Estudios Modernos -
                        <i class="bi bi-heart-fill text-success"></i>
                        Educación de Excelencia
                    </p>
                    <small class="text-muted">
                        <i class="bi bi-shield-lock"></i>
                        Sistema Seguro |
                        <i class="bi bi-telephone"></i>
                        Soporte: 0998521340
                        <br>
                        <i class="bi bi-copyright"></i>
                        <span class="text-muted">
                            Copyright ©Ing. Joffre Gómez 2025
                        </span>
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <script src="public/bootstrap//js//bootstrap.bundle.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="public/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Script para el sistema de notas -->
    <script>
        // Variables globales
        let materias = [];
        let estudiantes = [];
        let profesorId = <?php echo json_encode($idProfesor); ?>;

        // Cargar materias del profesor al iniciar la página
        document.addEventListener('DOMContentLoaded', function() {
            if (profesorId) {
                cargarMateriasProfesor();
                cargarEstudiantes();
                cargarNotasRegistradas();
            }

            // Event listeners
            document.getElementById('selectMateria').addEventListener('change', function() {
                const materiaSeleccionada = this.value;
                if (materiaSeleccionada) {
                    habilitarSeleccionEstudiante();
                } else {
                    deshabilitarSeleccionEstudiante();
                }
            });

            // Calcular promedio automáticamente
            ['nota1', 'nota2', 'nota3'].forEach(id => {
                document.getElementById(id).addEventListener('input', calcularPromedio);
            });

            // Botón limpiar
            document.getElementById('btnLimpiar').addEventListener('click', limpiarFormulario);

            // Envío del formulario
            document.getElementById('formNotas').addEventListener('submit', guardarNota);
        });

        // Cargar materias del profesor
        function cargarMateriasProfesor() {
            const formData = new FormData();
            formData.append('profesor_id', profesorId);

            fetch('app/obtener_materias_profesor.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error:', data.error);
                        return;
                    }

                    materias = data;
                    const selectMateria = document.getElementById('selectMateria');
                    selectMateria.innerHTML = '<option value="">Seleccione una materia</option>';

                    data.forEach(materia => {
                        const option = document.createElement('option');
                        option.value = materia.id;
                        option.textContent = `${materia.nrc} - ${materia.nombre}`;
                        selectMateria.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error al cargar materias:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudieron cargar las materias'
                    });
                });
        }

        // Cargar estudiantes
        function cargarEstudiantes() {
            fetch('app/obtener_estudiantes.php')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error:', data.error);
                        return;
                    }
                    estudiantes = data;
                })
                .catch(error => {
                    console.error('Error al cargar estudiantes:', error);
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'No se pudieron cargar los estudiantes'
                    });
                });
        }

        // Habilitar selección de estudiante
        function habilitarSeleccionEstudiante() {
            const selectEstudiante = document.getElementById('selectEstudiante');
            selectEstudiante.innerHTML = '<option value="">Seleccione un estudiante</option>';

            estudiantes.forEach(estudiante => {
                const option = document.createElement('option');
                option.value = estudiante.id;
                option.textContent = `${estudiante.nombre} ${estudiante.apellido} (${estudiante.ci})`;
                selectEstudiante.appendChild(option);
            });

            selectEstudiante.disabled = false;
        }

        // Deshabilitar selección de estudiante
        function deshabilitarSeleccionEstudiante() {
            const selectEstudiante = document.getElementById('selectEstudiante');
            selectEstudiante.innerHTML = '<option value="">Primero seleccione una materia</option>';
            selectEstudiante.disabled = true;
        }

        // Calcular promedio
        function calcularPromedio() {
            const nota1 = parseFloat(document.getElementById('nota1').value) || 0;
            const nota2 = parseFloat(document.getElementById('nota2').value) || 0;
            const nota3 = parseFloat(document.getElementById('nota3').value) || 0;

            const promedio = (nota1 + nota2 + nota3) / 3;
            document.getElementById('promedio').value = promedio.toFixed(2);
        }

        // Limpiar formulario
        function limpiarFormulario() {
            document.getElementById('formNotas').reset();
            document.getElementById('promedio').value = '';
            deshabilitarSeleccionEstudiante();
        }

        // Guardar nota
        function guardarNota(e) {
            e.preventDefault();

            const formData = new FormData();
            formData.append('materia_id', document.getElementById('selectMateria').value);
            formData.append('estudiante_id', document.getElementById('selectEstudiante').value);
            formData.append('nota1', document.getElementById('nota1').value);
            formData.append('nota2', document.getElementById('nota2').value);
            formData.append('nota3', document.getElementById('nota3').value);
            formData.append('promedio', document.getElementById('promedio').value);

            // Si estamos editando, agregar el ID de la nota
            if (editandoNota && notaIdEditando) {
                formData.append('nota_id', notaIdEditando);
            }

            // Mostrar loading
            const isEditing = editandoNota;
            Swal.fire({
                title: isEditing ? 'Actualizando...' : 'Guardando...',
                html: '<div class="spinner-border text-primary" role="status"></div>',
                showConfirmButton: false,
                allowOutsideClick: false
            });

            fetch('app/guardar_notas.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: isEditing ? '¡Nota actualizada!' : '¡Nota guardada!',
                            text: isEditing ? 'La calificación ha sido actualizada correctamente' : 'La calificación ha sido registrada correctamente',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        resetearFormulario();
                        cargarNotasRegistradas(); // Recargar la tabla de notas
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al guardar',
                            text: data.error || 'Error al guardar la nota'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'Error de conexión al guardar la nota'
                    });
                });
        }

        // Cargar notas registradas
        function cargarNotasRegistradas() {
            const formData = new FormData();
            formData.append('profesor_id', profesorId);

            fetch('app/obtener_notas_profesor.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error:', data.error);
                        return;
                    }

                    const tbody = document.querySelector('#tablaNotas tbody');
                    tbody.innerHTML = '';

                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No hay notas registradas</td></tr>';
                        return;
                    }

                    data.forEach(nota => {
                        const row = document.createElement('tr');
                        const estadoClass = nota.estado === 'Aprobado' ? 'text-success' : 'text-danger';

                        row.innerHTML = `
                            <td>${nota.estudiante_nombre}<br><small class="text-muted">CI: ${nota.estudiante_ci}</small></td>
                            <td>${nota.nrc} - ${nota.materia_nombre}</td>
                            <td><span class="badge bg-primary">${nota.n1}</span></td>
                            <td><span class="badge bg-info">${nota.n2}</span></td>
                            <td><span class="badge bg-warning">${nota.n3}</span></td>
                            <td><strong>${nota.promedio}</strong></td>
                            <td><span class="badge ${nota.estado === 'Aprobado' ? 'bg-success' : 'bg-danger'}">${nota.estado}</span></td>
                            <td>
                                <button class="btn btn-sm btn-warning me-1" onclick="editarNota(${nota.id})" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="eliminarNota(${nota.id})" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error al cargar notas:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudieron cargar las notas registradas'
                    });
                });
        }

        // Variable para controlar si estamos editando
        let editandoNota = false;
        let notaIdEditando = null;

        // Función para editar nota
        function editarNota(notaId) {
            // Obtener datos de la nota
            fetch(`app/obtener_nota_por_id.php?id=${notaId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const nota = data.nota;

                        // Llenar el formulario con los datos existentes
                        document.getElementById('selectMateria').value = nota.materia_id;

                        // Disparar el evento change para cargar estudiantes
                        const event = new Event('change');
                        document.getElementById('selectMateria').dispatchEvent(event);

                        // Esperar un poco para que se carguen los estudiantes
                        setTimeout(() => {
                            document.getElementById('selectEstudiante').value = nota.estudiante_id;
                            document.getElementById('nota1').value = nota.n1;
                            document.getElementById('nota2').value = nota.n2;
                            document.getElementById('nota3').value = nota.n3;
                            calcularPromedio();

                            // Marcar que estamos editando
                            editandoNota = true;
                            notaIdEditando = notaId;

                            // Cambiar el texto del botón
                            const submitBtn = document.querySelector('#formNotas button[type="submit"]');
                            submitBtn.innerHTML = '<i class="bi bi-pencil-square"></i> Actualizar Nota';
                            submitBtn.className = 'btn btn-warning';

                            // Mostrar botón cancelar
                            const btnCancelar = document.getElementById('btnCancelarEdicion');
                            btnCancelar.classList.remove('d-none');

                            // Scroll al formulario
                            document.getElementById('formNotas').scrollIntoView({
                                behavior: 'smooth'
                            });

                            Swal.fire({
                                icon: 'info',
                                title: 'Modo Edición',
                                text: 'Modifique los valores y haga clic en "Actualizar Nota"',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }, 500);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'No se pudo cargar la nota para editar'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al obtener los datos de la nota'
                    });
                });
        }

        // Función para eliminar nota
        function eliminarNota(notaId) {
            Swal.fire({
                title: '¿Está seguro?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Eliminando...',
                        html: '<div class="spinner-border text-danger" role="status"></div>',
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });

                    fetch(`app/eliminar_nota.php?id=${notaId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Eliminado',
                                    text: 'La nota ha sido eliminada correctamente',
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                // Recargar la tabla
                                cargarNotasRegistradas();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message || 'No se pudo eliminar la nota'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al eliminar la nota'
                            });
                        });
                }
            });
        }

        // Función para resetear el formulario al modo "crear"
        function resetearFormulario() {
            editandoNota = false;
            notaIdEditando = null;

            const submitBtn = document.querySelector('#formNotas button[type="submit"]');
            submitBtn.innerHTML = '<i class="bi bi-save"></i> Registrar Nota';
            submitBtn.className = 'btn btn-success';

            // Mostrar/ocultar botón cancelar
            const btnCancelar = document.getElementById('btnCancelarEdicion');
            btnCancelar.classList.add('d-none');

            limpiarFormulario();
        }

        // Evento para el botón cancelar edición
        document.getElementById('btnCancelarEdicion').addEventListener('click', function() {
            Swal.fire({
                title: '¿Cancelar edición?',
                text: 'Se perderán los cambios no guardados',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6c757d',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'Continuar editando'
            }).then((result) => {
                if (result.isConfirmed) {
                    resetearFormulario();
                }
            });
        });
    </script>

    <script>
        const modalLogin = new bootstrap.Modal(
            document.getElementById('loginModal'), {
                keyboard: false
            });

        // Conectar el botón del navbar con el modal
        document.getElementById('btn-actualizar-admin').addEventListener('click', function() {
            fetch('app/buscarAdminPorId.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: 1
                })
            }).then(function(response) {
                return response.json();
            }).then(function(request) {
                // Mostrar el modal
                modalLogin.show();




                document.getElementById('btn-login').addEventListener('click', function(e) {
                    e.preventDefault();

                    const usuario = document.getElementById('adminUsuario').value;
                    const password = document.getElementById('adminPassword').value;

                    const errorDiv = document.getElementById('loginError');





                    if (!usuario.trim() || !password.trim()) {
                        errorDiv.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Por favor complete todos los campos';
                        errorDiv.classList.remove('d-none');
                        console.log(usuario, password);
                        return;
                    }







                    if (usuario == request.usuario && password == request.password) {
                        console.log('Acceso concedido');
                    } else {
                        console.log('Acceso denegado');
                    }





                    if (usuario == request.usuario && password == request.password) {
                        // Mostrar carga inmediatamente
                        Swal.fire({
                            icon: 'info',
                            title: 'Verificando credenciales...',
                            html: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });

                        // Después de un momento mostrar éxito
                        setTimeout(() => {
                            modalLogin.hide();
                            Swal.fire({
                                icon: 'success',
                                title: '¡Acceso concedido!',
                                text: 'Redirigiendo al panel...',
                                timer: 1500,
                                showConfirmButton: false,
                                timerProgressBar: true
                            });

                            setTimeout(() => {
                                window.location.href = 'admin.php';
                            }, 1500);
                        }, 800);

                    } else {
                        // Mostrar carga inmediatamente para error también
                        Swal.fire({
                            icon: 'info',
                            title: 'Verificando credenciales...',
                            html: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });

                        // Después mostrar error
                        setTimeout(() => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de acceso',
                                text: 'Usuario o contraseña incorrectos',
                                timer: 3000,
                                showConfirmButton: false,
                                timerProgressBar: true
                            });
                        }, 800);
                    }
                });
            });
        });
    </script>


    <!-- Animaciones personalizadas -->
    <script>
        // Agregar clase de animación cuando el elemento entra en vista
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

        // Observar elementos con animación
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

        // Verificar si hay preferencia guardada
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
                    const offsetTop = target.offsetTop - 80; // Espacio para el navbar
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

        // Función para mostrar/ocultar contraseña en el modal
        function togglePasswordModal() {
            const passwordField = document.getElementById('adminPassword');
            const toggleIcon = document.getElementById('toggleIconModal');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }

        // Función para volver al inicio
        function volverInicio() {
            window.location.href = 'index.html';
        }
    </script>
</body>

</html>