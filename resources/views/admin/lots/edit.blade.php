@section('title', 'Lotes/Terrenos')
@section('body-id', 'admin-lots-edit')
@section('body-class', 'admin')
<x-AdminLayout>

@include('partials.admin.aside')

<main>
    @include('partials.admin.nav', ['pageHeading' => 'Editar Lote/Terreno #' . $lot->id])

    <div class="admin-main-content container">
        <form action="{{ route('admin.lots.update', ['id' => $lot->id]) }}" method="post" id="edit-lot-form">
            @csrf
            <div class="grid">
              <div class="block block-col-1-17">
                <div class="option-buttons-wrapper">
                    <a href="{{ route('lotDetails', $lot->slug) }}" class="btn-sm btn-outlined-primary" target="_blank">ver lote/terreno</a>
                    <a href="{{ route('admin.lots.index') }}" class="btn-sm btn-outlined-primary">volver al listado</a>
                </div>
              </div>
              <div class="block block-col-1-17 my6">
                <h3>General</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="title">Título</label>
                <input type="text" name="title" value="{{ old('title', $lot->title) }}" required>
              </div>
              <div class="block block-col-9-17">
                <label for="status">Estado</label>
                <select name="status">
                    <option value="activo" {{ old('status', $lot->status) == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="pausado" {{ old('status', $lot->status) == 'pausado' ? 'selected' : '' }}>Pausado</option>
                    <option value="reservado" {{ old('status', $lot->status) == 'reservado' ? 'selected' : '' }}>Reservado</option>
                    <option value="oculto" {{ old('status', $lot->status) == 'oculto' ? 'selected' : '' }}>Oculto</option>
                    <option value="vendido" {{ old('status', $lot->status) == 'vendido' ? 'selected' : '' }}>Vendido</option>
                </select>
              </div>
              <div class="block block-col-1-9">
                <label for="featured">Destacado</label>
                <select name="featured">
                    <option value="0" {{ old('featured', $lot->featured) == '0' ? 'selected' : '' }}>No</option>
                    <option value="1" {{ old('featured', $lot->featured) == '1' ? 'selected' : '' }}>Sí</option>
                </select>
              </div>
              <div class="block block-col-9-17">
                <label for="is_in_gated_community">Está dentro de un barrio cerrado?</label>
                <select name="is_in_gated_community">
                    <option value="0" {{ old('is_in_gated_community', $lot->featured) == '0' ? 'selected' : '' }}>No</option>
                    <option value="1" {{ old('is_in_gated_community', $lot->featured) == '1' ? 'selected' : '' }}>Sí</option>
                </select>
              </div>
              <div class="block block-col-1-17">
                <label for="description">Descripción</label>
                <textarea name="description"  minlength="100" maxlength="3000">{{ old('description', $lot->description) }}</textarea>
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Ubicación</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="public_address">Dirección Pública</label>
                <input type="text" name="public_address" value="{{ old('public_address', $lot->public_address) }}"> 
              </div>
              <div class="block block-col-9-17">
                <label for="real_address">Dirección Real <small>(iframe de Google Maps)</small> </label>
                <textarea name="real_address">{{ old('real_address', $lot->real_address) }}</textarea>
              </div>
              <div class="block block-col-1-9">
                <label for="country">País</label>
                <input type="text" name="country" value="{{ old('country', $lot->country) }}" required>
              </div>
              <div class="block block-col-9-17">
                <label for="state">Provincia/Estado</label>
                <input type="text" name="state" value="{{ old('state', $lot->state) }}" required>
              </div>
              <div class="block block-col-1-9">
                <label for="city">Ciudad</label>
                <input type="text" name="city" value="{{ old('city', $lot->city) }}" required>
              </div>
              <div class="block block-col-9-17">
                <label for="neighborhood">Barrio</label>
                <input type="text" name="neighborhood" value="{{ old('neighborhood', $lot->neighborhood) }}">
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Descripción del Lote/Terreno</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="frontage">Frente (m)</label>
                <input type="text" name="frontage" value="{{ old('frontage', $lot->frontage) }}">
              </div>
              <div class="block block-col-9-17">
                <label for="depth">Fondo (m)</label>
                <input type="text" name="depth" value="{{ old('depth', $lot->depth) }}">
              </div>
              <div class="block block-col-1-9">
                <label for="total_area">Superficie Total (m²)</label>
                <input type="text" name="total_area" value="{{ old('total_area', $lot->total_area) }}">
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Servicios </h3>
              </div>
              <div class="block block-col-1-9">
                <label for="services">Servicios <small>(ej, luz, cloaca, gas natural)</small></label>
                <input type="text" name="services" value="{{ old('services', $lot->services) }}">
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Valores y tipo de operación</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="price">Precio</label>
                <input type="text" name="price" value="{{ old('price', $lot->price) }}">
              </div>
              <div class="block block-col-9-17">
                <label for="transaction_type">Operación</label>
                <select name="transaction_type">
                    <option value="" {{ old('transaction_type', $lot->transaction_type) == '' ? 'selected' : '' }}>Seleccione una opción</option>
                    <option value="venta" {{ old('transaction_type', $lot->transaction_type) == 'venta' ? 'selected' : '' }}>Venta</option>
                    <option value="alquiler" {{ old('transaction_type', $lot->transaction_type) == 'alquiler' ? 'selected' : '' }}>Alquiler</option>
                    <option value="otro" {{ old('transaction_type', $lot->transaction_type) == 'otro' ? 'selected' : '' }}>Otro</option>
                </select>
              </div>
              

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Video y link externo</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="video">Video <small>(iframe de Youtube)</small></label>
                <textarea name="video">{{ old('video', $lot->video) }}</textarea>
              </div>
              <div class="block block-col-9-17">
                <label for="external_url">Link Externo <small>(ejemplo: www.sitio.com/casa-en-palermo)</small></label>
                <input type="text" name="external_url" value="{{ old('external_url', $lot->external_url)}}">
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Notas</h3>
              </div>
              <div class="block block-col-1-17">
                <label for="private_notes">Notas (Privado admin)</label>
                <textarea name="private_notes" maxlength="2000">{{ old('private_notes', $lot->private_notes) }}</textarea>
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
                  @foreach($lot->images as $image)
                      <div class="image-item" data-file-index="{{ $loop->index }}" data-image-type="existing" data-image-id="{{ $image->id }}">
                          <div class="thumbnail-wrapper">
                              <img src="{{ asset('public/files/img/lots/' . $image->thumbnail_image) }}" alt="{{ $image->img_alt }}" class="preview-thumbnail {{ $image->img_class }}">
                              <input type="hidden" name="image_order[]" value="{{ $image->order }}">
                              <input type="hidden" name="image_id[]" value="{{ $image->id }}">
                              <a href="javascript:void(0)" class="btn-remove-lot-image">X</a>
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
                <div class="btn-wrapper text-center"><button type="submit" class="btn btn-primary" id="edit-lot-button">Guardar</button></div>
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