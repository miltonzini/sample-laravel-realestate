@section('title', 'Admin Dashboard')
@section('body-id', 'admin-dashboard')
@section('body-class', 'admin')
<x-AdminLayout>

@include('partials.admin.aside')

<main>
    @include('partials.admin.nav')

    <div class="admin-main-content">
        <h2 class="mb5">Resumen</h2>
        <div class="row dashboard-summary-section">
            <a class="item rounded-4" href="{{ route('admin.properties.index') }}">
                <div class="number">{{ $propertiesCount }}</div>
                <div class="title">Propiedades</div>
            </a>
            <a class="item rounded-4" href="{{ route('admin.developments.index') }}">
                <div class="number">{{ $developmentsCount }}</div>
                <div class="title">Emprendimientos</div>
            </a>
            <a class="item rounded-4" href="{{ route('admin.lots.index') }}">
                <div class="number">{{ $lotsCount }}</div>
                <div class="title">Lotes/Terrenos</div>
            </a>
            <a class="item rounded-4" href="{{ route('admin.blog.index') }}">
                <div class="number">{{ $postsCount }}</div>
                <div class="title">Posts</div>
            </a>
            <a class="item rounded-4" href="{{ route('admin.users.index') }}">
                <div class="number">{{ $usersCount }}</div>
                <div class="title">Usuarios registrados</div>
            </a>
        </div>
    </div>
</main>
@push('scripts')
    <!-- Begin Page level JS files -->
    <!-- End Page level JS files -->

    
    <!-- Begin Controller level JS files -->
    @if (isset($scripts) && !empty($scripts) && $scripts[0] !== '')
        @foreach ($scripts as $script)
            <script src="{{ asset('public/admin/js/' . $script . $assetVersionQueryString) }}"></script>
        @endforeach
    @endif
    <!-- End Controller level JS files -->
@endpush
</x-AdminLayout>