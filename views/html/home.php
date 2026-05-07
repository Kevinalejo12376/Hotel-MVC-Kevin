
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Hotel Villa Marina</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="css/home1.css"/>
</head>
<body class="bg-[#0D1B2A] text-white">

<!-- ===== NAVBAR ===== -->
<nav class="fixed top-0 w-full z-50 bg-[#0D1B2A]/80 backdrop-blur-md border-b border-white/10">
  <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
      <span class="text-2xl gold">✦</span>
      <span class="serif text-2xl tracking-widest font-light">VIÑA DEL MAR</span>
    </div>
    <div class="hidden md:flex items-center gap-8 text-sm tracking-widest text-white/70 uppercase">
      <a href="#servicios" class="hover:text-white transition">Servicios</a>
      <a href="#galeria"   class="hover:text-white transition">Galería</a>
      <a href="#contacto"  class="hover:text-white transition">Contacto</a>
    </div>
    <div class="flex gap-3">
      <a href="index.php?action=getFormLoginUser"   
        class="btn-outline px-5 py-2 text-sm tracking-widest uppercase font-light">
        Iniciar Sesión
    </a>
      <a href="index.php?action=getFormRegisterUser"
        class="btn-gold px-5 py-2 text-sm tracking-widest uppercase font-medium">
        Registrarse
    </a>
    </div>
  </div>
</nav>

<!-- ===== HERO ===== -->
<section class="hero-bg min-h-screen flex flex-col items-start justify-center text-left px-6">
  <div class="max-w-4xl">
    <p class="fade-up delay-1 text-xs tracking-[0.4em] uppercase gold mb-4">Bienvenido al</p>
    <h1 class="fade-up delay-2 serif text-6xl md:text-8xl font-light leading-tight mb-6">
      Hotel<br/><em>Villa Marina</em>
    </h1>
    <p class="fade-up delay-3 text-white/100 text-lg max-w-xl font-light leading-relaxed mb-10">
      Un refugio de lujo frente al mar. Donde cada detalle es una experiencia y cada momento, un recuerdo eterno.
    </p>
    <a href="#servicios" class="fade-up delay-3 btn-hero px-10 py-3 text-sm tracking-widest uppercase">
      Descubrir
    </a>
  </div>
</section>

<!-- ===== SERVICIOS ===== -->
<section id="servicios" class="py-28 px-6 max-w-7xl mx-auto">
  <div class="text-center mb-16">
    <p class="text-xs tracking-[0.4em] uppercase gold mb-3">Lo que ofrecemos</p>
    <h2 class="serif text-5xl font-light">Nuestros Servicios</h2>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <?php
    $servicios = [
      ['card-img-1', '🛏️', 'Habitaciones',  'Suites y habitaciones de lujo con vista al mar, diseñadas para tu máximo confort.'],
      ['card-img-2', '🏊', 'Piscina & Spa', 'Relájate en nuestra piscina infinity y disfruta tratamientos de spa de clase mundial.'],
      ['card-img-3', '🍽️', 'Restaurante',   'Gastronomía de autor con los mejores sabores del Mediterráneo y del Pacífico.'],
      ['card-img-4', '💆', 'Bienestar',     'Yoga al amanecer, masajes y rituales de bienestar para renovar cuerpo y mente.'],
    ];
    foreach ($servicios as $s): ?>
    <div class="group relative overflow-hidden h-80 cursor-pointer">
      <div class="<?= $s[0] ?> absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110"></div>
      <div class="absolute inset-0 bg-gradient-to-t from-[#0D1B2A] via-[#0D1B2A]/40 to-transparent"></div>
      <div class="absolute bottom-0 left-0 right-0 p-6">
        <div class="text-2xl mb-2"><?= $s[1] ?></div>
        <h3 class="serif text-2xl font-light mb-2"><?= $s[2] ?></h3>
        <p class="text-white/60 text-sm leading-relaxed opacity-0 group-hover:opacity-100 transition-opacity duration-300">
          <?= $s[3] ?>
        </p>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ===== STATS ===== -->
<section class="stats-bar py-16 px-6">
  <div class="max-w-5xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
    <?php foreach ([['98','Habitaciones'],['4.9','Calificación'],['15','Años de Excelencia'],['12K+','Huéspedes Felices']] as $s): ?>
    <div>
      <p class="serif text-5xl gold font-light"><?= $s[0] ?></p>
      <p class="text-white/50 text-xs tracking-widest uppercase mt-1"><?= $s[1] ?></p>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ===== GALERÍA ===== -->
<section id="galeria" class="py-28 px-6 max-w-7xl mx-auto">
  <div class="text-center mb-16">
    <p class="text-xs tracking-[0.4em] uppercase gold mb-3">Momentos únicos</p>
    <h2 class="serif text-5xl font-light">Galería</h2>
  </div>
  <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
    <?php
    $fotos = [
      ['h-64', 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80'],
      ['h-64', 'https://images.unsplash.com/photo-1455587734955-081b22074882?auto=format&fit=crop&w=800&q=80'],
      ['h-64', 'https://images.unsplash.com/photo-1444201983204-c43cbd584d93?auto=format&fit=crop&w=800&q=80'],
      ['h-48', 'https://images.unsplash.com/photo-1561501900-3701fa6a0864?auto=format&fit=crop&w=800&q=80'],
      ['h-48', 'https://hips.hearstapps.com/hmg-prod/images/mejores-hoteles-lujo-europa-israel-jaffa-1663058825.jpeg?crop=0.8886666666666666xw:1xh;center,top&resize=1200:*s'],
      ['h-48', 'https://images.unsplash.com/photo-1602002418082-a4443e081dd1?auto=format&fit=crop&w=800&q=80'],
    ];
    foreach ($fotos as $f): ?>
    <div class="<?= $f[0] ?> overflow-hidden group">
      <img src="<?= $f[1] ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Hotel Villa Marina"/>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ===== CONTACTO ===== -->
<section id="contacto" class="py-20 px-6 bg-[#081422]">
  <div class="max-w-2xl mx-auto text-center">
    <p class="text-xs tracking-[0.4em] uppercase gold mb-3">Estamos para ti</p>
    <h2 class="serif text-5xl font-light mb-6">Contáctanos</h2>
    <p class="text-white/50 mb-8">Reservas, consultas o simplemente para saber más sobre nuestras experiencias.</p>
    <div class="flex flex-col md:flex-row gap-4 justify-center text-sm text-white/60">
      <span>📍 Viña del Mar, Chile</span>
      <span>📞 +56 32 000 0000</span>
      <span>✉️ info@hotelvinadelmar.cl</span>
    </div>
  </div>
</section>

<!-- ===== FOOTER ===== -->
<footer class="border-t border-white/10 py-8 px-6 text-center text-white/30 text-xs tracking-widest">
  © <?= date('Y') ?> Hotel Villa Marina · Todos los derechos reservados
</footer>


</body>
</html>