
@extends('site.layouts.main')

@section('title', 'Login')

@section('content')
<main class="register_wrapper" id="login">
    <div class="container">
        <form @submit.prevent>
            <div class="head">
                <h1>
                    Account Login
                </h1>
                <p>Do have an account? <a href="{{ route('site.register') }}">SignUp</a></p>
            </div>
            <div class="input">
                <input type="text" name="email" id="email" placeholder="Email" v-model="phone">
                <img src="{{ asset('/site/imgs/envelope-regular.svg') }}" alt="phone icon">
            </div>
            <div class="input">
                <input type="password" name="password" id="password" placeholder="Password" v-model="password">
                <img src="{{ asset('/site/imgs/lock-solid.svg') }}" alt="lock icon">
            </div>
            <button type="submit" class="button" @click="login(this.phone, this.password)">Login</button>
        </form>
    </div>
</main>
@endSection

@section('scripts')
<script>
const { createApp, ref } = Vue
createApp({
data() {
    return {
        phone: null,
        password: null,
    }
},
methods: {
    setCookie(name, value, days) {
        var expirationDate = new Date();
        expirationDate.setDate(expirationDate.getDate() + days);

        var expires = "expires=" + expirationDate.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/";
    },
    async login(phone, password) {
        $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`{{ route('site.loginprocess') }}`, {
                email: phone,
                password: password,
            },
            );
            if (response.data.status === true) {
                sessionStorage.setItem('user_token', response.data.data.token)
                this.setCookie('user_token', response.data.data.token, 30)
                document.getElementById('errors').innerHTML = ''
                let error = document.createElement('div')
                error.classList = 'success'
                error.innerHTML = response.data.message
                document.getElementById('errors').append(error)
                $('#errors').fadeIn('slow')
                setTimeout(() => {
                    $('.loader').fadeOut()
                    $('#errors').fadeOut('slow')
                    window.location.href = '{{ route("site.home") }}'
                }, 1300);
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
                $('form input').css('outline', '2px solid #e41749')
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
    }
},
created() {
    $('.loader').fadeOut()
},
}).mount('#login')
</script>
@endSection