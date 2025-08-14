<?php

require_once '../conexion/db.php';
// Consultar las materias con información de departamento y profesor
$sql = "SELECT 
    m.id,
    m.nrc,
    m.nombre AS materia_nombre,
    m.creditos,
    m.departamento_id,
    d.nombre AS departamento_nombre,
    CONCAT(p.nombre, ' ', p.apellido) AS profesor_nombre,
    p.id AS profesor_id
FROM materias m
JOIN departamentos d ON m.departamento_id = d.id
LEFT JOIN profesor p ON m.profesor_id = p.id
ORDER BY d.nombre, m.nombre";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$materias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener departamentos
$sql_dept = "SELECT * FROM departamentos ORDER BY nombre";
$stmt_dept = $pdo->prepare($sql_dept);
$stmt_dept->execute();
$departamentos = $stmt_dept->fetchAll(PDO::FETCH_ASSOC);

// Obtener carreras para estadísticas
$sql_carreras = "SELECT * FROM carreras";
$stmt_carreras = $pdo->prepare($sql_carreras);
$stmt_carreras->execute();
$carreras = $stmt_carreras->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <title>Gestión de Materias - ISEM</title>
    <style>
        .subjects-table-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
            position: relative;
            overflow: hidden;
        }

        .subjects-table-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
            border-radius: 16px 16px 0 0;
        }

        .table-modern {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: none;
            margin-bottom: 0;
        }

        .table-modern thead {
            background: var(--gradient-primary);
            color: white;
        }

        .table-modern thead th {
            border: none;
            padding: 1.2rem 1rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .table-modern tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #e9ecef;
        }

        .table-modern tbody tr:hover {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%);
            transform: scale(1.01);
            box-shadow: 0 4px 15px rgba(45, 80, 22, 0.1);
        }

        .table-modern tbody td {
            padding: 1.2rem 1rem;
            vertical-align: middle;
            border: none;
        }

        .nrc-badge {
            background: var(--gradient-secondary);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .id-badge {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .subject-name {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .career-badge {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 25px;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .credits-badge {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .professor-badge {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #212529;
            padding: 0.4rem 1rem;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .no-professor {
            background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 25px;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-action-table {
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-action-table:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .btn-back {
            background: linear-gradient(135deg, #e41717ff 0%, #5a6268 100%);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            border: none;
            font-size: 1rem;
        }

        .btn-back:hover {
            background: linear-gradient(135deg, #5a6268 0%, #495057 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(108, 117, 125, 0.3);
            color: white;
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 20px 20px;
        }

        .table-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--secondary-color);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .empty-icon {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .subjects-table-container {
                padding: 1rem;
                margin-top: 1rem;
            }

            .table-modern {
                font-size: 0.9rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .table-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="mb-0">
                            <i class="bi bi-journal-text me-3"></i>
                            Gestión de Materias
                        </h1>
                        <p class="mb-0 opacity-75">Administra las materias académicas de la institución</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="../admin.php" class="btn-back">
                            <i class="bi bi-arrow-left"></i>
                            Volver al Panel
                        </a>
                    </div>
                </div>
            </div>
        </div>



        <!-- Estadísticas -->
        <div class="container">
            <div class="table-stats">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <div class="stat-number"><?php echo count($materias); ?></div>
                    <div class="stat-label">Total Materias</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                    <div class="stat-number"><?php echo count($carreras); ?></div>
                    <div class="stat-label">Total Carreras</div>
                </div>
            </div>

            <!-- Contenido principal -->
            <?php if (empty($materias)): ?>
                <div class="empty-state">
                    <i class="bi bi-inbox empty-icon"></i>
                    <h4 class="text-muted mb-3">No hay materias registradas</h4>
                    <p class="text-muted mb-4">Crea la primera materia haciendo clic en "Nueva Materia" en la parte superior</p>
                </div>
            <?php else: ?>
                <div class="subjects-table-container">
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th><i class="bi bi-key me-2"></i>ID</th>
                                    <th><i class="bi bi-hash me-2"></i>NRC</th>
                                    <th><i class="bi bi-journal-text me-2"></i>Materia</th>
                                    <th><i class="bi bi-building me-2"></i>Departamento</th>
                                    <th><i class="bi bi-star me-2"></i>Créditos</th>
                                    <th><i class="bi bi-person me-2"></i>Profesor</th>
                                    <th class="text-center"><i class="bi bi-gear me-2"></i>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($materias as $materia): ?>
                                    <tr>
                                        <td>
                                            <span class="id-badge"><?php echo htmlspecialchars($materia['id']); ?></span>
                                        </td>
                                        <td>
                                            <span class="nrc-badge"><?php echo htmlspecialchars($materia['nrc']); ?></span>
                                        </td>
                                        <td>
                                            <div class="subject-name"><?php echo htmlspecialchars($materia['materia_nombre']); ?></div>
                                        </td>
                                        <td>
                                            <span class="career-badge">
                                                <i class="bi bi-building"></i>
                                                <?php echo htmlspecialchars($materia['departamento_nombre']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="credits-badge">
                                                <i class="bi bi-star-fill"></i>
                                                <?php echo $materia['creditos']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($materia['profesor_nombre']): ?>
                                                <span class="professor-badge">
                                                    <i class="bi bi-person-check"></i>
                                                    <?php echo htmlspecialchars($materia['profesor_nombre']); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="no-professor">
                                                    <i class="bi bi-person-x"></i>
                                                    Sin asignar
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="action-buttons">
                                                <button class="btn btn-custom btn-action-table"
                                                    onclick="editarMateria(<?php echo $materia['id']; ?>, '<?php echo addslashes($materia['nrc']); ?>', '<?php echo addslashes($materia['materia_nombre']); ?>', <?php echo $materia['creditos']; ?>, <?php echo $materia['departamento_id']; ?>, <?php echo $materia['profesor_id'] ?? 'null'; ?>)"
                                                    title="Editar materia">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-outline-danger btn-action-table"
                                                    onclick="eliminarMateria(<?php echo $materia['id']; ?>)"
                                                    title="Eliminar materia">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Editar Materia -->
    <div class="modal fade" id="editarMateriaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--gradient-secondary); color: white;">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil"></i>
                        Editar Materia
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEditarMateria">
                    <div class="modal-body">
                        <input type="hidden" id="editMateriaId" name="id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editNrcMateria" class="form-label fw-bold">NRC</label>
                                <input type="text" class="form-control" id="editNrcMateria" name="nrc" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editCreditosMateria" class="form-label fw-bold">Créditos</label>
                                <input type="number" class="form-control" id="editCreditosMateria" name="creditos" min="1" max="10" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editNombreMateria" class="form-label fw-bold">Nombre de la Materia</label>
                            <input type="text" class="form-control" id="editNombreMateria" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDepartamentoMateria" class="form-label fw-bold">Departamento</label>
                            <select class="form-select" id="editDepartamentoMateria" name="departamento_id" required onchange="cargarProfesoresPorDepartamento(this.value, 'edit')">
                                <option value="">Seleccionar departamento...</option>
                                <?php foreach ($departamentos as $departamento): ?>
                                    <option value="<?php echo $departamento['id']; ?>"><?php echo htmlspecialchars($departamento['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editProfesorMateria" class="form-label fw-bold">Profesor (Opcional)</label>
                            <select class="form-select" id="editProfesorMateria" name="profesor_id">
                                <option value="">Sin profesor asignado</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i>
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i>
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../public/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Función para editar materia
        function editarMateria(id, nrc, nombre, creditos, departamento_id, profesor_id) {
            document.getElementById('editMateriaId').value = id;
            document.getElementById('editNrcMateria').value = nrc;
            document.getElementById('editNombreMateria').value = nombre;
            document.getElementById('editCreditosMateria').value = creditos;
            document.getElementById('editDepartamentoMateria').value = departamento_id;

            // Cargar profesores del departamento y seleccionar el actual
            if (departamento_id) {
                cargarProfesoresPorDepartamento(departamento_id, 'edit', profesor_id);
            }

            new bootstrap.Modal(document.getElementById('editarMateriaModal')).show();
        }

        // Función para cargar profesores por departamento
        function cargarProfesoresPorDepartamento(departamentoId, tipo, profesorSeleccionado = null) {
            const selectProfesor = document.getElementById(tipo === 'edit' ? 'editProfesorMateria' : 'profesorMateria');

            // Limpiar opciones
            selectProfesor.innerHTML = '<option value="">Sin profesor asignado</option>';

            if (!departamentoId) return;

            fetch(`obtener_profesores_por_departamento.php?departamento_id=${departamentoId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        data.profesores.forEach(profesor => {
                            const option = document.createElement('option');
                            option.value = profesor.id;
                            option.textContent = profesor.nombre_completo;
                            if (profesorSeleccionado && profesor.id == profesorSeleccionado) {
                                option.selected = true;
                            }
                            selectProfesor.appendChild(option);
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Editar materia
        document.getElementById('formEditarMateria').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            Swal.fire({
                title: 'Procesando...',
                text: 'Actualizando materia',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('editar_materia.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: 'Materia actualizada correctamente',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message,
                            icon: 'error'
                        });
                    }
                });
        });

        // Función para eliminar materia
        function eliminarMateria(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('eliminar_materia.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                id: id
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: '¡Eliminada!',
                                    text: 'Materia eliminada correctamente',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: data.message,
                                    icon: 'error'
                                });
                            }
                        });
                }
            });
        }
    </script>
</body>

</html>