@section('title', 'Emprendimientos')
@section('body-id', 'admin-developments-edit')
@section('body-class', 'admin')
<x-AdminLayout>

@include('partials.admin.aside')

<main>
    @include('partials.admin.nav', ['pageHeading' => 'Editar Emprendimiento #' . $development->id])

    <div class="admin-main-content container">
        <form action="{{ route('admin.developments.update', ['id' => $development->id]) }}" method="post" id="edit-development-form">
            @csrf
            <div class="grid">
              <div class="block block-col-1-17">
                <div class="option-buttons-wrapper">
                    <a href="{{ route('developmentDetails', $development->slug) }}" class="btn-sm btn-outlined-primary" target="_blank">ver emprendimiento</a>
                    <a href="{{ route('admin.developments.index') }}" class="btn-sm btn-outlined-primary">volver al listado</a>
                </div>
              </div>
              <div class="block block-col-1-17 my6">
                <h3>General</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="title">Título</label>
                <input type="text" name="title" value="{{ old('title', $development->title) }}">
              </div>
              <div class="block block-col-9-17">
                <label for="estimated_delivery_date">Fecha de entrega estimada</label>
                <input type="text" name="estimated_delivery_date" value="{{ old('estimated_delivery_date', $development->estimated_delivery_date) }}">
              </div>

              <div class="block block-col-1-9">
                <label for="development_type">Tipo de emprendimiento </label>
                <select name="development_type">
                    <option value="n-a" {{ old('development_type', $development->development_type) == 'n-a' ? 'selected' : '' }}>n/a</option>
                    <option value="casa" {{ old('development_type', $development->development_type) == 'Edificio de departamentos' ? 'selected' : '' }}>Edificio de departamentos</option>
                    <option value="casa" {{ old('development_type', $development->development_type) == 'Edificio de oficinas' ? 'selected' : '' }}>Edificio de oficinas</option>
                    <option value="casa" {{ old('development_type', $development->development_type) == 'Otro' ? 'selected' : '' }}>Otro</option>
                </select>
              </div>

              <div class="block block-col-9-17">
                <label for="status">Estado del anuncio</label>
                <select name="status">
                  <option value="activo" {{ old('status', $development->status) == 'activo' ? 'selected' : '' }}>Activo</option>
                  <option value="pausado" {{ old('status', $development->status) == 'pausado' ? 'selected' : '' }}>Pausado</option>
                  <option value="reservado" {{ old('status', $development->status) == 'reservado' ? 'selected' : '' }}>Reservado</option>
                  <option value="oculto" {{ old('status', $development->status) == 'oculto' ? 'selected' : '' }}>Oculto</option>
                  <option value="vendido" {{ old('status', $development->status) == 'vendido' ? 'selected' : '' }}>Vendido</option>
                </select>
              </div>
              <div class="block block-col-1-9">
                <label for="project_status">Estado del proyecto</label>
                <input type="text" name="project_status" value="{{ old('project_status', $development->project_status) }}">
              </div>

              <div class="block block-col-9-17">
                <label for="developer">Empresa Desarrolladora</label>
                <input type="text" name="developer" value="{{ old('developer', $development->developer) }}">
              </div>
              <div class="block block-col-1-9">
                <label for="featured">Destacado</label>
                <select name="featured">
                  <option value="0" {{ old('featured', $development->featured) == '0' ? 'selected' : '' }}>No</option>
                  <option value="1" {{ old('featured', $development->featured) == '1' ? 'selected' : '' }}>Sí</option>
                </select>
              </div>
              
              <div class="block block-col-1-17">
                <label for="description">Descripción</label>
                <textarea name="description" minlength="100" maxlength="3000">{{ old('description', $development->description) }}</textarea>
              </div>

              <div class="block block-col-1-17">
                <label for="project_details">Detalles del proyecto</label>
                <textarea name="project_details" minlength="100" maxlength="3000">{{ old('project_details', $development->project_details) }}</textarea>
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Ubicación</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="public_address">Dirección Pública</label>
                <input type="text" name="public_address" value="{{ old('public_address', $development->public_address) }}">
              </div>
              <div class="block block-col-9-17">
                <label for="real_address">Dirección Real <small>(iframe de Google Maps)</small> </label>
                <textarea name="real_address">{{ old('real_address', $development->real_address) }}</textarea>
              </div>
              <div class="block block-col-1-9">
                <label for="country">País</label>
                <input type="text" name="country" value="{{ old('country', $development->country) }}">
              </div>
              <div class="block block-col-9-17">
                <label for="state">Provincia/Estado</label>
                <input type="text" name="state" value="{{ old('state', $development->state) }}">
              </div>
              <div class="block block-col-1-9">
                <label for="city">Ciudad</label>
                <input type="text" name="city" value="{{ old('city', $development->city) }}">
              </div>
              <div class="block block-col-9-17">
                <label for="neighborhood">Barrio</label>
                <input type="text" name="neighborhood" value="{{ old('neighborhood', $development->neighborhood) }}">
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Servicios y Amenities</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="services">Servicios</label>
                <input type="text" name="services" value="{{ old('services', $development->services) }}">
              </div>
              <div class="block block-col-9-17">
                <label for="amenities">Amenities</label>
                <input type="text" name="amenities" value="{{ old('amenities', $development->amenities) }}">
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Valores y tipo de operación</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="price_range">Rango de precios</label>
                <input type="text" name="price_range" value="{{ old('price_range', $development->price_range) }}">
              </div>
              <div class="block block-col-9-17">
                <label for="transaction_type">Operación</label>
                <select name="transaction_type">
                  <option value="" {{ old('transaction_type', $development->transaction_type) == '' ? 'selected' : '' }}>Seleccione una opción</option>
                  <option value="venta" {{ old('transaction_type', $development->transaction_type) == 'venta' ? 'selected' : '' }}>Venta</option>
                  <option value="alquiler" {{ old('transaction_type', $development->transaction_type) == 'alquiler' ? 'selected' : '' }}>Alquiler</option>
                  <option value="otro" {{ old('transaction_type', $development->transaction_type) == 'otro' ? 'selected' : '' }}>Otro</option>
                </select>
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Video y link externo</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="video">Video <small>(iframe de Youtube)</small></label>
                <textarea name="video">{{ old('video', $development->video) }}</textarea>
              </div>
              <div class="block block-col-9-17">
                <label for="external_url">Link Externo <small>(ejemplo: www.sitio.com/casa-en-palermo)</small></label>
                <input type="text" name="external_url" value="{{ old('external_url', $development->external_url) }}">
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Notas</h3>
              </div>
              <div class="block block-col-1-17">
                <label for="private_notes">Notas (Privado admin)</label>
                <textarea name="private_notes" maxlength="2000">{{ old('private_notes', $development->private_notes) }}</textarea>
              </div>
              {{-- <div class="block block-col-1-17">
                <label for="title">Notas (vendedor)</label>
                <textarea name="seller_notes" maxlength="2000"></textarea>
              </div> --}}

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h4>Imágenes</h4>
              </div>

              <div class="block block-col-1-17 my6">
                <label for="images">Seleccionar imágenes <small>(al menos 5 imágenes)</small></label>
                <input type="file" id="images" name="images[]" multiple accept="image/*" class="form-control">
              </div>
              <div class="block block-col-1-17 my6">
                <div id="image-preview-container" class="sortable-list">
                  <!-- Aquí se cargarán las vistas previas de las imágenes -->
                  @foreach($development->images as $image)
                      <div class="image-item" data-file-index="{{ $loop->index }}" data-image-type="existing" data-image-id="{{ $image->id }}">
                          <div class="thumbnail-wrapper">
                              <img src="{{ asset('public/files/img/developments/' . $image->thumbnail_image) }}" alt="{{ $image->img_alt }}" class="preview-thumbnail {{ $image->img_class }}">
                              <input type="hidden" name="image_order[]" value="{{ $image->order }}">
                              <input type="hidden" name="image_id[]" value="{{ $image->id }}">
                              <a href="javascript:void(0)" class="btn-remove-development-image">X</a>
                              <span class="file-name">
                                  {{ strlen($image->image) > 18 ? substr($image->image, 0, 18) . '...' : $image->image }}
                              </span>
                          </div>
                      </div>
                  @endforeach
                </div>
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <div class="btn-wrapper text-center">
                  <button type="submit" class="btn-lg btn-accent" id="edit-development-button">Guardar</button>
                </div>
              </div>
            </div>
        </form>
        

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