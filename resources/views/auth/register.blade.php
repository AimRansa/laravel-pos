@extends('layouts.auth')

@section('title', 'Register | Deeen Coffee')

@section('content')
  <h2>Create Your Account</h2>
  <p>Join Deeen Coffee today</p>

  {{-- ✅ Pesan sukses setelah redirect --}}
  @if (session('success'))
      <div style="color: green; margin-bottom: 10px;">
          {{ session('success') }}
      </div>
  @endif

  {{-- ✅ Pesan error validasi --}}
  @if ($errors->any())
      <div style="color: red; margin-bottom: 10px;">
          @foreach ($errors->all() as $error)
              <div>⚠️ {{ $error }}</div>
          @endforeach
      </div>
  @endif

  <form method="POST" action="{{ route('register') }}">
      @csrf
      <input type="text" name="first_name" placeholder="First Name" value="{{ old('first_name') }}" required>
      <input type="text" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}" required>
      <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="password_confirmation" placeholder="Confirm Password" required>

      <button type="submit">Register</button>
  </form>

  <div style="margin-top: 15px;">
      <span>Already have an account? <a href="{{ route('login') }}">Login</a></span>
  </div>
@endsection
