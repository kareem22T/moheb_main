@extends('admin.layouts.admin-layout')

@section('title', 'Edit Term')

@section('content')
<h3 class="mb-5">
    Edit Term
</h3>
<style>
    .toolbar button {
        font-size: 22px;
        font-weight: bold
    }
</style>
<div class="card" id="add_cat">
     @if($term)
    <div class="card-body">
        <div>
            <div class="w-100 mb-3">
                <label for="symbol" class="form-label">Term Name in English (for database only) *</label>
                <input type="text" class="form-control w-50" id="symbol" v-model="main_name">
                <input type="hidden" name="term_id" id="term_id" value="{{ $term->id }}">
            </div>
            <!-- Swiper -->
            <div class="w-100 mb-3 pb-3 gap-2"  style="display: grid; grid-template-columns: 1fr 1fr;">
                <div class="swiper-slide" v-for="(language, index) in languages_data" :key="index">
                    <div>
                        <label for="lang_name" class="form-label">Tearm in @{{language.name}} *</label>
                        <input type="text" class="form-control" id="lang_name" v-model="term_translations[language.symbol]">
                    </div>
                </div>
            </div>
            <!-- Swiper -->
            <div class="w-100 mb-3 pb-3 gap-2"  style="display: grid; grid-template-columns: 1fr;">
                <div class="swiper-slide" v-for="(language, index) in languages_data.slice(0, 7)" :key="index">
                    <div>
                        <label for="lang_name" class="form-label">Title in @{{language.name}} *</label>
                        <input type="text" class="form-control" id="lang_name" v-model="title_translations[language.symbol]">
                    </div>
                </div>
            </div>

            <!-- Swiper -->
            <div class="w-100 mb-4 pb-3">
                <div class="w-100 p-3" v-for="(language, index) in languages_data.slice(0, 7)" :key="index">
                    <div>
                        <label for="lang_name" class="form-label">Content in @{{language.name}} *</label>
                        <div class="card">
                            <div class="card-header">
                                <div class="toolbar d-flex gap-2 justify-content-center">
                                    <button @click="execCommand('bold')" class="btn btn-success"><b>B</b></button>
                                    <button @click="execCommand('italic')" class="btn btn-success"><i>I</i></button>
                                    <button @click="execCommand('underline')" class="btn btn-success"><u>U</u></button>
                                    <button @click="execCommand('insertOrderedList')" class="btn btn-success"><i class="ti ti-list-numbers"></i></button>
                                    <button @click="execCommand('insertUnorderedList')" class="btn btn-success"><i class="ti ti-list"></i></button>
                                    <button @click="execCommand('justifyLeft')" class="btn btn-success"><i class="ti ti-align-left"></i></button>
                                    <button @click="execCommand('justifyCenter')" class="btn btn-success"><i class="ti ti-align-center"></i></button>
                                    <button @click="execCommand('justifyRight')" class="btn btn-success"><i class="ti ti-align-right"></i></button>
                                    <button @click="insertHTML('<h2>Heading</h2>', 'article-content-' + language.symbol)" class="btn btn-success"><i class="ti ti-h-2"></i></button>
                                    <button @click="insertHTML('<h3>Heading</h3>', 'article-content-' + language.symbol)" class="btn btn-success"><i class="ti ti-h-3"></i></button>
                                    <button @click="insertHTML('<h4>Heading</h4>', 'article-content-' + language.symbol)" class="btn btn-success"><i class="ti ti-h-4"></i></button>
                                    <button @click="insertHTML('<h5>Heading</h5>', 'article-content-' + language.symbol)" class="btn btn-success"><i class="ti ti-h-5"></i></button>
                                    <button @click="insertHTML('<h6>Heading</h6>', 'article-content-' + language.symbol)" class="btn btn-success"><i class="ti ti-h-6"></i></button>
                                    <button @click="insertHTML('<p>Paragraph</p>', 'article-content-' + language.symbol)" class="btn btn-success">P</button>
                                    <button class="btn btn-success" @click="this.showImages = true; this.current_article_id = 'article-content-' + language.symbol"><i class="ti ti-photo-plus"></i></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div contenteditable="true" :id="'article-content-' + language.symbol" class="form-control" style="height: 350px; overflow: auto;" @changes="contentChanges(language.symbol)" v-html="content_translations[language.symbol]"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hide-content" v-if="showImages"></div>
            <div class="pop-up show-images-pop-up card" v-if="showImages" style="z-index: 2147483647; min-width: 90vw; height: 90vh; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; gap: 1rem;max-width: 100vw;">
                <input type="text" name="search" id="search" class="form-control w-25 mb-2" placeholder="Search" v-model="search" @input="getSearchImages(this.search)">
                <div class="imgs p-2 gap-3" v-if="images && images.length" style="display: flex;grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));flex-wrap: wrap;height: 100%;overflow: auto;">
                    <div class="img" @click="this.choosed_img = '/dashboard/images/uploads/' + img.path" v-for="(img, index) in images" :key="img.id" style="width: 260px;height: 230px;overflow: hidden;padding: 10px;border-radius: 1rem;box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
                        <img :src="'/dashboard/images/uploads/' + img.path" id="preview" alt="img logo" style="width: 100%;height: 100%;object-fit: contain;">
                    </div>
                </div>
                <div class="pagination w-100 d-flex gap-2 justify-content-center mt-3" v-if="last_page > 1">
                    <button class="btn btn-primary" :disabled="page === 1" @click="goToFirstPage">First</button>
                    <button class="btn btn-primary" :disabled="page === 1" @click="goToPreviousPage">Previous</button>

                    <div v-for="page_num in visiblePages" :key="page_num">
                      <label :for="`page_num_${page_num}`" class="btn btn-primary" :class="page_num === page ? 'active' : ''">@{{ page_num }}</label>
                      <input type="radio" class="d-none" name="page_num" :id="`page_num_${page_num}`" v-model="page" :value="page_num" @change="pageChanged">
                    </div>

                    <button class="btn btn-primary" :disabled="page === last_page" @click="goToNextPage">Next</button>
                    <button class="btn btn-primary" :disabled="page === last_page" @click="goToLastPage">Last</button>
                </div>

                <h1 v-if="images && !images.length && !search">There is not any image yet! (upload now)</h1>
                <div class="foot" style="display: flex;width: 100%;justify-content: space-between;gap: 1rem;">
                    <button class="btn btn-primary" @click="this.showUploadPopUp = true">Upload Image</button>
                    <div class="hide-content" v-if="showUploadPopUp"></div>
                    <div class="pop-up card p-3" v-if="showUploadPopUp">
                        <label for="image" class="mb-2">Choose Image File</label>
                        <input type="file" name="image" id="image" class="form-control mb-4" @change="imageChanges">
                        <div class="d-flex gap-2 w-100 justify-content-center">
                            <button class="btn btn-light"  @click="this.showUploadPopUp = false">Cancel</button>
                            <button class="btn btn-secondary" @click="uploadImage(image)">
                                Add Image
                            </button>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-light"   @click="this.showImages = false; this.search = null; getSearch()">Cancel</button>
                        <button class="btn btn-success"  @click="insertImgToArticle()">Choose</button>
                    </div>
                </div>
            </div>

            <div class="mb-3 w-100 d-flex gap-3">
                <div class="w-25">
                    <div  @click="this.showImages = true; this.current_article_id = null" class="w-100 h-100 p-3 d-flex justify-content-center align-items-center form-control" style="max-height: 170px;">
                        <img :src="preview_img ? preview_img :(thumbnail_path ? thumbnail_path : '/dashboard/images/add_image.svg')" id="preview" alt="img logo" style="width: 100%; max-width: 100%;object-fit: contain;height: 100%;">
                    </div>
                </div>
                <div class="w-75">
                    <div class="w-100 mb-3 d-flex gap-3">
                        <div class="w-100" v-if="categories_data">
                            <label for="symbol" class="form-label">Category *</label>
                            <select name="cat_type" id="cat_type" class="form-control" v-model="cat_id" @change="prevSubCat()">
                                <option v-for="(category, index) in categories_data" :key="index" :value="category.id" v-if="categories_data.length > 0">
                                    @{{category.main_name}}
                                </option>
                            </select>
                        </div>

                        <div class="w-100" v-if="show_sub_categories">
                            <label for="symbol" class="form-label">Sub Category</label>
                            <select name="cat_type" id="cat_type" class="form-control" v-model="cat_id" @change="prevSubCat()">
                                <option v-for="(category, index) in sub_categories_data" :key="index" :value="category.id" v-if="categories_data.length > 0">
                                    @{{category.main_name}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <!-- Swiper -->
                    <div class="mb-3 pb-5">
                        <div>
                            <div  v-for="(language, index) in languages_data.slice(0, 7)" :key="index">
                                <div>
                                    <label for="sound_iframe" class="form-label">Sound in @{{language.name}} (iframe)</label>
                                    <input type="file" class="form-control" id="sound_iframe" @change="handleFileChange($event, language.symbol)">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 w-100">
                <div class="w-100 d-flex gap-2 mb-3" style="position: relative">
                    <input v-model="tagInput" @keydown.enter="addTag" placeholder="Enter a tag..." class="form-control" @input="getTagSearch(tagInput)">
                    <button class="btn btn-secondary w-25" @click="addTag">Add Tag</button>
                    <div class="suggestions card p-2 w-100" style="position: absolute; top: calc(100% + 10px);
                    lef: 0; max-height: 132px; overflow: auto;" v-if="search_tags && search_tags.length > 0">
                        <div class="p-1 btn btn-light mb-1" style="text-align: left;padding: .3rem 1rem !important" v-for="tag in search_tags" :key="tag.id"  @click="this.tagInput = tag.name; addTag(); this.search_tags = []" > @{{tag.name}} </div>
                    </div>
                </div>
                <ul class="d-flex gap-2" style="flex-wrap: wrap">
                    <li v-for="(tag, index) in tags" :key="index" class="btn btn-light">
                        @{{ tag }}
                        <button @click="removeTag(index)" class="ti ti-x" style="background: transparent; border: none; cursor: pointer;"></button>
                    </li>
                </ul>
            </div>
            <div  class="d-flex justify-content-between gap-4 align-items-end flex-wrap-wrap">
                <div class="w-50">
                </div>
                <button type="submit" class="btn btn-primary w-50 form-control" style="height: fit-content" @click="getContentTranslations().then(() => { save(main_name, term_translations, title_translations, content_translations, preview_img, sounds_translations, cat_id, tags)})">Save</button>
            </div>
        </div>
    </div>
    @else
    @php
        return redirect('/admin/words/preview');
    @endphp
    @endif
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('/libs/swiper.css') }}">
<style>
    .swiper-button-next, .swiper-button-prev {
        width: fit-content !important;
        height: fit-content !important;
        padding: 4px !important;
        display: flex !important;
        bottom: 0 !important;
        top: auto;
        z-index: 9999;
    }
    .swiper-pagination {
        bottom: 0
    }
    .swiper-button-next::after, .swiper-button-prev::after {
        content: ""
    }
