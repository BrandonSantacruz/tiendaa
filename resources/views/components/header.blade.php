<header class="bg-white shadow-md sticky top-0 z-30">
    <div class="flex items-center justify-between h-16 px-6">
        <!-- Hamburger Menu para mobile -->
        <button class="md:hidden text-gray-600 hover:text-gray-900" id="toggle-sidebar">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Logo/Title -->
        <div class="flex items-center flex-1 ml-4 md:ml-0">
            <h1 class="text-xl font-bold text-gray-900">Tienda Online</h1>
        </div>

        <!-- User Menu -->
        <div class="flex items-center space-x-4">
            <!-- User Info -->
            <div class="hidden md:block text-right">
                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500">
                    @php
                        $role = Auth::user()->getPrimaryRole();
                    @endphp
                    {{ $role ? ucfirst($role->name) : 'Sin rol' }}
                </p>
            </div>

            <!-- Dropdown Menu -->
            <div class="relative">
                <button class="flex items-center text-gray-600 hover:text-gray-900 focus:outline-none" id="user-menu-btn">
                    <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" alt="User">
                </button>

                <!-- Dropdown Items -->
                <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 first:rounded-t-lg">
                        Mi Perfil
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Configuración
                    </a>
                    <hr class="my-1">
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 last:rounded-b-lg">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    // Toggle sidebar en mobile
    document.getElementById('toggle-sidebar')?.addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        sidebar?.classList.toggle('open');
    });

    // Toggle user menu
    document.getElementById('user-menu-btn')?.addEventListener('click', function() {
        const menu = document.getElementById('user-menu');
        menu?.classList.toggle('hidden');
    });

    // Cerrar menu al clickear fuera
    document.addEventListener('click', function(event) {
        const userMenu = document.getElementById('user-menu');
        const userMenuBtn = document.getElementById('user-menu-btn');
        if (userMenu && userMenuBtn && !userMenuBtn.contains(event.target) && !userMenu.contains(event.target)) {
            userMenu.classList.add('hidden');
        }
    });
</script>
