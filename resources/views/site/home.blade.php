@extends('site.layouts.main')

@section('title', 'Home')

@section('content')
<div id="home">
    @include('site.includes.header')
    <section class="football" v-if="football">
        <div class="container">
            <div class="head">
                <h1><i class="fa-solid fa-futbol"></i> @{{ page_content ? page_content.sections.football : "Football" }}</h1>
                <a :href="`/category/${football.id}`" class="view-more"> @{{ page_content ? page_content.sections.view_all : "View All" }}</a>
            </div>
            <div class="sub_categories" v-if="football.terms && football.terms.length">
                <a class="card" :href="`/term/${term.name.toLowerCase().replace(/\s+/g, '-').replace(/\s+|\/+/g, '-')}/${term.id}`" v-for="term in football.terms" :key="term.id">
                    <img :src="term.thumbnail_path" alt="">
                    <h1>@{{ term.names.length > 0 ? term.names[0].term : term.name }}</h1>
                </a>
            </div>
        </div>
    </section>
    <section class="most-popular"  v-if="categories && categories.length > 0">
        <div class="container">
            <div class="head">
                <h1><i class="fa-solid fa-ranking-star"></i> @{{ page_content ? page_content.sections.most_popular : "Most Popular Sports" }}</h1>
                {{-- <a href="" class="view-more">@{{ page_content ? page_content.sections.view_all : "View All" }}</a> --}}
            </div>
            <div class="sub_categories">
                <a :href="`/category/${cat.id}`" class="card" v-for="cat in categories" :key="cat.id">
                    <img :src="cat.thumbnail_path" alt="">
                    <h1>@{{ cat.name }}</h1>
                </a>
            </div>
        </div>
    </section>
    <section class="latest-terms"  v-if="terms && terms.length > 0">
        <div class="container">
            <div class="terms-wrapper">
                <div class="head">
                    <h1><i class="fa-solid fa-list-ul"></i> @{{ page_content ? page_content.sections.last_difinations : "Last Updated Difinations" }}</h1>
                </div>
                <div class="terms">
                    <div class="term" v-for="(term, index) in terms" :key="index">
                        <a :href="`/term/${term.name.toLowerCase().replace(/\s+/g, '-').replace(/\s+|\/+/g, '-')}/${term.id}`" target="_blanck" >
                            <h2>@{{ term.name }}</h2>
                            <h4>@{{ term.category_name }}</h4>
                        </a>
                        <i class="fa-regular fa-heart" :class="term.isFav ? 'active' : ''" @click="handleFav(term.id)"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="latest-articles" v-if="articles && articles.length > 0">
        <div class="container">
            <div class="head">
                <h1><i class="fa-regular fa-newspaper"></i> @{{ page_content ? page_content.sections.last_news : "Latest News" }}</h1>
                <a href="" class="view-more">@{{ page_content ? page_content.sections.view_all : "View All" }}</a>
            </div>
            <div class="articles">
                <a :href="`/article/${article.id}`" class="article" v-for="(article, index) in articles" :key="index">
                    <div class="thumbnail">
                        <img :src="article.thumbnail_path">
                    </div>
                    <div class="text">
                        <h1 class="title">
                            @{{article.title}}
                        </h1>
                        <p v-html="article.content.length >= 70 ? article.content.slice(0, 70) + ' ...' : article.content">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequuntur nisi quidem dolore. Necessitatibus aliquam laudantium adipisci, nobis hic mollitia vero delectus illum quasi quo.
                        </p>
                        <div class="foot">
                            <div class="time"><i class="fa-regular fa-clock"></i> @{{ new Date(article.created_at).toLocaleString("en-US", {
                                                                                    month: "long",
                                                                                    day: "numeric",
                                                                                    year: "numeric"
                                                                                    }) }}</div>
                            <div class="author">
                                <i class="fa-regular fa-user"></i> <span>Admin</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>
    @include('site.includes.footer')
</div>
@endsection
@section('scripts')
<script>
const { createApp, ref } = Vue

createApp({
data() {
    return {
        user: null,
        languages_data: null,
        current_lang: "EN",
        terms: null,
        categories: null,
        articles: null,
        all_categories: null,
        football: null,
        page_translations: null,
        page_content: this.page_translations ? this.page_translations[this.current_lang] : '',
        showProfileMore: false,
        search: null,
        searchArticles: [],
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
        this.getFootball(this.current_lang)
        this.getLatestTerms(this.current_lang)
        this.getLatestArticles(this.current_lang)
        this.getLatestCategories(this.current_lang)
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
    async getLatestTerms(lang){
        $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post( `/latest-terms`, {
                lang: lang
            },
            );
            $('.loader').fadeOut()
            if (response.data.status === true) {
                document.getElementById('errors').innerHTML = ''
                this.terms = response.data.data
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
    async getFootball(lang){
        $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post( `/get_football_cat`, {
                lang: lang
            },
            );
            $('.loader').fadeOut()
            if (response.data.status === true) {
                document.getElementById('errors').innerHTML = ''
                this.football = response.data.data
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
    async handleFav(term_id){
        try {
            const response = await axios.post( `/fav-add-delete`, {
                term_id: term_id
            },
            );
            $('.loader').fadeOut()
            if (response.data.status === true) {
                this.getLatestTerms(this.current_lang)
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
    async getLatestCategories(lang){
        $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post( `/latest-categories`, {
                lang: lang
            },
            );
            $('.loader').fadeOut()
            if (response.data.status === true) {
                document.getElementById('errors').innerHTML = ''
                this.categories = response.data.data
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
    async getLatestArticles(lang){
        $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post( `/latest-articles`, {
                lang: lang
            },
            );
            $('.loader').fadeOut()
            if (response.data.status === true) {
                document.getElementById('errors').innerHTML = ''
                this.articles = response.data.data
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
    async handleSearch(lang){
        try {
            const response = await axios.post( `/search`, {
                lang: this.current_lang,
                page: this.currentPage,
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
        this.getFootball(this.current_lang)
        this.getLatestTerms(this.current_lang)
        this.getLatestArticles(this.current_lang)
        this.getLatestCategories(this.current_lang)
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
@endSection
