@extends('site.layouts.main')

@section('title', 'Home')

@section('content')
<div id="home">
    @include('site.includes.header')
    <section class="football" v-if="football" style="min-height: 80vh">
        <div class="container">
            <div class="head" style="margin-bottom: 4px">
                <h1><i class="fa-solid fa-category"></i> @{{ football.name }}</h1>
            </div>
            <p style="padding: 4px 8px;margin-bottom: 16px;font-weight: 700"> @{{ football.description }}</p>
            <div class="sub_categories" v-if="terms && terms.length">
                <a class="card" :href="`/term/${getSlug(term.name)}/${term.id}`" v-for="term in terms" :key="term.id">
                    <img :src="term.thumbnail_path" alt="">
                    <h1>@{{ term.names.length > 0 ? term.names[0].term : term.name }}</h1>
                </a>
            </div>
            <div class="pagination">
                <button class="prev" @click="currentPage > 1 ? currentPage -= 1 : ''; getCategory()" :disabled="currentPage === 1">Previous</button>
                    <div v-for="page_num in visiblePages" :key="page_num">
                        <label :for="`page_num_${page_num}`" class="btn btn-primary" :class="page_num == currentPage ? 'active' : ''">@{{ page_num }}</label>
                        <input type="radio" style="display: none" class="d-none" name="page_num" :id="`page_num_${page_num}`" v-model="page" :value="page_num" @change="currentPage = page_num; getCategory()">
                      </div>
                <button class="next" @click="currentPage < lastPage ? currentPage += 1 : ''; getCategory()" :disabled="currentPage === lastPage">Next</button>
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
        showSearch: false,
        current_lang: "EN",
        terms: null,
        all_categories: null,
        categories: null,
        articles: null,
        football: null,
        search: null,
        page_translations: null,
        page_content: this.page_translations ? this.page_translations[this.current_lang] : '',
        showProfileMore: false,
        searchArticles: [],
        currentPage: 1,
        lastPage: null, // This value should be set based on your actual last page
        maxVisiblePages: 5 // Set the maximum number of visible pages
    }
},
computed: {
    visiblePages() {
      const range = 8;
      let start = Math.max(this.currentPage - Math.floor(range / 2), 1);
      let end = start + range - 1;

      if (end > this.lastPage) {
        end = this.lastPage;
        start = Math.max(end - range + 1, 1);
      }

      const pages = [];
      for (let i = start; i <= end; i++) {
        pages.push(i);
      }

      return pages;
    }
  },
methods: {
    getSlug(term) {
            return term.replace(/\s+|\/+|["']/g, '-').replace(/-{2,}/g, '-')
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
        this.getCategory(this.current_lang)
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

},
created() {
    this.getLang().then(() => {
        this.getCategory(this.current_lang)
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
