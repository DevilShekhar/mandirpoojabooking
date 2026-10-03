@extends('frontend.layouts.app')
@section('title', 'Our Temples')
@section('meta_keyword', 'Temples, Hindu Temples, Famous Temples, India Temples')
@section('meta_description', 'Explore our temples and discover famous temples across India.')
@section('content')

<section class="team-area pt-60 pt-sm-44">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="li-section-title capitalize mb-25">
                    <h2>
                        <span>Our Temples</span>
                    </h2>
                </div>
            </div>
        </div>

        <div class="row">
            @forelse($temples as $temple)
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="team-member mb-30"
                        style="height: 360px; display: flex; flex-direction: column;">

                        <div class="team-thumb"
                            style="height: 220px; flex-shrink: 0; overflow: hidden;">
                            <a href="{{ route('frontend.our-temple.show', $temple->slug) }}">
                                <img
                                    src="{{ $temple->main_image ? asset('storage/' . $temple->main_image) : asset('assets/frontend/assets/images/temple-placeholder.jpg') }}"
                                    alt="{{ $temple->name }}"
                                    style="width:100%;height:220px;object-fit:cover;">
                            </a>
                        </div>

                        <div class="team-content text-center"
                            style="height: 140px; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 15px;">

                            <h3 style="margin-bottom: 8px;">
                                <a href="{{ route('frontend.our-temple.show', $temple->slug) }}">
                                    {{ $temple->name }}
                                </a>
                            </h3>

                            <p style="margin-bottom: 10px;">
                                {{ $temple->city->name ?? '' }}
                                @if($temple->city && $temple->state)
                                    ,
                                @endif
                                {{ $temple->state->name ?? '' }}
                            </p>

                            @if($temple->is_verified)
                                <span class="badge bg-success">
                                    Verified Temple
                                </span>
                            @endif

                            <a href="{{ route('frontend.our-temple.show', $temple->slug) }}"
                                class="btn btn-primary mt-2">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <h4>No temples available.</h4>
                    </div>
                </div>
            @endforelse
        </div>

        @if($temples->hasPages())
            <div class="row">
                <div class="col-12">
                    <div class="pagination-wrap text-center mt-20">
                        {{ $temples->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

@endsection