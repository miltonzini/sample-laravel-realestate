@section('title', 'Lotes/Terrenos')
@section('body-id', 'admin-lots-create')
@section('body-class', 'admin')
<x-AdminLayout>

@include('partials.admin.aside')

<main>
    @include('partials.admin.nav', ['pageHeading' => 'Crear Lote/Terreno'])

    <div class="admin-main-content container">
        <form action="{{ route('admin.lots.store') }}" method="post" id="create-lot-form">            
            @csrf
            <div class="grid">
              <div class="block block-col-1-17 my6">
                <h3>General</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="title">Título</label>
                <input type="text" name="title" required>
              </div>
              <div class="block block-col-9-17">
                <label for="status">Estado</label>
                <select name="status">
                    <option value="activo">Activo</option>
                    <option value="pausado">Pausado</option>
                    <option value="reservado">Reservado</option>
                    <option value="oculto" selected>Oculto</option>
                    <option value="vendido">Vendido</option>
                </select>
              </div>
              <div class="block block-col-1-9">
                <label for="featured">Destacado</label>
                <select name="featured">
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                </select>
              </div>
              <div class="block block-col-9-17">
                <label for="is_in_gated_community">Está dentro de un barrio cerrado?</label>
                <select name="is_in_gated_community">
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
                <input type="text" name="country" required>
              </div>
              <div class="block block-col-9-17">
                <label for="state">Provincia/Estado</label>
                <input type="text" name="state" required>
              </div>
              <div class="block block-col-1-9">
                <label for="city">Ciudad</label>
                <input type="text" name="city" required>
              </div>
              <div class="block block-col-9-17">
                <label for="neighborhood">Barrio</label>
                <input type="text" name="neighborhood">
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Descripción del Lote/Terreno</h3>
              </div>
              <div class="block block-col-1-9">
                <label for="frontage">Frente (m)</label>
                <input type="text" name="frontage">
              </div>
              <div class="block block-col-9-17">
                <label for="depth">Fondo (m)</label>
                <input type="text" name="depth">
              </div>
              <div class="block block-col-1-9">
                <label for="total_area">Superficie Total (m²)</label>
                <input type="text" name="total_area">
              </div>

              <div class="block block-col-1-17 my6"><hr class="divider"></div>

              <div class="block block-col-1-17 my6">
                <h3>Servicios </h3>
              </div>
              <div class="block block-col-1-9">
                <label for="services">Servicios <small>(ej, luz, cloaca, gas natural)</small></label>
                <input type="text" name="services">
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
                <div class="btn-wrapper text-center"><button type="submit" class="btn btn-primary" id="create-lot-button">Guardar</button></div>
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