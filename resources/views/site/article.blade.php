@extends('site.layouts.main')

@section('title', 'article | ')

@section('content')
<div id="article" v-if="article_data && article_data.length > 0">
    @include('site.includes.header')
    <div class="container">
                <aside>
            <div class="top_cat">
                <h1 class="top_title">
                    Top Categories <span class="line"></span>
                </h1>
                <div class="categories">
                    <div class="cat">
                        <img src="{{ asset('/site/imgs/top-basketball.jpg') }}">
                        <h3>Basketball</h3>
                    </div>
                    <div class="cat">
                        <img src="{{ asset('/site/imgs/top-basketball.jpg') }}">
                        <h3>Basketball</h3>
                    </div>
                    <div class="cat">
                        <img src="{{ asset('/site/imgs/top-basketball.jpg') }}">
                        <h3>Basketball</h3>
                    </div>
                    <div class="cat">
                        <img src="{{ asset('/site/imgs/top-basketball.jpg') }}">
                        <h3>Basketball</h3>
                    </div>
                    <div class="cat">
                        <img src="{{ asset('/site/imgs/top-basketball.jpg') }}">
                        <h3>Basketball</h3>
                    </div>
                </div>
            </div>
            <div class="top_words">
                <h1 class="top_title">
                    Top Words <span class="line"></span>
                </h1>
                <div class="terms">
                    <a  class="term">
                        <h2>Off Side</h2>
                        <h4>Football</h4>
                    </a>
                    <a  class="term">
                        <h2>Off Side</h2>
                        <h4>Football</h4>
                    </a>
                    <a  class="term">
                        <h2>Off Side</h2>
                        <h4>Football</h4>
                    </a>
                    <a  class="term">
                        <h2>Off Side</h2>
                        <h4>Football</h4>
                    </a>
                    <a  class="term">
                        <h2>Off Side</h2>
                        <h4>Football</h4>
                    </a>
                    <a  class="term">
                        <h2>Off Side</h2>
                        <h4>Football</h4>
                    </a>
                </div>
            </div>
        </aside>
        <article>
            <div class="head">
                <h1>@{{ article_data.title }}</h1>
                <div>
                    <span>By <b>Admin</b></span>
                    <span class="date">
                        <i class="fa-regular fa-calendar-days" style="font-size: 18px; margin: 0 7px;"></i> @{{ new Date(article_data.created_at).toLocaleString("en-US", {
                                                                                    month: "long",
                                                                                    day: "numeric",
                                                                                    year: "numeric"
                                                                                    }) }}
                    </span>
                </div>
            </div>
            <div class="thumbnail" v-if="article_data.thumbnail_path">
                <img :src="article_data.thumbnail_path" alt="">
            </div>
            <div class="content" v-html="article_data.content">
            </div>
            <div class="sound" v-html="article_data.sound">
            </div>
        </article>
    </div>
    @include('site.includes.footer')
</div>
@endsection

@section('scripts')
<script>
const { createApp, ref } = Vue
createApp({
data() {
    return {
        article_id: `{{ request()->id }}`,
        article_data: null,
        user: null,
        all_categories: null,
        languages_data: null,
        current_lang: "EN",
        search: null,
        searchArticles: [],
        showProfileMore: false,
    }
},
methods: {
    async getarticle(id, lang){
        $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post( `/article`, {
                id: id,
                lang: lang
            },
            );
            $('.loader').fadeOut()
            if (response.data.status === true) {
                document.getElementById('errors').innerHTML = ''
                this.article_data = response.data.data
                document.title = 'Moheb | article | ' + this.article_data.title;
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
    async getLanguages() {
        $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`/admin/categories/get-languages`, {
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
                const response = await axios.get(`{{ route('site.get-user') }}`,
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
        this.getarticle(this.article_id, this.current_lang)
        this.getAllCategories(this.current_lang)
        if (this.current_lang.includes("AR")) {
            document.body.classList = 'AR'
        } else {
            document.body.classList = ''
        }

    },
    async getLang() {
        var isLang = this.checkCookie('lang');
        if (isLang) {
            sessionStorage.setItem('lang', this.getCookie('lang'))
            this.current_lang = sessionStorage.getItem('lang')
        }
    },
    async handleSearch(lang){
        try {
            const response = await axios.post( `{{ route('words.search') }}`, {
                lang: lang,
                search_words: this.search
            },
            );
            $('.loader').fadeOut()
            if (response.data.status === true) {
                document.getElementById('errors').innerHTML = ''
                this.searchArticles = response.data.data.data
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
        this.getarticle(this.article_id, this.current_lang)
        this.getAllCategories(this.current_lang)
        if (this.current_lang.includes("AR")) {
            document.body.classList = 'AR'
        }
    })
    this.getUser()
    this.getLanguages()
},
}).mount('#article')
</script>
@endSection
