@section('title', 'Crear Usuario')
@section('body-id', 'admin-create-user')
@section('body-class', 'admin')
<x-AdminLayout>

@include('partials.admin.aside')

<main>
    @include('partials.admin.nav', ['pageHeading' => 'Crear Usuario'])

    <div class="admin-main-content container">
        <div class="grid">
          <div class="block block-col-1-8">
            <form class="user" action="{{ route('admin.users.store') }}" method="post" id="create-user-dashboard-form">
                @csrf
                <input type="text" class="form-control form-control-user" placeholder="Nombre" name="name">
                <input type="text" class="form-control form-control-user" placeholder="Apellido" name="surname">
                <input type="email" class="form-control form-control-user" placeholder="Email" name="email">
                <input type="password" class="form-control form-control-user" placeholder="Contraseña" name="password">
                <input type="password" class="form-control form-control-user" placeholder="Repetir contraseña" name="repeat-password">

                <button type="submit" class="btn btn-primary btn-user btn-block" id="create-user-dashboard-button">Crear Usuario</button>
            </form>
          </div>
        </div>
        
        

    </div>
</main>
@push('scripts')
    <!-- Begin Page level JS files -->
    <!-- End Page level JS files -->

    
    <!-- Begin Controller level JS files -->
    @if (isset($scripts) && !empty($scripts) && $scripts[0] !== '')
        @foreach ($scripts as $script)
            <script src="{{ asset('public/admin/js/' . $script) }}"></script>
        @endforeach
    @endif
    <!-- End Controller level JS files -->
@endpush
</x-AdminLayout>