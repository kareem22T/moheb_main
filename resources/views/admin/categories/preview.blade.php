@extends('admin.layouts.admin-layout')

@section('title', 'Catigories')

@section('categories_preview_active', 'active')

@section('content')
<h3 class="mb-5">
    Categories (Sports)
</h3>
<div class="card w-100" id="lang_prev">
    <div class="card-header d-flex justify-content-between gap-3">
        <input type="text" name="search" id="search" class="form-control w-25" placeholder="Search" v-model="search" @input="getSearch(this.search)">
        <a href="/admin/categories/add" class="btn btn-primary w-fit d-flex gap-2 align-items-center">
            <i class="ti ti-plus"></i> Add Category
        </a>
    </div>
    <div class="card-body p-4">
    <div class="table-responsive" v-if="categories_data && categories_data.length > 0">
        <table class="table text-nowrap mb-0 align-middle">
        <thead class="text-dark fs-4">
            <tr>
                <th class="border-bottom-0">
                    <h6 class="fw-semibold mb-0 d-inline-flex align-items-center">Id</h6>
                    {{-- <a href="" class="ml-2 sort text-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-sort" width="1rem" height="1rem" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M3 9l4 -4l4 4m-4 -4v14"></path>
                            <path d="M21 15l-4 4l-4 -4m4 4v-14"></path>
                        </svg>
                    </a> --}}
                </th>
                <th class="border-bottom-0">
                    <h6 class="fw-semibold mb-0 d-inline-flex align-items-center">Name</h6>
                </th>
                <th class="border-bottom-0">
                    <h6 class="fw-semibold mb-0 d-inline-flex align-items-center">Description</h6>
                </th>
                <th class="border-bottom-0">
                    <h6 class="fw-semibold mb-0 d-inline-flex align-items-center">Sub Categories</h6>
                </th>
                <th class="border-bottom-0">
                    <h6 class="fw-semibold mb-0 d-inline-flex align-items-center">Controls</h6>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(category, index) in categories_data" :key="index">
                <td class="border-bottom-0"><h6 class="fw-semibold mb-0">@{{category.id}}</h6></td>
                <td class="border-bottom-0"><h6 class="fw-semibold mb-0">@{{category.main_name}}</h6></td>
                <td class="border-bottom-0">
                    <p class="mb-0 fw-normal">@{{category.description ? (category.description.length > 45 ? category.description.substring(0, 45) + "..." : category.description) : 'Empty'}}</p>
                </td>
                <td class="border-bottom-0">
                    <p class="mb-0 fw-normal">@{{category.sub_categories.length > 0 ? category.sub_categories[0].main_name + (category.sub_categories[1] ? ', ' + category.sub_categories[1].main_name : '') + (category.sub_categories[2] ? ', ...' : '') : 'Doesn\'t has Sub Categories'}}</p>
                </td>
                <td class="border-bottom-0">
                    <div class="d-flex gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-menu-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" :stroke="category.is_in_nav ? '#13DEB9' : '#FA896B'" fill="none" stroke-linecap="round" stroke-linejoin="round"  style="cursor: pointer;height: 40px;object-fit: contain;object-position: center;margin-right: 8px;" @click="makeAtNav(category.id)">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M4 6l16 0" />
                            <path d="M4 12l16 0" />
                            <path d="M4 18l16 0" />
                          </svg>
                        <svg v-if="!category.isTop" @click='makeTop(category.id)' xmlns="http://www.w3.org/2000/svg" style="cursor: pointer;height: 40px;object-fit: contain;object-position: center;margin-right: 8px;" class="icon icon-tabler icon-tabler-star" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#9e9e9e" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                        </svg>
                        <svg v-if="category.isTop" xmlns="http://www.w3.org/2000/svg" @click='makeTop(category.id)' style="cursor: pointer;height: 40px;object-fit: contain;object-position: center;margin-right: 8px;" class="icon icon-tabler icon-tabler-star-filled" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffec00" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z" stroke-width="0" fill="currentColor" />
                          </svg>
                        <a :href="`/admin/category/${category.id}`" class="btn btn-success p-2 edit_lang_btn"><h4 class="ti ti-eye text-light m-0 fw-semibold"></h4></a>
                        <a :href="`/admin/categories/edit/${category.id}`" class="btn btn-secondary p-2"><h4 class="ti ti-edit text-light m-0 fw-semibold"></h4></a>
                        <button class="btn btn-danger p-2" @click="this.delete_pop_up = true; getValues(category.id, category.main_name)"><h4 class="ti ti-trash text-light m-0 fw-semibold"></h4></button>
                    </div>
                </td>
            </tr>
        </tbody>
        </table>
    </div>
    <div class="pagination w-100 d-flex gap-2 justify-content-center mt-3" v-if="last_page > 1">
        <div v-for="page_num in last_page" :key="page_num" >
            <label :for="`page_num_${page_num}`" class="btn btn-primary" :class="page_num == page ? 'active' : ''">@{{ page_num }}</label>
            <input type="radio" class="d-none" name="page_num" :id="`page_num_${page_num}`" v-model="page" :value="page_num" @change="search == '' ? getCategories() : getSearch(this.search)">
        </div>
    </div>
    <h4 class="text-center">
        @{{ categories_data && categories_data.length == 0 ? 'There is no any Category' : '' }}
    </h4>
    <h4 class="text-center">
        @{{ categories_data === false ? 'Server error try again later' : '' }}
    </h4>
    </div>

    <div class="hide-content" v-if="delete_pop_up"></div>
    <div class="pop-up delete_pop_up card w-50" style="margin: auto; display: none;"  :class="{ 'show': delete_pop_up }" v-if="delete_pop_up">
        <div class="card-body">
            <form @submit.prevent>
                <h5 class="mb-3 text-center">
                    Are you sure you want to delete @{{ cat_name }} category?
                </h5>
                <h6 class="mb-3 text-center">
                    Note: All terms that under this category or its sub categories will be deleted
                </h6>
                <div class="btns d-flex w-100 justify-content-between gap-3">
                    <button class="btn btn-light w-100" @click="delete_pop_up = false; getValus(null, null, null)">Cancel</button>
                    <button class="btn btn-danger w-100" @click="deletCat(cat_id)">delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const { createApp, ref } = Vue

