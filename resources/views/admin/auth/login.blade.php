<!DOCTYPE html>


<html lang="en">

  <!-- begin::Head -->
  <head>

    <!--begin::Base Path (base relative path for assets of this page) -->
    <base href="../../../../">

    <!--end::Base Path -->
    <meta charset="utf-8" />
    <title>Admin - Tariefchecker</title>
    <meta name="description" content="Login page example">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--begin::Fonts -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
      WebFont.load({
        google: {
          "families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
        },
        active: function() {
          sessionStorage.fonts = true;
        }
      });
    </script>

    <!--end::Fonts -->

    <!--begin::Page Custom Styles(used by this page) -->
    <link href="{{ asset('/admin-style/css/demo1/pages/login/login-1.css')}}" rel="stylesheet" type="text/css" />

    <!--end::Page Custom Styles -->

    <!--begin::Global Theme Styles(used by all pages) -->
    <link href="{{ asset('/admin-style/vendors/global/vendors.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/admin-style/css/demo1/style.bundle.css')}}" rel="stylesheet" type="text/css" />

    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->
    <link href="{{ asset('/admin-style/css/demo1/skins/header/base/light.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/admin-style/css/demo1/skins/header/menu/light.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/admin-style/css/demo1/skins/brand/dark.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/admin-style/css/demo1/skins/aside/dark.css')}}" rel="stylesheet" type="text/css" />

    <!--end::Layout Skins -->
    <link rel="shortcut icon" href="{{ asset('/admin-style/media/logos/favicon.ico')}}" />
  </head>

  <!-- end::Head -->

  <!-- begin::Body -->
  <body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

    <!-- begin:: Page -->
    <div class="kt-grid kt-grid--ver kt-grid--root">
      <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v1" id="kt_login">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile">

          <!--begin::Aside-->
          <div class="kt-grid__item kt-grid__item--order-tablet-and-mobile-2 kt-grid kt-grid--hor kt-login__aside" style="background-image: url(./admin-style/media//bg/bg-4.jpg);">
            <div class="kt-grid__item">
              <a href="#" class="kt-login__logo">
                <img src="./admin-style/logo.png">
              </a>
            </div>
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver">
              <div class="kt-grid__item kt-grid__item--middle">
                 <h3 class="kt-login__title">Tariefchecker Comparison App Admin.</h3>
                <!--<h4 class="kt-login__subtitle">COMPARE ALL ENERGY SUPPLIERS FOR FREE AND QUICKLY.</h4>-->
              </div>
            </div>
            <div class="kt-grid__item">
              <div class="kt-login__info">
                <!--<div class="kt-login__copyright">-->
                <!--  &copy 2019 Tarief Checker-->
                <!--</div>-->
               
              </div>
            </div>
          </div>

          <!--begin::Aside-->

          <!--begin::Content-->
          <div class="kt-grid__item kt-grid__item--fluid  kt-grid__item--order-tablet-and-mobile-1  kt-login__wrapper">


            <!--begin::Body-->
            <div class="kt-login__body">

              <!--begin::Signin-->
              <div class="kt-login__form">
                <div class="kt-login__title">
                  <h3>Sign In</h3>
                </div>

                <!--begin::Form-->
                <form class="kt-form" method="POST" action="{{ url('/admin/login') }}">
                   {{ csrf_field() }}
                  <div class="form-group">
                    <input class="form-control" required="" type="text" placeholder="E-Mail Address"  name="email" value="{{ old('email') }}" autocomplete="off">
                  </div>
                    @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong style="color: red;">{{ $errors->first('email') }}</strong>
                                    </span>
                    @endif

                  <div class="form-group">
                    <input class="form-control" required="" type="password"  placeholder="Password" name="password">
                  </div>

                   @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong style="color: red;">{{ $errors->first('password') }}</strong>
                                    </span>
                   @endif

                  <!--begin::Action-->
                  <div class="kt-login__actions">
                    <!--<a href="#" class="kt-link kt-login__link-forgot">-->
                    <!--  Forgot Password ?-->
                    <!--</a>-->
                    <button type="submit" id="kt_login_signin_submit" class="btn btn-primary btn-elevate kt-login__btn-primary">Sign In</button>
                  </div>

                  <!--end::Action-->
                </form>

                <!--end::Form-->


              
              </div>

              <!--end::Signin-->
            </div>

            <!--end::Body-->
          </div>

          <!--end::Content-->
        </div>
      </div>
    </div>

    <!-- end:: Page -->

    <!-- begin::Global Config(global config for global JS sciprts) -->
    <script>
      var KTAppOptions = {
        "colors": {
          "state": {
            "brand": "#5d78ff",
            "dark": "#282a3c",
            "light": "#ffffff",
            "primary": "#5867dd",
            "success": "#34bfa3",
            "info": "#36a3f7",
            "warning": "#ffb822",
            "danger": "#fd3995"
          },
          "base": {
            "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
            "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
          }
        }
      };
    </script>

    <!-- end::Global Config -->

    <!--begin::Global Theme Bundle(used by all pages) -->
    <script src="{{ asset('/admin-style/vendors/global/vendors.bundle.js')}}" type="text/javascript"></script>
    <script src="{{ asset('/admin-style/js/demo1/scripts.bundle.js')}}" type="text/javascript"></script>

    <!--end::Global Theme Bundle -->

    <!--begin::Page Scripts(used by this page) -->
    <script src="{{ asset('/admin-style/js/demo1/pages/login/login-1.js')}}" type="text/javascript"></script>

    <!--end::Page Scripts -->
  </body>

  <!-- end::Body -->
</html>