@extends('layouts.auth')

@section('title', 'Register | Deeen Coffee')

@section('content')
  <h2>Create Your Account</h2>
  <p>Join Deeen Coffee today</p>

  <form method="POST" action="{{ route('register') }}">
      @csrf
      <input type="text" name="first_name" placeholder="First Name" required>
      <input type="text" name="last_name" placeholder="Last Name" required>
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="password_confirmation" placeholder="Confirm Password" required>

      <button type="submit">Register</button>
  </form>

  <div style="margin-top: 15px;">
      <span>Already have an account? <a href="{{ route('login') }}">Login</a></span>
  </div>
@endsection
