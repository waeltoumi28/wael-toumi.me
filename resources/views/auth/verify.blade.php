<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('css/main.css')}}">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Agricura - Verify Email</title>
  </head>
  <body>
    <section class="material-half-bg">
      <div class="cover"></div>
    </section>
    <section class="lockscreen-content">
      <div class="logo">
        <h1>Agricura</h1>
      </div>
      <div class="lock-box"><img class="rounded-circle user-image" src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/128.jpg">
        <h4 class="text-center user-name">John Doe</h4>
        <p class="text-center text-muted">Verfiy Your Email Address</p>
        <div class="unlock-form">
            <p class="text-center"><a href="!#">A fresh verification link has been sent to your email address.</a></p>
          <p class="text-center text-muted">Before proceeding, please check your email for a verification link.  <br />If you did not receive the email, click the button below.</p>
          <div class="btn-container">
            <a href="{{ route('verification.resend') }}" type="button" class="btn btn-primary btn-block"><i class="fa fa-unlock fa-lg"></i>Refresh </a>
          </div>
        </div>
      </div>
    </section>
    <!-- Essential javascripts for application to work-->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="js/plugins/pace.min.js"></script>
  </body>
</html>