@section('title', 'Admin Dashboard')
@section('body-id', 'admin-dashboard')
@section('body-class', 'admin')
<x-AdminLayout>

@include('partials.admin.aside')

<main>
    @include('partials.admin.nav')

    <div class="admin-main-content">
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Amet, placeat nobis dolore quasi nesciunt dolor maxime quis harum perspiciatis id quos odit illum minima numquam error culpa nihil aut assumenda!</p>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Amet, placeat nobis dolore quasi nesciunt dolor maxime quis harum perspiciatis id quos odit illum minima numquam error culpa nihil aut assumenda!</p>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Amet, placeat nobis dolore quasi nesciunt dolor maxime quis harum perspiciatis id quos odit illum minima numquam error culpa nihil aut assumenda!</p>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Amet, placeat nobis dolore quasi nesciunt dolor maxime quis harum perspiciatis id quos odit illum minima numquam error culpa nihil aut assumenda!</p>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Amet, placeat nobis dolore quasi nesciunt dolor maxime quis harum perspiciatis id quos odit illum minima numquam error culpa nihil aut assumenda!</p>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Amet, placeat nobis dolore quasi nesciunt dolor maxime quis harum perspiciatis id quos odit illum minima numquam error culpa nihil aut assumenda!</p>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Amet, placeat nobis dolore quasi nesciunt dolor maxime quis harum perspiciatis id quos odit illum minima numquam error culpa nihil aut assumenda!</p>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Amet, placeat nobis dolore quasi nesciunt dolor maxime quis harum perspiciatis id quos odit illum minima numquam error culpa nihil aut assumenda!</p>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Amet, placeat nobis dolore quasi nesciunt dolor maxime quis harum perspiciatis id quos odit illum minima numquam error culpa nihil aut assumenda!</p>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Amet, placeat nobis dolore quasi nesciunt dolor maxime quis harum perspiciatis id quos odit illum minima numquam error culpa nihil aut assumenda!</p>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Amet, placeat nobis dolore quasi nesciunt dolor maxime quis harum perspiciatis id quos odit illum minima numquam error culpa nihil aut assumenda!</p>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Amet, placeat nobis dolore quasi nesciunt dolor maxime quis harum perspiciatis id quos odit illum minima numquam error culpa nihil aut assumenda!</p>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Amet, placeat nobis dolore quasi nesciunt dolor maxime quis harum perspiciatis id quos odit illum minima numquam error culpa nihil aut assumenda!</p>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Amet, placeat nobis dolore quasi nesciunt dolor maxime quis harum perspiciatis id quos odit illum minima numquam error culpa nihil aut assumenda!</p>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Amet, placeat nobis dolore quasi nesciunt dolor maxime quis harum perspiciatis id quos odit illum minima numquam error culpa nihil aut assumenda!</p>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Amet, placeat nobis dolore quasi nesciunt dolor maxime quis harum perspiciatis id quos odit illum minima numquam error culpa nihil aut assumenda!</p>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Amet, placeat nobis dolore quasi nesciunt dolor maxime quis harum perspiciatis id quos odit illum minima numquam error culpa nihil aut assumenda!</p>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Amet, placeat nobis dolore quasi nesciunt dolor maxime quis harum perspiciatis id quos odit illum minima numquam error culpa nihil aut assumenda!</p>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Amet, placeat nobis dolore quasi nesciunt dolor maxime quis harum perspiciatis id quos odit illum minima numquam error culpa nihil aut assumenda!</p>
    </div>
</main>
@push('scripts')
    <!-- Begin Page level JS files -->
    <!-- End Page level JS files -->

    
    <!-- Begin Controller level JS files -->
    @if (isset($scripts) && !empty($scripts) && $scripts[0] !== '')
        @foreach ($scripts as $script)
            <script src="{{ asset('public/admin/js/' . $script) }}"></script>
        @endforeach
    @endif
    <!-- End Controller level JS files -->
@endpush
</x-AdminLayout>