createApp({
    data() {
        return {
            cat_id: null,
            cat_name: null,
            delete_pop_up: false,
            categories_data: null,
            search: null,
            page: 1,
            total: 0,
            last_page: 0,
        }
    },
    methods: {
        async update(lang_id, lang_symbol, lang_name) {
            $('.loader').fadeIn().css('display', 'flex')
            try {
                const response = await axios.post(`/admin/languages/edit`, {
                    lang_id: lang_id,
                    lang_symbol: lang_symbol,
                    lang_name: lang_name,
                },
                );
                if (response.data.status === true) {
                    document.getElementById('errors').innerHTML = ''
                    let error = document.createElement('div')
                    error.classList = 'success'
                    error.innerHTML = response.data.message
                    document.getElementById('errors').append(error)
                    $('#errors').fadeIn('slow')
                    this.edit_pop_up = false
                    setTimeout(() => {
                        $('.loader').fadeOut()
                        $('#errors').fadeOut('slow')
                        location.reload();
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
        async deletCat(cat_id) {
            $('.loader').fadeIn().css('display', 'flex')
            try {
                const response = await axios.post(`/admin/categories/delete`, {
                    cat_id: cat_id,
                },
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
                        location.reload();
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
                const response = await axios.post(`/admin/categories?page=${this.page}`, {
                },
                );
                if (response.data.status === true) {
                    $('.loader').fadeOut()
                    this.categories_data = response.data.data
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
        async getSearch(search_words) {
            try {
                const response = await axios.post(`/admin/categories/search?page=${this.page}`, {
                    search_words: search_words,
                },
                );
                if (response.data.status === true) {
                    this.categories_data = response.data.data.data
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
        async makeTop(cat_id) {
            try {
                const response = await axios.post(`/admin/categories/makeTop`, {
                    cat_id: cat_id,
                },
                );
                if (response.data.status === true) {
                    this.getCategories()
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
        async makeAtNav(cat_id) {
            try {
                const response = await axios.post(`/admin/categories/make-at-nav`, {
                    cat_id: cat_id,
                },
                );
                if (response.data.status === true) {
                    this.getCategories()
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
        getValues(cat_id, cat_name) {
            this.cat_id = cat_id
            this.cat_name = cat_name
        }
    },
    created() {
        this.getCategories()
        $('.loader').fadeOut()
    },
}).mount('#lang_prev')
</script>
@endsection
