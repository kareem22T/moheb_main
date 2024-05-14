@extends('site.layouts.main')

@section('title', 'Term | ')

@section('content')
<div id="term" v-if="term_data && term_data.length > 0">
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
                                <h3>{{ $cat->names->count() > 0 ? $cat->names?[0]->name : $cat->name }}</h3>
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
                                <h2>{{ $term->names?[0]->term }}</h2>
                                <h4>{{ $term->category?->names->count() > 0 ? $term->category?->names?[0]->name : $term->category?->name }}</h4>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </aside>
        <article>
            <div class="head">
                <h1>@{{ term_data.title }}</h1>
                <div style="display: flex;justify-content: space-between;">
                    <div>
                        <span>By <b>Admin</b></span>
                        <span class="date">
                            <i class="fa-regular fa-calendar-days" style="font-size: 18px; margin: 0 7px;"></i> @{{ new Date(term_data.created_at).toLocaleString("en-US", {
                                                                                    month: "long",
                                                                                    day: "numeric",
                                                                                    year: "numeric"
                                                                                    }) }}
                        </span>
                    </div>
                    <button class="add_to_fav">
                        <i class="fa-regular fa-heart" :class="term_data.isFav ? 'active' : ''" @click="handleFav(term_id)"></i>
                    </button>
                </div>
            </div>
            <div class="thumbnail" v-if="term_data.thumbnail_path">
                <img :src="term_data.thumbnail_path" alt="">
            </div>
            @php
                $lang_FR = App\Models\Language::where('symbol', 'FR')->first();
                $term_FR = App\Models\Term::with(
                    [
                        "names" => function ($q) use ($lang_FR) {
                            $q->where("language_id", $lang_FR->id);
                        },
                        "contents" => function ($q) use ($lang_FR) {
                            $q->where("language_id", $lang_FR->id);
                        },
                        "sounds" => function ($q) use ($lang_FR) {
                            $q->where("language_id", $lang_FR->id);
                        },
                    ]
                )->find(request()->id);
                $lang_EN = App\Models\Language::where('symbol', 'EN')->first();
                $term_EN = App\Models\Term::with(
                    [
                        "names" => function ($q) use ($lang_EN) {
                            $q->where("language_id", $lang_EN->id);
                        },
                        "contents" => function ($q) use ($lang_EN) {
                            $q->where("language_id", $lang_EN->id);
                        },
                        "sounds" => function ($q) use ($lang_EN) {
                            $q->where("language_id", $lang_EN->id);
                        },
                    ]
                )->find(request()->id);
                $lang_ESP = App\Models\Language::where('symbol', 'ESP')->first();
                $term_ESP = App\Models\Term::with(
                    [
                        "names" => function ($q) use ($lang_ESP) {
                            $q->where("language_id", $lang_ESP->id);
                        },
                        "contents" => function ($q) use ($lang_ESP) {
                            $q->where("language_id", $lang_ESP->id);
                        },
                        "sounds" => function ($q) use ($lang_ESP) {
                            $q->where("language_id", $lang_ESP->id);
                        },
                    ]
                )->find(request()->id);
                $lang_ITA = App\Models\Language::where('symbol', 'ITA')->first();
                $term_ITA = App\Models\Term::with(
                    [
                        "names" => function ($q) use ($lang_ITA) {
                            $q->where("language_id", $lang_ITA->id);
                        },
                        "contents" => function ($q) use ($lang_ITA) {
                            $q->where("language_id", $lang_ITA->id);
                        },
                        "sounds" => function ($q) use ($lang_ITA) {
                            $q->where("language_id", $lang_ITA->id);
                        },
                    ]
                )->find(request()->id);
                $lang_DEU = App\Models\Language::where('symbol', 'DEU')->first();
                $term_DEU = App\Models\Term::with(
                    [
                        "names" => function ($q) use ($lang_DEU) {
                            $q->where("language_id", $lang_DEU->id);
                        },
                        "contents" => function ($q) use ($lang_DEU) {
                            $q->where("language_id", $lang_DEU->id);
                        },
                        "sounds" => function ($q) use ($lang_DEU) {
                            $q->where("language_id", $lang_DEU->id);
                        },
                    ]
                )->find(request()->id);
                $lang_PORT = App\Models\Language::where('symbol', '(PORT)')->first();
                $term_PORT = App\Models\Term::with(
                    [
                        "names" => function ($q) use ($lang_PORT) {
                            $q->where("language_id", $lang_PORT->id);
                        },
                        "contents" => function ($q) use ($lang_PORT) {
                            $q->where("language_id", $lang_PORT->id);
                        },
                        "sounds" => function ($q) use ($lang_PORT) {
                            $q->where("language_id", $lang_PORT->id);
                        },
                    ]
                )->find(request()->id);
                $lang_AR = App\Models\Language::where('symbol', 'AR')->first();
                $term_AR = App\Models\Term::with(
                    [
                        "names" => function ($q) use ($lang_AR) {
                            $q->where("language_id", $lang_AR->id);
                        },
                        "contents" => function ($q) use ($lang_AR) {
                            $q->where("language_id", $lang_AR->id);
                        },
                        "sounds" => function ($q) use ($lang_AR) {
                            $q->where("language_id", $lang_AR->id);
                        },
                    ]
                )->find(request()->id);
            @endphp
            <div class="content" style="margin-bottom: 0;">
                <h2>Terme en Français: {{ $term_FR->names?[0]->term }}</h2>
                {!! $term_FR->contents?[0]->content !!}
                <div style="margin-top: 10px">
                    {!! $term_FR->sounds->count() > 0 ? $term_FR->sounds?[0]->iframe : '' !!}
                </div>
            </div>
            <div class="content" style="margin-bottom: 0;">
                <h2>Term in English: {{ $term_EN->names?[0]->term }}</h2>
                {!! $term_EN->contents?[0]->content !!}
                <div style="margin-top: 10px">
                    {!! $term_EN->sounds->count() > 0 ? $term_EN->sounds?[0]->iframe : '' !!}
                </div>
            </div>
            <div class="content" style="margin-bottom: 0;">
                <h2>Termino en Español: {{ $term_ESP->names?[0]->term }}</h2>
                {!! $term_ESP->contents?[0]->content !!}
                <div style="margin-top: 10px">
                    {!! $term_ESP->sounds->count() > 0 ? $term_ESP->sounds?[0]->iframe : '' !!}
                </div>
            </div>
            <div class="content" style="margin-bottom: 0;">
                <h2>Termine in Italiano: {{ $term_ITA->names?[0]->term }}</h2>
                {!! $term_ITA->contents?[0]->content !!}
                <div style="margin-top: 10px">
                    {!! $term_ITA->sounds->count() > 0 ? $term_ITA->sounds?[0]->iframe : '' !!}
                </div>
            </div>
            <div class="content" style="margin-bottom: 0;">
                <h2>Begriff auf Deutsch: {{ $term_DEU->names?[0]->term }}</h2>
                {!! $term_DEU->contents?[0]->content !!}
                <div style="margin-top: 10px">
                    {!! $term_DEU->sounds->count() > 0 ? $term_DEU->sounds?[0]->iframe : '' !!}
                </div>
            </div>
            <div class="content" style="margin-bottom: 0;">
                <h2>Termo em Português: {{ $term_PORT->names?[0]->term }}</h2>
                {!! $term_PORT->contents?[0]->content !!}
                <div style="margin-top: 10px">
                    {!! $term_PORT->sounds->count() > 0 ? $term_PORT->sounds?[0]->iframe : '' !!}
                </div>
            </div>
            <div dir="rtl"  class="content content_ar" style="margin-bottom: 0; padding-bottom: 0">
                @php
                    $lang_egp = App\Models\Language::where("symbol", "AR (EGY)")->first();
                    $term_egp = App\Models\Term::with(["names" => function ($q) use ($lang_egp) {
                        $q->where("language_id", $lang_egp->id);
                    }])->find(request()->id);
                    $lang_AL = App\Models\Language::where("symbol", "AR (AL)")->first();
                    $term_AL = App\Models\Term::with(["names" => function ($q) use ($lang_AL) {
                        $q->where("language_id", $lang_AL->id);
                    }])->find(request()->id);
                    $lang_SA = App\Models\Language::where("symbol", "AR ( SA)")->first();
                    $term_SA = App\Models\Term::with(["names" => function ($q) use ($lang_SA) {
                        $q->where("language_id", $lang_SA->id);
                    }])->find(request()->id);
                    $lang_LA = App\Models\Language::where("symbol", "AR (LA)")->first();
                    $term_LA = App\Models\Term::with(["names" => function ($q) use ($lang_LA) {
                        $q->where("language_id", $lang_LA->id);
                    }])->find(request()->id);
                    @endphp
                <h2 style='font-family: "Cairo", sans-serif !important;' >المصطلح بالعربية الفصحى: {{ $term_AR->names?[0]->term }}</h2>
                @if ($term_egp->names->count() > 0)
                <h2 style="margin-top: 0">
                    المصطلح باللهجة المصرية:
                    {{ $term_egp->names?[0]->term }}
                </h2>
                @endif
                @if ($term_AL->names->count() > 0)
                <h2 style="margin-top: 0">
                    المصطلح بلهجة شمال افريقيا:
                    {{ $term_AL->names?[0]->term }}
                </h2>
                @endif
                @if ($term_SA->names->count() > 0)
                <h2 style="margin-top: 0">
                    المصطلح باللهجة الخليجية:
                    {{ $term_SA->names?[0]->term }}
                </h2>
                @endif
                @if ($term_LA->names->count() > 0)
                <h2 style="margin-top: 0">
                    المصطلح بلهجة دول الشام:
                    {{ $term_LA->names?[0]->term }}
                </h2>
                @endif
                {!! $term_AR->contents?[0]->content !!}
                <div style="margin-top: 10px">
                    {!! $term_AR->sounds->count() > 0 ? $term_AR->sounds?[0]->iframe : '' !!}
                </div>
            </div>
            <hr style="height: 1px;border: none;background: rgba(0, 0, 0, .4);margin-top: 24px">
            <div class="tags_wrapper" style="
                display: flex;
                justify-content: start;
                align-items: center;
                flex-wrap: wrap;
                gap: 16px;
                padding: 16px;
            ">
                <a :href="`/search/${tag.name}`" style="
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 8px;
                color: white;
                background: #1a3467;
                padding: 4px 8px;
                border-radius: 5px;" class="tag" v-for="tag in term_data.tags" :key="tag.id">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-tag" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="#fff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M7.5 7.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                        <path d="M3 6v5.172a2 2 0 0 0 .586 1.414l7.71 7.71a2.41 2.41 0 0 0 3.408 0l5.592 -5.592a2.41 2.41 0 0 0 0 -3.408l-7.71 -7.71a2 2 0 0 0 -1.414 -.586h-5.172a3 3 0 0 0 -3 3z" />
                    </svg>
                    @{{tag.name}}
                </a>
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
        term_id: `{{ request()->id }}`,
        term_data: null,
        user: null,
        languages_data: null,
        current_lang: "EN",
        page_translations: null,
        page_content: this.page_translations ? this.page_translations[this.current_lang] : '',
        all_categories: null,
        search: null,
        searchArticles: [],
        showProfileMore: false,
        page_translations: null,
        page_content: this.page_translations ? this.page_translations[this.current_lang] : '',

    }
},
methods: {
    async handleFav(term_id){
        try {
            const response = await axios.post( `/fav-add-delete`, {
                term_id: term_id
            },
            );
            $('.loader').fadeOut()
            if (response.data.status === true) {
                this.getTerm(this.term_id, this.current_lang)
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
    async getTerm(id, lang){
        $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post( `/term`, {
                id: id,
                lang: lang
            },
            );
            $('.loader').fadeOut()
            if (response.data.status === true) {
                document.getElementById('errors').innerHTML = ''
                this.term_data = response.data.data
                document.title = 'Moheb | Term | ' + this.term_data.title;
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
            const response = await axios.post( `/search`, {
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
},
created() {
    this.getLang().then(() => {
        this.getTerm(this.term_id, this.current_lang)
        this.getAllCategories(this.current_lang)
        if (this.current_lang.includes("AR")) {
            document.body.classList = 'AR'
        }
    })
    this.getUser()
    this.getLanguages()
},
}).mount('#term')
</script>
@endSection
