@extends('layouts.app')

@section('content')
<section class="vh-100 p-0">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6 text-black">

      

        <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">

          <!-- Formulario de inicio de sesión -->
          <form method="POST" action="{{ route('login') }}" style="width: 23rem;" class="mt-5 pt-5">
            @csrf <!-- Agregar token CSRF obligatorio -->

            <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">ACCEDER AL <strong style="color: blue">SISTEMA</strong></h3>

            <!-- Campo de correo electrónico -->
            <div class="form-outline mb-4">
              <label class="form-label" for="email"> <strong>Correo Electrónico</strong> </label>
              <input type="email" id="email" name="email" class="form-control form-control-lg" value="{{ old('email') }}" required autofocus />
              @error('email')
                <p class="text-danger">{{ $message }}</p> <!-- Mostrar error de validación -->
              @enderror
            </div>

            <!-- Campo de contraseña -->
            <div class="form-outline mb-4">
              <label class="form-label" for="password"> <strong>Contraseña</strong> </label>
              <input type="password" id="password" name="password" class="form-control form-control-lg" required />
              @error('password')
                <p class="text-danger">{{ $message }}</p> <!-- Mostrar error de validación -->
              @enderror
            </div>

            <!-- Botón de inicio de sesión -->
            <div class="pt-1 mb-4">
              <button class="btn btn-primary btn-lg btn-block" type="submit">Ingresar</button>
            </div>

            <!-- Enlace para recuperar la contraseña -->
            <p class=" mb-5 pb-lg-2">
              <a  href="{{ route('password.request') }}">Recuperar Contraseña</a>
            </p>

            <!-- Enlace para registrarse -->
             
          </form>

        </div>

      </div>
      <div class="col-sm-6 px-0 d-none d-sm-block">
        <img src="{{asset('iconos/back.png')}}" alt="Login image"
          class="w-100 vh-100" style="object-fit: cover; object-position: left;">
      </div>
    </div>
  </div>
</section>
@endsection
