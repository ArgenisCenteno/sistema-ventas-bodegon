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
                                <h3 class="p-2 bold">Reporte de Recibos</h3>
                            </div>
                            <div class="d-flex justify-content-end mt-3">

                            </div>
                        </div>
                        <div class="card-body">

                            <form action="{{ route('recibos.export') }}" method="GET">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="start_date">Fecha Inicio</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control"
                                            required>
                                    </div>
                                    <div class="col">
                                        <label for="end_date">Fecha Fin</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control" required>
                                    </div>
                                    <div class="col">
                                        <label for="end_date">Tipo</label>
                                        <select name="type" id="type" class="form-control">
                                                <option value="EXCEL">EXCEL</option>
                                                <option value="PDF">PDF</option>
                                            </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Exportar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main> <!--end::App Main--> <!--begin::Footer-->
@endsection
@include('layout.script')
<script src="{{ asset('js/adminlte.js') }}"></script>
<script src="{{asset('js/sweetalert2.js')}}"></script>
