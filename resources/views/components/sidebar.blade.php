<aside id="sidebar" class="w-64 bg-gray-900 text-white shadow-lg md:relative fixed md:translate-x-0">
    <!-- Logo -->
    <div class="p-6 border-b border-gray-700">
        <a href="{{ route('home') }}" class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"></path>
                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zm11 4a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <span class="text-xl font-bold">Tienda</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        @php
            $user = Auth::user();
            $role = $user->getPrimaryRole();
            $roleName = $role ? $role->slug : null;
        @endphp

        <!-- Super Admin Menu -->
        @if($roleName === 'super-admin')
            <div class="space-y-2">
                <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Administración</h3>
                
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.707.707a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                    </svg>
                    <span>Usuarios</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 7H7v6h6V7z"></path>
                        <path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2V2a1 1 0 112 0v1h1a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2h1a2 2 0 01-2 2h-1v1a1 1 0 11-2 0v-1h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H6a2 2 0 01-2-2v-1H3a1 1 0 110-2h1V9H3a1 1 0 110-2h1V5a2 2 0 012-2h1V2zM5 5h10v10H5V5z"></path>
                    </svg>
                    <span>Roles</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0L10 9.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"></path>
                    </svg>
                    <span>Permisos</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                    <span>Reportes</span>
                </a>
            </div>
        @endif

        <!-- Vendedor Menu -->
        @if($roleName === 'vendedor')
            <div class="space-y-2">
                <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Vendedor</h3>
                
                <a href="{{ route('seller.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('seller.dashboard') ? 'bg-blue-600' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.707.707a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"></path>
                    </svg>
                    <span>Mis Productos</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"></path>
                        <path d="M5 16a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        <path d="M16 16a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Órdenes</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267V7.418z"></path>
                        <path fill-rule="evenodd" d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h12a1 1 0 001-1V5a1 1 0 00-1-1H3zm12 12a2 2 0 01-2 2H3a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v11z"></path>
                    </svg>
                    <span>Reportes de Ventas</span>
                </a>
            </div>
        @endif

        <!-- Cliente Menu -->
        @if($roleName === 'cliente')
            <div class="space-y-2">
                <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Mi Compra</h3>
                
                <a href="{{ route('client.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('client.dashboard') ? 'bg-blue-600' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.707.707a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"></path>
                        <path d="M5 16a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        <path d="M16 16a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Catálogo</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"></path>
                        <path d="M5 16a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        <path d="M16 16a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Mis Compras</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 2a1 1 0 011-1h8a1 1 0 011 1v1h2a1 1 0 110 2h-.22l-.894 4.47a2 2 0 01-1.979 1.53h-7.613a2 2 0 01-1.979-1.53L2.22 6H2a1 1 0 110-2h2V2zm2 2v6h6V4H7z"></path>
                    </svg>
                    <span>Mi Carrito</span>
                </a>
            </div>
        @endif

        <!-- Menu General -->
        <div class="pt-6 border-t border-gray-700 space-y-2">
            <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">General</h3>
            
            <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2z"></path>
                </svg>
                <span>Soporte</span>
            </a>

            <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                </svg>
                <span>Valoraciones</span>
            </a>
        </div>
    </nav>

    <!-- Footer -->
    <div class="p-4 border-t border-gray-700">
        <p class="text-xs text-gray-400 text-center">© 2026 Tienda Online</p>
    </div>
</aside>
