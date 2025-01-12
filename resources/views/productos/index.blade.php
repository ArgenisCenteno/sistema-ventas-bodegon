@extends('layout.app')
@section('content')
<main class="app-main"> <!--begin::App Content Header-->
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card border-0 my-5">
                        <div class="px-2 row">
                            <div class="col-lg-12">
                                @include('flash::message')
                            </div>
                            <div class="col-md-6 col-6">
                                <h3 class="p-2 bold">Productos Registrados</h3>
                            </div>

                            <div class="col md-6 col-6">
                                <div class="d-flex justify-content-end mt-3">
                                    <!-- Botón Registrar -->
                                   

                                    <!-- Formulario Exportar -->
                                    <form action="{{ route('productos.export') }}" method="GET"
                                        class="d-flex align-items-center">
                                        @csrf
                                         
                                             
                                                
                                                <select id="disponible" name="disponible" class="form-control">
                                                    <option value="">Todos</option>
                                                    <option value="1">Disponible</option>
                                                    <option value="0">No disponible</option>
                                                </select>
                                           
                                         
                                        <button type="submit" class="btn btn-success round mx-1">Exportar</button>
                                        <a href="{{ route('productos.create') }}"
                                        class="btn btn-primary round mx-1">Registrar</a>
                                    </form>
                                </div>

                            </div>

                        </div>
                        <div class="card-body">

                            @include('productos.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main> <!--end::App Main--> <!--begin::Footer-->
@endsection