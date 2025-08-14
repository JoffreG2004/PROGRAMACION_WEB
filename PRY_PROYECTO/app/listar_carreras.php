<?php
require_once '../conexion/db.php';

// Consultar las carreras con información de departamento
$sql = "SELECT 
    c.id,
    c.codigo,
    c.nombre AS carrera_nombre,
    c.duracion_semestres,
    c.departamento_id,
    d.nombre AS departamento_nombre
FROM carreras c
JOIN departamentos d ON c.departamento_id = d.id
ORDER BY d.nombre, c.nombre";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$carreras = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener departamentos
$sql_dept = "SELECT * FROM departamentos ORDER BY nombre";
$stmt_dept = $pdo->prepare($sql_dept);
$stmt_dept->execute();
$departamentos = $stmt_dept->fetchAll(PDO::FETCH_ASSOC);
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

    <title>Gestión de Carreras - ISEM</title>
    <style>
        .careers-table-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
            position: relative;
            overflow: hidden;
        }

        .careers-table-container::before {
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
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background: white;
        }

        .table-modern thead {
            background: var(--gradient-primary);
            color: white;
        }

        .table-modern thead th {
            padding: 1.2rem 1rem;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }

        .table-modern tbody td {
            padding: 1.2rem 1rem;
            vertical-align: middle;
            border: none;
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

        .codigo-badge {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
            min-width: 80px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        }

        .career-name {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .duration-badge {
            background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%);
            color: #333;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .department-badge {
            background: linear-gradient(135deg, #6f42c1 0%, #8a63d2 100%);
            color: white;
            padding: 0.25rem 0.6rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        .action-btn {
            padding: 0.4rem 0.8rem;
            margin: 0 0.2rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .btn-edit {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }

        .btn-edit:hover {
            background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }

        .btn-delete:hover {
            background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
            color: white;
        }

        .btn-back {
            background: linear-gradient(135deg, #ec0707ff 0%, #5a6268 100%);
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

        .btn-new {
            background: var(--gradient-primary);
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

        .btn-new:hover {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(40, 167, 69, 0.3);
            color: white;
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 20px 20px;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-icon {
            font-size: 4rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }

        .table-modern tbody tr:hover {
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        .careers-stats {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="mb-0">
                        <i class="bi bi-journal-bookmark-fill me-3"></i>
                        Gestión de Carreras
                    </h1>
                    <p class="mb-0 opacity-75">Administra las carreras académicas y planes de estudio</p>
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

    <div class="container">
        <!-- Estadísticas -->
        <div class="careers-stats">
            <div class="row">
                <div class="col-md-4">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo count($carreras); ?></div>
                        <div class="stat-label">Total Carreras</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo count(array_unique(array_column($carreras, 'departamento_nombre'))); ?></div>
                        <div class="stat-label">Departamentos</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo count($carreras) > 0 ? round(array_sum(array_column($carreras, 'duracion_semestres')) / count($carreras), 1) : 0; ?></div>
                        <div class="stat-label">Duración Promedio</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones principales -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">
                <i class="bi bi-list me-2"></i>
                Lista de Carreras
            </h3>
        </div>

        <!-- Tabla de carreras -->
        <?php if (empty($carreras)): ?>
            <div class="empty-state">
                <i class="bi bi-inbox empty-icon"></i>
                <h4 class="text-muted mb-3">No hay carreras registradas</h4>
                <p class="text-muted mb-4">Crea la primera carrera haciendo clic en "Nueva Carrera" en la parte superior</p>
            </div>
        <?php else: ?>
            <div class="careers-table-container">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th><i class="bi bi-key me-2"></i>ID</th>
                                <th><i class="bi bi-code-square me-2"></i>Código</th>
                                <th><i class="bi bi-journal-text me-2"></i>Carrera</th>
                                <th><i class="bi bi-clock me-2"></i>Duración</th>
                                <th><i class="bi bi-building me-2"></i>Departamento</th>
                                <th class="text-center"><i class="bi bi-gear me-2"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($carreras as $carrera): ?>
                                <tr>
                                    <td>
                                        <span class="id-badge"><?php echo htmlspecialchars($carrera['id']); ?></span>
                                    </td>
                                    <td>
                                        <span class="codigo-badge"><?php echo htmlspecialchars($carrera['codigo']); ?></span>
                                    </td>
                                    <td>
                                        <div class="career-name"><?php echo htmlspecialchars($carrera['carrera_nombre']); ?></div>
                                    </td>
                                    <td>
                                        <span class="duration-badge">
                                            <i class="bi bi-clock"></i>
                                            <?php echo htmlspecialchars($carrera['duracion_semestres']); ?> semestres
                                        </span>
                                    </td>
                                    <td>
                                        <span class="department-badge">
                                            <i class="bi bi-building"></i>
                                            <?php echo htmlspecialchars($carrera['departamento_nombre']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="action-btn btn-edit"
                                            onclick="editarCarrera(<?php echo $carrera['id']; ?>, '<?php echo addslashes($carrera['codigo']); ?>', '<?php echo addslashes($carrera['carrera_nombre']); ?>', <?php echo $carrera['duracion_semestres']; ?>, <?php echo $carrera['departamento_id']; ?>)"
                                            title="Editar carrera">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="action-btn btn-delete"
                                            onclick="eliminarCarrera(<?php echo $carrera['id']; ?>, '<?php echo addslashes($carrera['carrera_nombre']); ?>')"
                                            title="Eliminar carrera">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <!-- Modal Editar Carrera -->
    <div class="modal fade" id="editarCarreraModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil me-2"></i>
                        Editar Carrera
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editarCarreraForm">
                        <input type="hidden" id="editCarreraId" name="id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editCodigoCarrera" class="form-label">Código</label>
                                <input type="text" class="form-control" id="editCodigoCarrera" name="codigo" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editDuracionCarrera" class="form-label">Duración (semestres)</label>
                                <input type="number" class="form-control" id="editDuracionCarrera" name="duracion_semestres" min="1" max="12" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="editNombreCarrera" class="form-label">Nombre de la Carrera</label>
                                <input type="text" class="form-control" id="editNombreCarrera" name="nombre" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="editDepartamentoCarrera" class="form-label">Departamento</label>
                                <select class="form-select" id="editDepartamentoCarrera" name="departamento_id" required>
                                    <option value="">Seleccionar departamento...</option>
                                    <?php foreach ($departamentos as $departamento): ?>
                                        <option value="<?php echo $departamento['id']; ?>"><?php echo $departamento['nombre']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save"></i> Actualizar Carrera
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="../public/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Editar carrera
        function editarCarrera(id, codigo, nombre, duracion, departamento_id) {
            document.getElementById('editCarreraId').value = id;
            document.getElementById('editCodigoCarrera').value = codigo;
            document.getElementById('editNombreCarrera').value = nombre;
            document.getElementById('editDuracionCarrera').value = duracion;
            document.getElementById('editDepartamentoCarrera').value = departamento_id;

            const modal = new bootstrap.Modal(document.getElementById('editarCarreraModal'));
            modal.show();
        }

        document.getElementById('editarCarreraForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            Swal.fire({
                title: 'Actualizando carrera...',
                text: 'Por favor espere',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('editar_carrera.php', {
                    method: 'POST',
                    body: formData
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    if (data.success) {
                        Swal.fire({
                            title: 'Carrera actualizada exitosamente',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editarCarreraModal'));
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

        // Eliminar carrera
        function eliminarCarrera(id, nombre) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: `Se eliminará la carrera "${nombre}" permanentemente`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Eliminando carrera...',
                        text: 'Por favor espere',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('eliminar_carrera.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                id: id
                            })
                        })
                        .then(function(response) {
                            return response.json();
                        })
                        .then(function(data) {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Carrera eliminada',
                                    text: 'La carrera ha sido eliminada exitosamente',
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    location.reload();
                                });
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
                }
            });
        }
    </script>
</body>

</html>