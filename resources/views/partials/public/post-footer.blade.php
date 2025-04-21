<section class="post-footer-section">
    <div class="container">
        <div class="row grid">
            <div class="block block-col-2-16 centerCcontent" id="block-2">
                {{-- @if (!Session::has('user'))
                    <p><span><a class="btn-sm btn-outlined-light" href="{{ route('login') }}">Iniciar Sesión</a></span></p>
                @else
                    <p>
                        <span><a href="{{ route('admin.dashboard') }}">Panel Admin</a></span>
                        <span>| <a href="{{ route('logout-user') }}">Cerrar sesión</a></span>
                    </p>
                @endif --}}
                <p>© <?php echo date('Y') ?> - Sample Real Estate | 
                Desarrollo web - <a href="https://mzdev.com.ar/" target="_blank" class="sencillostudio-brand"><strong>Milton Zini</strong></a> 
                </p>
            </div>
        </div>
    </div>
</section>
