@section('title', 'Login')
@section('body-id', 'login-page')
{{-- @section('body-class', '') --}}
<x-PublicLayout>
<main>
    <section class="right-background-image-section text-dark-b" id="login">
    <div class="block-1">
        <div class="content-wrapper">
            <h3 class="section-title-center">Iniciar sesión</h3>
            <form method="post" action="{{ route('login-user') }}" onsubmit="showLoggingInMessageToast()" id="login-form">
                @csrf
                <input type="text" name="website" id="form-hidden-field">
                <input type="email" name="email" id="form-email" required placeholder="Email" value="">
                <input type="password" name="password" id="form-password" placeholder="Contraseña" value="">
                <button type="submit" class="btn btn-primary btn-user btn-block" id="login-button">Ingresar</button>
            </form>
            <div class="links">
                <a href="{{ route('home') }}">Volver a inicio</a>
            </div>
        </div>
    </div>
    <div class="block-2">
        &nbsp;
    </div>
</section>
</main>
@push('scripts')
    <!-- Begin Page level JS files -->
    <!-- End Page level JS files -->

    
    <!-- Begin Controller level JS files -->
    @if (isset($scripts) && !empty($scripts) && $scripts[0] !== '')
        @foreach ($scripts as $script)
            <script src="{{ asset('public/js/' . $script) }}"></script>
        @endforeach
    @endif
    <!-- End Controller level JS files -->
@endpush
</x-PublicLayout>