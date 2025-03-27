<header class="header">
    <nav class="navbar {{ $class ?? '' }}">
        <div class="nav-wrapper container-wide">
            <div class="brand"><a href="#"><img src="{{ asset('public/img/sencillo/sencillostudio-sm.svg') }}" alt=""></a></div>

            <div class="menu-toggle">
                <i class="fa-solid fa-bars"></i>
            </div>

            <div class="links flex flex-ai-c">
                <a href="{{ "route('lorem')" }}" class="nav-link">Item 1</a>
                <a href="{{ "route('lorem')" }}" class="nav-link">Item 2</a>
                <a href="{{ "route('lorem')" }}" class="nav-link">Item 3</a>
                <a href="{{ "route('lorem')" }}" class="nav-link">Item 4</a>
                <a href="#contact" class="btn btn-outlined-light">Item 5</a>
                <a href="{{ "route('lorem')" }}" class="btn btn-primary">Item 6</a>
            </div>
        </div>
    </nav>
</header>