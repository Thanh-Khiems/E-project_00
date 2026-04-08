<div class="row g-4 mb-4">
    @foreach($items as $item)
        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi {{ $item['icon'] }}"></i></div>
                <div>
                    <p>{{ $item['label'] }}</p>
                    <h3>{{ $item['value'] }}</h3>
                </div>
            </div>
        </div>
    @endforeach
</div>
