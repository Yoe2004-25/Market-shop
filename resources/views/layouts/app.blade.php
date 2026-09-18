<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        @auth
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="notifDropdown"
           role="button" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            @php
                $unreadCount = auth()->user()->unreadNotifications()->count();
            @endphp
            @if($unreadCount > 0)
                <span class="badge bg-danger">{{ $unreadCount }}</span>
            @endif
        </a>
        <ul class="dropdown-menu dropdown-menu-end" style="min-width: 320px;">
            @forelse(auth()->user()->notifications()->take(5)->get() as $notif)
                <li>
                    <a class="dropdown-item text-wrap" href="{{ url('/payments/' . ($notif->data['payment_id'] ?? '')) }}">
                        <small class="text-muted d-block">
                            {{ $notif->created_at->diffForHumans() }}
                        </small>
                        {{ $notif->data['message'] ?? 'New notification' }}
                    </a>
                </li>
            @empty
                <li><span class="dropdown-item text-muted">No notifications</span></li>
            @endforelse
        </ul>
    </li>
@endauth
    </body>
</html>
