@extends('layout.app')
@section('content')

<main class="app-main " > <!--begin::App Content Header-->
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    </div>
                    <h1 class="mb-0">Bienvenido {{Auth::user()->name}}</h1>
                <div class="col-sm-6">
                    
                </div>
            </div> <!--end::Row-->
        </div> <!--end::Container-->
    </div> <!--end::App Content Header--> <!--begin::App Content-->
    <div class="app-content"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            @if(Auth::user()->hasRole('superAdmin') || Auth::user()->hasRole('empleado'))
            @include('cajas')
            @else
            @include('cajasUser')
            @endif

        </div> <!--end::Container-->
    </div> <!--end::App Content-->
</main> <!--end::App Main--> <!--begin::Footer-->



@endsection

@section('js')
@include('layout.script')
<script src="{{ asset('js/adminlte.js') }}"></script>

 
 
@endsection