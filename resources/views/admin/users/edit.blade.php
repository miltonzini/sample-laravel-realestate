@section('title', 'Editar Usuario')
@section('body-id', 'admin-users-edit')
@section('body-class', 'admin')
<x-AdminLayout>

@include('partials.admin.aside')

<main>
    @include('partials.admin.nav', ['pageHeading' => 'Editar Usuario #' . $user->id])

    <div class="admin-main-content container">
        <form class="user" action="{{ route('admin.users.update', ['id' => $user->id]) }}" method="post" id="edit-user-form">
            @csrf
            <div class="grid">
                <div class="block block-col-1-9">
                <label for="name">Nombre</label>  
                    <input type="text" class="form-control form-control-user" placeholder="Nombre" name="name" value="{{ old('name', $user->name) }}">
                </div>
                <div class="block block-col-9-17">
                <label for="surname">Apellido</label>  
                    <input type="text" class="form-control form-control-user" placeholder="Apellido" name="surname" value="{{ old('surname', $user->surname) }}">
                </div>
                <div class="block block-col-1-9">
                <label for="email">Correo electrónico</label>  
                    <input type="email" class="form-control form-control-user" placeholder="Email" name="email" value="{{ old('email', $user->email) }}">
                </div>
                <div class="block block-col-9-17">
                <label for="role">Rol de usuario</label>  
                    <select name="role">
                        <option value="guest" {{ old('role', $user->role) == 'guest' ? 'selected' : '' }}>Invitado</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrador</option>
                    </select>
                </div>
                
                <div class="block block-col-1-9">
                    <label for="password">Contraseña <small>(opcional)</small></label>  
                    <input type="password" class="form-control form-control-user" placeholder="Contraseña" name="password" autocomplete="new-password">
                </div>
                <div class="block block-col-9-17">
                    <label for="repeat-password">Repetir Contraseña</label>  
                    <input type="password" class="form-control form-control-user" placeholder="Repetir contraseña" name="repeat-password" autocomplete="new-password">
                </div>
                
                <div class="block block-col-1-17 my6">                    
                    <div class="btn-wrapper text-center"><button type="submit" class="btn btn-primary" id="edit-user-button">Actualizar Usuario</button></div>                    
                </div>
            </div>
        </form>
    </div>

</main>
@push('scripts')
    <!-- Begin Page level JS files -->
    <!-- End Page level JS files -->

    
    <!-- Begin Controller level JS files -->
    @if (isset($scripts) && !empty($scripts) && $scripts[0] !== '')
        @foreach ($scripts as $script)
            <script src="{{ asset('public/admin/js/' . $script . $assetVersionQueryString) }}"></script>
        @endforeach
    @endif
    <!-- End Controller level JS files -->
@endpush
</x-AdminLayout>