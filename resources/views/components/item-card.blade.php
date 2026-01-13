@props(['item'])

<div class="col-4 col-md-2 col-lg-1">
    <a href="{{ route('items.show', $item->id) }}" class="text-decoration-none">
        <div class="card bg-dark border-secondary h-100 item-card position-relative" title="{{ $item->dname }}">
            <img src="{{ $item->img_url }}" class="card-img-top p-1" alt="{{ $item->dname }}" loading="lazy">
            <div class="card-footer p-1 text-center border-0 bg-transparent">
                <small class="text-warning fw-bold d-block" style="font-size: 11px;">
                    <span style="color: gold;">🟡</span> {{ $item->cost }}
                </small>
            </div>
        </div>
    </a>
</div>
<style>
    .item-card { transition: 0.2s; background-color: #1a1a1a !important; }
    .item-card:hover { transform: scale(1.05); border-color: #aaa !important; background-color: #252525 !important; z-index: 5; }
</style>