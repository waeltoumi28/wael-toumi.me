<?php
use Illuminate\Support\Facades\DB;

$promo = DB::table('vas_cos_promotions')->get();


?>
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
    <script src="js/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="{{asset('build/css/intlTelInput.css')}}">
    <style>
      .iti {
          width: 100%;
        }
    </style>
    <link href="https://www.africabet.co.zw/content/app/DefaultTemplate/images/favicons/favicon-site22.ico" rel="icon" type="image/ico">
    <title>Register - AfricaBet Loyalty System</title>
  </head>
  <body>
    <section class="material-half-bg" style="background: url('/img/background-1.png');">
      <div class="cover" style="height: 100vh;opacity: 0.94"></div>
    </section>
    <section class="login-content">
      <div class="logo">
        <h1><img src="img/logo_large.png" style="max-height: 120px;" /></h1>
      </div>
      <div class="login-box" style="min-height: 500px !important;">
        <form method="POST" action="{{ route('register') }}" class="login-form">
        @csrf
          <h3 class="login-head"><i class="fa fa-lg fa-fw fa-lock"></i>Create Account</h3>
          @if(count($errors) > 0)
            @foreach($errors->all() as $error)
                <div class="alert alert-danger">
                    {{$error}}
                </div>
            @endforeach
          @endif
          @if(isset($_GET['error']))
            <div class="alert alert-danger">
              <?php echo  str_replace("+"," ",$_GET['error']) ?>
            </div>
          @endif
          <!----./Start input fields----->
          <input type="hidden" name="status" value="0">
          <div class="form-group">
            <label for="firstname">First Name</label>
            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter First Name">
            {{-- <small id="error-firstname" class="form-text text-muted">We'll never share your email with anyone else.</small> --}}
          </div>
          <div class="form-group">
            <label for="lastname">Last Name</label>
            <input type="text" class="form-control @error('cell') is-invalid @enderror" id="lastname" name="lastname" placeholder="Enter Last Name">
            {{-- <small id="error-lastname" class="form-text text-muted">We'll never share your email with anyone else.</small> --}}
          </div>
          <div class="form-group">
            <label for="cell">Whatsapp Number</label><br>
            <input type="tel" class="form-control" id="cell" name="cell" value="{{ old('cell') }}" autocomplete="cell" placeholder="Whatsapp Number" autofocus>
            <input type="hidden" name="country_code" id="country_code" />
            {{-- <small id="error-cell" class="form-text text-muted">We'll never share your email with anyone else.</small> --}}
          </div>
          <!-----./End input fields----->
          <div class="form-group btn-container">
            <button class="btn btn-block green" ><i class="fa fa-unlock fa-lg fa-fw"></i>Apply</button>
          </div>
          <div class="form-group mt-3">
            <p class="semibold-text mb-0"><a href="{{URL::to('/')}}/login" class="link-underline"><i class="fa fa-angle-left fa-fw"></i> Back to Login</a></p>
          </div>
        </form>
      </div>
    </section>
    <!-- Essential javascripts for application to work-->

    <script src="{{asset('js/popper.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/main.js')}}"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="{{asset('js/plugins/pace.min.js')}}"></script>
    <script src="{{asset('build/js/intlTelInput.js')}}"></script>
    <script type="text/javascript">

        var input = document.querySelector("#cell");
        var iti = window.intlTelInput(input, {
            // allowDropdown: false,
            autoHideDialCode: false,
            // autoPlaceholder: "off",
            // dropdownContainer: document.body,
            // excludeCountries: ["us"],
            // formatOnDisplay: false,
            // geoIpLookup: function(callback) {
            //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
            //     var countryCode = (resp && resp.country) ? resp.country : "";
            //     callback(countryCode);
            //   });
            // },
            // hiddenInput: "full_number",
            initialCountry: "zw",
            // localizedCountries: { 'de': 'Deutschland' },
            // nationalMode: false,
            // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
            // placeholderNumberType: "MOBILE",
            // preferredCountries: ['cn', 'jp'],
            separateDialCode: true,
            utilsScript: "build/js/utils.js",
          });
          
          input.addEventListener('change', function () {
              if (input.value.trim()) {
                  if (iti.isValidNumber()) {
                      //validMsg.classList.remove("hide");
                      var text = (iti.isValidNumber()) ? iti.getNumber() : "Please enter a number below";
                      var textNode = document.createTextNode(text);
                      //$('input#cell').val(textNode.data);
                      var countryData = iti.getSelectedCountryData();
                      $('input#country_code').val(countryData.dialCode); 
                      //var getCode = telInput.intlTelInput('getSelectedCountryData').dialCode;
                      //alert(getCode);
                  } else {
                      input.classList.add("error");
                      var errorCode = iti.getValidationError();
                      errorMsg.innerHTML = errorMap[errorCode];
                      errorMsg.classList.remove("hide");
                  }
              }
          });
          
      
    </script>
  </body>
</html>