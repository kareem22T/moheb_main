@extends('admin.layouts.admin-layout')

@section('title', 'Catigories')

@section('categories_preview_active', 'active')

@section('content')
@if ($category)
<div class="d-flex gap-2 justify-content-between mb-3">
    <div class="w-75">
        <h3>
            {{ $category->main_name }} Category
        </h3>
        <p>
            {{ $category->description }}
        </p>
        <a href="/moheb2/admin/categories/edit/{{ $category->id }}" class="btn btn-success">Edit Category</a>
    </div>
    <div class="img card p-2" style="max-height: 150px; max-width: 140px">
        <img src="{{$category->thumbnail_path ?  $category->thumbnail_path : '/dashboard/images/add_image.svg' }}" id="preview" alt="img logo" style="width: 100%; max-width: 100%;object-fit: contain;height: 100%;">                                                
    </div>
</div>
@endif
<div class="card w-100" id="lang_prev">
    <div class="card-header d-flex justify-content-between gap-3">
        <h4>Sub Categories</h4>
    </div>
    <div class="card-body p-4">
    <div class="table-responsive">
        <table class="table text-nowrap mb-0 align-middle">
        @if ($category->sub_categories->count() > 0)
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
                    <h6 class="fw-semibold mb-0 d-inline-flex align-items-center">Controls</h6>
                </th>
            </tr>
        </thead>
        @endif
        <tbody>
            @foreach ($category->sub_categories as $cat)
                <tr>
                    <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{$cat->id}}</h6></td>
                    <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{$cat->main_name}}</h6></td>
                    <td class="border-bottom-0">
                        <p class="mb-0 fw-normal">{{$cat->description}}</p>
                    </td>
                    <td class="border-bottom-0">
                        <div class="d-flex gap-2">
                            <a href="/moheb2/admin/categories/edit/{{ $cat->id }}" class="btn btn-secondary p-2"><h4 class="ti ti-edit text-light m-0 fw-semibold"></h4></a>
                            <button class="btn btn-danger p-2" @click="this.delete_pop_up = true; getValues('{{ $cat->id }}', '{{ $cat->main_name }}')"><h4 class="ti ti-trash text-light m-0 fw-semibold"></h4></button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </table>
    </div>
    <h4 class="text-center mt-2">
        {{ $category->sub_categories->count() == 0 ? 'There is no any Sub Category' : '' }}
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
        }
    },
    methods: {
        async update(lang_id, lang_symbol, lang_name) {
            $('.loader').fadeIn().css('display', 'flex')
            try {
                const response = await axios.post(`/moheb2/admin/languages/edit`, {
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
                const response = await axios.post(`/moheb2/admin/categories/delete`, {
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
                const response = await axios.post(`/moheb2/admin/categories`, {
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
        async getSearch(search_words) {
            try {
                const response = await axios.post(`/moheb2/admin/categories/search`, {
                    search_words: search_words,
                },
                );
                if (response.data.status === true) {
                    this.categories_data = response.data.data.data
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