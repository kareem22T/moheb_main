<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('/site/css/main.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">    <style>
    .loader {
        width: 100vw;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 99999999;
        background: #ffffff !important;
        backdrop-filter: blur(1px);
        }
        .custom-loader {
        --d:22px;
        width: 4px;
        height: 4px;
        border-radius: 50%;
        color: #ff3100;
        box-shadow:
            calc(1*var(--d))      calc(0*var(--d))     0 0,
            calc(0.707*var(--d))  calc(0.707*var(--d)) 0 1px,
            calc(0*var(--d))      calc(1*var(--d))     0 2px,
            calc(-0.707*var(--d)) calc(0.707*var(--d)) 0 3px,
            calc(-1*var(--d))     calc(0*var(--d))     0 4px,
            calc(-0.707*var(--d)) calc(-0.707*var(--d))0 5px,
            calc(0*var(--d))      calc(-1*var(--d))    0 6px;
        animation: s7 1s infinite steps(8);
        }

        @keyframes s7 {
        100% {transform: rotate(1turn)}
        }

        #errors {
            position: fixed;
            top: 190px;
            right: 1.25rem;
            display: flex;
            flex-direction: column;
            max-width: calc(100% - 1.25rem * 2);
            gap: 1rem;
            z-index: 99999999999999999999;

            }
            #errors >* {
            width: 100%;
            color: #fff;
            font-size: 1.1rem;
            padding: 1rem;
            border-radius: 1rem;
            box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
            }

            #errors .error {
            background: #e41749;
            }
            #errors .success {
            background: #12c99b;
            }

    </style>
    <style>
        .pagination label {
            width: 40px;
            height: 40px;
            background: #1a3467;
            display: block;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: 600;
            cursor: pointer;
        }
        .pagination label.active {
            background: white;
            border: 2px solid #1a3467;
            color: #1a3467;
        }
        .pagination .prev, .pagination .next {
            width: 70px;
            height: 40px;
            background: #1a3467;
            display: block;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: 600;
            cursor: pointer;
        }
        .pagination button:disabled {
            opacity: .5;
        }
        .pagination label input {
            display: none
        }
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 2rem
        }
        .suggestion a span{
            font-size: 14px !important;
            padding-left: 20px;
            color: #b10a0b !important;
            position: relative;
        }
        .suggestion a span::after{
            content: '';
            position: absolute;
            top: 50%;
            left: 8px;
            transform: translateY(-50%);
            width: 9px;
            height: 2px;
            background: #b10a0b;
        }
        .sub_categories .card {
            position: relative;
        }
        .sub_categories .card .cat {
            position: absolute;
            top: 8px;
            right: 8px;
            background: #1a3467a6;
            color: white;
            font-size: 14px;
            padding: 4px 8px;
            border-radius: 5px;
            font-weight: 600
        }
    </style>
    <script>
        document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        });
        document.addEventListener('copy', function(e) {
            e.clipboardData.setData('text/plain', 'Please do not copy text');
            e.clipboardData.setData('text/html', '<b>Please do not copy text</b>');
            e.preventDefault();
        });
    </script>
    <title>Moheb | @yield('title')</title>
</head>
<body>
    <div class="loader" style="background-color: #fff;">
        <div class="custom-loader"></div>
    </div>
    <div id="errors"></div>
    @yield('content')
    <script src="{{ asset('/libs/vue.js') }}"></script>
    <script src="{{ asset('/libs/jquery.js') }}"></script>
    <script src="{{ asset('/libs/axios.js') }}"></script>

    @yield('scripts')

    <script>

        $(document).on('click', '.more .fa-bars', function () {
            $('.mobile-menu').fadeIn().css('display', 'flex')
        })

        $(document).on('click', '.close', function () {
            $('.mobile-menu').fadeOut()
        })
        $(function() {
            $(this).bind("contextmenu", function(e) {
                e.preventDefault();
            });
        });
        $(document).bind("contextmenu",function(e) {
            e.preventDefault();
            });
            $(document).keydown(function(e){
            if(e.which === 123){
                return false;
            }
        });
        $(document).ready(function() {
        $('#search').on('keydown input', function(event) {
                // Check if the Enter key is pressed
                if (event.key === 'Enter' || event.keyCode === 13) {
                    event.preventDefault();  // Prevent the default action (if necessary)
                    window.location.href = `/search/${$(this).val()}`;
                }
            });
        });
    </script>
</body>
</html>
