@extends('site.layouts.main')

@section('title', 'United Podcast | اتصل بنا')
@section('contact_active', 'active')

@section('content')
@php
    $contact = App\Models\Contact::first();
@endphp
@if($contact)
<style>
    .about::after {
        width: 80px;
        height: 80px;
        content: '';
        position: absolute;
        border-top: 3px solid;
        top: 0;
        right: 0;
        border-right: 3px solid;
        border-color: #040486;
        border-radius: 0 10px 0 0
    }
    .about::before {
        width: 80px;
        height: 80px;
        content: '';
        position: absolute;
        border-left: 3px solid;
        bottom: 0;
        left: 0;
        border-bottom: 3px solid;
        border-color: #040486;
        border-radius: 0 0 0 10px
    }
    @media (max-width: 575.98px) {
        .about {
            grid-template-columns: 1fr !important;
            padding: 15px !important
        }
        .about p {
            font-size: 14px !important
        }
    }
    .social a {
        color: white;
        font-size: 18px;
        font-weight: 600;
        display: flex;
        flex-direction: column-reverse;
        justify-content: center;
        align-items: center;
        border: 1px solid white;
        padding: 10px;
        width: 100%;
        border-radius: 5px;
        transition: all .3s ease-in
    }
    .social a:hover {
        background: white;
        color: #1a3467
    }
    .social a:hover svg {
        stroke: #1a3467
    }
    .social a.anghami:hover svg {
        fill: #1a3467
    }
    .social a svg {
        stroke: #fff;
        width: 30px;
        height: 30px;
        transition: all .3s ease-in
    }
    .social a.anghami svg {
        fill: #fff;
        width: 27px;
        height: 27px;
        transition: all .3s ease-in
    }
</style>

