@section('title', 'Crear Post')
@section('body-id', 'admin-create-post')
@section('body-class', 'admin')
<x-AdminLayout>

@include('partials.admin.aside')

<main>
    @include('partials.admin.nav', ['pageHeading' => 'Crear Post'])

    <div class="admin-main-content container">
        <form action="{{ route('admin.blog.store') }}" method="post" id="create-post-form">
            @csrf
            <div class="grid">
              <div class="block block-col-1-17 my6">
                <h3>General</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="title">Título</label>
                <input type="text" name="title" >
              </div>
              <div class="block block-col-9-17">
                <label for="slug">Slug</label>
                <input type="text" name="slug">
                </select>
              </div>
              <div class="block block-col-1-9">
                <label for="status">Estado</label>
                <select name="status">
                    <option value="activo">Activo</option>
                    <option value="oculto" selected>Oculto</option>
                </select>
              </div>
              <div class="block block-col-9-17">
                <label for="description">Descripción Breve</label>
                <textarea name="short_description"  minlength="100" maxlength="3000"></textarea>
              </div>


              <div class="block block-col-1-17">
                <label for="body">Contenido del Post</label>
                <textarea name="body" minlength="100" maxlength="20000" id="post-body-textarea"></textarea>
              </div>
            
              <div class="block block-col-1-17 my6"><hr class="divider"></div>


              <div class="block block-col-1-17 my6">
                <h3>Botón / Call to Action <small>(opcional)</small></h3>
              </div>
              <div class="block block-col-1-9">
                <label for="button_text">Texto del Botón <small>(ejemplo: "más información")</small></label>
                <input type="text" name="button_text">
              </div>
              <div class="block block-col-9-17">
                <label for="button_url">Link del botón <small>(ejemplo: www.sitio.com/casa-en-palermo)</small></label>
                <input type="text" name="button_url">
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>


              <div class="block block-col-1-17 my6">
                <h4>Imagen para el post</h4>
              </div>

              <div class="block block-col-1-17 my6">
                <label for="button_text">Texto alternativo para la imagen</label>
                <input type="text" name="img_alt">
                <label for="images">Seleccionar imagen</label>
                <input type="file" id="images" name="image" accept="image/*" class="form-control">
              </div>
              <div class="block block-col-1-17 my6">
                <div id="image-preview-container" class="sortable-list">
                  <!-- Aquí se cargarán las vistas previas de las imágenes -->
                </div>
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <div class="btn-wrapper text-center"><button type="submit" class="btn btn-primary" id="create-post-button">Guardar</button></div>
              </div>
            </div>
        </form>
    </div>
</main>
@push('scripts')
    <!-- Begin Page level JS files -->
    <script src="{{ asset('public/admin/vendor/ckeditor/ckeditor.js') }}"></script> 
    <script>
      CKEDITOR.replace('post-body-textarea');
    </script>
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