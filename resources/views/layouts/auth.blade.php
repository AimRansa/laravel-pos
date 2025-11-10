<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title', 'Deeen Coffee | Auth')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  @vite(['resources/sass/app.scss', 'resources/js/app.js'])
  @yield('css')

  <style>
    /* Reset dan pastikan full viewport */
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
    }

    /* Background full page */
    body {
      font-family: 'Poppins', sans-serif;
      background: url("{{ asset('images/bg-login.png') }}") no-repeat center center fixed;
      background-size: cover;
      background-color: #ffffff; /* fallback color */
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      overflow: hidden; /* cegah scroll yang bikin garis hitam */
    }

    /* Wrapper agar benar-benar center bahkan di screen kecil */
    .auth-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
      height: 100vh;
      padding: 20px;
    }

    /* Kontainer utama login/register */
    .auth-container {
      width: 600px; /* diperlebar */
     max-width: 90%;
     background: rgba(255, 255, 255, 0.65);
     border-radius: 25px;
     box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
     text-align: center;
     padding: 50px 60px;
     backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    transition: all 0.3s ease;
    animation: fadeIn 0.7s ease;
    position: relative;
    z-index: 1;
    }


    @media (max-width: 600px) {
  .auth-container img {
    width: 120px; /* kecil lagi di layar sempit */
     }
    }


    .auth-container:hover {
      transform: translateY(-3px);
      box-shadow: 0 25px 60px rgba(0,0,0,0.3);
    }

    .auth-container img {
      width: 200px;
      margin-bottom: 15px;
    }

    .auth-container h2 {
      color: #1b352b;
      font-weight: 700;
      margin-bottom: 5px;
    }

    .auth-container p {
      color: #444;
      font-size: 0.95rem;
      margin-bottom: 25px;
    }

    .auth-container form {
      width: 100%;
    }

    .auth-container form input {
      width: 100%;
      padding: 12px 14px;
      margin-bottom: 18px;
      border: 1px solid rgba(0,0,0,0.2);
      border-radius: 10px;
      font-size: 0.95rem;
      transition: all 0.2s ease;
    }

    .auth-container form input:focus {
      border-color: #234534;
      box-shadow: 0 0 6px #23453450;
      outline: none;
    }

    .auth-container button {
      width: 100%;
      padding: 12px;
      background-color: #234534;
      color: white;
      border: none;
      border-radius: 10px;
      font-weight: 600;
      transition: all 0.3s ease;
      cursor: pointer;
      letter-spacing: 0.3px;
    }

    .auth-container button:hover {
      background-color: #2f5b44;
      transform: scale(1.02);
    }

    .auth-container a {
      color: #234534;
      text-decoration: none;
      font-weight: 500;
    }

    .auth-container a:hover {
      text-decoration: underline;
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(10px);}
      to {opacity: 1; transform: translateY(0);}
    }

    @media (max-width: 600px) {
      .auth-container {
        width: 90%;
        padding: 30px 25px;
      }
    }
  </style>
</head>

<body>
  <div class="auth-wrapper">
    <div class="auth-container">
      <img src="{{ asset('images/logo-deeen.png') }}" alt="Deeen Coffee Logo">
      @yield('content')
    </div>
  </div>
  @yield('js')
</body>
</html>
