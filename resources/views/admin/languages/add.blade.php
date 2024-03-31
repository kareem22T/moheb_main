@extends('admin.layouts.admin-layout')

@section('title', 'Add Language')

@section('laguages_add_active', 'active')

@section('content')
<h3 class="mb-5">
    Add language
</h3>
<div class="card" id="add_lang">
    <div class="card-body">
        <form @submit.prevent>
            <div  class="d-flex justify-content-between gap-4">
                <div class="mb-3 w-50">
                    <label for="symbol" class="form-label">Language Symbol (EN)</label>
                    <input type="text" class="form-control" id="symbol" v-model="symbol">
                </div>
                <div class="mb-3 w-50">
                    <label for="lang_name" class="form-label">Language Name</label>
                    <input type="text" class="form-control" id="lang_name" v-model="lang_name">
                </div>
            </div>
            <button type="submit" class="btn btn-primary" @click="add(this.symbol, this.lang_name)"><i class="ti ti-plus"></i> Add</button>
        </form>
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
            lang_name: null
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
        }
    },
    created() {
        $('.loader').fadeOut()
    },
}).mount('#add_lang')
</script>
@endsection