<div id="home">
    @include('site.includes.header')
    <div class="container">

        <div class="about" style="padding: 1.5rem;width: 100%;margin: auto;position: relative;display: grid;grid-template-columns: 1fr 1fr;gap: 2rem; margin-top: 2rem; margin-bottom: 5rem">
            <div class="img" style="position: relative;width: 100%;height: 100%;">
                <img src="{{asset('/assets/img/contact.jpg')}}" alt="" style="width: 100%;height: 100%;object-fit: cover;border-radius: 10px;position: absolute;top: 0;left: 0;">
                <div class="bg" style="width: 100%;height: 100%;top: 0;left: 0;position: absolute;background: #040486;border-radius: 10px;opacity: 0.7;"></div>
                <div class="social" style="position: relative;top: 0;left: 0;width: 100%;height: 100%;display: flex;flex-direction: column;justify-content: center;align-items: center;gap: 10px;padding: 1rem;display: grid;grid-template-columns: 1fr 1fr;">
                @if($contact->email)
                    <a  href="mailto:{{$contact->email}}" style="grid-column: span 2">
                        {{ $contact->email }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="#0d0d0d" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                            <path d="M3 7l9 6l9 -6" />
                        </svg>
                    </a>
                @endif
                @if($contact->phone)
                    <a  href="tel:{{$contact->phone}}" style="grid-column: span 2">
                        {{ $contact->phone }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-phone-call" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="#0d0d0d" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" />
                            <path d="M15 7a2 2 0 0 1 2 2" />
                            <path d="M15 3a6 6 0 0 1 6 6" />
                          </svg>
                    </a>
                @endif
                    @if($contact->facebook)
                        <a  href="{{$contact->facebook}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-facebook" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="#0d0d0d" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" />
                              </svg>
                        </a>
                    @endif
                    @if($contact->instagram)
                        <a  href="{{$contact->instagram}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-instagram" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="#0d0d0d" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M4 4m0 4a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" />
                                <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                <path d="M16.5 7.5l0 .01" />
                              </svg>
                        </a>
                    @endif
                    @if($contact->youtube)
                        <a  href="{{$contact->youtube}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-youtube" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="#0d0d0d" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M2 8a4 4 0 0 1 4 -4h12a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-12a4 4 0 0 1 -4 -4v-8z" />
                                <path d="M10 9l5 3l-5 3z" />
                              </svg>
                        </a>
                    @endif
                    @if($contact->tiktok)
                        <a  href="{{$contact->tiktok}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-tiktok" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="#0d0d0d" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M21 7.917v4.034a9.948 9.948 0 0 1 -5 -1.951v4.5a6.5 6.5 0 1 1 -8 -6.326v4.326a2.5 2.5 0 1 0 4 2v-11.5h4.083a6.005 6.005 0 0 0 4.917 4.917z" />
                              </svg>
                        </a>
                    @endif
                    @if($contact->x)
                        <a  href="{{$contact->x}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-x" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="#0d0d0d" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M4 4l11.733 16h4.267l-11.733 -16z" />
                                <path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772" />
                              </svg>
                        </a>
                    @endif
                    @if($contact->spotify)
                        <a  href="{{$contact->spotify}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-spotify" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#0d0d0d" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                <path d="M8 11.973c2.5 -1.473 5.5 -.973 7.5 .527" />
                                <path d="M9 15c1.5 -1 4 -1 5 .5" />
                                <path d="M7 9c2 -1 6 -2 10 .5" />
                              </svg>
                        </a>
                    @endif
                    @if($contact->anghami)
                        <a  class="anghami" href="{{$contact->anghami}}">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 612 611.29" style="enable-background:new 0 0 612 611.29;" xml:space="preserve">

                       <g>
                           <path class="st0" d="M594.72,568.31c-9.06-1.59-17.78-1.98-25.46-5c-3.27-1.29-6.17-8.08-6.19-12.39
                               c-0.37-79.8,0.49-159.61-0.48-239.4c-1.27-104.04-46.53-183.72-138.65-233C295.31,9.7,136.53,64.96,74.29,196.5
                               c-59.94,126.67-6.44,274.09,114.13,336.5c56.39,29.19,115.94,36.43,177.61,21.42c14.04-3.42,27.09-4.65,41.04-1.01
                               c10.77,2.81,21.92,4.17,33.64,9.13c-13.98,5.91-27.64,12.77-41.99,17.59c-160.45,53.89-332.17-40.4-374.06-205.15
                               C-16.17,214.42,91.11,50.05,254.53,20.58c149.72-27,295.34,66.86,332.3,214.92c5.22,20.91,7.94,42.95,8.17,64.5
                               c0.92,86.02,0.4,172.05,0.38,258.08C595.37,561.11,595,564.13,594.72,568.31z"/>
                           <path class="st0" d="M384.24,494.16c0,11.48,1.03,20.78-0.53,29.64c-0.58,3.32-6.78,6.88-11.06,8.14
                               c-44.71,13.17-89.89,14.02-134.19-0.29C149.72,503,94.7,442.54,74.34,351.37C49.4,239.73,118.94,116.97,228.1,82.52
                               c96.71-30.53,182.16-9.66,252.46,64.05c38.28,40.13,59.66,89.4,60.67,145.11c1.55,85.23,0.63,170.5,0.71,255.75
                               c0,2.65-0.59,5.31-1.14,9.99c-9.07-1.57-17.86-2.25-25.95-4.99c-2.58-0.87-4.65-7.2-4.68-11.05c-0.32-46.71-0.21-93.43-0.22-140.14
                               c-0.01-33.09,0.15-66.18-0.06-99.27c-0.6-93.49-71.25-176.94-162.58-196.13C239.34,83.14,126.39,153.99,104.75,271.3
                               c-19.3,104.6,55.27,213.6,159.47,233.73C304.37,512.78,342.84,508.97,384.24,494.16z"/>
                           <path class="st0" d="M384.52,434.75c0,12.77,0.75,22.87-0.43,32.73c-0.39,3.22-5.33,6.96-9.02,8.55
                               c-72.58,31.22-163.63,7.4-212.68-55.38c-86.07-110.15-28.08-269.7,109.2-295.97c116.24-22.25,215.67,65.67,218.1,174.2
                               c1.79,80.13,0.39,160.34,0.35,240.51c0,2.63-0.62,5.27-1.18,9.75c-9.63-1.96-18.83-3.15-27.48-6.01c-2.5-0.83-4.77-6.44-4.82-9.89
                               c-0.38-28.41-0.2-56.82-0.21-85.23c-0.01-46.7,0.21-93.41-0.08-140.11c-0.48-77.42-51.53-137.93-127.38-151.47
                               c-70.29-12.55-145.42,34.37-166.54,103.99c-22.9,75.54,14.07,154.5,87.09,184.34c41.03,16.76,82.02,14.76,122.23-3.8
                               C375.13,439.37,378.52,437.66,384.52,434.75z"/>
                           <path class="st0" d="M382.42,373.11c0,11.49,1.8,22.98-0.72,33.42c-1.38,5.71-9.33,11.18-15.57,14.14
                               c-44.75,21.25-88.87,20.51-131.05-6.82c-49.16-31.86-70.67-92.32-53.05-147.04c18.09-56.19,69.94-92.98,128.5-91.19
                               c68.16,2.09,123.99,57.6,124.98,126.5c1.07,74.34,0.2,148.7,0.5,223.06c0.04,9.77-3.46,12.17-12.63,10.41
                               c-18.26-3.5-18.28-3.2-18.28-22.16c0-69.68,0.43-139.37-0.19-209.05c-0.5-56.33-47.03-99.87-103.27-97.97
                               c-39.26,1.33-74.14,27.04-88.7,65.39c-13.79,36.31-3.71,78.19,25.53,106.02c28.17,26.81,71.03,34.75,106.23,18.37
                               C357.47,390.25,368.91,381.49,382.42,373.11z"/>
                       </g>
                       </svg>
                    </a>
                    @endif
                </div>
            </div>
            <div style="display: flex; align-items: center;align-items: center;justify-content: center;width: 100%">
                <div style="display: flex;flex-direction: column;justify-content: center;align-items: center;gap: 0;width: 100%">
                    <h3 style="font-weight: 700;">@{{ page_content.contact.head }}</h3>
                    <p>
                        @{{ page_content.contact.head }}
                    </p>
                    <div class="form-group" style="margin: 20px 0 16px;width: 100%;display: flex; flex-direction: column; gap: 10px">
                        <input type="text" name="text" id="name" class="form-control" :placeholder="page_content.contact.name" style="box-sizing: border-box;margin: 0;padding: 9.8px 11px;color: rgba(0, 0, 0, 0.88);font-size: 16px;line-height: 1.15;list-style: none;position: relative;display: inline-block;width: 100%;min-width: 0;background-color: #ffffff;background-image: none;border-width: 1px;border-style: solid;border-color: #d9d9d9;border-radius: 8px;transition: all 0.2s;direction: ltr;">
                        <input type="text" name="text" id="subject" class="form-control" :placeholder="page_content.contact.address" style="box-sizing: border-box;margin: 0;padding: 9.8px 11px;color: rgba(0, 0, 0, 0.88);font-size: 16px;line-height: 1.15;list-style: none;position: relative;display: inline-block;width: 100%;min-width: 0;background-color: #ffffff;background-image: none;border-width: 1px;border-style: solid;border-color: #d9d9d9;border-radius: 8px;transition: all 0.2s;direction: ltr;">
                        <input type="text" name="email" id="email" class="form-control" :placeholder="page_content.contact.email" style="box-sizing: border-box;margin: 0;padding: 9.8px 11px;color: rgba(0, 0, 0, 0.88);font-size: 16px;line-height: 1.15;list-style: none;position: relative;display: inline-block;width: 100%;min-width: 0;background-color: #ffffff;background-image: none;border-width: 1px;border-style: solid;border-color: #d9d9d9;border-radius: 8px;transition: all 0.2s;direction: ltr;">
                        <textarea name="msg" id="msg" cols="30"  class="form-control" rows="4" :placeholder="page_content.contact.message" style="box-sizing: border-box;margin: 0;padding: 9.8px 11px;color: rgba(0, 0, 0, 0.88);font-size: 16px;line-height: 1.15;list-style: none;position: relative;display: inline-block;width: 100%;min-width: 0;background-color: #ffffff;background-image: none;border-width: 1px;border-style: solid;border-color: #d9d9d9;border-radius: 8px;transition: all 0.2s;direction: ltr;resize: none"></textarea>
                    </div>
                    <button id="login_with_email" style="outline: none;position: relative;display: inline-block;font-weight: 400;white-space: nowrap;text-align: center;background-image: none;background-color: #1a3467;border: 1px solid transparent;cursor: pointer;transition: all 0.2s cubic-bezier(0.645, 0.045, 0.355, 1);user-select: none;touch-action: manipulation;line-height: 1.15;color: #fff;padding: 10px 3rem;width: 100%;border-radius: 10px;">@{{page_content.contact.send}}</button>
                </div>
        </div>
    </div>
</div>
@include('site.includes.footer')
@endif
@endsection
@section("scripts")
<script>
    $(".loader").fadeOut()
</script>
<script>

    const { createApp, ref } = Vue
    createApp({
    data() {
        return {
            user: null,
            languages_data: null,
            current_lang: "EN",
            terms: null,
            all_categories: null,
            categories: null,
            articles: null,
            football: null,
            page_translations: null,
            page_content: this.page_translations ? this.page_translations[this.current_lang] : '',
            showProfileMore: false,
            searchArticles: [],
            currentPage: 1,
            lastPage: null, // This value should be set based on your actual last page
            maxVisiblePages: 5 // Set the maximum number of visible pages
        }
    },
    methods: {
        async getLanguages() {
            $('.loader').fadeIn().css('display', 'flex')
            try {
                const response = await axios.post(`/admin/admin/categories/get-languages`, {
                },
                );
                if (response.data.status === true) {
                    $('.loader').fadeOut()
                    this.languages_data = response.data.data
                } else {
                    $('.loader').fadeOut()
                    document.getElementById('errors').innerHTML = ''
                    $.each(response.data.errors, function (key, value) {
                        let error = document.createElement('div')
                        error.classList = 'error'
                        error.innerHTML = value
                        document.getElementById('errors').append(error)
                    });
                    $('#errors').fadeIn('slow')
                    setTimeout(() => {
                        $('input').css('outline', 'none')
                        $('#errors').fadeOut('slow')
                    }, 5000);
                }

            } catch (error) {
                document.getElementById('errors').innerHTML = ''
                let err = document.createElement('div')
                err.classList = 'error'
                err.innerHTML = 'server error try again later'
                document.getElementById('errors').append(err)
                $('#errors').fadeIn('slow')
                $('.loader').fadeOut()
                this.languages_data = false
                setTimeout(() => {
                    $('#errors').fadeOut('slow')
                }, 3500);

                console.error(error);
            }
        },
        async getAllCategories(lang){
            $('.loader').fadeIn().css('display', 'flex')
            try {
                const response = await axios.post( `/get-categories`, {
                    lang: lang
                },
                );
                $('.loader').fadeOut()
                if (response.data.status === true) {
                    document.getElementById('errors').innerHTML = ''
                    this.all_categories = response.data.data
                    setTimeout(() => {
                        $('#errors').fadeOut('slow')
                    }, 4000);
                } else {
                    document.getElementById('errors').innerHTML = ''
                    $.each(response.data.errors, function (key, value) {
                        let error = document.createElement('div')
                        error.classList = 'error'
                        error.innerHTML = value
                        document.getElementById('errors').append(error)
                    });
                    $('#errors').fadeIn('slow')
                    setTimeout(() => {
                        $('input').css('outline', 'none')
                        $('#errors').fadeOut('slow')
                    }, 3500);
                }

            } catch (error) {
                document.getElementById('errors').innerHTML = ''
                let err = document.createElement('div')
                err.classList = 'error'
                err.innerHTML = 'server error try again later'
                document.getElementById('errors').append(err)
                $('#errors').fadeIn('slow')
                $('.loader').fadeOut()

                setTimeout(() => {
                    $('#errors').fadeOut('slow')
                }, 3500);

                console.error(error);
            }
        },

        getCookie(cookieName) {
            const name = cookieName + "=";
            const decodedCookie = decodeURIComponent(document.cookie);
            const cookieArray = decodedCookie.split(';');

            for(let i = 0; i < cookieArray.length; i++) {
                let cookie = cookieArray[i];
                while (cookie.charAt(0) === ' ') {
                cookie = cookie.substring(1);
                }
                if (cookie.indexOf(name) === 0) {
                return cookie.substring(name.length, cookie.length);
                }
            }
            return "";
        },
        checkCookie(cookieName) {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = cookies[i].trim();
                if (cookie.indexOf(`${cookieName}=`) === 0) {
                    return true; // Found 'user_token' cookie
                }
            }
            return false; // 'user_token' cookie not found
        },
        async getUser() {
            var hasUserTokenCookie = this.checkCookie('user_token');
            if (hasUserTokenCookie) {
                sessionStorage.setItem('user_token', this.getCookie('user_token'))
            }
            let user_token = sessionStorage.getItem('user_token')
            if (user_token) {
                $('.loader').fadeIn().css('display', 'flex')
                try {
                    const response = await axios.get(`/get-user`,
                        {
                            headers: {
                                'AUTHORIZATION': `Bearer ${user_token}`
                            }
                        },
                    );
                    $('.loader').fadeOut()
                    if (response.data.status === true) {
                        sessionStorage.setItem('user', JSON.stringify(response.data.data))
                        this.user = response.data.data
                    } else {
                        return false;
                    }

                } catch (error) {
                    console.error(error);
                    return false;
                }
            }
        },
        setCookie(name, value, days) {
            var expirationDate = new Date();
            expirationDate.setDate(expirationDate.getDate() + days);

            var expires = "expires=" + expirationDate.toUTCString();
            document.cookie = name + "=" + value + ";" + expires + ";path=/";
        },
        setLang() {
            this.setCookie('lang', this.current_lang, 30)
            this.getAllCategories(this.current_lang)
            if (this.current_lang.includes("AR")) {
                document.body.classList = 'AR'
            } else {
                document.body.classList = ''
            }
            this.page_content = this.page_translations ? this.page_translations[this.current_lang] : '';

        },
        async getLang() {
            fetch("/json/home.json?v={{time()}}")
            .then((response) => response.json())
            .then((data) => {
            // Use the JSON data
                this.page_translations = data;
                this.page_content = this.page_translations ? this.page_translations[this.current_lang] : ''
            })
            .catch((error) => {
            console.log('Error:', error);
            });

            var isLang = this.checkCookie('lang');
            if (isLang) {
                sessionStorage.setItem('lang', this.getCookie('lang'))
                this.current_lang = sessionStorage.getItem('lang')
            }
        },
        computed: {
            visiblePages() {
            const totalPages = this.lastPage;
            const currentPage = this.currentPage;
            const maxVisiblePages = this.maxVisiblePages;

            const startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

            // Adjust startPage and endPage if not enough pages are available to fill maxVisiblePages
            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            return Array.from({ length: endPage - startPage + 1 }, (_, i) => startPage + i);
            }
        },
        async getCategory(){
            $('.loader').fadeIn().css('display', 'flex')
            try {
                const response = await axios.post( `/category`, {
                    page: this.currentPage,
                    lang: this.current_lang,
                    id: "{{request()->id}}"
                },
                );
                $('.loader').fadeOut()
                if (response.data.status === true) {
                    document.getElementById('errors').innerHTML = ''
                    this.football = response.data.data.category
                    this.terms = response.data.data.terms.data
                    this.currentPage = response.data.data.terms.current_page
                    this.lastPage = response.data.data.terms.last_page
                    setTimeout(() => {
                        $('#errors').fadeOut('slow')
                    }, 4000);
                } else {
                    document.getElementById('errors').innerHTML = ''
                    $.each(response.data.errors, function (key, value) {
                        let error = document.createElement('div')
                        error.classList = 'error'
                        error.innerHTML = value
                        document.getElementById('errors').append(error)
                    });
                    $('#errors').fadeIn('slow')
                    setTimeout(() => {
                        $('input').css('outline', 'none')
                        $('#errors').fadeOut('slow')
                    }, 3500);
                }

            } catch (error) {
                document.getElementById('errors').innerHTML = ''
                let err = document.createElement('div')
                err.classList = 'error'
                err.innerHTML = 'server error try again later'
                document.getElementById('errors').append(err)
                $('#errors').fadeIn('slow')
                $('.loader').fadeOut()

                setTimeout(() => {
                    $('#errors').fadeOut('slow')
                }, 3500);

                console.error(error);
            }
        },
    },
    created() {
        this.getLang().then(() => {
            this.getAllCategories(this.current_lang)
            if (this.current_lang.includes("AR")) {
                document.body.classList = 'AR'
            }
        })
        this.getUser()
        this.getLanguages()
    },
    }).mount('#home')
    </script>
@endsection

