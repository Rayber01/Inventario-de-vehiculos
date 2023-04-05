@extends('layouts.app')




@section('content')
    <div class="container mdl-grid">
        <div class="mdl-cell mdl-cell--4-col mdl-cell--4-offset">
            <div class="mdl-card mdl-shadow--2dp">
                <div class="mdl-card__title">
                    <h2 class="mdl-card__title-text">{{ __('Registro') }}</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label @error('name') is-invalid @enderror">
                            <input class="mdl-textfield__input" type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
                            <label class="mdl-textfield__label" for="name">{{ __('Nombre') }}</label>
                            @error('name')
                                <span class="mdl-textfield__error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label @error('email') is-invalid @enderror">
                            <input class="mdl-textfield__input" type="email" id="email" name="email" value="{{ old('email') }}" required>
                            <label class="mdl-textfield__label" for="email">{{ __('Correo electrónico') }}</label>
                            @error('email')
                                <span class="mdl-textfield__error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label @error('password') is-invalid @enderror">
                            <input class="mdl-textfield__input" type="password" id="password" name="password" required>
                            <label class="mdl-textfield__label" for="password">{{ __('Contraseña') }}</label>
                            @error('password')
                                <span class="mdl-textfield__error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="password" id="password_confirmation" name="password_confirmation" required>
                            <label class="mdl-textfield__label" for="password_confirmation">{{ __('Confirmar contraseña') }}</label>
                        </div>

                        <div class="mdl-card__actions mdl-card--border">
                            <button type="submit" class="waves-effect waves-light btn">{{ __('Registrar') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
