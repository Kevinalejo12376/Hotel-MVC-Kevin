<?php
require_once "controller/HabitacionesController.php";
require_once "controller/ReservasController.php";

$id_user = $_SESSION['usuario']['id'] ?? null;
$nombre = htmlspecialchars($_SESSION['usuario']['nombre'] ?? $_SESSION['usuario_nombre'] ?? 'Huésped');
$habitaciones_lista = HabitacionesController::obtenerHabitaciones();
$categorias = HabitacionesController::obtenerCategorias();
$metodos_pago = ReservasController::obtenerMetodosPago();
$reservas = $id_user ? ReservasController::obtenerReservasPorUsuario($id_user) : [];
$hay_reservas = count($reservas) > 0;

$fmt_fecha = fn($f) => date('d M Y', strtotime($f));

$estado_cfg = [
  1 => ['label' => 'Activa', 'class' => 'estado-confirmada'],
  2 => ['label' => 'Pendiente', 'class' => 'estado-pendiente'],
  3 => ['label' => 'Completada', 'class' => 'estado-completada'],
  4 => ['label' => 'Cancelada', 'class' => 'estado-cancelada'],
];

$pago_icon = ['Bancolombia' => '🏦', 'Nequi' => '💜', 'Daviplata' => '❤️'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mis Reservas · Hotel Villa Marina</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Jost:wght@300;400;500&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="css/homes2.css" />
  <link rel="stylesheet" href="css/reservas.css" />
  <link rel="icon" href="img/recurso.png" type="image/png" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>

  <nav class="navbar">
    <div class="navbar-inner">
      <a href="index.php?action=getFormInicioExitoso" class="navbar-logo">
        <img src="img/logo.png" alt="Logo Hotel Villa Marina" class="logo-img"
          onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'" />
        <div class="logo-fallback" style="display:none;">
          <span class="logo-icon">✦</span>
          <span class="logo-text">VILLA MARINA</span>
        </div>
      </a>

      <ul class="navbar-menu">
        <li><a href="index.php?action=getFormInicioExitoso#inicio" class="nav-link">Inicio</a></li>
        <li><a href="index.php?action=getFormInicioExitoso#servicios" class="nav-link">Servicios</a></li>
        <li><a href="index.php?action=getFormInicioExitoso#habitaciones" class="nav-link">Habitaciones</a></li>
        <li><a href="index.php?action=reservas" class="nav-link nav-link--active">Mis Reservas</a></li>
      </ul>

      <div class="navbar-actions">
        <span class="nav-username"><?= $nombre ?></span>
        <a href="index.php?action=cerrarSesion" class="btn-logout">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
          </svg>
          Cerrar Sesión
        </a>
      </div>

      <button class="hamburger" id="hamburger" aria-label="Menú">
        <span></span><span></span><span></span>
      </button>
    </div>
  </nav>

  <header class="reservations-hero">
    <div class="hero-content">
      <p class="hero-tag">Área personal</p>
      <h1>Mis Reservas</h1>
      <p class="hero-subtitle">Aquí administras todas tus reservas desde un solo lugar.</p>
    </div>
  </header>

  <main class="main-content">
    <div class="content-inner">

      <?php if ($hay_reservas): ?>
        <div class="stats-row">
          <?php
          $total_reservas = count($reservas);
          $confirmadas = count(array_filter($reservas, fn($r) => (int) $r['estado'] === 1));
          $pendientes = count(array_filter($reservas, fn($r) => (int) $r['estado'] === 2));
          $completadas = count(array_filter($reservas, fn($r) => (int) $r['estado'] === 3));
          ?>
          <div class="stat-card">
            <span class="stat-icon">🗓️</span>
            <div>
              <p class="stat-num"><?= $total_reservas ?></p>
              <p class="stat-label">Total reservas</p>
            </div>
          </div>
          <div class="stat-card stat-card--green">
            <span class="stat-icon">✅</span>
            <div>
              <p class="stat-num"><?= $confirmadas ?></p>
              <p class="stat-label">Confirmadas</p>
            </div>
          </div>
          <div class="stat-card stat-card--yellow">
            <span class="stat-icon">⏳</span>
            <div>
              <p class="stat-num"><?= $pendientes ?></p>
              <p class="stat-label">Pendientes</p>
            </div>
          </div>
          <div class="stat-card stat-card--gray">
            <span class="stat-icon">🏁</span>
            <div>
              <p class="stat-num"><?= $completadas ?></p>
              <p class="stat-label">Completadas</p>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <div class="toolbar">
        <h2 class="toolbar-title"><?= $hay_reservas ? 'Historial de reservas' : 'Tus próximas reservas' ?></h2>
        <div class="toolbar-actions">
          <button type="button" class="btn btn-primary" onclick="abrirModalNuevaReserva()">
            <i class="fas fa-plus"></i> Nueva Reserva
          </button>
          <button type="button" class="btn btn-secondary" onclick="generarReporte()">
            <i class="fas fa-file-alt"></i> Reporte General
          </button>
        </div>
      </div>

      <?php if (!$hay_reservas): ?>
        <div class="empty-state">
          <div class="empty-icon">🛏️</div>
          <h3 class="empty-title">Aún no tienes reservas</h3>
          <p class="empty-msg">Crea tu primera reserva y revísala en esta sección.</p>
          <button type="button" class="btn btn-primary" onclick="abrirModalNuevaReserva()">Reservar ahora</button>
        </div>
      <?php else: ?>
        <div class="table-wrap">
          <table class="reservas-table">
            <thead>
              <tr>
                <th>Habitación</th>
                <th>Fechas</th>
                <th>Personas</th>
                <th>Pago</th>
                <th>Total</th>
                <th>Estado</th>
                <th class="th-acciones">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($reservas as $r):
                $ec = $estado_cfg[$r['estado']] ?? ['label' => $r['estado'], 'class' => ''];
                ?>
                <tr class="res-row" data-id="<?= $r['id'] ?>">
                  <td class="td-hab">
                    <div class="hab-cell">
                      <img
                        src="<?= htmlspecialchars($r['img'] ?? 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=900&q=80') ?>"
                        alt="Habitación <?= htmlspecialchars($r['habitacion']) ?>" class="hab-thumb" />
                      <div>
                        <p class="hab-nombre">Habitación <?= htmlspecialchars($r['habitacion']) ?></p>
                        <span class="hab-tipo"><?= htmlspecialchars($r['tipo']) ?></span>
                      </div>
                    </div>
                  </td>
                  <td class="td-fechas">
                    <div class="fechas-cell">
                      <div class="fecha-item">
                        <span class="fecha-lbl">Entrada</span>
                        <span class="fecha-val"><?= $fmt_fecha($r['entrada']) ?></span>
                      </div>
                      <div class="fecha-sep">→</div>
                      <div class="fecha-item">
                        <span class="fecha-lbl">Salida</span>
                        <span class="fecha-val"><?= $fmt_fecha($r['salida']) ?></span>
                      </div>
                      <span class="noches-badge"><?= htmlspecialchars($r['noches']) ?> noches</span>
                    </div>
                  </td>
                  <td class="td-center">
                    <span class="personas-cell">
                      <?= str_repeat('👤', min(4, (int) $r['personas'])) ?>
                      <span class="personas-num"><?= htmlspecialchars($r['personas']) ?></span>
                    </span>
                  </td>
                  <td class="td-center">
                    <span class="pago-cell"><?= $pago_icon[$r['pago']] ?? '💳' ?>     <?= htmlspecialchars($r['pago']) ?></span>
                  </td>
                  <td class="td-total">$<?= number_format($r['total'], 0, ',', '.') ?></td>
                  <td class="td-center"><span
                      class="estado-badge <?= $ec['class'] ?>"><?= htmlspecialchars($ec['label']) ?></span></td>
                  <td class="td-acciones">
                    <div class="acciones-wrap">
                      <button class="btn-accion btn-editar" title="Editar reserva"
                        onclick="abrirEditarReserva('<?= $r['id'] ?>')">
                        <i class="fas fa-pen-to-square"></i>
                      </button>
                      <button class="btn-accion btn-descargar" title="Descargar reserva PDF"
                        onclick="window.open('index.php?action=reporteReserva&id_reserva=<?= $r['id'] ?>', '_blank')">
                        <i class="fas fa-file-pdf"></i>
                      </button>
                      <button class="btn-accion btn-borrar" title="Cancelar reserva"
                        onclick="confirmarBorrar('<?= $r['id'] ?>')">
                        <i class="fas fa-ban"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </main>

  <div id="modalNuevaReserva" class="modal-backdrop" onclick="cerrarModalNuevaReserva(event)">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modal-nueva-title">
      <button class="modal-close" onclick="cerrarModalNuevaReserva(null, true)" aria-label="Cerrar">✕</button>
      <div class="modal-header">
        <div class="modal-header-img-wrap">
          <img id="modal-nueva-img"
            src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=900&q=80" alt=""
            class="modal-header-img" />
          <div class="modal-header-overlay"></div>
        </div>
        <div class="modal-header-info">
          <span class="modal-hab-tipo">Nueva Reserva</span>
          <h2 id="modal-nueva-title" class="modal-hab-nombre">Reservar habitación</h2>
          <div class="modal-precio-display">
            <span class="modal-precio-label">Precio por noche</span>
            <span id="modal-nueva-precio-noche" class="modal-precio-valor">—</span>
          </div>
        </div>
      </div>
      <div class="modal-body">
        <div class="modal-resumen" id="modal-nueva-resumen">
          <div class="resumen-row"><span class="resumen-label">Categoría</span><span id="res-nueva-categoria"
              class="resumen-valor">—</span></div>
          <div class="resumen-row"><span class="resumen-label">Habitación</span><span id="res-nueva-habitacion"
              class="resumen-valor">—</span></div>
          <div class="resumen-row"><span class="resumen-label">Noches</span><span id="res-nueva-noches"
              class="resumen-valor">—</span></div>
          <div class="resumen-row resumen-total"><span class="resumen-label">Total estimado</span><span
              id="res-nueva-total" class="resumen-valor resumen-total-val">—</span></div>
        </div>

        <form id="formNuevaReserva" class="modal-form" action="index.php?action=reservarHabitacion" method="POST">
          <input type="hidden" name="id_habitacion" id="input-nueva-hab-id">
          <input type="hidden" name="precio" id="input-nueva-precio-hidden">

          <div class="form-section">
            <p class="form-section-title">🏷️ Selecciona categoría</p>
            <select id="select-categoria" class="form-select" onchange="cambiarCategoria()">
              <option value="">Selecciona una categoría</option>
              <?php foreach ($categorias as $cat): ?>
                <option value="<?= htmlspecialchars($cat['id']) ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-section">
            <p class="form-section-title">🛏️ Selecciona habitación</p>
            <select id="select-habitacion" class="form-select" onchange="cambiarHabitacion()" disabled>
              <option value="">Primero selecciona una categoría</option>
            </select>
          </div>

          <div class="form-section">
            <p class="form-section-title">📅 Fechas de estadía</p>
            <div class="calendar-wrap">
              <div class="cal-month-nav">
                <button type="button" class="cal-nav-btn" onclick="cambiarMesNueva(-1)">‹</button>
                <span class="cal-month-label" id="cal-nueva-month-label"></span>
                <button type="button" class="cal-nav-btn" onclick="cambiarMesNueva(1)">›</button>
              </div>
              <div class="cal-grid-head">
                <span>Do</span><span>Lu</span><span>Ma</span><span>Mi</span><span>Ju</span><span>Vi</span><span>Sá</span>
              </div>
              <div class="cal-grid" id="cal-nueva-grid"></div>
            </div>
            <div class="fechas-display" id="fechas-nueva-texto"><span class="fecha-chip-hint">Selecciona entrada y
                salida</span></div>
            <input type="hidden" name="fecha_inicio" id="fecha-nueva-inicio">
            <input type="hidden" name="fecha_final" id="fecha-nueva-fin">
          </div>

          <div class="form-section">
            <p class="form-section-title">👥 Número de personas</p>
            <div class="personas-selector">
              <button type="button" class="personas-btn" id="personas-nueva-decr" onclick="cambiarPersonasNueva(-1)"
                disabled>−</button>
              <span class="personas-display">
                <span id="personas-nueva-num" class="personas-num">1</span>
                <span class="personas-label">persona<span id="personas-nueva-plural"
                    style="display:none;">s</span></span>
                <span id="personas-nueva-hint" class="personas-hint">Selecciona categoría y habitación para
                  activar</span>
              </span>
              <button type="button" class="personas-btn" id="personas-nueva-incr" onclick="cambiarPersonasNueva(1)"
                disabled>+</button>
            </div>
            <input type="hidden" name="num_personas" id="personas-nueva-input" value="1">
          </div>

          <div class="form-section">
            <p class="form-section-title">💳 Método de pago</p>
            <div class="pago-options" id="pago-opciones-nueva">
              <?php foreach ($metodos_pago as $pago): ?>
                <label class="pago-option">
                  <input type="radio" name="id_metodo_pago" value="<?= $pago['id'] ?>" <?= $pago['id'] == 1 ? 'checked' : '' ?>>
                  <span class="pago-icon"><?= $pago_icon[$pago['nombre']] ?? '💳' ?></span>
                  <span class="pago-name"><?= htmlspecialchars($pago['nombre']) ?></span>
                </label>
              <?php endforeach; ?>
            </div>
          </div>

          <button type="submit" class="btn-reservar" id="btn-nueva-reservar">Reservar Ahora</button>
        </form>
      </div>
    </div>
  </div>

  <div id="modalEditarReserva" class="modal-backdrop" onclick="cerrarModalEditar(event)">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modal-editar-title">
      <button class="modal-close" onclick="cerrarModalEditar(null, true)" aria-label="Cerrar">✕</button>
      <div class="modal-header modal-header--simple">
        <div class="modal-header-info">
          <span class="modal-hab-tipo text-center">Gestión de Reserva</span>
          <h2 id="modal-editar-title" class="modal-hab-nombre text-center">Editar reserva</h2>
        </div>
      </div>
      <div class="modal-body">
        <div class="modal-resumen modal-resumen--detallado" id="modal-edit-resumen">
          <div class="resumen-row"><span class="resumen-label">Categoría</span><span id="res-edit-categoria"
              class="resumen-valor">—</span></div>
          <div class="resumen-row"><span class="resumen-label">Habitación</span><span id="res-edit-habitacion"
              class="resumen-valor">—</span></div>
          <div class="resumen-row"><span class="resumen-label">Fechas</span><span id="res-edit-fechas"
              class="resumen-valor">—</span></div>
          <div class="resumen-row"><span class="resumen-label">Personas</span><span id="res-edit-personas"
              class="resumen-valor">—</span></div>
          <div class="resumen-row"><span class="resumen-label">Pago</span><span id="res-edit-pago"
              class="resumen-valor">—</span></div>
          <div class="resumen-divider"></div>
          <div class="resumen-row resumen-total"><span class="resumen-label">Total estimado</span><span
              id="res-edit-total" class="resumen-valor resumen-total-val">—</span></div>
        </div>

        <form id="formEditarReserva" class="modal-form">
          <input type="hidden" id="edit-reserva-id">
          <input type="hidden" id="edit-precio-hidden">

          <div class="form-section">
            <p class="form-section-title">🏷️ Categoría</p>
            <select id="edit-select-categoria" class="form-select" onchange="cambiarCategoriaEdit()">
              <option value="">Selecciona una categoría</option>
              <?php foreach ($categorias as $cat): ?>
                <option value="<?= htmlspecialchars($cat['id']) ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-section">
            <p class="form-section-title">🛏️ Habitación</p>
            <select id="edit-select-habitacion" class="form-select" onchange="cambiarHabitacionEdit()" disabled>
              <option value="">Selecciona una habitación</option>
            </select>
            <input type="hidden" id="edit-hab-id" name="id_habitacion">
          </div>

          <div class="form-section">
            <p class="form-section-title">📅 Fechas de estadía</p>
            <div class="calendar-wrap">
              <div class="cal-month-nav">
                <button type="button" class="cal-nav-btn" onclick="cambiarMesEdit(-1)">‹</button>
                <span class="cal-month-label" id="cal-edit-month-label"></span>
                <button type="button" class="cal-nav-btn" onclick="cambiarMesEdit(1)">›</button>
              </div>
              <div class="cal-grid-head">
                <span>Do</span><span>Lu</span><span>Ma</span><span>Mi</span><span>Ju</span><span>Vi</span><span>Sá</span>
              </div>
              <div class="cal-grid" id="cal-edit-grid"></div>
            </div>
            <div class="fechas-display" id="fechas-edit-texto"><span class="fecha-chip-hint">Selecciona entrada y
                salida</span></div>
            <input type="hidden" id="edit-fecha-inicio" name="fecha_inicio">
            <input type="hidden" id="edit-fecha-fin" name="fecha_final">
          </div>

          <div class="form-section">
            <p class="form-section-title">👥 Número de personas</p>
            <div class="personas-selector">
              <button type="button" class="personas-btn" id="personas-edit-decr"
                onclick="cambiarPersonasEdit(-1)">−</button>
              <span class="personas-display">
                <span id="personas-edit-num" class="personas-num">1</span>
                <span class="personas-label">persona<span id="personas-edit-plural"
                    style="display:none;">s</span></span>
              </span>
              <button type="button" class="personas-btn" id="personas-edit-incr"
                onclick="cambiarPersonasEdit(1)">+</button>
            </div>
            <input type="hidden" id="personas-edit-input" name="num_personas" value="1">
          </div>

          <div class="form-section">
            <p class="form-section-title">💳 Método de pago</p>
            <div class="pago-options" id="pago-opciones-edit">
              <?php foreach ($metodos_pago as $pago): ?>
                <label class="pago-option">
                  <input type="radio" name="id_metodo_pago_edit" value="<?= $pago['id'] ?>" <?= $pago['id'] == 1 ? 'checked' : '' ?>>
                  <span class="pago-icon"><?= $pago_icon[$pago['nombre']] ?? '💳' ?></span>
                  <span class="pago-name"><?= htmlspecialchars($pago['nombre']) ?></span>
                </label>
              <?php endforeach; ?>
            </div>
          </div>

          <button type="submit" class="btn-reservar">Guardar Cambios</button>
        </form>
      </div>
    </div>
  </div>

  <div id="modalDel" class="modal-backdrop" onclick="cerrarModalDel(event)">
    <div class="modal-box modal-del" role="dialog" aria-modal="true">
      <div class="modal-del-icon">⚠️</div>
      <h3 class="modal-del-title">¿Cancelar reserva?</h3>
      <p class="modal-del-msg">Estás a punto de cancelar la reserva <strong id="del-id"></strong>. Esta acción no se
        puede deshacer.</p>
      <div class="modal-del-actions">
        <button class="btn-del-cancel" onclick="cerrarModalDel()">No</button>
        <button class="btn-del-confirm" onclick="ejecutarBorrar()">Sí, cancelar</button>
      </div>
    </div>
  </div>

  <div id="toast" class="toast">
    <span id="toast-icon"></span>
    <div class="toast-content">
      <div id="toast-title" class="toast-title"></div>
      <div id="toast-sub" class="toast-sub"></div>
    </div>
  </div>

  <script>
    const habitaciones = <?= json_encode($habitaciones_lista) ?>;
    const reservasData = <?= json_encode($reservas) ?>;
    let currentNuevaDate = new Date();
    let nuevaInicio = null;
    let nuevaFin = null;
    let nuevaPrecio = 0;
    let nuevaMaxPersonas = 1;
    let reservaABorrar = null;

    function abrirModalNuevaReserva() {
      document.getElementById('modalNuevaReserva').classList.add('open');
      document.body.style.overflow = 'hidden';
      document.getElementById('select-categoria').value = '';
      document.getElementById('select-habitacion').innerHTML = '<option value="">Primero selecciona una categoría</option>';
      document.getElementById('select-habitacion').disabled = true;
      document.getElementById('input-nueva-hab-id').value = '';
      document.getElementById('input-nueva-precio-hidden').value = '';
      document.getElementById('modal-nueva-precio-noche').textContent = '—';
      document.getElementById('res-nueva-categoria').textContent = '—';
      document.getElementById('res-nueva-habitacion').textContent = '—';
      document.getElementById('fechas-nueva-texto').innerHTML = '<span class="fecha-chip-hint">Selecciona entrada y salida</span>';
      document.getElementById('fecha-nueva-inicio').value = '';
      document.getElementById('fecha-nueva-fin').value = '';
      document.getElementById('personas-nueva-input').value = '1';
      document.getElementById('personas-nueva-num').textContent = '1';
      document.getElementById('personas-nueva-plural').style.display = 'none';
      document.getElementById('personas-nueva-decr').disabled = true;
      document.getElementById('personas-nueva-incr').disabled = true;
      document.getElementById('personas-nueva-hint').textContent = 'Selecciona habitación y fechas para activar';
      nuevaInicio = null;
      nuevaFin = null;
      nuevaPrecio = 0;
      nuevaMaxPersonas = 1;
      currentNuevaDate = new Date();
      renderizarCalendarioNueva();
    }

    function cerrarModalNuevaReserva(event, force) {
      if (force || (event && event.target === document.getElementById('modalNuevaReserva'))) {
        document.getElementById('modalNuevaReserva').classList.remove('open');
        document.body.style.overflow = '';
      }
    }

    async function cambiarCategoria() {
      const selectCat = document.getElementById('select-categoria');
      const selectHab = document.getElementById('select-habitacion');

      const nombreCat = selectCat.value ? selectCat.options[selectCat.selectedIndex].text : '—';
      document.getElementById('res-nueva-categoria').textContent = nombreCat;
      document.getElementById('res-nueva-habitacion').textContent = '—';
      document.getElementById('input-nueva-hab-id').value = '';
      document.getElementById('input-nueva-precio-hidden').value = '';
      document.getElementById('modal-nueva-precio-noche').textContent = '—';
      nuevaPrecio = 0;
      nuevaMaxPersonas = 1;

      if (!selectCat.value) {
        selectHab.innerHTML = '<option value="" selected disabled>Seleccione una habitacion</option>';
        selectHab.disabled = true;
        return;
      }

      try {
        const response = await fetch(`index.php?action=getRoomsByType&type_room_id=${selectCat.value}`);
        const result = await response.json();
        const habitaciones = result.data;

        selectHab.innerHTML = '<option value="" selected disabled>Seleccione una habitacion</option>';

        if (result.ok && habitaciones.length > 0) {

          habitaciones.forEach((habitacion) => {
            selectHab.innerHTML += `<option value="${habitacion.id}" data-precio="${habitacion.precio}" data-max_personas="${habitacion.max_personas}">Habitacion ${habitacion.number} · ${habitacion.descripcion}</option>`;
          });

          selectHab.disabled = false;

        } else {
          selectHab.innerHTML = '<option value="" selected disabled>No hay habitaciones disponibles</option>';
          selectHab.disabled = true;
        }

      } catch (error) {
        console.log("fallo");
      }
    }

    function cambiarHabitacion() {
      const select = document.getElementById('select-habitacion');
      const habitacionId = select.value;
      const opcion = select.selectedOptions[0];
      if (!habitacionId) {
        document.getElementById('input-nueva-hab-id').value = '';
        document.getElementById('input-nueva-precio-hidden').value = '';
        document.getElementById('modal-nueva-precio-noche').textContent = '—';
        document.getElementById('res-nueva-habitacion').textContent = '—';
        nuevaPrecio = 0;
        nuevaMaxPersonas = 1;
        actualizarPersonasNuevaControls();
        return;
      }

      document.getElementById('input-nueva-hab-id').value = habitacionId;
      nuevaPrecio = parseFloat(opcion.dataset.precio) || 0;
      nuevaMaxPersonas = parseInt(opcion.dataset.max_personas) || 1;
      document.getElementById('input-nueva-precio-hidden').value = nuevaPrecio;
      document.getElementById('modal-nueva-precio-noche').textContent = '$' + nuevaPrecio.toLocaleString();
      document.getElementById('res-nueva-habitacion').textContent = opcion.textContent;
      actualizarPersonasNuevaControls();
      calcularTotalNueva();
    }

    function cambiarMesNueva(delta) {
      currentNuevaDate.setMonth(currentNuevaDate.getMonth() + delta);
      renderizarCalendarioNueva();
    }

    function renderizarCalendarioNueva() {
      const grid = document.getElementById('cal-nueva-grid');
      const label = document.getElementById('cal-nueva-month-label');
      grid.innerHTML = '';
      const year = currentNuevaDate.getFullYear();
      const month = currentNuevaDate.getMonth();
      const monthName = new Intl.DateTimeFormat('es-ES', { month: 'long', year: 'numeric' }).format(currentNuevaDate);
      label.textContent = monthName.charAt(0).toUpperCase() + monthName.slice(1);
      const firstDay = new Date(year, month, 1).getDay();
      const totalDays = new Date(year, month + 1, 0).getDate();
      const today = new Date(); today.setHours(0, 0, 0, 0);

      for (let i = 0; i < firstDay; i++) {
        const div = document.createElement('div');
        div.className = 'cal-cell cal-blank';
        grid.appendChild(div);
      }

      for (let d = 1; d <= totalDays; d++) {
        const dateObj = new Date(year, month, d);
        const dateStr = dateObj.toISOString().split('T')[0];
        const div = document.createElement('div');
        div.className = 'cal-cell';
        div.textContent = d;
        if (dateObj < today) {
          div.classList.add('cal-past');
        } else {
          div.onclick = () => seleccionarFechaNueva(dateStr);
          if (dateStr === nuevaInicio) div.classList.add('cal-start');
          if (dateStr === nuevaFin) div.classList.add('cal-end');
          if (nuevaInicio && nuevaFin && dateStr > nuevaInicio && dateStr < nuevaFin) div.classList.add('cal-range');
        }
        grid.appendChild(div);
      }
    }

    function seleccionarFechaNueva(fecha) {
      if (!nuevaInicio || (nuevaInicio && nuevaFin)) {
        nuevaInicio = fecha;
        nuevaFin = null;
        document.getElementById('fechas-nueva-texto').innerHTML = `<span class="fecha-chip entrada">Entrada: ${fecha}</span> <span class="fecha-chip-hint">Selecciona salida</span>`;
      } else if (fecha > nuevaInicio) {
        nuevaFin = fecha;
        document.getElementById('fechas-nueva-texto').innerHTML = `<span class="fecha-chip entrada">Entrada: ${nuevaInicio}</span> <span class="fecha-chip salida">Salida: ${nuevaFin}</span>`;
      } else {
        nuevaInicio = fecha;
        nuevaFin = null;
      }
      document.getElementById('fecha-nueva-inicio').value = nuevaInicio || '';
      document.getElementById('fecha-nueva-fin').value = nuevaFin || '';
      renderizarCalendarioNueva();
      calcularTotalNueva();
      actualizarPersonasNuevaControls();
    }

    function cambiarPersonasNueva(delta) {
      const input = document.getElementById('personas-nueva-input');
      const display = document.getElementById('personas-nueva-num');
      const plural = document.getElementById('personas-nueva-plural');
      const hint = document.getElementById('personas-nueva-hint');
      const habitacionSeleccionada = document.getElementById('select-habitacion').value;
      if (!habitacionSeleccionada || !nuevaInicio || !nuevaFin) return;
      let num = parseInt(input.value) + delta;
      num = Math.max(1, Math.min(nuevaMaxPersonas, num));
      input.value = num;
      display.textContent = num;
      plural.style.display = num > 1 ? 'inline' : 'none';
      hint.textContent = num >= nuevaMaxPersonas ? `Máximo ${nuevaMaxPersonas} persona${nuevaMaxPersonas > 1 ? 's' : ''}` : 'Selecciona fechas para continuar';
    }

    function actualizarPersonasNuevaControls() {
      const deshabilitar = !document.getElementById('select-habitacion').value || !nuevaInicio || !nuevaFin;
      document.getElementById('personas-nueva-decr').disabled = deshabilitar;
      document.getElementById('personas-nueva-incr').disabled = deshabilitar;
    }

    function calcularTotalNueva() {
      const resNoches = document.getElementById('res-nueva-noches');
      const resTotal = document.getElementById('res-nueva-total');
      if (nuevaInicio && nuevaFin && nuevaPrecio > 0) {
        const d1 = new Date(nuevaInicio);
        const d2 = new Date(nuevaFin);
        const noches = Math.round((d2 - d1) / (1000 * 60 * 60 * 24));
        resNoches.textContent = noches > 0 ? noches : '—';
        resTotal.textContent = noches > 0 ? '$' + (noches * nuevaPrecio).toLocaleString() : '—';
      } else {
        resNoches.textContent = '—';
        resTotal.textContent = '—';
      }
    }

    function validarNuevaReserva(e) {
      const categoria = document.getElementById('select-categoria').value;
      const habitacion = document.getElementById('select-habitacion').value;
      const fechaInicio = document.getElementById('fecha-nueva-inicio').value;
      const fechaFin = document.getElementById('fecha-nueva-fin').value;
      const personas = document.getElementById('personas-nueva-input').value;
      const pago = document.querySelector('input[name="id_metodo_pago"]:checked');

      if (!categoria || !habitacion || !fechaInicio || !fechaFin || !personas || !pago) {
        e.preventDefault();
        showToast('⚠️', 'Campos incompletos', 'Debes completar todos los campos antes de reservar.');
      }
    }

    function abrirEditarReserva(id) {
      const reserva = reservasData.find(r => r.id == id);
      if (!reserva) {
        showToast('⚠️', 'Error', 'Reserva no encontrada');
        return;
      }
      document.getElementById('modalEditarReserva').classList.add('open');
      document.body.style.overflow = 'hidden';
      document.getElementById('edit-reserva-id').value = reserva.id;
      document.getElementById('edit-fecha-inicio').value = reserva.entrada;
      document.getElementById('edit-fecha-fin').value = reserva.salida;
      document.getElementById('personas-edit-input').value = reserva.personas;
      document.getElementById('personas-edit-num').textContent = reserva.personas;
      document.getElementById('personas-edit-plural').style.display = reserva.personas > 1 ? 'inline' : 'none';

      // Set summary initially
      document.getElementById('res-edit-categoria').textContent = reserva.tipo || '—';
      document.getElementById('res-edit-habitacion').textContent = reserva.habitacion ? `Habitación ${reserva.habitacion}` : '—';
      document.getElementById('res-edit-fechas').textContent = `${reserva.entrada} al ${reserva.salida}`;
      document.getElementById('res-edit-personas').textContent = reserva.personas;
      document.getElementById('res-edit-pago').textContent = reserva.pago || '—';
      document.getElementById('res-edit-total').textContent = '$' + parseInt(reserva.total).toLocaleString();
      editPrecio = reserva.total / Math.max(1, Math.round((new Date(reserva.salida) - new Date(reserva.entrada)) / (1000 * 60 * 60 * 24)));

      const categoriaValue = reserva.categoria_id ?? '';
      document.getElementById('edit-select-categoria').value = categoriaValue;
      cargarHabitacionesEdit(categoriaValue, reserva.habitacion_id);
      document.querySelectorAll('input[name="id_metodo_pago_edit"]').forEach(input => {
        input.checked = input.value == reserva.id_metodo_pago;
      });
      currentEditDate = new Date(reserva.entrada);
      editInicio = reserva.entrada;
      editFin = reserva.salida;
      renderizarCalendarioEdit();
    }

    // ====== AQUÍ COMIENZA EL AJAX PARA EL MODAL DE EDICIÓN ======
    // Es el mismo concepto de la imagen, pero adaptado para cargar una habitación ya seleccionada (cuando editas)
    async function cargarHabitacionesEdit(categoriaId, selectedId = null) {
      const selectHab = document.getElementById('edit-select-habitacion');

      if (!categoriaId) {
        selectHab.innerHTML = '<option value="" selected disabled>Seleccione una habitacion</option>';
        selectHab.disabled = true;
        return;
      }

      try {
        // 1. Hacemos el fetch al PHP (igual a tu imagen)
        const response = await fetch(`index.php?action=getRoomsByType&type_room_id=${categoriaId}`);

        // 2. Convertimos a JSON (igual a tu imagen)
        const result = await response.json();

        // 3. Extraemos el array (igual a tu imagen)
        const habitaciones = result.data;

        // 4. Reiniciamos el select (igual a tu imagen)
        selectHab.innerHTML = '<option value="" selected disabled>Seleccione una habitacion</option>';

        if (result.ok && habitaciones.length > 0) {

          // 5. Recorremos las habitaciones con forEach (igual a tu imagen)
          habitaciones.forEach((habitacion) => {
            // 6. Añadimos el HTML (igual a tu imagen, sumando data-precio y data-max_personas para que la edición funcione)
            selectHab.innerHTML += `<option value="${habitacion.id}" data-precio="${habitacion.precio}" data-max_personas="${habitacion.max_personas}">Habitacion ${habitacion.number} · ${habitacion.descripcion}</option>`;
          });

          // 7. Habilitamos el select
          selectHab.disabled = false;

          // (Esta parte es extra para la edición: Si ya había una habitación guardada, la dejamos preseleccionada)
          if (selectedId) {
            selectHab.value = selectedId;
            cambiarHabitacionEdit();
          }

        } else {
          selectHab.innerHTML = '<option value="" selected disabled>No hay habitaciones disponibles</option>';
          selectHab.disabled = true;
        }

      } catch (error) {
        // 8. Mensaje de fallo (igual a tu imagen)
        console.log("fallo");
      }
    }

    function cambiarCategoriaEdit() {
      const selectCat = document.getElementById('edit-select-categoria');
      const categoriaId = selectCat.value;
      const nombreCat = categoriaId ? selectCat.options[selectCat.selectedIndex].text : '—';
      document.getElementById('res-edit-categoria').textContent = nombreCat;
      document.getElementById('res-edit-habitacion').textContent = '—';
      // Llamamos a la función de AJAX pasando el ID de la nueva categoría que elegimos
      cargarHabitacionesEdit(categoriaId);
    }
    // ====== FIN DE LA IMPLEMENTACIÓN DE AJAX PARA EDICIÓN ======

    function cambiarHabitacionEdit() {
      const select = document.getElementById('edit-select-habitacion');
      const opcion = select.selectedOptions[0];
      if (!opcion) return;
      document.getElementById('edit-hab-id').value = select.value;
      document.getElementById('edit-precio-hidden').value = opcion.dataset.precio || 0;
      const max = parseInt(opcion.dataset.max_personas) || 1;
      const num = Math.min(max, parseInt(document.getElementById('personas-edit-input').value));
      document.getElementById('personas-edit-input').value = num;
      document.getElementById('personas-edit-num').textContent = num;
      document.getElementById('personas-edit-plural').style.display = num > 1 ? 'inline' : 'none';

      document.getElementById('res-edit-habitacion').textContent = opcion.textContent;
      document.getElementById('res-edit-personas').textContent = num;

      editPrecio = parseFloat(opcion.dataset.precio) || 0;
      calcularTotalEdit();
    }

    let currentEditDate = new Date();
    let editInicio = null;
    let editFin = null;
    let editPrecio = 0;

    function calcularTotalEdit() {
      const resFechas = document.getElementById('res-edit-fechas');
      const resTotal = document.getElementById('res-edit-total');

      if (editInicio && editFin) {
        resFechas.textContent = `${editInicio} al ${editFin}`;
        if (editPrecio > 0) {
          const d1 = new Date(editInicio);
          const d2 = new Date(editFin);
          const noches = Math.max(1, Math.round((d2 - d1) / (1000 * 60 * 60 * 24)));
          resTotal.textContent = '$' + (noches * editPrecio).toLocaleString();
        } else {
          resTotal.textContent = '—';
        }
      } else {
        resFechas.textContent = editInicio ? `Desde ${editInicio}` : '—';
        resTotal.textContent = '—';
      }
    }

    function cambiarMesEdit(delta) {
      currentEditDate.setMonth(currentEditDate.getMonth() + delta);
      renderizarCalendarioEdit();
    }

    function renderizarCalendarioEdit() {
      const grid = document.getElementById('cal-edit-grid');
      const label = document.getElementById('cal-edit-month-label');
      grid.innerHTML = '';
      const year = currentEditDate.getFullYear();
      const month = currentEditDate.getMonth();
      const monthName = new Intl.DateTimeFormat('es-ES', { month: 'long', year: 'numeric' }).format(currentEditDate);
      label.textContent = monthName.charAt(0).toUpperCase() + monthName.slice(1);
      const firstDay = new Date(year, month, 1).getDay();
      const totalDays = new Date(year, month + 1, 0).getDate();
      const today = new Date(); today.setHours(0, 0, 0, 0);

      for (let i = 0; i < firstDay; i++) {
        const div = document.createElement('div');
        div.className = 'cal-cell cal-blank';
        grid.appendChild(div);
      }

      for (let d = 1; d <= totalDays; d++) {
        const dateObj = new Date(year, month, d);
        const dateStr = dateObj.toISOString().split('T')[0];
        const div = document.createElement('div');
        div.className = 'cal-cell';
        div.textContent = d;
        if (dateObj < today) {
          div.classList.add('cal-past');
        } else {
          div.onclick = () => seleccionarFechaEdit(dateStr);
          if (dateStr === editInicio) div.classList.add('cal-start');
          if (dateStr === editFin) div.classList.add('cal-end');
          if (editInicio && editFin && dateStr > editInicio && dateStr < editFin) div.classList.add('cal-range');
        }
        grid.appendChild(div);
      }
    }

    function seleccionarFechaEdit(fecha) {
      if (!editInicio || (editInicio && editFin)) {
        editInicio = fecha;
        editFin = null;
        document.getElementById('fechas-edit-texto').innerHTML = `<span class="fecha-chip entrada">Entrada: ${fecha}</span> <span class="fecha-chip-hint">Selecciona salida</span>`;
      } else if (fecha > editInicio) {
        editFin = fecha;
        document.getElementById('fechas-edit-texto').innerHTML = `<span class="fecha-chip entrada">Entrada: ${editInicio}</span> <span class="fecha-chip salida">Salida: ${editFin}</span>`;
      } else {
        editInicio = fecha;
        editFin = null;
      }
      document.getElementById('edit-fecha-inicio').value = editInicio || '';
      document.getElementById('edit-fecha-fin').value = editFin || '';
      renderizarCalendarioEdit();
      calcularTotalEdit();
    }

    function cambiarPersonasEdit(delta) {
      const input = document.getElementById('personas-edit-input');
      const display = document.getElementById('personas-edit-num');
      const plural = document.getElementById('personas-edit-plural');
      const max = parseInt(document.getElementById('edit-select-habitacion').selectedOptions[0]?.dataset.max_personas) || 1;
      let num = parseInt(input.value) + delta;
      num = Math.max(1, Math.min(max, num));
      input.value = num;
      display.textContent = num;
      plural.style.display = num > 1 ? 'inline' : 'none';
      document.getElementById('res-edit-personas').textContent = num;
    }

    function cerrarModalEditar(event, force) {
      if (force || (event && event.target === document.getElementById('modalEditarReserva'))) {
        document.getElementById('modalEditarReserva').classList.remove('open');
        document.body.style.overflow = '';
      }
    }

    function confirmarBorrar(id) {
      reservaABorrar = id;
      document.getElementById('modalDel').classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function cerrarModalDel() {
      document.getElementById('modalDel').classList.remove('open');
      document.body.style.overflow = '';
      reservaABorrar = null;
    }

    function ejecutarBorrar() {
      const id = reservaABorrar;
      if (!id) return;
      fetch('index.php?action=eliminarReserva', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id_reserva=${encodeURIComponent(id)}`
      })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            document.querySelectorAll(`[data-id="${id}"]`).forEach(el => el.remove());
            showToast('✅', 'Reserva cancelada', `La reserva ${id} fue cancelada.`);
            cerrarModalDel();
          } else {
            showToast('❌', 'Error', data.message || 'No se pudo cancelar la reserva.');
          }
        })
        .catch(() => showToast('❌', 'Error', 'No se pudo conectar al servidor.'));
    }

    function descargarReserva(id, hab, entrada, salida, total, pago) {
      const texto = [
        '╔══════════════════════════════════════╗',
        '║   HOTEL VILLA MARINA — RESERVA       ║',
        '╚══════════════════════════════════════╝',
        '',
        `Código: ${id}`,
        `Habitación: ${hab}`,
        `Entrada: ${entrada}`,
        `Salida: ${salida}`,
        `Pago: ${pago}`,
        `Total: ${total}`,
        '',
        'Gracias por elegirnos. Hotel Villa Marina',
      ].join('\n');
      const blob = new Blob([texto], { type: 'text/plain;charset=utf-8' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `Reserva-${id}.txt`;
      a.click();
      URL.revokeObjectURL(url);
    }

    function generarReporte() {
      const filas = document.querySelectorAll('.res-row');
      if (!filas.length) {
        showToast('⚠️', 'Sin reservas', 'No hay reservas para generar un reporte.');
        return;
      }
      
      // Abrimos el reporte de Excel en una nueva pestaña usando el router y controlador
      window.open('index.php?action=reporteGeneral', '_blank');
      showToast('📄', 'Generando reporte', 'Tu reporte de Excel está siendo descargado.');
    }

    function showToast(icon, title) {
      const toast = document.getElementById('toast');
      document.getElementById('toast-icon').textContent = icon;
      document.getElementById('toast-title').textContent = title;
      toast.classList.add('show');
      setTimeout(() => toast.classList.remove('show'), 4000);
    }

    document.getElementById('formNuevaReserva').addEventListener('submit', validarNuevaReserva);

    // Event listener for payment method change in edit modal
    document.querySelectorAll('input[name="id_metodo_pago_edit"]').forEach(radio => {
      radio.addEventListener('change', function () {
        const label = this.closest('.pago-option').querySelector('.pago-name').textContent;
        document.getElementById('res-edit-pago').textContent = label;
      });
    });

    document.getElementById('formEditarReserva').addEventListener('submit', function (e) {
      e.preventDefault();
      const id = document.getElementById('edit-reserva-id').value;
      const id_habitacion = document.getElementById('edit-hab-id').value;
      const fecha_inicio = document.getElementById('edit-fecha-inicio').value;
      const fecha_fin = document.getElementById('edit-fecha-fin').value;
      const personas = document.getElementById('personas-edit-input').value;
      const precio = document.getElementById('edit-precio-hidden').value;
      const pago = document.querySelector('input[name="id_metodo_pago_edit"]:checked')?.value;

      if (!id_habitacion || !fecha_inicio || !fecha_fin || !personas || !pago) {
        showToast('⚠️', 'Campos incompletos', 'Completa todos los campos para guardar.');
        return;
      }

      fetch('index.php?action=actualizarReserva', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `reserva_id=${encodeURIComponent(id)}&id_habitacion=${encodeURIComponent(id_habitacion)}&fecha_inicio=${encodeURIComponent(fecha_inicio)}&fecha_fin=${encodeURIComponent(fecha_fin)}&personas=${encodeURIComponent(personas)}&id_metodo_pago=${encodeURIComponent(pago)}&precio=${encodeURIComponent(precio)}`
      })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'success') {
            showToast('✅', 'Reserva actualizada', 'Los cambios se guardaron correctamente.');
            setTimeout(() => location.reload(), 1200);
          } else {
            showToast('❌', 'Error', 'No se pudo actualizar la reserva.');
          }
        })
        .catch(() => showToast('❌', 'Error', 'No se pudo conectar al servidor.'));
    });

    const hamburgerButton = document.getElementById('hamburger');
    if (hamburgerButton) {
      hamburgerButton.addEventListener('click', () => {
        hamburgerButton.classList.toggle('open');
      });
    }

    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') {
        cerrarModalNuevaReserva(null, true);
        cerrarModalEditar(null, true);
        cerrarModalDel();
      }
    });
  </script>
</body>

</html>