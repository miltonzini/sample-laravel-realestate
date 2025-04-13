@section('title', 'Propiedades')
@section('body-id', 'admin-properties-edit')
@section('body-class', 'admin')
<x-AdminLayout>

@include('partials.admin.aside')

<main>
    @include('partials.admin.nav', ['pageHeading' => 'Editar Propiedad #' . $property->id])

    <div class="admin-main-content container">
        <form action="{{ route('admin.properties.update', ['id' => $property->id]) }}" method="post" id="edit-property-form">            
            @csrf
            <div class="grid">
              <div class="block block-col-1-17 my6">
                <h3>General</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="title">Título</label>
                <input type="text" name="title" value="{{ old('title', $property->title) }}">
              </div>
              <div class="block block-col-9-17">
                <label for="property_type">Tipo de propiedad</label>
                <select name="property_type">
                    <option value="casa" {{ old('property_type', $property->property_type) == 'casa' ? 'selected' : '' }}>Casa</option>
                    <option value="departamento" {{ old('property_type', $property->property_type) == 'departamento' ? 'selected' : '' }}>Departamento</option>
                    <option value="ph" {{ old('property_type', $property->property_type) == 'ph' ? 'selected' : '' }}>PH</option>
                    <option value="monoambiente" {{ old('property_type', $property->property_type) == 'monoambiente' ? 'selected' : '' }}>Monoambiente</option>
                </select>
              </div>
              <div class="block block-col-1-9">
                <label for="status">Estado</label>
                <select name="status">
                    <option value="activo" {{ old('status', $property->status) == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="pausado" {{ old('status', $property->status) == 'pausado' ? 'selected' : '' }}>Pausado</option>
                    <option value="reservado" {{ old('status', $property->status) == 'reservado' ? 'selected' : '' }}>Reservado</option>
                    <option value="oculto" {{ old('status', $property->status) == 'oculto' ? 'selected' : '' }}>Oculto</option>
                    <option value="vendido" {{ old('status', $property->status) == 'vendido' ? 'selected' : '' }}>Vendido</option>
                </select>
              </div>
              <div class="block block-col-9-17">
                <label for="featured">Destacado</label>
                <select name="featured">
                    <option value="0" {{ old('featured', $property->featured) == '0' ? 'selected' : '' }}>No</option>
                    <option value="1" {{ old('featured', $property->featured) == '1' ? 'selected' : '' }}>Sí</option>
                </select>
              </div>
              <div class="block block-col-1-17">
                <label for="description">Descripción</label>
                <textarea name="description"  minlength="100" maxlength="3000">{{ old('description', $property->description) }}</textarea>
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Ubicación</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="public_address">Dirección Pública</label>
                <input type="text" name="public_address" value="{{ old('public_address', $property->public_address) }}"> 
              </div>
              <div class="block block-col-9-17">
                <label for="real_address">Dirección Real <small>(iframe de Google Maps)</small> </label>
                <textarea name="real_address">{{ old('real_address', $property->real_address) }}</textarea>
              </div>
              <div class="block block-col-1-9">
                <label for="country">País</label>
                <input type="text" name="country" value="{{ old('country', $property->country) }}">
              </div>
              <div class="block block-col-9-17">
                <label for="state">Provincia/Estado</label>
                <input type="text" name="state" value="{{ old('state', $property->state) }}">
              </div>
              <div class="block block-col-1-9">
                <label for="city">Ciudad</label>
                <input type="text" name="city" value="{{ old('city', $property->city) }}">
              </div>
              <div class="block block-col-9-17">
                <label for="neighborhood">Barrio</label>
                <input type="text" name="neighborhood" value="{{ old('neighborhood', $property->neighborhood) }}">
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Descripción del Inmueble</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="rooms">Ambientes</label>
                <input type="number" name="rooms" min="0" value="{{ old('rooms', $property->rooms) }}">
              </div>
              <div class="block block-col-9-17">
                <label for="bathrooms">Baños</label>
                <input type="number" name="bathrooms" min="0" value="{{ old('bathrooms', $property->bathrooms) }}">
              </div>
              <div class="block block-col-1-9">
                <label for="covered_area">Superficie Cubierta (m²)</label>
                <input type="text" name="covered_area" value="{{ old('covered_area', $property->covered_area) }}">
              </div>
              <div class="block block-col-9-17">
                <label for="total_area">Superficie Total (m²)</label>
                <input type="text" name="total_area" value="{{ old('total_area', $property->total_area) }}">
              </div>
              <div class="block block-col-1-9">
                <label for="width">Ancho (m)</label>
                <input type="text" name="width" value="{{ old('width', $property->width) }}">
              </div>
              <div class="block block-col-9-17">
                <label for="length">Largo (m)</label>
                <input type="text" name="length" value="{{ old('length', $property->length) }}">
              </div>
              <div class="block block-col-1-9">
                <label for="orientation">Orientación</label>
                <select name="orientation">
                    <option value="" {{ old('orientation', $property->orientation) == '' ? 'selected' : '' }}>Seleccione una opción</option>
                    <option value="n" {{ old('orientation', $property->orientation) == 'n' ? 'selected' : '' }}>N</option>
                    <option value="s" {{ old('orientation', $property->orientation) == 's' ? 'selected' : '' }}>S</option>
                    <option value="e" {{ old('orientation', $property->orientation) == 'e' ? 'selected' : '' }}>E</option>
                    <option value="o" {{ old('orientation', $property->orientation) == 'o' ? 'selected' : '' }}>O</option>
                    <option value="ne" {{ old('orientation', $property->orientation) == 'ne' ? 'selected' : '' }}>NE</option>
                    <option value="no" {{ old('orientation', $property->orientation) == 'no' ? 'selected' : '' }}>NO</option>
                    <option value="se" {{ old('orientation', $property->orientation) == 'se' ? 'selected' : '' }}>SE</option>
                    <option value="so" {{ old('orientation', $property->orientation) == 'so' ? 'selected' : '' }}>SO</option>
                </select>
              </div>
              <div class="block block-col-9-17">
                <label for="position">Posición</label>
                <select name="position">
                    <option value="" {{ old('position', $property->position) == '' ? 'selected' : '' }}>Seleccione una opción</option>
                    <option value="front" {{ old('position', $property->position) == 'front' ? 'selected' : '' }}>Frente</option>
                    <option value="rear" {{ old('position', $property->position) == 'rear' ? 'selected' : '' }}>Contrafrente</option>
                    <option value="interior" {{ old('position', $property->position) == 'interior' ? 'selected' : '' }}>Pulmón interno</option>
                </select>
              </div>
              <div class="block block-col-1-9">
                <label for="year_built">Año de construcción</label>
                <input type="number" name="year_built" value="{{ old('year_built', $property->year_built) }}" min="1900">
              </div>
              <div class="block block-col-9-17">
                <label for="storage_room">¿Tiene baulera?</label>
                <select name="storage_room" >
                    <option value="0" {{ old('storage_room', $property->storage_room) == '0' ? 'selected' : '' }}>No</option>
                    <option value="1" {{ old('storage_room', $property->storage_room) == '1' ? 'selected' : '' }}>Sí</option>
                </select>
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Servicios y Amenities</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="services">Servicios</label>
                <input type="text" name="services" value="{{ old('services', $property->services) }}">
              </div>
              <div class="block block-col-9-17">
                <label for="heating_type">Calefacción</label>
                <input type="text" name="heating_type" value="{{ old('heating_type', $property->heating_type) }}">
              </div>
              <div class="block block-col-1-9">
                <label for="amenities">Amenities</label>
                <input type="text" name="amenities" value="{{ old('amenities', $property->amenities) }}"">
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Valores y tipo de operación</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="price">Precio</label>
                <input type="text" name="price" value="{{ old('price', $property->price) }}">
              </div>
              <div class="block block-col-9-17">
                <label for="transaction_type">Operación</label>
                <select name="transaction_type">
                    <option value="" {{ old('transaction_type', $property->transaction_type) == '' ? 'selected' : '' }}>Seleccione una opción</option>
                    <option value="venta" {{ old('transaction_type', $property->transaction_type) == 'venta' ? 'selected' : '' }}>Venta</option>
                    <option value="alquiler" {{ old('transaction_type', $property->transaction_type) == 'alquiler' ? 'selected' : '' }}>Alquiler</option>
                    <option value="otro" {{ old('transaction_type', $property->transaction_type) == 'otro' ? 'selected' : '' }}>Otro</option>
                </select>
              </div>
              <div class="block block-col-1-9">
                <label for="hoa_fees">Expensas</label>
                <input type="text" name="hoa_fees" value="{{ old('hoa_fees', $property->hoa_fees) }}">
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Video y link externo</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="video">Video <small>(iframe de Youtube)</small></label>
                <textarea name="video">{{ old('video', $property->video) }}</textarea>
              </div>
              <div class="block block-col-9-17">
                <label for="external_url">Link Externo <small>(ejemplo: www.sitio.com/casa-en-palermo)</small></label>
                <input type="text" name="external_url" value="{{ old('external_url', $property->external_url) }}">
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Notas</h3>
              </div>
              <div class="block block-col-1-17">
                <label for="private_notes">Notas (Privado admin)</label>
                <textarea name="private_notes" maxlength="2000">{{ old('private_notes', $property->private_notes) }}</textarea>
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
                  @foreach($property->images as $image)
                      <div class="image-item" data-file-index="{{ $loop->index }}" data-image-type="existing" data-image-id="{{ $image->id }}">
                          <div class="thumbnail-wrapper">
                              <img src="{{ asset('public/files/img/properties/' . $image->thumbnail_image) }}" alt="{{ $image->img_alt }}" class="preview-thumbnail {{ $image->img_class }}">
                              <input type="hidden" name="image_order[]" value="{{ $image->order }}">
                              <input type="hidden" name="image_id[]" value="{{ $image->id }}">
                              <a href="javascript:void(0)" class="btn-remove-property-image">X</a>
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
                <div class="btn-wrapper text-center"><button type="submit" class="btn btn-primary" id="edit-property-button">Guardar</button></div>
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
            <script src="{{ asset('public/admin/js/' . $script) }}"></script>
        @endforeach
    @endif
    <!-- End Controller level JS files -->
@endpush
</x-AdminLayout>