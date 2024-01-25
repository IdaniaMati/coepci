<!-- resources/views/auth/empleado-login.blade.php -->

@extends('layouts.masterv')

@section('content')
    <div class="container">
        <form method="POST" action="{{ route('empleado.login.submit') }}">
            @csrf

            <div class="mb-3">
                <label for="curp" class="form-label">CURP</label>
                <input type="text" class="form-control @error('curp') is-invalid @enderror" id="curp" name="curp" value="{{ old('curp') }}" required>
                @error('curp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
@endsection
