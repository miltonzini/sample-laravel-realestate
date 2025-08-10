@section('title', 'Lotes/Terrenos')
@section('body-id', 'admin-lots-index')
@section('body-class', 'admin')
<x-AdminLayout>

@include('partials.admin.aside')

<main>
    @include('partials.admin.nav', ['pageHeading' => 'Listado de Lotes/Terrenos'])

    <div class="admin-main-content">
        <div class="container-wide py5">
            <div class="grid">
                <div class="block block-col-1-8">
                    <p><strong>Total:</strong> {{ $lotsCount }} lotes/terrenos</p>
                </div>
                <div class="block block-col-9-16">
                    <form id="search-form" action="{{ route('admin.lots.search') }}" method="post">
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
                @if (!empty($lots) && count($lots) > 0 )
                    <div class="table-wrapper">
                        <table class="responsive-table">
                            <tr>
                                <th>Id</th>
                                <th>Título</th>
                                <th>Transacción</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                            @foreach ($lots as $lot)
                            <tr>
                                <td><strong>{{ $lot->id }}</strong></td>
                                <td>{{ strlen($lot->title) > 30 ? substr($lot->title, 0, 30).'...' : $lot->title }}</td>
                                <td>{{ $lot->transaction_type }}</td>
                                <td>{{ $lot->status }}</td>
                                <td>
                                    <a href="{{-- route('lotDetails', $lot->slug) --}}" target="_blank">Ver</a> |
                                    <a href="{{ route('admin.lots.edit', $lot->id) }}">Editar</a> |
                                    <a href="javascript:void(0)" class="text-danger" id="deleteLotModalAnchor-{{$lot->id}}">Eliminar</a>
                                    <div id="deleteLotModal-{{$lot->id}}" class="modal">
                                        <div class="modal-content">
                                            <span class="deleteLotModal close">&times;</span>
                                            <div class="wrapper">
                                                <p>¿Está seguro de eliminar este lote/terreno?</p>
                                                <div class="buttons-wrapper">
                                                    <a class="btn btn-sm btn-danger-muted" href="javascript:void(0)" id="deleteLotAnchor-{{$lot->id}}">Eliminar</a>
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
                <p>No se encontraron lotes/terrenos</p>
                @endif
              </div>
              <div class="block block-col-1-17">
                <div class="pagination p-4">
                {{ $lots->links() }}
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
            <script src="{{ asset('public/admin/js/' . $script . $assetVersionQueryString) }}"></script>
        @endforeach
    @endif
    <!-- End Controller level JS files -->
@endpush
</x-AdminLayout>