<header class="header">
    <nav class="navbar {{ $class ?? '' }}">
        <div class="nav-wrapper container-wide">
            <div class="brand">
                <a href="{{ route('home') }}">
                    @if (!($hideLogoOnLoad ?? false))       
                    <img src="{{ asset('public/img/sample-realestate-brand/sample-realestate-logo-sm.svg') }}" alt="Sample Real Estate" class="regular-navbar-logo">
                    @endif
                    <img src="{{ asset('public/img/sample-realestate-brand/sample-realestate-logo-sm.svg') }}" alt="Sample Real Estate" class="sticky-navbar-logo">
                </a>
            </div>

            <div class="menu-toggle">
                <i class="fa-solid fa-bars"></i>
            </div>

            <div class="links flex flex-ai-c">
                <a href="{{ route('properties') }}" class="nav-link">Propiedades</a>
                <a href="{{ route('developments') }}" class="nav-link">Emprendimientos</a>
                <a href="{{ route('blog.index') }}" class="nav-link">Blog</a>
                <a href="#contact" class="btn btn-primary">Contactanos</a>
            </div>
        </div>
    </nav>
</header>