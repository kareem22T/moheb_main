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
        .play_btn.playing .icon-tabler-player-play-filled{
            display: none
        }
        .play_btn.pausing .icon-tabler-player-pause-filled{
            display: none
        }
        .play_btn {
            padding: 10px;
            border: none;
            background-color: #b10a0b;
            color: white;
            cursor: pointer;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center
        }
        .audio-player audio{
            display: none
        }
        body {
  font-family: Arial, sans-serif;
}

.search-container {
  margin-bottom: 20px;
}

#search-input {
  width: 100%;
  padding: 10px;
  margin-bottom: 10px;
}

#search-results {
  list-style-type: none;
  padding: 0;
}

#search-results li {
  padding: 10px;
  background-color: #f1f1f1;
  margin-bottom: 5px;
  cursor: pointer;
}

.audio-player {
  display: flex;
  align-items: center;
  gap: 10px;
}

button {
  padding: 10px;
  border: none;
  background-color: #007BFF;
  color: white;
  cursor: pointer;
}

button.play {
  background-color: #28a745;
}

button.pause {
  background-color: #dc3545;
}

input[type="range"] {
  width: 150px;
}

span {
  font-size: 14px;
}

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
        audio:hover, audio:focus, audio:active
{
-webkit-box-shadow: 15px 15px 20px rgba(0,0, 0, 0.4);
-moz-box-shadow: 15px 15px 20px rgba(0,0, 0, 0.4);
box-shadow: 15px 15px 20px rgba(0,0, 0, 0.4);
-webkit-transform: scale(1.05);
-moz-transform: scale(1.05);
transform: scale(1.05);
}


audio
{
-webkit-transition:all 0.5s linear;
-moz-transition:all 0.5s linear;
-o-transition:all 0.5s linear;
transition:all 0.5s linear;
-moz-box-shadow: 2px 2px 4px 0px #006773;
-webkit-box-shadow:  2px 2px 4px 0px #006773;
box-shadow: 2px 2px 4px 0px #006773;
-moz-border-radius:7px 7px 7px 7px ;
-webkit-border-radius:7px 7px 7px 7px ;
border-radius:7px 7px 7px 7px ;
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
        .content-fluid {
            display: flex;
            justify-content: center;
            align-items: start;
            gap: 16px
        }
        .content-fluid >div >*:not(.container) {
            width: 100%
        }
        header .bottom {
            width: 100%;
            padding: .5rem 0;
            background-color: var(--secondary-color);
            white-space: nowrap;
        }
        .content-fluid >img {
            max-width: 150px;
            margin-top: 24px;
            border-radius: 8px
        }
        .ad {
            width: 100% !important;
            max-width: 750px;
            border-radius: 8px;
            margin: auto;
            display: block;
            margin-top: 24px;
        }
        @media (max-width: 1481px) {
            .content-fluid >img {
                display: none
            }
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
        // $(document).bind("contextmenu",function(e) {
        //     e.preventDefault();
        //     });
        //     $(document).keydown(function(e){
        //     if(e.which === 123){
        //         return false;
        //     }
        // });
        // // $(document).ready(function() {
        // //     $('#searchForm').on('submit', function(event) {
        // //         event.preventDefault();  // Prevent the default action
        // //         $("#error").fadeOut()
        // //         window.location.href = `/search/${$('#search').val()}`;
        // //     });
        // // });
        // // $(document).ready(function() {
        // //     $('#search').on('keydown input', function(event) {
        // //     $("#error").fadeOut()
        // //         // Check if the Enter key is pressed
        // //         if (event.key === 'Enter' || event.keyCode === 13) {
        // //             event.preventDefault();  // Prevent the default action (if necessary)
        // //             window.location.href = `/search/${$(this).val()}`;
        // //         }
        // //     });
        // // });

        document.querySelectorAll('.audio_wrapper').forEach((wrapper, index) => {
  const audio = wrapper.querySelector('audio');
  const playPauseButton = wrapper.querySelector(`#play-pause-${index + 1}`);
  const seekSlider = wrapper.querySelector(`#seek-slider-${index + 1}`);
  const currentTimeElem = wrapper.querySelector(`#current-time-${index + 1}`);
  const durationElem = wrapper.querySelector(`#duration-${index + 1}`);
  const volumeSlider = wrapper.querySelector(`#volume-slider-${index + 1}`);

  let isPlaying = false;

  // Play or pause audio
  playPauseButton.addEventListener('click', () => {
    if (isPlaying) {
      audio.pause();
      playPauseButton.textContent = 'Play';
      playPauseButton.classList.replace('pause', 'play');
    } else {
      audio.play();
      playPauseButton.textContent = 'Pause';
      playPauseButton.classList.replace('play', 'pause');
    }
    isPlaying = !isPlaying;
  });

  // Update seek slider as audio plays
  audio.addEventListener('timeupdate', () => {
    const currentTime = audio.currentTime;
    const duration = audio.duration;
    seekSlider.value = (currentTime / duration) * 100;
    currentTimeElem.textContent = formatTime(currentTime);
    durationElem.textContent = formatTime(duration);
  });

  // Seek audio
  seekSlider.addEventListener('input', () => {
    const duration = audio.duration;
    audio.currentTime = (seekSlider.value / 100) * duration;
  });

  // Change volume
  volumeSlider.addEventListener('input', () => {
    audio.volume = volumeSlider.value;
  });

  // Format time in minutes and seconds
  function formatTime(time) {
    const minutes = Math.floor(time / 60);
    const seconds = Math.floor(time % 60);
    return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
  }
});

$(document).on("click", ".audio-player .play_btn", function () {
    var audioElement = $(this).prev()[0]; // Get the DOM element
    if (audioElement.paused) {
        audioElement.play();
        $(this).removeClass('pausing').addClass('playing');
    } else {
        audioElement.pause();
        $(this).removeClass('playing').addClass('pausing');
    }
});
    </script>
</body>
</html>
