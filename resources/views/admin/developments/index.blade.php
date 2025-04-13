@section('title', 'Emprendimientos')
@section('body-id', 'admin-developments-index')
@section('body-class', 'admin')
<x-AdminLayout>

@include('partials.admin.aside')

<main>
    @include('partials.admin.nav', ['pageHeading' => 'Listado de Emprendimientos'])

    <div class="admin-main-content">
        <div class="container-wide py5">
            <div class="grid">
                <div class="block block-col-1-8">
                    <p><strong>Total:</strong> {{ $developmentsCount }} emprendimientos</p>
                </div>
                <div class="block block-col-9-16">
                    <form id="search-form" action="{{ route('admin.developments.search') }}" method="post">
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
                @if (!empty($developments) && count($developments) > 0 )
                    <div class="table-wrapper">
                        <table class="responsive-table">
                            <tr>
                                <th>Id</th>
                                <th>Título</th>
                                <th>Tipo de propiedad</th>
                                <th>Entrega estimada</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                            @foreach ($developments as $development)
                            <tr>
                                <td><strong>{{ $development->id }}</strong></td>
                                <td>{{ strlen($development->title) > 30 ? substr($development->title, 0, 30).'...' : $development->title }}</td>
                                <td>{{ $development->property_type }}</td>
                                <td>{{ $development->estimated_delivery_date }}</td>
                                <td>{{ $development->status }}</td>
                                <td>
                                    <a href="{{ route('developmentDetails', $development->slug) }}" target="_blank">Ver</a> |
                                    <a href="{{ route('admin.developments.edit', $development->id) }}">Editar</a> |
                                    <a href="javascript:void(0)" class="text-danger" id="deleteDevelopmentModalAnchor-{{$development->id}}">Eliminar</a>
                                    <div id="deleteDevelopmentModal-{{$development->id}}" class="modal">
                                        <div class="modal-content">
                                            <span class="deleteDevelopmentModal close">&times;</span>
                                            <div class="wrapper">
                                                <p>¿Está seguro de eliminar este emprendimiento?</p>
                                                <div class="buttons-wrapper">
                                                    <a class="btn btn-sm btn-danger-muted" href="javascript:void(0)" id="deleteDevelopmentAnchor-{{$development->id}}">Eliminar</a>
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
                <p>No se encontraron emprendimientos</p>
                @endif
              </div>
              <div class="block block-col-1-17">
                <div class="pagination p-4">
                {{ $developments->links() }}
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
    @if (isset($scripts))
        @foreach ($scripts as $script)
            <script src="{{ asset('public/admin/js/' . $script) }}"></script>
        @endforeach
    @endif
    <!-- End Controller level JS files -->
@endpush
</x-AdminLayout>