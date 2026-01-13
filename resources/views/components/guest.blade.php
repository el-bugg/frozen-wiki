<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Frozen Wiki') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/frozen-theme.css') }}" rel="stylesheet">
    
    <style>
        .glass-auth-container {
            background: rgba(5, 11, 20, 0.75);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(0, 217, 255, 0.2);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.8);
        }
        .auth-input {
            background: rgba(15, 23, 42, 0.6) !important;
            border: 1px solid #334e68 !important;
            color: #cceeff !important;
            padding: 12px 16px;
        }
        .auth-input:focus {
            background: rgba(15, 23, 42, 0.8) !important;
            border-color: #00d9ff !important;
            box-shadow: 0 0 10px rgba(0, 217, 255, 0.3) !important;
        }
        .auth-label {
            font-family: 'Cinzel', serif;
            font-size: 0.7rem;
            letter-spacing: 2px;
            color: #00d9ff;
            text-transform: uppercase;
            margin-bottom: 5px;
            display: block;
        }
        .btn-neon-auth {
            background: transparent;
            border: 1px solid #00d9ff;
            color: #00d9ff;
            font-family: 'Cinzel', serif;
            letter-spacing: 3px;
            font-weight: bold;
            transition: all 0.3s ease;
            text-transform: uppercase;
        }
        .btn-neon-auth:hover {
            background: #00d9ff;
            color: #000;
            box-shadow: 0 0 20px rgba(0, 217, 255, 0.6);
        }
        .frozen-title {
            text-shadow: 0 0 10px rgba(0, 217, 255, 0.5);
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900">
    
    <div class="position-fixed top-0 start-0 w-100 h-100 overflow-hidden" style="z-index: -1;">
        <video autoplay muted loop playsinline class="w-100 h-100 object-fit-cover" style="opacity: 0.5;">
            <source src="https://cdn.cloudflare.steamstatic.com/apps/dota2/videos/dota_react/homepage/dota_montage_webm.webm" type="video/webm">
        </video>
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle, rgba(0,0,0,0.1) 0%, #050b14 100%);"></div>
    </div>

    <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center py-5">
        <div class="mb-4 text-center">
            <a href="/" class="text-decoration-none">
                <h1 class="frozen-text display-4 m-0" data-text="FROZEN WIKI">FROZEN WIKI</h1>
            </a>
        </div>

        <div class="glass-auth-container rounded-3 p-4 p-md-5 w-100" style="max-width: 450px;">
            {{ $slot }}
        </div>
    </div>

</body>
</html>