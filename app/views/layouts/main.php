<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? APP_NAME ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Configuración personalizada de Tailwind -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Estilos personalizados -->
    <style>
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .slide-in {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
        /* Oculta cualquier elemento con x-cloak hasta que Alpine.js lo procese */
        [x-cloak] { display: none !important; }
        /* Refuerzo: oculta el menú de usuario por defecto y solo Alpine.js lo muestra */
        .dropdown-menu-user { display: none; }
        [x-show="open"]:not([x-cloak]) { display: block !important; }
        @keyframes fadeInMenu { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeInMenu 0.18s ease-in; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-home text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-900"><?= APP_NAME ?></span>
                    </a>
                </div>
                
                <!-- Navegación principal -->
                <nav class="hidden md:flex space-x-8">
                    <a href="/" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        Inicio
                    </a>
                    <a href="/properties" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        Propiedades
                    </a>
                    <?php if (isAuthenticated()): ?>
                        <a href="/dashboard" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Dashboard
                        </a>
                    <?php endif; ?>
                </nav>
                
                <!-- Menú de usuario -->
                <div class="flex items-center space-x-4">
                    <?php if (isAuthenticated()): ?>
                        <!-- Notificaciones -->
                        <button class="relative p-2 text-gray-600 hover:text-primary-600 transition-colors">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400"></span>
                        </button>
                        <!-- Menú de usuario dinámico -->
                        <div class="relative" id="user-menu-container">
                            <button id="user-menu-button" type="button"
                                class="w-9 h-9 bg-primary-100 rounded-full flex items-center justify-center text-primary-600 font-bold text-lg shadow hover:bg-primary-200 focus:outline-none"
                                aria-haspopup="true" aria-expanded="false">
                                <?= strtoupper(substr($_SESSION['user_nombre'] ?? 'U', 0, 1)) ?>
                            </button>
                            <div id="user-menu-dropdown"
                                class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-50 animate-fade-in"
                                style="min-width: 12rem;">
                                <div class="px-4 py-2 text-sm text-gray-700 border-b">
                                    <?= htmlspecialchars($_SESSION['user_nombre'] ?? 'Usuario') ?>
                                </div>
                                <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">Mi Perfil</a>
                                <?php if (hasRole(ROLE_ADMIN)): ?>
                                    <a href="/admin/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">Panel Admin</a>
                                <?php endif; ?>
                                <?php if (hasRole(ROLE_AGENTE)): ?>
                                    <a href="/agente/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">Panel Agente</a>
                                <?php endif; ?>
                                <a href="/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">Cerrar Sesión</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Botones de autenticación -->
                        <a href="/login" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Iniciar Sesión
                        </a>
                        <a href="/register" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            Registrarse
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenido principal -->
    <main class="flex-1">
        <!-- Mensajes flash -->
        <?php $flashMessages = getFlashMessages(); ?>
        <?php if (!empty($flashMessages)): ?>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <?php foreach ($flashMessages as $message): ?>
                    <div class="fade-in mb-4 p-4 rounded-md <?= $message['type'] === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800' ?>">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <?php if ($message['type'] === 'success'): ?>
                                    <i class="fas fa-check-circle text-green-400"></i>
                                <?php else: ?>
                                    <i class="fas fa-exclamation-circle text-red-400"></i>
                                <?php endif; ?>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">
                                    <?= htmlspecialchars($message['message']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Contenido de la página -->
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Información de la empresa -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-home text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold"><?= APP_NAME ?></span>
                    </div>
                    <p class="text-gray-300 mb-4">
                        Plataforma líder en la gestión inmobiliaria, conectando clientes y agentes 
                        para facilitar la compra y venta de propiedades.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-linkedin text-xl"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Enlaces rápidos -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Enlaces Rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-gray-300 hover:text-white transition-colors">Inicio</a></li>
                        <li><a href="/properties" class="text-gray-300 hover:text-white transition-colors">Propiedades</a></li>
                        <li><a href="/about" class="text-gray-300 hover:text-white transition-colors">Acerca de</a></li>
                        <li><a href="/contact" class="text-gray-300 hover:text-white transition-colors">Contacto</a></li>
                    </ul>
                </div>
                
                <!-- Información de contacto -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contacto</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-primary-400"></i>
                            Santo Domingo, RD
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-2 text-primary-400"></i>
                            (809) 555-0000
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-2 text-primary-400"></i>
                            info@propeasy.com
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Línea divisoria -->
            <div class="border-t border-gray-700 mt-8 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-300 text-sm">
                        &copy; <?= date('Y') ?> <?= APP_NAME ?>. Todos los derechos reservados.
                    </p>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a href="/privacy" class="text-gray-300 hover:text-white text-sm transition-colors">
                            Política de Privacidad
                        </a>
                        <a href="/terms" class="text-gray-300 hover:text-white text-sm transition-colors">
                            Términos de Servicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Alpine.js para interactividad -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- Scripts personalizados -->
    <script>
        // Función para cerrar mensajes flash automáticamente
        document.addEventListener('DOMContentLoaded', function() {
            const flashMessages = document.querySelectorAll('.fade-in');
            flashMessages.forEach(function(message) {
                setTimeout(function() {
                    message.style.opacity = '0';
                    setTimeout(function() {
                        message.remove();
                    }, 300);
                }, 5000);
            });

            // Refuerzo para cerrar el menú de usuario si queda abierto
            // Busca el menú desplegable y el botón de usuario
            const userDropdown = document.querySelector('[x-data] > div[x-show]');
            const userDropdownButton = document.querySelector('[x-data] > button');
            if (userDropdown && userDropdownButton) {
                // Cierra el menú al hacer clic en cualquier opción
                userDropdown.querySelectorAll('a').forEach(function(link) {
                    link.addEventListener('click', function() {
                        // Forzar el cierre usando Alpine.js
                        const alpineComponent = userDropdown.parentElement.__x;
                        if (alpineComponent) {
                            alpineComponent.$data.open = false;
                        }
                    });
                });
                // Cierra el menú al hacer clic fuera (refuerzo, Alpine ya lo hace, pero esto es extra)
                document.addEventListener('click', function(event) {
                    if (!userDropdown.contains(event.target) && !userDropdownButton.contains(event.target)) {
                        const alpineComponent = userDropdown.parentElement.__x;
                        if (alpineComponent) {
                            alpineComponent.$data.open = false;
                        }
                    }
                });
            }
        });
        
        // Función para mostrar/ocultar menú móvil
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('user-menu-button');
            const menu = document.getElementById('user-menu-dropdown');
            const container = document.getElementById('user-menu-container');
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                menu.classList.toggle('hidden');
            });
            // Cierra el menú al hacer clic fuera
            document.addEventListener('click', function(e) {
                if (!container.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
            // Cierra el menú al seleccionar una opción
            menu.querySelectorAll('a').forEach(function(link) {
                link.addEventListener('click', function() {
                    menu.classList.add('hidden');
                });
            });
        });
    </script>
</body>
</html> 