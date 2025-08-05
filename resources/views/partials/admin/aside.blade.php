<button class="aside-toggle-button" onclick="toggleAside()"><i class="bi bi-chevron-left"></i></button>
<aside class="dashboard-aside">
    <div class="aside-header">
        <div class="logo-wrapper">
            <a href="{{ route('admin.dashboard') }}" class="logo"><img src="{{ asset('public/img/sample-realestate-brand/sample-realestate-logo-sm.svg') }}" alt="brand-logo"></a>
        </div>
    </div>
    <div class="aside-content">
        <div class="aside-item {{ setActiveRoute('admin.dashboard') }}">
            <a href="{{ route('admin.dashboard')}}"><h4 class="aside-item-title "><i class="bi bi-house-fill"></i> Inicio</h4></a>
        </div>
        {{-- <div class="aside-category">
            <h4 class="aside-category-title">General</h4>
            <ul>
                <a href="#"><li class="item"><i class="bi bi-house-fill"></i> Link</li></a>
                <a href="#"><li class="item"><i class="bi bi-house-fill"></i> Link</li></a>
            </ul>
        </div> --}}
        <div class="aside-category">
            <h4 class="aside-category-title">Propiedades</h4>
            <ul>
                <a href="{{ route('admin.properties.create') }}"><li class="item {{ setActiveRoute('admin.properties.create') }}"><i class="bi bi-houses-fill"></i> Nueva Propiedad</li></a>
                <a href="{{ route('admin.properties.index') }}"><li class="item {{ setActiveRoute('admin.properties.index') }}"><i class="bi bi-houses-fill"></i> Ver Propiedades</li></a>
            </ul>
        </div>
        <div class="aside-category">
            <h4 class="aside-category-title">Emprendimientos</h4>
            <ul>
                <a href="{{ route('admin.developments.create') }}"><li class="item {{ setActiveRoute('admin.developments.create') }}"><i class="bi bi-houses-fill"></i> Nuevo Emprendimiento</li></a>
                <a href="{{ route('admin.developments.index') }}"><li class="item {{ setActiveRoute('admin.developments.index') }}"><i class="bi bi-houses-fill"></i> Ver Emprendimientos</li></a>
            </ul>
        </div>
        <div class="aside-category">
            <h4 class="aside-category-title">Lotes/Terrenos</h4>
            <ul>
                <a href="{{ route('admin.lots.create') }}"><li class="item {{ setActiveRoute('admin.lots.create') }}"><i class="bi bi-houses-fill"></i> Nuevo Lote/Terreno</li></a>
                <a href="{{ route('admin.lots.index') }}"><li class="item {{ setActiveRoute('admin.lots.index') }}"><i class="bi bi-houses-fill"></i> Ver Lotes/Terrenos</li></a>
            </ul>
        </div>
        <div class="aside-category">
            <h4 class="aside-category-title">Blog</h4>
                <ul>
                <a href="{{ route('admin.blog.create') }}"><li class="item {{ setActiveRoute('admin.usblogers.create') }}"><i class="bi bi-file-text-fill"></i> Nuevo Post</li></a>
                <a href="{{ route('admin.blog.index') }}"><li class="item {{ setActiveRoute('admin.blog.index') }}"><i class="bi bi-book-fill"></i> Ver Posts</li></a>
            </ul>
        </div>
        <div class="aside-category">
            <h4 class="aside-category-title">Usuarios</h4>
            <ul>
            <a href="{{ route('admin.users.create') }}"><li class="item {{ setActiveRoute('admin.users.create') }}"><i class="bi bi-person-fill"></i> Nuevo usuario</li></a>
            <a href="{{ route('admin.users.index') }}"><li class="item {{ setActiveRoute('admin.users.index') }}"><i class="bi bi-people-fill"></i> Ver Usuarios</li></a>
            </ul>
        </div>
    </div>
    <div class="user-block">
        <h5 class="Name">{{ Session('user')['name'] . ' ' . Session('user')['surname'] }}</h5>
        <p class="email">{{ Session('user')['email'] }}</p>
        
        <p class="role">[{{ Session('userRole') }}]</p>
        <div class="buttons-wrapper">
            <a href="{{ route('logout-user') }}" class="btn-sm btn-secondary-muted">Cerrar sesión</a>
            {{-- <a href="#" class="btn-sm btn-secondary-muted">Perfil</a> --}}
        </div>
    </div>
    {{-- <div class="aside-footer">
        <div class="sencillostudio-brand-wrapper">
            <a class="logo" href="https://sencillostudio.ar" target="_blank"><img src="{{ asset('public/img/sample-realestate-brand/sample-realestate-logo-sm.svg') }}" alt="sencillostudio-logo"> Panel</a>
        </div>
    </div> --}}
</aside>