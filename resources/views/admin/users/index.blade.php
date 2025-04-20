@section('title', 'Usuarios')
@section('body-id', 'admin-users-index')
@section('body-class', 'admin')
<x-AdminLayout>

@include('partials.admin.aside')

<main>
    @include('partials.admin.nav', ['pageHeading' => 'Listado de Usuarios'])

    <div class="admin-main-content">
        <div class="container-wide py5">
            <div class="grid">
                <div class="block block-col-1-8">
                    <p><strong>Total:</strong> {{ $usersCount }} usuarios registrados</p>
                </div>
                <div class="block block-col-9-16">
                    <form id="search-form" action="{{ route('admin.users.search') }}" method="post">
                        @csrf
                        <div class="search-container">
                            <input type="text" placeholder="Buscar..." name="search">
                            <button type="submit">&#128269;</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="container-wide">
            <div class="grid">
              <div class="block block-col-1-17">
                @if (!empty($users) && count($users) > 0 )
                    <div class="table-wrapper">
                        <table class="responsive-table">
                            <tr>
                                <th>Id</th>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Fecha registro</th>
                                <th>Acciones</th>
                            </tr>
                            @foreach ($users as $user)
                            <tr>
                                <td><strong>{{ $user->id }}</strong></td>
                                <td>{{ $user->name . ' ' . $user->surname }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role }}</td>
                                <td>{{ $user->created_at->format('d-M-y') }}</td>
                                <td>
                                    {{-- <a href="#" target="_blank">Ver</a> | --}}
                                    <a href="{{ route('admin.users.edit', $user->id) }}">Editar</a> |
                                    <a href="javascript:void(0)" class="text-danger" id="deleteUserModalAnchor-{{$user->id}}">Eliminar</a>
                                    <div id="deleteUserModal-{{$user->id}}" class="modal">
                                        <div class="modal-content">
                                            <span class="deleteUserModal close">&times;</span>
                                            <div class="wrapper">
                                                <p>¿Está seguro de eliminar a este usuario?</p>
                                                <div class="buttons-wrapper">
                                                    <a class="btn btn-sm btn-danger-muted" href="javascript:void(0)" id="deleteUserAnchor-{{$user->id}}">Eliminar</a>
                                                    <a class="btn btn-sm btn-primary cancel-button" href="javascript:void(0)">Cancelar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                
                    </div>
                @else
                <p>No se encontraron usuarios</p>
                @endif
              </div>
              <div class="block block-col-1-17">
                <div class="pagination p-4">
                {{ $users->links() }}
                <div>
              </div>
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
            <script src="{{ asset('public/admin/js/' . $script . $assetVersionQueryString) }}"></script>
        @endforeach
    @endif
    <!-- End Controller level JS files -->
@endpush
</x-AdminLayout>