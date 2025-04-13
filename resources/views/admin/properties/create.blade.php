@section('title', 'Propiedades')
@section('body-id', 'admin-properties-create')
@section('body-class', 'admin')
<x-AdminLayout>

@include('partials.admin.aside')

<main>
    @include('partials.admin.nav', ['pageHeading' => 'Crear Propiedad'])

    <div class="admin-main-content container">
        <form action="{{ route('admin.properties.store') }}" method="post" id="create-property-form">            
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
                <label for="property_type">Tipo de propiedad</label>
                <select name="property_type">
                    <option value="casa">Casa</option>
                    <option value="departamento">Departamento</option>
                    <option value="ph">PH</option>
                    <option value="monoambiente">Monoambiente</option>
                </select>
              </div>
              <div class="block block-col-1-9">
                <label for="status">Estado</label>
                <select name="status">
                    <option value="activo">Activo</option>
                    <option value="pausado">Pausado</option>
                    <option value="reservado">Reservado</option>
                    <option value="oculto" selected>Oculto</option>
                    <option value="vendido">Vendido</option>
                </select>
              </div>
              <div class="block block-col-9-17">
                <label for="featured">Destacado</label>
                <select name="featured">
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                </select>
              </div>
              <div class="block block-col-1-17">
                <label for="description">Descripción</label>
                <textarea name="description"  minlength="100" maxlength="3000"></textarea>
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Ubicación</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="public_address">Dirección Pública</label>
                <input type="text" name="public_address">
              </div>
              <div class="block block-col-9-17">
                <label for="real_address">Dirección Real <small>(iframe de Google Maps)</small> </label>
                <textarea name="real_address"></textarea>
              </div>
              <div class="block block-col-1-9">
                <label for="country">País</label>
                <input type="text" name="country">
              </div>
              <div class="block block-col-9-17">
                <label for="state">Provincia/Estado</label>
                <input type="text" name="state">
              </div>
              <div class="block block-col-1-9">
                <label for="city">Ciudad</label>
                <input type="text" name="city">
              </div>
              <div class="block block-col-9-17">
                <label for="neighborhood">Barrio</label>
                <input type="text" name="neighborhood">
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Descripción del Inmueble</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="rooms">Ambientes</label>
                <input type="number" name="rooms" min="0">
              </div>
              <div class="block block-col-9-17">
                <label for="bathrooms">Baños</label>
                <input type="number" name="bathrooms" min="0">
              </div>
              <div class="block block-col-1-9">
                <label for="covered_area">Superficie Cubierta (m²)</label>
                <input type="text" name="covered_area">
              </div>
              <div class="block block-col-9-17">
                <label for="total_area">Superficie Total (m²)</label>
                <input type="text" name="total_area">
              </div>
              <div class="block block-col-1-9">
                <label for="width">Ancho (m)</label>
                <input type="text" name="width">
              </div>
              <div class="block block-col-9-17">
                <label for="length">Largo (m)</label>
                <input type="text" name="length">
              </div>
              <div class="block block-col-1-9">
                <label for="orientation">Orientación</label>
                <select name="orientation">
                    <option value="" selected>Seleccione una opción</option>
                    <option value="N">N</option>
                    <option value="S">S</option>
                    <option value="E">E</option>
                    <option value="O">O</option>
                    <option value="NE">NE</option>
                    <option value="NO">NO</option>
                    <option value="SE">SE</option>
                    <option value="SO">SO</option>
                </select>
              </div>
              <div class="block block-col-9-17">
                <label for="position">Posición</label>
                <select name="position">
                    <option value="" selected>Seleccione una opción</option>
                    <option value="front">Frente</option>
                    <option value="rear">Contrafrente</option>
                    <option value="interior">Pulmón interno</option>
                </select>
              </div>
              <div class="block block-col-1-9">
                <label for="year_built">Año de construcción</label>
                <input type="number" name="year_built" min="1900">
              </div>
              <div class="block block-col-9-17">
                <label for="storage_room">¿Tiene baulera?</label>
                <select name="storage_room">
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                </select>
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Servicios y Amenities</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="services">Servicios</label>
                <input type="text" name="services">
              </div>
              <div class="block block-col-9-17">
                <label for="heating_type">Calefacción</label>
                <input type="text" name="heating_type">
              </div>
              <div class="block block-col-1-9">
                <label for="amenities">Amenities</label>
                <input type="text" name="amenities">
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Valores y tipo de operación</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="price">Precio</label>
                <input type="text" name="price">
              </div>
              <div class="block block-col-9-17">
                <label for="transaction_type">Operación</label>
                <select name="transaction_type">
                    <option value="" selected>Seleccione una opción</option>
                    <option value="venta">Venta</option>
                    <option value="alquiler">Alquiler</option>
                    <option value="otro">Otro</option>
                </select>
              </div>
              <div class="block block-col-1-9">
                <label for="hoa_fees">Expensas</label>
                <input type="text" name="hoa_fees">
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Video y link externo</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="video">Video <small>(iframe de Youtube)</small></label>
                <textarea name="video"></textarea>
              </div>
              <div class="block block-col-9-17">
                <label for="external_url">Link Externo <small>(ejemplo: www.sitio.com/casa-en-palermo)</small></label>
                <input type="text" name="external_url">
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Notas</h3>
              </div>
              <div class="block block-col-1-17">
                <label for="private_notes">Notas (Privado admin)</label>
                <textarea name="private_notes" maxlength="2000"></textarea>
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
                </div>
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <div class="btn-wrapper text-center"><button type="submit" class="btn btn-primary" id="create-property-button">Guardar</button></div>
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