</style>
@endsection

@section('scripts')
<!-- Swiper JS -->
<script src="{{ asset('/libs/swiper.js') }}"></script>

<!-- Initialize Swiper -->
<script>
window.onload = function() {
    var swiper = new Swiper(".mySwiper", {
      pagination: {
        el: ".swiper-pagination",
        type: "fraction",
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });
};
</script>

<script>
const { createApp, ref } = Vue;

createApp({
  data() {
    return {
      main_name: null,
      cat_id: null,
      languages_data: [],
      categories_data: null,
      sub_categories_data: null,
      thumbnail: null,
      thumbnail_path: null,
      term_translations: {},
      title_translations: {},
      content_translations: {},
      sounds_translations: {},
      show_sub_categories: false,
      term_data: null,
      tagInput: '',
      tags: [],
      search_tags: null,
      images: null,
      showImages: false,
      showUploadPopUp: false,
      image: null,
      choosed_img: null,
      current_article_id: null,
      search_tags: null,
      preview_img: null,
      page: 1,
      total: 0,
      last_page: 0,
    }
  },
  computed: {
    visiblePages() {
      const range = 8;
      let start = Math.max(this.page - Math.floor(range / 2), 1);
      let end = start + range - 1;

      if (end > this.last_page) {
        end = this.last_page;
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
    handleFileChange(event, key) {
            const files = event.target.files;
            if (files.length > 0) {
                // For simplicity, assuming a single file upload per language
                console.log(files[0]);
                this.sounds_translations[key] = files[0]
                console.log(this.sounds_translations);
            }
    },
    pageChanged() {
      if (!this.search) {
        this.getImages();
      } else {
        this.getSearchImages(this.search);
      }
    },
    goToFirstPage() {
      this.page = 1;
      this.pageChanged();
    },
    goToPreviousPage() {
      if (this.page > 1) {
        this.page -= 1;
        this.pageChanged();
      }
    },
    goToNextPage() {
      if (this.page < this.last_page) {
        this.page += 1;
        this.pageChanged();
      }
    },
    goToLastPage() {
      this.page = this.last_page;
      this.pageChanged();
    },
    async save(main_name, term_translations, title_translations, content_translations, thumbnail, sounds_translations, cat_id, tags) {
      $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`/admin/words/edit`, {
                main_name: main_name,
                term_translations: term_translations,
                title_translations: title_translations,
                content_translations: content_translations,
                thumbnail: thumbnail,
                sounds_translations: sounds_translations,
                cat_id: cat_id,
                term_id: this.term_id,
                tags: tags
            },
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
            );
            if (response.data.status === true) {
            document.getElementById('errors').innerHTML = ''
            let error = document.createElement('div')
            error.classList = 'success'
            error.innerHTML = response.data.message
            document.getElementById('errors').append(error)
            $('#errors').fadeIn('slow')
            $('.loader').fadeOut()
            setTimeout(() => {
                $('#errors').fadeOut('slow')
                window.location.href = '/admin/words'
            }, 2000);
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

            setTimeout(() => {
            $('#errors').fadeOut('slow')
            }, 3500);

            console.error(error);
        }
    },
    async getCategories() {
        $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`/admin/categories/main`, {
                cat: 'cat'
            },
            );
            if (response.data.status === true) {
                $('.loader').fadeOut()
                this.categories_data = response.data.data
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
    addTag() {
      if (this.tagInput.trim() !== '') {
        this.tags.push(this.tagInput.trim());
        this.tagInput = '';
      }
    },
    removeTag(index) {
      this.tags.splice(index, 1);
    },
    async getTagSearch(search_words) {
        try {
            const response = await axios.post(`/admin/tags/search`, {
                search_words: search_words,
            },
            );
            if (response.data.status === true) {
                if (search_words != '')
                    this.search_tags = response.data.data.data
                else
                    this.search_tags = []
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
            this.Tags_data = false
            setTimeout(() => {
                $('#errors').fadeOut('slow')
            }, 3500);

            console.error(error);
        }
    },
    async getTerm() {
        $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`/admin/word`, {
                term_id: this.term_id
            },
            );
            if (response.data.status === true) {
                $('.loader').fadeOut()
                this.term_data = response.data.data
                this.main_name = this.term_data.name
                this.thumbnail_path = this.term_data.thumbnail_path
                this.cat_id = this.term_data.category.id

                for (let index = 0; index < this.term_data.tags.length; index++) {
                    const element = this.term_data.tags[index];
                    this.tags.push(element.name)
                }
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
    async getNameTranslations() {
        $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`/admin/word/names`, {
                term_id: this.term_id
            },
            );
            if (response.data.status === true) {
                $('.loader').fadeOut()
                this.term_translations = response.data.data
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
    async getTermTitles() {
        $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`/admin/word/titles`, {
                term_id: this.term_id
            },
            );
            if (response.data.status === true) {
                $('.loader').fadeOut()
                this.title_translations = response.data.data
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
    async getTermContent() {
        $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`/admin/word/contents`, {
                term_id: this.term_id
            },
            );
            if (response.data.status === true) {
                $('.loader').fadeOut()
                this.content_translations = response.data.data
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
    async getLanguages() {
        $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`/admin/words/get-languages`, {
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
    async getSubCategories() {
        $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`/admin/categories/sub`, {
                cat_id: this.cat_id
            },
            );
            if (response.data.status === true) {
                $('.loader').fadeOut()
                this.sub_categories_data = response.data.data
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
    pushCatTranslation(id, name) {
        this.category_translations.push({
            lang_id: id,
            name: name
        })
    },
    prevSubCat() {
        this.getSubCategories().then(() => {
            if (this.sub_categories_data.length) {
                this.show_sub_categories = true
            }
        })
    },
    execCommand(command) {
        document.execCommand(command, false, null);
    },
    insertHTML(html, element, key) {
        document.getElementById(element).focus();
        document.execCommand('insertHTML', false, html);
    },
    photoChanges(event) {
        this.thumbnail = event.target.files[0];
    },
    async getContentTranslations () {
        console.log(this.languages_data);
        await this.languages_data.forEach((language, index) => {
            if (document.getElementById('article-content-' + language.symbol) && document.getElementById('article-content-' + language.symbol).innerHTML != '')
                this.content_translations[language.symbol] = document.getElementById('article-content-' + language.symbol).innerHTML;
        })
    },
    previewThumbnail () {
        this.preview_img = this.choosed_img
        this.showImages = false
    },
    async getImages() {
        $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.get(`/admin/images/get_images?page=${this.page}`
            );
            if (response.data.status === true) {
                $('.loader').fadeOut()
                this.images = response.data.data.data
                this.total = response.data.data.total
                this.last_page = response.data.data.last_page
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
    async getSearchImages(search_words) {
        try {
            const response = await axios.post(`/admin/images/search?page=${this.page}`, {
                search_words: search_words,
            },
            );
            if (response.data.status === true) {
                this.images = response.data.data.data
                this.total = response.data.data.total
                this.last_page = response.data.data.last_page
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
    async uploadImage(image) {
        $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`/admin/images/upload`, {
                img: image,
            },
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }

            );
            if (response.data.status === true) {
                document.getElementById('errors').innerHTML = ''
                let error = document.createElement('div')
                error.classList = 'success'
                error.innerHTML = response.data.message
                document.getElementById('errors').append(error)
                $('#errors').fadeIn('slow')
                $('.loader').fadeOut()
                this.showUploadPopUp = false;
                this.getImages()
                setTimeout(() => {
                    $('#errors').fadeOut('slow')
                }, 3000);
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
    imageChanges(event) {
        this.image = event.target.files[0];
                // check if file is valid image
        var file = event.target.files[0];
        var fileType = file.type;
        var validImageTypes = ["image/gif", "image/jpeg", "image/jpg", "image/png"];
        if ($.inArray(fileType, validImageTypes) < 0) {
            document.getElementById("errors").innerHTML = "";
            let error = document.createElement("div");
            error.classList = "error";
            error.innerHTML =
                "Invalid file type. Please choose a GIF, JPEG, or PNG image.";
            document.getElementById("errors").append(error);
            $("#errors").fadeIn("slow");
            setTimeout(() => {
                $("#errors").fadeOut("slow");
            }, 2000);

            $(this).val(null);
        } else {
            // display image preview
            var reader = new FileReader();
            reader.onload = function (e) {
            };
            reader.readAsDataURL(file);
        }

    },
    chooseImage(imagePath) {
        this.choosed_img = '/dashboard/images/uploads/' + imagePath;
    },
    insertImgToArticle () {
        if (this.current_article_id) {
            if (this.choosed_img) {
                this.insertHTML('<img src="' + this.choosed_img + '" />', this.current_article_id)
                this.chooseImage = null
                this.current_article_id = null
                this.showImages = null
            }else {
                document.getElementById('errors').innerHTML = ''
                let err = document.createElement('div')
                err.classList = 'error'
                err.innerHTML = 'Please Choose an image or uploade one'
                document.getElementById('errors').append(err)
                $('#errors').fadeIn('slow')
                $('.loader').fadeOut()
                setTimeout(() => {
                    $('#errors').fadeOut('slow')
                }, 3500);

            }
        } else {
            this.previewThumbnail()
        }

    }

  },
  created() {
    this.getLanguages()
    this.getCategories()
    this.getImages()
  },
  mounted() {
    this.term_id = document.getElementById('term_id').value ? document.getElementById('term_id').value : undefined;
    this.getTerm()
    this.getNameTranslations()
    this.getTermTitles()
    this.getTermContent()
    this.getTermSounds()
    $("#thumbnail").change(function () {
        // check if file is valid image
        var file = this.files[0];
        var fileType = file.type;
        var validImageTypes = ["image/gif", "image/jpeg", "image/jpg", "image/png"];
        if ($.inArray(fileType, validImageTypes) < 0) {
            document.getElementById("errors").innerHTML = "";
            let error = document.createElement("div");
            error.classList = "error";
            error.innerHTML =
                "Invalid file type. Please choose a GIF, JPEG, or PNG image.";
            document.getElementById("errors").append(error);
            $("#errors").fadeIn("slow");
            setTimeout(() => {
                $("#errors").fadeOut("slow");
            }, 2000);

            $(this).val(null);
            $("#preview").attr(
                "src",
                this.thumbnail_path ? '/dashboard/images/uploads/terms_thumbnail/' + this.thumbnail_path : "/dashboard/images/add_image.svg"
            );
            $(".photo_group i").removeClass("fa-edit").addClass("fa-plus");
        } else {
            // display image preview
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#preview").attr("src", e.target.result);
                $(".photo_group  i")
                    .removeClass("fa-plus")
                    .addClass("fa-edit");
                $(".photo_group label >i").fadeOut("fast");
            };
            reader.readAsDataURL(file);
        }
    });
  },
}).mount('#add_cat')
$(document).on('click', '.imgs .img', function () {
    $(this).css('border', '1px solid #13DEB9')
    $(this).siblings().css('border', 'none')
})
</script>
@endsection
