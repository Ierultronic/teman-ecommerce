<div class="promotion-banner">
    @if($promotions->isNotEmpty())
        <div class="promotion-banners grid gap-4 {{ $style === 'horizontal' ? 'grid-cols-1' : ($style === 'vertical' ? 'grid-cols-1' : 'grid-cols-2') }}">
            @foreach($promotions as $promotion)
                <div class="promotion-banner-item relative overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 {{ $style === 'vertical' ? 'aspect-[3/4]' : ($style === 'square' ? 'aspect-square' : 'aspect-[16/6]') }}">
                    <img src="{{ $promotion->banner_image_url }}" 
                         alt="{{ $promotion->title }}" 
                         class="w-full h-full object-cover">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                    
                    <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                        <h3 class="font-bold text-lg mb-1">{{ $promotion->title }}</h3>
                        <p class="text-sm opacity-90">{{ $promotion->type }}</p>
                        @if($promotion->description)
                            <p class="text-xs opacity-75 mt-1 line-clamp-2">{{ $promotion->description }}</p>
                        @endif
                        @if($promotion->minimum_amount)
                            <p class="text-xs mt-2 font-medium">
                                Min. spend: RM {{ number_format($promotion->minimum_amount, 2) }}
                            </p>
                        @endif
                    </div>
                    
                    <!-- Promotion Badge -->
                    <div class="absolute top-2 right-2">
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">PROMO</span>
                    </div>
                    
                    <!-- Expiry Date -->
                    @if($promotion->ends_at)
                        <div class="absolute top-2 left-2">
                            <span class="bg-white/90 text-gray-800 text-xs font-medium px-2 py-1 rounded-full">
                                Expires {{ $promotion->ends_at->format('M d') }}
                            </span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        
        @if($style === 'horizontal')
            <style>
                .line-clamp-2 {
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                }
            </style>
        @endif
    @else
        <div class="text-center py-8 text-gray-500">
            <i data-feather="tag" class="w-8 h-8 mx-auto mb-2"></i>
            <p>No active promotions available</p>
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:updated', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
