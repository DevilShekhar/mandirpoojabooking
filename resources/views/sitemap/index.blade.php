{!! '<'.'?xml version="1.0" encoding="UTF-8" ?'.'>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('frontend.our-temples') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('frontend.puja') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('frontend.online-puja.index') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ route('frontend.sanatanevents') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ route('frontend.news') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('customer.register') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.4</priority>
    </url>
    <url>
        <loc>{{ route('temple.register') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.4</priority>
    </url>
    <url>
        <loc>{{ route('event-creator.register') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.4</priority>
    </url>
    <url>
        <loc>{{ route('tickit-booking.tickitfillter') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    
    <!-- Static Legal & Info Pages -->
    <url>
        <loc>{{ route('frontend.about-us') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>{{ route('frontend.privacy-policy') }}</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>
    <url>
        <loc>{{ route('frontend.terms-and-conditions') }}</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>
    <url>
        <loc>{{ route('frontend.disclaimer') }}</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    @foreach ($temples as $temple)
    <url>
        <loc>{{ route('frontend.our-temple.show', $temple->slug) }}</loc>
        <lastmod>{{ $temple->updated_at ? $temple->updated_at->toAtomString() : now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    @foreach ($pujas as $puja)
    <url>
        <loc>{{ route('frontend.puja.show', $puja->slug) }}</loc>
        <lastmod>{{ $puja->updated_at ? $puja->updated_at->toAtomString() : now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    @foreach ($onlinePujas as $onlinePuja)
    <url>
        <loc>{{ route('frontend.online-puja.show', $onlinePuja->slug) }}</loc>
        <lastmod>{{ $onlinePuja->updated_at ? $onlinePuja->updated_at->toAtomString() : now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    @foreach ($events as $event)
    <url>
        <loc>{{ route('frontend.sanatanevents.show', $event->slug) }}</loc>
        <lastmod>{{ $event->updated_at ? $event->updated_at->toAtomString() : now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    @foreach ($news as $item)
    <url>
        <loc>{{ route('frontend.news.show', $item->slug) }}</loc>
        <lastmod>{{ $item->updated_at ? $item->updated_at->toAtomString() : now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach

</urlset>