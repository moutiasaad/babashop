<!doctype html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/admin/img/icon-lovard.png">
    <title>Babashop - Connexion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="/assets/css/font-icon.css">
    <link rel="stylesheet" href="/assets/css/custom.css">

    <meta property="og:title" content="Application Babashop">
    <meta property="og:description" content="">
    <meta property="og:image" content="">
    <meta property="og:type" content="application">
    <meta property="og:url" content="{{ url()->current() }}">

  </head>
  <body>


    <div class="login-wrap">
        <div class="login-area">
          <div class="login-main">
           <div class="login-logo"><h2 style="color:#B4442A;">Babashop</h2></div>
            <form method="POST" action="" >
                @csrf
            <div id="step1" class="signup-block step" style="margin-top:-50px;" >
                @if($errors->has('success'))
                <div class="alert alert-success" role="alert">
                    Votre compte a été enregistré avec succès. Vous pouvez maintenant vous connecter.
                  </div>
                @endif
            <div id="step2" class="signup-block step ">
              <h3>Bienvenue,</h3>
              <p>Connexion au panneau d'administration Babashop</p>
              <div class="input-wrap mt-4">
                <label class="label" for="">Email</label>
                <div class="input-in">
                  <input class="input" type="text" name="email"  value="">
                </div>
              </div>
              <div class="input-wrap mt-3">
                <label class="label" for="">Mot de passe</label>
                <div class="input-in">
                  <span class="inp-icon togglePassword" data-target="password1"><i class="fa-regular fa-eye-slash"></i></span>
                  <input class="input" type="password" name="password" id="password1">
                </div>
                @if($errors->has('error'))
                <div class="input-error pass-err" ><i class="icon-icon-18"></i> L'email ou le mot de passe est incorrect </div>
                @endif

                @if(session('mustLogin'))
                <div class="input-error email-err">
                    <i class="icon-icon-18"></i> Veuillez vous connecter d'abord
                </div>
            @endif

              </div>
              {{-- <p class="pt-2"><a href="/forgot">Mot de passe oublié?</a></p> --}}
              <button class="button mt-3 full nextStep" type="submit" id="loginBtn">
                <span id="btnText">Se connecter</span>
                <span id="btnLoader" style="display:none;">
                  <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                  Connexion en cours...
                </span>
              </button>

            </div>
        </form>
          </div>
        </div>
      </div>


      <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      <script src="/assets/js/custom.js"></script>
      <script>
        document.querySelector('form').addEventListener('submit', function () {
          const btn    = document.getElementById('loginBtn');
          const text   = document.getElementById('btnText');
          const loader = document.getElementById('btnLoader');
          btn.disabled = true;
          text.style.display  = 'none';
          loader.style.display = 'inline';
        });
      </script>

      </body>
    </html>
