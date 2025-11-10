@extends('layouts.auth')

@section('title', 'Login | Deeen Coffee')

@section('content')
  <h2>Welcome to Deeen Coffee</h2>
  <p>Login to continue your experience</p>

  <form method="POST" action="{{ route('login') }}">
      @csrf
      <input type="text" name="username" placeholder="Username" required autofocus>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
  </form>

  <div style="margin-top: 15px;">
      <a href="{{ route('password.request') }}">Forgot password?</a><br>
      <span>Don't have an account? <a href="{{ route('register') }}">Register</a></span>
  </div>
@endsection
