@section('title', 'Propiedades')
@section('body-id', 'admin-properties-index')
@section('body-class', 'admin')
<x-AdminLayout>

@include('partials.admin.aside')

<main>
    @include('partials.admin.nav', ['pageHeading' => 'Listado de Propiedades'])

    <div class="admin-main-content">
        <div class="container-wide py5">
            <div class="grid">
                <div class="block block-col-1-8">
                    <p><strong>Total:</strong> {{ $propertiesCount }} propiedades</p>
                </div>
                <div class="block block-col-9-16">
                    <form id="search-form" action="{{ route('admin.properties.search') }}" method="post">
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
                @if (!empty($properties) && count($properties) > 0 )
                    <div class="table-wrapper">
                        <table class="responsive-table">
                            <tr>
                                <th>Id</th>
                                <th>Título</th>
                                <th>Tipo de propiedad</th>
                                <th>Transacción</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                            @foreach ($properties as $property)
                            <tr>
                                <td><strong>{{ $property->id }}</strong></td>
                                <td>{{ strlen($property->title) > 30 ? substr($property->title, 0, 30).'...' : $property->title }}</td>
                                <td>{{ $property->property_type }}</td>
                                <td>{{ $property->transaction_type }}</td>
                                <td>{{ $property->status }}</td>
                                <td>
                                    <a href="{{ route('propertyDetails', $property->slug) }}" target="_blank">Ver</a> |
                                    <a href="{{ route('admin.properties.edit', $property->id) }}">Editar</a> |
                                    <a href="javascript:void(0)" class="text-danger" id="deletePropertyModalAnchor-{{$property->id}}">Eliminar</a>
                                    <div id="deletePropertyModal-{{$property->id}}" class="modal">
                                        <div class="modal-content">
                                            <span class="deletePropertyModal close">&times;</span>
                                            <div class="wrapper">
                                                <p>¿Está seguro de eliminar esta propiedad?</p>
                                                <div class="buttons-wrapper">
                                                    <a class="btn btn-sm btn-danger-muted" href="javascript:void(0)" id="deletePropertyAnchor-{{$property->id}}">Eliminar</a>
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
                <p>No se encontraron propiedades</p>
                @endif
              </div>
              <div class="block block-col-1-17">
                <div class="pagination p-4">
                {{ $properties->links() }}
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