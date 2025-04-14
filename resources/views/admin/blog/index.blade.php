@section('title', 'Listado Posts')
@section('body-id', 'admin-blog-index')
@section('body-class', 'admin')
<x-AdminLayout>

@include('partials.admin.aside')

<main>
    @include('partials.admin.nav', ['pageHeading' => 'Listado de Posts'])

    <div class="admin-main-content">
        <div class="container-wide py5">
            <div class="grid">
                <div class="block block-col-1-8">
                    <p><strong>Total:</strong> {{ $postsCount }} posts</p>
                </div>
                <div class="block block-col-9-16">
                    <form id="search-form" action="{{ route('admin.blog.search') }}" method="post">
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
                @if (!empty($posts) && count($posts) > 0 )
                    <div class="table-wrapper">
                        <table class="responsive-table">
                            <tr>
                                <th>Id</th>
                                <th>Título</th>
                                <th>Creado</th>
                                <th>Editado</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                            @foreach ($posts as $post)
                            <tr>
                                <td><strong>{{ $post->id }}</strong></td>
                                <td>{{ strlen($post->title) > 30 ? substr($post->title, 0, 30).'...' : $post->title }}</td>
                                <td>{{ $post->created_at }}</td>
                                <td>{{ $post->updated_at }}</td>
                                <td>{{ $post->status }}</td>
                                <td>
                                    <a href="{{ route('blog.post', $post->slug) }}" target="_blank">Ver</a> |
                                    <a href="{{ route('admin.blog.edit', $post->id) }}">Editar</a> |
                                    <a href="javascript:void(0)" class="text-danger" id="deletePostModalAnchor-{{$post->id}}">Eliminar</a>
                                    <div id="deletePostModal-{{$post->id}}" class="modal">
                                        <div class="modal-content">
                                            <span class="deletePostModal close">&times;</span>
                                            <div class="wrapper">
                                                <p>¿Está seguro de eliminar este post?</p>
                                                <div class="buttons-wrapper">
                                                    <a class="btn btn-sm btn-danger-muted" href="javascript:void(0)" id="deletePostAnchor-{{$post->id}}">Eliminar</a>
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
                <p>No se encontraron posts</p>
                @endif
              </div>
              <div class="block block-col-1-17">
                <div class="pagination p-4">
                {{ $posts->links() }}
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