@extends('dashboard')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- MONITOR -->
    <a href="{{ url('/monitor') }}"
       target="_blank"
       class="bg-blue-600 hover:bg-blue-700 text-white p-6 rounded-lg shadow text-center">
        🖥️ Monitor
    </a>

    <!-- SORTEADOR -->
    <a href="{{ url('/sorteador') }}"
       target="_blank"
       class="bg-green-600 hover:bg-green-700 text-white p-6 rounded-lg shadow text-center">
        🎲 Sorteador
    </a>

    <!-- MONITOR TV -->
    <a href="{{ url('/monitor-tv') }}"
       target="_blank"
       class="bg-yellow-500 hover:bg-yellow-600 text-black p-6 rounded-lg shadow text-center font-bold">
        📺 Monitor TV
    </a>

</div>

@endsection
