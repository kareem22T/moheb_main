@extends('admin.layouts.admin-layout')

@section('title', 'Add Language')

@section('laguages_content_active', 'active')

@section('content')
<h3 class="mb-5">
    Site content
</h3>
<div id="site_content">
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table text-nowrap mb-0 align-middle">
            <thead class="text-dark fs-4">
                <tr>
                    <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0 d-inline-flex align-items-center">File Name</h6>
                    </th>
                    <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0 d-inline-flex align-items-center">Discription</h6>
                    </th>
                    <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0 d-inline-flex align-items-center">Controls</h6>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border-bottom-0">
                        <h6 class="fw-semibold mb-1">Home.json</h6>
                    </td>
                    <td class="border-bottom-0">
                        <p class="mb-0 fw-normal">Contain Main page content with all languages</p>
                    </td>
                    <td class="border-bottom-0">
                        <div class="d-flex gap-2">
                            <button class="btn btn-secondary p-2" @click="showPopUp('home')"><h4 class="ti ti-edit text-light m-0 fw-semibold"></h4></button>
                            <a href="{{ asset('/json/home.json') }}" download="home" class="btn btn-success p-2"><h4 class="ti ti-download text-light m-0 fw-semibold"></h4></a>
                        </div>
                    </td>
                </tr>
            </tbody>
            </table>
        </div>
    </div>
</div>

<div class="hide-content" v-if="content_pop_up"></div>
<div class="pop-up content-pup-up card w-50" style="margin: auto" v-if="content_pop_up">
    <div class="card-body">
        <form @submit.prevent>
            <div class="form-group w-100">
                <h4 class="text-center">Uploade <span>@{{ file_name }}</span> JSON file</h4>
                <label for="file" class="w-100">
                    <span class="btn btn-primary w-100 mb-3">Upload <i class="ti ti-upload"></i></span>
                </label>
                <input type="file" name="file" id="file" class="d-none" @change="getFile">
            </div>
            <div class="btns d-flex w-100 justify-content-between gap-3">
                <button class="btn btn-light w-100" @click="content_pop_up = false;">Cancel</button>
                <button class="btn btn-secondary w-100" @click="update(this.file, this.file_name)">Update</button>
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
            symbol: null,
            lang_name: null,
            file_name: null,
            content_pop_up: false,
            file: null,
        }
    },
    methods: {
        async add(symbol, lang_name) {
            $('.loader').fadeIn().css('display', 'flex')
            try {
                const response = await axios.post(`/admin/languages/add`, {
                    symbol: symbol,
                    name: lang_name,
                },
                );
                if (response.data.status === true) {
                    document.getElementById('errors').innerHTML = ''
                    let error = document.createElement('div')
                    error.classList = 'success'
                    error.innerHTML = response.data.message
                    document.getElementById('errors').append(error)
                    $('#errors').fadeIn('slow')
                    setTimeout(() => {
                        $('.loader').fadeOut()
                        $('#errors').fadeOut('slow')
                        window.location.href = '/admin/languages'
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
        async update(file, file_name) {
            $('.loader').fadeIn().css('display', 'flex')
            try {
                const response = await axios.post(`/admin/languages/content/update`, {
                    file: file,
                    file_name: file_name,
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
                    setTimeout(() => {
                        $('.loader').fadeOut()
                        $('#errors').fadeOut('slow')
                        this.content_pop_up = false;
                        this.file = null;
                        this.file_name = null;
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

                setTimeout(() => {
                    $('#errors').fadeOut('slow')
                }, 3500);

                console.error(error);
            }
        },
        showPopUp(fileName) {
            this.file_name = fileName; 
            this.content_pop_up = true;
        },
        getFile (event) {
            this.file = event.target.files[0];
        }
    },
    created() {
        $('.loader').fadeOut()
    },
}).mount('#site_content')
</script>
@endsection