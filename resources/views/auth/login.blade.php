@extends('layouts.app')

@section('content')
<div class="w-screen h-screen flex items-center justify-center bg-white">
    <div class="rounded-lg flex w-full h-full">
        <div class="hidden md:block md:w-4/12">
            <img src="{{ asset('vendor_assets/images/authentication/img-auth-sideimg.jpg') }}" alt="Login Image" class="w-full h-full object-cover rounded-l-lg">
        </div>
        <div class="w-full md:w-8/12 p-10 rounded-r-lg flex items-center justify-center">
            <form action="{{ route('auth.login.submit') }}" method="POST" class="w-full md:max-w-[400px]">
                @csrf
                <div class="mb-4">
                    <h1 class="font-bold text-[30px]">Monitoring System</h1>
                </div>
                @include('components.input', [
                    'id' => 'username',
                    'label' => 'Username',
                    'type' => 'text',
                    'name' => 'username',
                    'placeholder' => 'Ussername',
                ])
                @include('components.input', [
                    'id' => 'password',
                    'label' => 'Password',
                    'type' => 'password',
                    'name' => 'password',
                    'placeholder' => 'Password',
                ])
                <div>
                    <button type="submit" class="w-full mt-4 px-4 py-2 bg-[#14176c] rounded-full text-white">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
