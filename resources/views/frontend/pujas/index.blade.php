@extends('frontend.layouts.app')

@section('content')
<section class="our-puja-section py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="our-puja-title">Our Sacred Pujas</h2>
                <p class="our-puja-subtitle">Discover sacred rituals and book a puja for divine blessings.</p>
            </div>
        </div>
        <div class="row">
            @forelse($pujas as $puja)
                @php
                    $image = $puja->image ?: 'images/default-puja.jpg';
                    $temple = $puja->temple;
                    $location = collect([
                        $temple?->city?->name,
                        $temple?->district?->name,
                        $temple?->state?->name
                    ])->filter()->unique()->implode(', ');
                @endphp
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mb-4">
                    <div class="our-puja-card h-100">
                        <div class="our-puja-image-wrap">
                            <img src="{{ asset('storage/' . ltrim($image, '/')) }}" alt="{{ $puja->name }}" class="our-puja-image" loading="lazy">
                            @if($puja->pujaCategory)
                                <span class="our-puja-category">{{ $puja->pujaCategory->name }}</span>
                            @endif
                        </div>
                        <div class="our-puja-content">
                            <h3 class="our-puja-name">{{ $puja->name }}</h3>
                            @if($puja->deity)
                                <p class="our-puja-info">
                                    <i class="fa fa-om"></i>
                                    <strong>Deity:</strong> {{ $puja->deity->name }}
                                </p>
                            @endif
                            @if($temple)
                                <p class="our-puja-info">
                                    <i class="fa fa-university"></i>
                                    <strong>Temple:</strong> {{ $temple->name }}
                                </p>
                            @endif
                            @if($location)
                                <p class="our-puja-info">
                                    <i class="fa fa-map-marker"></i>
                                    {{ $location }}
                                </p>
                            @endif
                            @if($puja->duration)
                                <p class="our-puja-info">
                                    <i class="fa fa-clock-o"></i>
                                    <strong>Duration:</strong> {{ $puja->duration }}
                                </p>
                            @endif
                            @if($puja->description)
                                <p class="our-puja-description">{{ \Illuminate\Support\Str::limit(strip_tags($puja->description), 110) }}</p>
                            @endif
                            <div class="our-puja-badges">
                                @if($puja->is_online)
                                    <span class="our-puja-badge">Online Puja</span>
                                @endif
                                @if($puja->is_offline)
                                    <span class="our-puja-badge">Temple Puja</span>
                                @endif
                            </div>
                            <a href="{{ route('frontend.puja.show', ['slug' => $puja->slug]) }}" class="our-puja-button">
                                View Details <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="our-puja-empty text-center py-5">
                        <i class="fa fa-heart-o"></i>
                        <h4>No Pujas Available</h4>
                        <p>Please check back later for upcoming pujas.</p>
                    </div>
                </div>
            @endforelse
        </div>
        @if($pujas->hasPages())
            <div class="row mt-3">
                <div class="col-12 d-flex justify-content-center">
                    {{ $pujas->links() }}
                </div>
            </div>
        @endif
    </div>
</section>
<style>
.our-puja-section {
    background: #fff;
}
.our-puja-title {
    color: #222;
    font-size: 34px;
    font-weight: 700;
    margin-bottom: 12px;
}
.our-puja-subtitle {
    color: #777;
    font-size: 16px;
    margin-bottom: 0;
}
.our-puja-card {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.our-puja-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 28px rgba(0, 0, 0, 0.12);
}
.our-puja-image-wrap {
    position: relative;
    height: 230px;
    overflow: hidden;
    background: #f7f7f7;
}
.our-puja-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.our-puja-card:hover .our-puja-image {
    transform: scale(1.05);
}
.our-puja-category {
    position: absolute;
    top: 14px;
    left: 14px;
    padding: 7px 12px;
    background: #ea0505;
    color: #fff;
    border-radius: 5px;
    font-size: 12px;
    font-weight: 600;
}
.our-puja-content {
    padding: 20px;
    display: flex;
    flex-direction: column;
    height: calc(100% - 230px);
}
.our-puja-name {
    color: #222;
    font-size: 20px;
    font-weight: 700;
    line-height: 1.4;
    margin-bottom: 15px;
}
.our-puja-info {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    color: #666;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 9px;
    overflow-wrap: anywhere;
}
.our-puja-info i {
    color: #ea0505;
    width: 18px;
    margin-top: 4px;
    margin-right: 5px;
    flex-shrink: 0;
    text-align: center;
}
.our-puja-info strong {
    color: #333;
    margin-right: 3px;
    flex-shrink: 0;
}
.our-puja-description {
    color: #777;
    font-size: 14px;
    line-height: 1.7;
    margin-top: 5px;
    margin-bottom: 15px;
}
.our-puja-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: auto;
    margin-bottom: 18px;
}
.our-puja-badge {
    display: inline-block;
    padding: 6px 10px;
    background: #fff0f0;
    color: #ea0505;
    border-radius: 5px;
    font-size: 12px;
    font-weight: 600;
}
.our-puja-button {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px 16px;
    background: #ea0505;
    color: #fff;
    border: 1px solid #ea0505;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.3s ease, color 0.3s ease;
}
.our-puja-button:hover {
    background: #fff;
    color: #ea0505;
}
.our-puja-button i {
    font-size: 14px;
}
.our-puja-empty {
    border: 1px dashed #ddd;
    border-radius: 10px;
    background: #fafafa;
}
.our-puja-empty > i {
    color: #ea0505;
    font-size: 42px;
    margin-bottom: 15px;
}
.our-puja-empty h4 {
    color: #222;
    font-weight: 700;
}
.our-puja-empty p {
    color: #777;
    margin-bottom: 0;
}
@media (max-width: 767px) {
    .our-puja-title {
        font-size: 28px;
    }
    .our-puja-image-wrap {
        height: 210px;
    }
    .our-puja-content {
        padding: 16px;
    }
    .our-puja-name {
        font-size: 18px;
    }
}
</style>
@endsection