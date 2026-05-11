<?php


$mensaje = $_SESSION["mensaje"] ?? "";
$tipo = $_SESSION["tipo"] ?? "";
$errores = $_SESSION["errors"] ?? [];
$datos = $_SESSION["datos"] ?? [];
$usuario = $_SESSION["usuario"] ?? null;


// LIMPIAR SESIÓN (IMPORTANTE)
unset($_SESSION["mensaje"], $_SESSION["tipo"], $_SESSION["errors"], $_SESSION["datos"]);
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Iniciar Sesión · Hotel Villa Marina</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="css/styles.css"/>
  <link rel="icon" href="img/recurso.png" type="image/png">
</head>
<body>

<div class="auth-layout">

  <!-- Panel izquierdo: imagen decorativa -->
  <div class="auth-panel-img">
    <div class="auth-panel-overlay"></div>
    <div class="auth-panel-content">
      <span class="auth-logo-icon">✦</span>
      <h2 class="auth-logo-name">VIÑA DEL MAR</h2>
      <p class="auth-panel-quote">
        "Un refugio de lujo frente al mar,<br/>donde cada momento es eterno."
      </p>
    </div>
  </div>

  <!-- Panel derecho: formulario -->
  <div class="auth-form-side">

    <!-- Volver al inicio -->
    <a href="index.php" class="auth-back-link">
      ← Volver al inicio
    </a>

    <div class="auth-form-box">
      <?php if (!empty($errores['general'])): ?>
        <div class="alert-error" style="margin-bottom:1rem;">
          <?= htmlspecialchars($errores['general']) ?>
        </div>
      <?php endif; ?>

      <p class="auth-tag">Bienvenido de vuelta</p>
      <h1 class="auth-title">Iniciar Sesión</h1>

      <form action="index.php?action=loginUser" method="POST" class="auth-form" id="loginForm">
        <div class="field-group">
          <label class="field-label" for="email">Email</label>
          <input
            id="email"
            type="email"
            name="email"
            placeholder="correo@ejemplo.com"
            class="field-input"
            value="<?= htmlspecialchars($datos['email'] ?? '') ?>"
          />
          <p id="emailError" class="field-error hidden"></p>
          <?php if (!empty($errores['email'])): ?>
            <p class="field-error"><?= htmlspecialchars($errores['email']) ?></p>
          <?php endif; ?>
        </div>
        <div class="field-group">
          <label class="field-label" for="password">Contraseña</label>
          <input
            id="password"
            type="password"
            name="password"
            required
            placeholder="••••••••"
            class="field-input"
          />
          <p id="passwordError" class="field-error hidden"></p>
          <?php if (!empty($errores['password'])): ?>
            <p class="field-error"><?= htmlspecialchars($errores['password']) ?></p>
          <?php endif; ?>
        </div>
        <button type="submit" class="btn-submit">
          Entrar
        </button>
      </form>

      <p class="auth-switch">
        <script>
          document.getElementById('loginForm').addEventListener('submit', function (e) {
            // Limpiar errores previos
            const errorFields = ['emailError', 'passwordError'];
            errorFields.forEach(id => {
              const el = document.getElementById(id);
              if (el) {
                el.textContent = '';
                el.classList.add('hidden');
              }
            });

            let valid = true;
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            if (!email) {
              document.getElementById('emailError').textContent = 'El email es obligatorio';
              document.getElementById('emailError').classList.remove('hidden');
              valid = false;
            } else if (!/^\S+@\S+\.\S+$/.test(email)) {
              document.getElementById('emailError').textContent = 'Email no válido';
              document.getElementById('emailError').classList.remove('hidden');
              valid = false;
            }
            if (!password) {
              document.getElementById('passwordError').textContent = 'La contraseña es obligatoria';
              document.getElementById('passwordError').classList.remove('hidden');
              valid = false;
            } else if (password.length < 8) {
              document.getElementById('passwordError').textContent = 'Mínimo 8 caracteres';
              document.getElementById('passwordError').classList.remove('hidden');
              valid = false;
            }
            if (!valid) {
              e.preventDefault();
            }
          });
        </script>
        ¿No tienes cuenta?
        <a href="index.php?action=getFormRegisterUser" class="auth-switch-link">Regístrate aquí</a>
      </p>

    </div>
  </div>

</div>

</body>
</html>