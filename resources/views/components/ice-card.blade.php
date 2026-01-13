@props(['interactive' => false])

<div {{ $attributes->merge(['class' => 'ice-card-container position-relative h-100 overflow-hidden rounded border border-info ' . ($interactive ? 'ice-hover' : '')]) }}>
    <div class="card-body p-4 position-relative z-2" style="background: rgba(5, 11, 20, 0.9); backdrop-filter: blur(10px); height: 100%;">
        {{ $slot }}
    </div>
    <div class="ice-particles position-absolute top-0 start-0 w-100 h-100 z-1 pointer-events-none opacity-50">
        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 640 640" preserveAspectRatio="none">
            <circle id="ice1" cx="0" cy="0" r="5" fill="#00d9ff" style="filter: blur(2px);" />
            <circle id="ice2" cx="0" cy="0" r="3" fill="#ffffff" />
            <circle id="ice3" cx="0" cy="0" r="4" fill="#aeeeff" style="filter: blur(1px);" />
        </svg>
    </div>
</div>
<style>
    .ice-card-container {
        border-color: #2a4b5f !important;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        transition: all 0.4s ease;
    }
    .ice-hover:hover {
        border-color: #00d9ff !important;
        box-shadow: 0 0 25px rgba(0, 217, 255, 0.25);
        transform: translateY(-5px);
    }
    #ice1, #ice2, #ice3 { animation: ice-float 15s infinite linear; }
    #ice1 { animation-duration: 20s; offset-path: path("M10,10 Q200,300 400,10 T600,600"); }
    #ice2 { animation-duration: 25s; offset-path: path("M600,10 Q400,400 200,200 T10,600"); }
    #ice3 { animation-duration: 18s; offset-path: path("M320,600 Q100,400 500,200 T320,10"); }
    @keyframes ice-float {
        0% { offset-distance: 0%; opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { offset-distance: 100%; opacity: 0; }
    }
</style>