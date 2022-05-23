<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Login - Agricura Client</title>
  </head>
  <body>
    <section class="material-half-bg">
      <div class="cover"></div>
    </section>
    <section class="login-content">
      <div class="logo">
        <h1><img src="{{asset('img/agricura_logo.jpg')}}" /></h1>
      </div>
      <div class="login-box" style="min-height: 230px !important">
        <div class="login-form">
                        @csrf
          <div class="form-group">
            <label class="control-label text-center"><span  style="font-size: 18px;">Thank you for applying for an Agricura Loyalty Account.</span> <br /> <br />Our Agricura personnel will get in touch with you once your account has been approved. </label>
          </div>
          <div class="form-group text-center">
           {!! Form::open(['action' => 'PendingController@show', 'method' => 'GET']) !!}
            <button class="btn btn-block" style="background: #00B122; color: #fff">Go to Login Page</button>
           {!! Form::close() !!}
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