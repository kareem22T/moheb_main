import { checkCookie } from "./check-is-cookie";

export async function getUser() {
    var hasUserTokenCookie = checkCookie('user_token');
    if (hasUserTokenCookie) {
        sessionStorage.setItem('user_token', getCookie('user_token'))
    }
    let user_token = sessionStorage.getItem('user_token')
    if (user_token) {
        $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.get(`{{ route('site.register') }}`,
                {
                    headers: {
                        'AUTHORIZATION': `Bearer ${user_token}`
                    }
                },
            );
            $('.loader').fadeOut()
            if (response.data.status === true) {
                sessionStorage.setItem('user', JSON.stringify(response.data.data))
                return true;
            } else {
                return false;
            }

        } catch (error) {
            console.error(error);
            return false;
        }
    }
}