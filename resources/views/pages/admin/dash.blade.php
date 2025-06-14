@extends('pages.layouts.admin')

@section('title', 'Dashboard')

@section('content')

    <img src="https://themewagon.com/wp-content/uploads/2021/11/celestial-1.png"
            alt="Dashboard UI Inspiration" class="w-full h-auto">
    <div class="flex flex-col items-center justify-center h-screen">
        <h1 class="text-4xl font-bold mb-4">Achavas mesmo que isso era a nossa dashboard?</h1>
        <p class="text-lg">É que a nossa vai ser melhor XD</p>
        <p>Links e cenas:</p>
        <ul class="list-disc list-inside">
            <li><a href="{{ route('board.stock') }}" class="text-blue-500 hover:underline">Stock</a></li>
        </ul>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(() => {
                window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
            }, 1000);
        });
    </script>

@endsection