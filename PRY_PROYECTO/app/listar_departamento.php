<?php

require_once '../conexion/db.php';
// consultar los departamentos de la base de datos
$sql = "SELECT * FROM departamentos";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$departamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

    <title>Gestión de Departamentos - ISEM</title>
    <style>
        .department-card-custom {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 16px;
            padding: 1.8rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 2px solid transparent;
            height: 160px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .department-card-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
            border-radius: 16px 16px 0 0;
        }

        .department-card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(45, 80, 22, 0.2);
            border-color: var(--secondary-color);
            background: linear-gradient(135deg, #ffffff 0%, #f1f3f4 100%);
        }

        .department-info {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .department-icon {
            background: var(--gradient-primary);
            border-radius: 50%;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(45, 80, 22, 0.3);
        }

        .department-icon i {
            font-size: 2rem;
            color: white;
        }

        .department-card-custom:hover .department-icon {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 6px 20px rgba(45, 80, 22, 0.4);
        }

        .department-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
            text-transform: capitalize;
        }

        .department-actions {
            display: flex;
            gap: 8px;
            flex-direction: column;
        }

        .btn-action {
            padding: 8px 16px;
            font-size: 0.85rem;
            border-radius: 8px;
            min-width: 90px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-action:hover {
            transform: translateY(-2px);
        }

        .department-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(420px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 20px 20px;
        }

        .btn-back {
            background: linear-gradient(135deg, #c21111ff 0%, #5a6268 100%);
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

        @media (max-width: 768px) {
            .department-grid {
                grid-template-columns: 1fr;
            }

            .department-card-custom {
                height: auto;
                flex-direction: column;
                text-align: center;
                padding: 1.5rem;
            }

            .department-info {
                flex-direction: column;
                margin-bottom: 1rem;
            }

            .department-icon {
                margin-right: 0;
                margin-bottom: 1rem;
            }

            .department-actions {
                flex-direction: row;
                justify-content: center;
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

        <!-- Contenido principal -->
        <div class="container mt-5">
            <?php if (empty($departamentos)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                    <h4 class="text-muted mb-3">No hay departamentos registrados</h4>
                    <p class="text-muted mb-4">Crea el primer departamento haciendo clic en "Nuevo Departamento"</p>
                    <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#crearDepartamentoModal">
                        <i class="bi bi-plus-circle"></i>
                        Crear Primer Departamento
                    </button>
                </div>
            <?php else: ?>
                <div class="department-grid">
                    <?php foreach ($departamentos as $departamento): ?>
                        <div class="department-card-custom">
                            <div class="department-info">
                                <div class="department-icon">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div>
                                    <div class="text-muted small mb-1">ID: <?php echo htmlspecialchars($departamento['id']); ?></div>
                                    <h3 class="department-title"><?php echo htmlspecialchars($departamento['nombre']); ?></h3>
                                </div>
                            </div>
                            <div class="department-actions">
                                <button class="btn btn-custom btn-action"
                                    onclick="editarDepartamento(<?php echo $departamento['id']; ?>, '<?php echo addslashes($departamento['nombre']); ?>')"
                                    title="Editar departamento">
                                    <i class="bi bi-pencil"></i>
                                    Editar
                                </button>
                                <button class="btn btn-outline-danger btn-action"
                                    onclick="eliminarDepartamento(<?php echo $departamento['id']; ?>)"
                                    title="Eliminar departamento">
                                    <i class="bi bi-trash"></i>
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Crear Departamento -->
    <div class="modal fade" id="crearDepartamentoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--gradient-primary); color: white;">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle"></i>
                        Crear Nuevo Departamento
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formCrearDepartamento">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombreDepartamento" class="form-label fw-bold">Nombre del Departamento</label>
                            <input type="text" class="form-control" id="nombreDepartamento" name="nombre" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i>
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i>
                            Crear Departamento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Departamento -->
    <div class="modal fade" id="editarDepartamentoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--gradient-secondary); color: white;">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil"></i>
                        Editar Departamento
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEditarDepartamento">
                    <div class="modal-body">
                        <input type="hidden" id="editDepartamentoId" name="id">
                        <div class="mb-3">
                            <label for="editNombreDepartamento" class="form-label fw-bold">Nombre del Departamento</label>
                            <input type="text" class="form-control" id="editNombreDepartamento" name="nombre" required>
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
        // Crear departamento
        document.getElementById('formCrearDepartamento').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('guardar_departamento.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: 'Departamento creado correctamente',
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
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Error al crear el departamento',
                        icon: 'error'
                    });
                });
        });

        // Función para editar departamento
        function editarDepartamento(id, nombre) {
            document.getElementById('editDepartamentoId').value = id;
            document.getElementById('editNombreDepartamento').value = nombre;

            new bootstrap.Modal(document.getElementById('editarDepartamentoModal')).show();
        }

        // Editar departamento
        document.getElementById('formEditarDepartamento').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('editar_departamento.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: 'Departamento actualizado correctamente',
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

        // Función para eliminar departamento
        function eliminarDepartamento(id) {
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
                    fetch('eliminar_departamento.php', {
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
                                    title: '¡Eliminado!',
                                    text: 'Departamento eliminado correctamente',
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