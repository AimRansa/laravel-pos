@extends('layouts.auth')

@section('title', 'Verify Email | Deeen Coffee')

@section('content')
  <h2>Verify Your Email</h2>
  <p>Please check your email for a verification link.</p>

  @if (session('resent'))
      <div style="color: green; font-weight: 600;">
          A fresh verification link has been sent to your email.
      </div>
  @endif

  <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
      @csrf
      <button type="submit">Resend Verification Link</button>
  </form>

  <div style="margin-top: 20px;">
    <a href="{{ route('logout') }}" 
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
       Logout
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
  </div>
@endsection
