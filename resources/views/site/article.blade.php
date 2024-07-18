@extends('site.layouts.main')

@section('title', 'article | ')

@section('content')
<div id="article" v-if="article_data && article_data.length > 0">
    @include('site.includes.header')
    <div class="container">
        <aside>
            <div class="top_cat">
                <h1 class="top_title">
                    @{{page_content.top_cat}} <span class="line"></span>
                </h1>
                @php
                  $lang = (isset($_COOKIE['lang'])) ? $_COOKIE['lang'] : null; // Check for cookie first

                    // If no cookie, check for session
                    if (is_null($lang) && Session::has('lang')) {
                    $lang = Session::get('lang');
                    }

                    // Set default if neither cookie nor session is set
                    $lang = $lang ?? 'EN'; // Use nullish coalescing operator (??=)

                    $language = App\Models\Language::where("symbol", $lang)->first();
                    $top_categories = App\Models\Category::with(["names" => function ($q) use ($language) {
                        $q->where("language_id", $language->id);
                    }])->where("isTop", true)->get();

                    $topTerms = App\Models\Term::with(["category" => function ($q) use ($language) {
                    $q->with(["names" => function ($Q) use ($language){
                        $Q->where("language_id", $language->id);
                    }]);
                    }, "names" => function ($Qq) use ($language){
                        $Qq->where("language_id", $language->id);
                    }])->orderBy('vists', 'desc')
                    ->limit(6)
                    ->get();
                @endphp
                <div class="categories">
                    @if ($top_categories->count() > 0)
                        @foreach ($top_categories as $cat)
                            <a href="/category/{{$cat->id}}" class="cat" style="position: relative">
                                <div class="after" style="  width: 100%;
                                height: 100%;
                                position: absolute;
                                top: 0;
                                left: 0;
                                background: rgb(0,0,0);
                                background: linear-gradient(180deg, rgba(0,0,0,0) 37%, rgba(0,0,0,1) 100%);"></div>
                                <img src="{{$cat->thumbnail_path}}">
                                <h3>{{ $cat->names[0]->name }}</h3>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="top_words">
                <h1 class="top_title">
                    @{{page_content.top_word}} <span class="line"></span>
                </h1>
                <div class="terms">
                    @if ($topTerms->count() > 0)
                        @foreach ($topTerms as $term)
                            <a href="/term/{{$term->name}}/{{$term->id}}" class="term">
                                <h2>{{ $term->names[0]->term }}</h2>
                                <h4>{{ $term->category->names[0]->name }}</h4>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </aside>
        <article>
            <div class="head">
                <h1>@{{ article_data.title }}</h1>
                <div>
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
        showSearch: false,
        user: null,
        all_categories: null,
        languages_data: null,
        current_lang: "EN",
        page_content: null,
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
        window.location.reload()
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
    async handleSearch(lang){
        try {
            const response = await axios.post( `/search-term`, {
                lang: lang,
                search_words: this.search
            },
            );
            $('.loader').fadeOut()
            if (response.data.status === true) {
                document.getElementById('errors').innerHTML = ''
                this.searchArticles = response.data.data.data
            } else {
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
