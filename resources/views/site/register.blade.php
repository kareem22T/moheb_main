
@extends('site.layouts.main')

@section('title', 'Register')

@section('content')
<main class="register_wrapper" id="register">
    <div class="container">
        <a href="/" class="fa fa-x" style="position: fixed;font-size: 30px;top: 20px; right: 20px;"></a>

        <form @submit.prevent>
            <div class="head">
                <h1>
                    Register Your Account
                </h1>
                <p>Already have an account? <a href="{{ route('site.login') }}">Login</a></p>
            </div>
            <div class="input">
                <input type="text" name="email" id="email" placeholder="Email" v-model="email">
                <img src="{{ asset('/site/imgs/envelope-regular.svg') }}" alt="email icon">
            </div>
            <div class="input">
                <input type="text" name="phone" id="phone" placeholder="Phone Number" v-model="phone">
                <img src="{{ asset('/site/imgs/phone-solid.svg') }}" alt="phone icon">
            </div>
            <div class="input">
                <input type="text" name="dob" id="dob" placeholder="Date Of Birth"
                onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control" v-model="dob">
                <img src="{{ asset('/site/imgs/calendar-days-regular.svg') }}" alt="calendar icon">
            </div>
            <div class="input">
                <input type="password" name="password" id="password" placeholder="Password" v-model="password">
                <img src="{{ asset('/site/imgs/lock-solid.svg') }}" alt="lock icon">
            </div>
            <div class="input">
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" v-model="password_confirmation">
                <img src="{{ asset('/site/imgs/lock-solid.svg') }}" alt="lock icon">
            </div>
            <button type="submit" class="button" @click="registerMethod(this.email, this.phone, this.dob, this.password, this.password_confirmation)">Register Account</button>
            {{-- <p>By clicking here and continuing, <br> I agree to the <router-link to="/terms">Terms</router-link> of Service and <router-link to="/privacy-policy">Privacy Policy</router-link></p> --}}
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
        email: null,
        dob: null,
        password: null,
        password_confirmation: null,
    }
},
methods: {
    setCookie(name, value, days) {
        var expirationDate = new Date();
        expirationDate.setDate(expirationDate.getDate() + days);

        var expires = "expires=" + expirationDate.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/";
    },
    async registerMethod(email, phone, dob, password, password_confirmation) {
        $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post( `/register`, {
                email: email,
                phone: phone,
                dob: dob,
                password: password,
                password_confirmation: password_confirmation,
            },
            );
            $('.loader').fadeOut()
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
                    $('#errors').fadeOut('slow')
                    window.location.href = `{{ route('site.home') }}`
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
    }
},
created() {
    $('.loader').fadeOut()
},
}).mount('#register')
</script>
@endSection
