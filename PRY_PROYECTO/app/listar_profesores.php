<?php

require_once '../conexion/db.php';
// Consultar los profesores con información de departamento
$sql = "SELECT 
    p.id,
    p.nombre,
    p.apellido,
    p.usuario,
    p.email,
    d.nombre AS departamento_nombre,
    d.id AS departamento_id
FROM profesor p
JOIN departamentos d ON p.departamento_id = d.id
ORDER BY d.nombre, p.apellido, p.nombre";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$profesores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener departamentos para el modal de edición
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

    <title>Gestión de Profesores - ISEM</title>
    <style>
        .professors-table-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
            position: relative;
            overflow: hidden;
        }

        .professors-table-container::before {
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

        .professor-name {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .professor-email {
            color: #6c757d;
            font-size: 0.9rem;
            font-style: italic;
        }

        .department-badge {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
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
            background: linear-gradient(135deg, #dd0505ff 0%, #5a6268 100%);
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

        .professors-stats {
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
                        <i class="bi bi-person-workspace me-3"></i>
                        Gestión de Profesores
                    </h1>
                    <p class="mb-0 opacity-75">Administra la información de todo el personal docente</p>
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
        <div class="professors-stats">
            <div class="row">
                <div class="col-md-6">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo count($profesores); ?></div>
                        <div class="stat-label">Total Profesores</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo count(array_unique(array_column($profesores, 'departamento_nombre'))); ?></div>
                        <div class="stat-label">Departamentos</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones principales -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">
                <i class="bi bi-list me-2"></i>
                Lista de Profesores
            </h3>
        </div>

        <!-- Tabla de profesores -->
        <?php if (empty($profesores)): ?>
            <div class="empty-state">
                <i class="bi bi-inbox empty-icon"></i>
                <h4 class="text-muted mb-3">No hay profesores registrados</h4>
                <p class="text-muted mb-4">Crea el primer profesor haciendo clic en "Nuevo Profesor" en la parte superior</p>
            </div>
        <?php else: ?>
            <div class="professors-table-container">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th><i class="bi bi-key me-2"></i>ID</th>
                                <th><i class="bi bi-person me-2"></i>Profesor</th>
                                <th><i class="bi bi-envelope me-2"></i>Email/Usuario</th>
                                <th><i class="bi bi-building me-2"></i>Departamento</th>
                                <th class="text-center"><i class="bi bi-gear me-2"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($profesores as $profesor): ?>
                                <tr>
                                    <td>
                                        <span class="id-badge"><?php echo htmlspecialchars($profesor['id']); ?></span>
                                    </td>
                                    <td>
                                        <div class="professor-name"><?php echo htmlspecialchars($profesor['nombre'] . ' ' . $profesor['apellido']); ?></div>
                                    </td>
                                    <td>
                                        <div class="professor-email"><?php echo htmlspecialchars($profesor['email']); ?></div>
                                        <small class="text-muted">@<?php echo htmlspecialchars($profesor['usuario']); ?></small>
                                    </td>
                                    <td>
                                        <span class="department-badge">
                                            <i class="bi bi-building"></i>
                                            <?php echo htmlspecialchars($profesor['departamento_nombre']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="action-btn btn-edit"
                                            onclick="editarProfesor(<?php echo $profesor['id']; ?>, '<?php echo addslashes($profesor['nombre']); ?>', '<?php echo addslashes($profesor['apellido']); ?>', <?php echo $profesor['departamento_id']; ?>)"
                                            title="Editar profesor">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="action-btn btn-delete"
                                            onclick="eliminarProfesor(<?php echo $profesor['id']; ?>, '<?php echo addslashes($profesor['nombre'] . ' ' . $profesor['apellido']); ?>')"
                                            title="Eliminar profesor">
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



    <!-- Modal Editar Profesor -->
    <div class="modal fade" id="editarProfesorModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil me-2"></i>
                        Editar Profesor
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editarProfesorForm">
                        <input type="hidden" id="editProfesorId" name="id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editNombreProfesor" class="form-label">Nombres</label>
                                <input type="text" class="form-control" id="editNombreProfesor" name="nombre" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editApellidoProfesor" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="editApellidoProfesor" name="apellido" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="editDepartamentoProfesor" class="form-label">Departamento</label>
                                <select class="form-control" id="editDepartamentoProfesor" name="departamento_id" required>
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
                                <i class="bi bi-save"></i> Actualizar Profesor
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
        // Editar profesor
        function editarProfesor(id, nombre, apellido, departamento_id) {
            document.getElementById('editProfesorId').value = id;
            document.getElementById('editNombreProfesor').value = nombre;
            document.getElementById('editApellidoProfesor').value = apellido;
            document.getElementById('editDepartamentoProfesor').value = departamento_id;

            const modal = new bootstrap.Modal(document.getElementById('editarProfesorModal'));
            modal.show();
        }

        document.getElementById('editarProfesorForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            Swal.fire({
                title: 'Actualizando profesor...',
                text: 'Por favor espere',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('editar_profesor.php', {
                    method: 'POST',
                    body: formData
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    if (data.success) {
                        Swal.fire({
                            title: 'Profesor actualizado exitosamente',
                            // Usa SOLO html O text, no ambos
                            html: `<div class="text-center">
                      <i class="bi bi-check-circle-fill text-success fs-1 mb-3"></i>
                      <p class="mb-2">Los datos han sido editados correctamente</p>
                      <div class="alert alert-info">
                          <strong>Usuario:</strong> ${data.profesor.usuario}<br>
                          <strong>Contraseña:</strong> ${data.profesor.contraseña}
                      </div>
                   </div>`,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000,
                            width: '500px'
                        }).then(() => {
                            location.reload();
                        });
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editarProfesorModal'));
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

        // Eliminar profesor
        function eliminarProfesor(id, nombre) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: `Se eliminará al profesor "${nombre}" permanentemente`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Eliminando profesor...',
                        text: 'Por favor espere',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('eliminar_profesor.php', {
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
                                    title: 'Profesor eliminado',
                                    text: 'El profesor ha sido eliminado exitosamente',
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