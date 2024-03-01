<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\DataFormController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class RegisterController extends Controller
{
    use DataFormController;

    public function getRegisterIndex() {
        return view('site.register');
    }

    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'unique:users,email', 'email'],
            'phone' => 'required|unique:users,phone',
            'password' => ['required', 'min:8'],
            'dob' => 'required|date'
        ], [
            'email.required' => 'Please enter your email address.',
            'phone.required' => 'Please enter your phone number.',
            'email.unique' => 'This email address already exists.',
            'phone.unique' => 'This phone number already exists.',
            'password.required' => 'Please enter a password.',
            'password.min' => 'Password should be at least 8 characters long.',
            'dob.required' => 'Please enter you date of brith',
            'email.email' => 'Plese enter a valid email'
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Registration failed', [$validator->errors()->first()], []);
        }

        $createUser = User::create([
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'dob' => $request->dob
        ]);

        if ($createUser) :
            $token = $createUser->createToken('token')->plainTextToken;
            return
                $this->jsonData(
                    true,
                    $createUser->verify,
                    'Register successfuly',
                    [],
                    [
                        'id' => $createUser->id,
                        'email' => $createUser->email,
                        'phone' => $createUser->phone,
                        'dob' => $createUser->dob,
                        'token' => $token
                    ]
                );
        endif;

    }

    public function getLoginIndex(Request $request) {
        return view('site.login');
    }

    public function getUser(Request $request)
    {
        if ($request->user())
            return $this->jsonData(true, $request->user()->verify, '', [], ['user' => $request->user()]);
        else
            return $this->jsonData(false, null, 'Account Not Found', [], []);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:8',
        ], [
            'email.required' => 'please enter your email or phone number',
            'password.required' => 'please enter your password',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Login failed', [$validator->errors()->first()], []);
        }

        if (filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $request->input('email'), 'password' => $request->input('password')];
        } else {
            $credentials = ['phone' => $request->input('email'), 'password' => $request->input('password')];
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            return $this->jsonData(true, $user->verify, 'Successfully Operation', [], ['token' => $token]);
        }
        return $this->jsonData(false, null, 'Faild Operation', ['Your email/phone number or password are incorrect'], []);
    }

    public function logutIndex() {
        return view('site.logout');
    }

    public function logout(Request $request) {
        $user = $request->user();
        $token = $user->currentAccessToken();
        $token->delete();

        if ($user) {
            return $this->jsonData(true, 0, 'Logged out successfully', [], []);
        } else {
            return $this->jsonData(false, null, 'Could not logout', ['Server error try again later'], []);
        }
    }

}
