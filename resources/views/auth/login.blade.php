@extends('layouts.app')

@section('content')
<div class="w-screen h-screen flex items-center justify-center bg-[#E94F07]">
    <div class="w-full md:w-8/12 p-10 rounded-r-lg flex items-center justify-center">
        <div class="flex flex-col items-center gap-4 bg-white p-10 rounded-lg">
            <img src="{{ asset('asset/img/Icon/logo_bpbd.png') }}" class="w-[100px]" alt="">
            <form action="{{ route('auth.login.submit') }}" method="POST" class="w-full md:max-w-[500px]">
                @csrf
                <div class="mb-4">
                    <h1 class="font-bold text-center text-[30px]">Monitoring System</h1>
                </div>
                @include('components.input', [
                    'id' => 'username',
                    'label' => 'Username',
                    'type' => 'text',
                    'name' => 'username',
                    'placeholder' => 'Username',
                ])
                @include('components.input', [
                    'id' => 'password',
                    'label' => 'Password',
                    'type' => 'password',
                    'name' => 'password',
                    'placeholder' => 'Password',
                ])
                <div>
                    <button type="submit" class="w-full mt-4 px-4 py-2 bg-[#083C76] rounded-full text-white">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
