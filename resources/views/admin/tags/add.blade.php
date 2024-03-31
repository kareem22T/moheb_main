@extends('admin.layouts.admin-layout')

@section('title', 'Add Tag')

@section('tags_add_active', 'active')

@section('content')
<h3 class="mb-5">
    Add Tag
</h3>
<div class="card" id="add_Tag">
    <div class="card-body">
        <form @submit.prevent>
            <div  class="d-flex justify-content-between gap-4">
                <div class="mb-3 w-50">
                    <label for="Tag_name" class="form-label">Tag Name</label>
                    <input type="text" class="form-control" id="Tag_name" v-model="Tag_name">
                </div>
            </div>
            <button type="submit" class="btn btn-primary" @click="add(this.symbol, this.Tag_name)"><i class="ti ti-plus"></i> Add</button>
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
            Tag_name: null
        }
    },
    methods: {
        async add(symbol, Tag_name) {
            $('.loader').fadeIn().css('display', 'flex')
            try {
                const response = await axios.post(`/admin/tags/add`, {
                    symbol: symbol,
                    name: Tag_name,
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
                        window.location.href = '/admin/tags'
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
}).mount('#add_Tag')
</script>
@endsection