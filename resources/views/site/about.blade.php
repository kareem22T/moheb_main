@extends('site.layouts.main')

@section('title', 'About us')
@section('about_active', 'active')

@section('content')
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
        border-color: #1a3467;
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
        border-color: #1a3467;
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
</style>
<div id="home">
    @include('site.includes.header')
    <div class="container" style="margin-top: 2rem; margin-bottom: 5rem">

        <div class="about" style="padding: 1.5rem;width: 100%;margin: auto;position: relative;display: grid;grid-template-columns: 1fr 1fr;gap: 2rem;">
            <img src="{{asset('/site/imgs/about.jpg')}}" alt="" style="width: 100%;border-radius: 10px">
            <div>
                <h1 style="font-weight: 600;color: #0d0d0d;display: flex;alig-items: center;gap: 10px">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-square" width="40" height="40" viewBox="0 0 24 24" stroke-width="2" stroke="#0d0d0d" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M12 9h.01" />
                        <path d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14z" />
                        <path d="M11 12h1v4h1" />
                    </svg>
                    @{{ page_content.about.head }}</h1>
                    <p v-if="descriptions" style="line-height: 25px;margin-bottom: 0;  font-size: 1rem;">
                        @{{descriptions[current_lang] ? descriptions[current_lang] : descriptions['EN']}}
                    </p>
                </div>
            </div>
    </div>
    @include('site.includes.footer')
</div>
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
            descriptions: [],
            currentPage: 1,
            lastPage: null, // This value should be set based on your actual last page
            maxVisiblePages: 5 // Set the maximum number of visible pages
        }
    },
    methods: {
        async getAbout() {
        try {
            const response = await axios.get(`https://amrmoheb.com/admin/get-about`
            );
            this.descriptions = response.data
        } catch (error) {
            document.getElementById('errors').innerHTML = ''
            let err = document.createElement('div')
            err.classList = 'error'
            err.innerHTML = 'server error try again later'
            document.getElementById('errors').append(err)
            $('#errors').fadeIn('slow')
            $('.loader').fadeOut()
            this.Tags_data = false
            setTimeout(() => {
                $('#errors').fadeOut('slow')
            }, 3500);

            console.error(error);
        }
    },
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
        this.getAbout()
        this.getUser()
        this.getLanguages()
    },
    }).mount('#home')
    </script>
@endsection



