@extends('pages.layouts.public')

@section('content')

    @auth
        <div class="user-info bg-red-300 w-96 h-96">
            <i class="fas fa-user"></i>
            <span>{{ auth()->user()->name }}</span>
        </div>
    @endauth
    @guest
        <div class="guest-info bg-blue-300 w-96 h-96">
            <i class="fas fa-user"></i>
            <span>Guest</span>
        </div>
    @endguest

@endsection
