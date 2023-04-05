@extends('layouts.app')

@section('content')

<div class="container my-4">
    <h2 class="mdl-card__title-text">{{ __('Iniciar sesión') }}</h2>
    <form method="POST" action="{{ route('login') }}" class="col s12">
        @csrf
        <div class="row">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label @error('email') is-invalid @enderror">
                <input class="mdl-textfield__input" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                <label class="mdl-textfield__label" for="email">{{ __('Correo electrónico') }}</label>
                @error('email')
                <br>
                    <span class="mdl-textfield__error">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label @error('password') is-invalid @enderror">
                <input class="mdl-textfield__input" type="password" id="password" name="password" required>
                <label class="mdl-textfield__label" for="password">{{ __('Contraseña') }}</label>
                @error('password')
                    <span class="mdl-textfield__error">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>Recordarme</span>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <button type="submit" class="waves-effect waves-light btn">Iniciar sesión</button>
                @if (Route::has('password.request'))
                <a class="waves-effect waves-light btn" href="{{ route('password.request') }}">Olvidaste tu contraseña?</a>
                @endif
            </div>
        </div>
    </form>
</div>
@endsection
