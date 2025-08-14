<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Estudiante - Sistema Académico</title>

    <!-- Bootstrap CSS -->
    <link href="public/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom CSS -->
    <link href="public/css/style.css" rel="stylesheet">

    <style>
        .hero-section {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
            opacity: 0.3;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .btn-success {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: linear-gradient(45deg, #20c997, #28a745);
            transform: translateY(-2px);
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .badge {
            font-size: 0.9em;
            padding: 8px 12px;
        }

        .nota-cell {
            text-align: center;
            font-weight: bold;
            font-size: 1.1em;
        }

        .promedio-cell {
            background-color: #f8f9fa;
            text-align: center;
            font-weight: bold;
            font-size: 1.2em;
        }

        .estado-aprobado {
            color: #28a745;
            font-weight: bold;
        }

        .estado-reprobado {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-mortarboard-fill me-2"></i>
                Portal Estudiante
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#mis-notas">
                            <i class="bi bi-journal-text"></i> Mis Notas
                        </a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link" id="estudianteNombre">
                            <i class="bi bi-person-circle"></i> Cargando...
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">
                            <i class="bi bi-box-arrow-left"></i> Salir
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-4 fade-in-up">
                        <i class="bi bi-journal-bookmark-fill me-3"></i>
                        Consulta de Calificaciones
                    </h1>
                    <p class="lead mb-4 fade-in-up" style="animation-delay: 0.2s;">
                        Revisa tu desempeño académico y mantente al día con tus calificaciones
                    </p>
                </div>
                <div class="col-lg-4">
                    <div class="text-center fade-in-up" style="animation-delay: 0.4s;">
                        <i class="bi bi-graph-up display-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mis Notas Section -->
    <section id="mis-notas" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card fade-in-up">
                        <div class="card-header bg-success text-white">
                            <h4 class="mb-0">
                                <i class="bi bi-journal-text me-2"></i>
                                Mis Calificaciones
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="tablaNotas">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Materia</th>
                                            <th>NRC</th>
                                            <th>Profesor</th>
                                            <th>Parcial 1</th>
                                            <th>Parcial 2</th>
                                            <th>Parcial 3</th>
                                            <th>Promedio</th>
                                            <th>Estado</th>
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

            <!-- Resumen Académico -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-success">Materias Aprobadas</h5>
                            <h2 class="text-success" id="materiasAprobadas">0</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-danger">Materias Reprobadas</h5>
                            <h2 class="text-danger" id="materiasReprobadas">0</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Total Materias</h5>
                            <h2 class="text-primary" id="totalMaterias">0</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-info">Promedio General</h5>
                            <h2 class="text-info" id="promedioGeneral">0.00</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botón Volver -->
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="index.html" class="btn btn-success btn-lg">
                        <i class="bi bi-arrow-left me-2"></i>
                        Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="public/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script>
        // Variables globales
        let estudianteId = null;

        // Inicialización cuando se carga la página
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener datos del estudiante desde sessionStorage
            const estudianteData = sessionStorage.getItem('estudianteData');

            if (!estudianteData) {
                Swal.fire({
                    icon: 'error',
                    title: 'Sesión no válida',
                    text: 'No se encontraron datos de sesión. Será redirigido al inicio.',
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'index.html';
                });
                return;
            }

            const estudiante = JSON.parse(estudianteData);
            estudianteId = estudiante.id;

            // Mostrar nombre del estudiante
            document.getElementById('estudianteNombre').innerHTML =
                `<i class="bi bi-person-circle"></i> ${estudiante.nombre} ${estudiante.apellido}`;

            // Cargar notas del estudiante
            cargarNotasEstudiante();
        });

        // Función para cargar las notas del estudiante
        function cargarNotasEstudiante() {
            const formData = new FormData();
            formData.append('estudiante_id', estudianteId);

            fetch('app/obtener_notas_estudiante.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarNotas(data.notas);
                        calcularResumen(data.notas);
                    } else {
                        console.error('Error al cargar notas:', data.message);
                        const tbody = document.querySelector('#tablaNotas tbody');
                        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No hay notas registradas</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudieron cargar las calificaciones'
                    });
                });
        }

        // Función para mostrar las notas en la tabla
        function mostrarNotas(notas) {
            const tbody = document.querySelector('#tablaNotas tbody');
            tbody.innerHTML = '';

            if (notas.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No hay calificaciones registradas</td></tr>';
                return;
            }

            notas.forEach(nota => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td><strong>${nota.materia_nombre}</strong></td>
                    <td><span class="badge bg-secondary">${nota.nrc}</span></td>
                    <td>${nota.profesor_nombre}</td>
                    <td class="nota-cell"><span class="badge bg-primary">${nota.n1}</span></td>
                    <td class="nota-cell"><span class="badge bg-info">${nota.n2}</span></td>
                    <td class="nota-cell"><span class="badge bg-warning">${nota.n3}</span></td>
                    <td class="promedio-cell"><strong>${nota.promedio}</strong></td>
                    <td>
                        <span class="badge ${nota.estado === 'Aprobado' ? 'bg-success' : 'bg-danger'}">
                            ${nota.estado}
                        </span>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Función para calcular y mostrar el resumen académico
        function calcularResumen(notas) {
            let aprobadas = 0;
            let reprobadas = 0;
            let sumaPromedios = 0;

            notas.forEach(nota => {
                if (nota.estado === 'Aprobado') {
                    aprobadas++;
                } else {
                    reprobadas++;
                }
                sumaPromedios += parseFloat(nota.promedio);
            });

            const total = notas.length;
            const promedioGeneral = total > 0 ? (sumaPromedios / total).toFixed(2) : '0.00';

            // Actualizar contadores
            document.getElementById('materiasAprobadas').textContent = aprobadas;
            document.getElementById('materiasReprobadas').textContent = reprobadas;
            document.getElementById('totalMaterias').textContent = total;
            document.getElementById('promedioGeneral').textContent = promedioGeneral;
        }
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
    </script>
</body>

</html>