@php
    $settings = Utility::settings();
    $setting = App\Models\Utility::colorset();
    
    $color = 'b';

    if(isset($setting['color_flag']) && $setting['color_flag'] == 'true')
    {
        $themeColor = black;
    }
    else {
        $themeColor = 'black';
    }
    $company_logo = \App\Models\Utility::GetLogo();
    $logo=\App\Models\Utility::get_file('uploads/logo/');
   
@endphp

<!DOCTYPE html>

<head>
    <title>{{(\App\Models\Utility::getValByName('title_text')) ? \App\Models\Utility::getValByName('title_text') : config('app.name', 'WhatsStore SaaS')}} - @yield('title')</title>
    <!-- Meta -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Farm Exceed" />
    <meta name="keywords" content="Farm Exceed" />
    <meta name="author" content="Farm Exceed" />

    <!-- Favicon icon -->
    <link rel="icon" href="{{asset(Storage::url('uploads/logo/')).'/favicon.png' . '?timestamp='. time()}}" type="image/png">

    <link rel="stylesheet" href="{{ asset('custom/libs/animate.css/animate.min.css') }}" id="stylesheet">

   
            <link rel="stylesheet" href="{{ asset('assets/css/style.css')}}" id="main-style-link">
     
   
        <link rel="stylesheet" href="{{ asset('css/custom-auth.css') }}" id="main-style-link">
    
    <style>
        :root {
            --color-customColor: <?= $color ?>;    
        }

        .center-logo {
            height: ;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/custom-color.css') }}">
</head>

<body>
    
    <!-- [custom-login] start -->
    <div class="custom-login">
        
        <div class="bg-login bg-white">
            
        </div>
        <div class="custom-login-inner">
            <header class="dash-header logo center-logo">
                <img src="landing/logo.png" height="200" width="400" alt="">
            </header>
            <main class="custom-wrapper">
                <div class="custom-row">
                    <div class="card">
                        @yield('content')
                    </div>
                </div>
            </main>
            <footer>
                <div class="auth-footer">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <span>&copy; {{date('Y')}} Farm Exceed </span>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

   
   
    @if (Session::has('success'))
    <script>
        show_toastr('{{ __('Success') }}', '{!! session('success') !!}', 'success');
    </script>
    {{ Session::forget('success') }}
    @endif
    @if (Session::has('error'))
    <script>
        show_toastr('{{ __('Error') }}', '{!! session('error') !!}', 'error');
    </script>
    {{ Session::forget('error') }}
    @endif
    
</body>
</html
