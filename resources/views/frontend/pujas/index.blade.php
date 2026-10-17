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
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="puja-filter-card">
                    <div class="puja-filter-header">
                        <h4><i class="fa fa-filter"></i> Filter Pujas</h4>
                    </div>
                    <div class="puja-filter-body">
                        <div class="filter-group">
                            <label>City</label>
                            <div class="searchable-select" data-name="city">
                                <button type="button" class="searchable-select-button">
                                    <span>All Cities</span>
                                    <i class="fa fa-angle-down"></i>
                                </button>
                                <div class="searchable-select-dropdown">
                                    <div class="searchable-select-search">
                                        <i class="fa fa-search"></i>
                                        <input type="text" placeholder="Search city..." autocomplete="off">
                                    </div>
                                    <div class="searchable-select-options">
                                        <div class="searchable-option active" data-value="">All Cities</div>
                                        @foreach($cities as $city)
                                            <div class="searchable-option" data-value="{{ $city->id }}">{{ $city->name }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <input type="hidden" name="city" value="">
                            </div>
                        </div>
                        <div class="filter-group">
                            <label>Deity</label>
                            <div class="searchable-select" data-name="deity">
                                <button type="button" class="searchable-select-button">
                                    <span>All Deities</span>
                                    <i class="fa fa-angle-down"></i>
                                </button>
                                <div class="searchable-select-dropdown">
                                    <div class="searchable-select-search">
                                        <i class="fa fa-search"></i>
                                        <input type="text" placeholder="Search deity..." autocomplete="off">
                                    </div>
                                    <div class="searchable-select-options">
                                        <div class="searchable-option active" data-value="">All Deities</div>
                                        @foreach($deities as $deity)
                                            <div class="searchable-option" data-value="{{ $deity->id }}">{{ $deity->name }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <input type="hidden" name="deity" value="">
                            </div>
                        </div>
                        <div class="filter-group">
                            <label>Puja</label>
                            <div class="searchable-select" data-name="puja">
                                <button type="button" class="searchable-select-button">
                                    <span>All Pujas</span>
                                    <i class="fa fa-angle-down"></i>
                                </button>
                                <div class="searchable-select-dropdown">
                                    <div class="searchable-select-search">
                                        <i class="fa fa-search"></i>
                                        <input type="text" placeholder="Search puja..." autocomplete="off">
                                    </div>
                                    <div class="searchable-select-options">
                                        <div class="searchable-option active" data-value="">All Pujas</div>
                                        @foreach($allPujas as $item)
                                            <div class="searchable-option" data-value="{{ $item->id }}">{{ $item->name }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <input type="hidden" name="puja" value="">
                            </div>
                        </div>                        
                        <button type="button" id="resetFilters" class="puja-reset-btn"><i class="fa fa-refresh"></i> Reset Filters</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-8">
                <div id="pujaResults">
                    @fragment('pujaResults')
                    <div class="row">
                        @forelse($pujas as $puja)
                            @php
                                $image = $puja->image ?: 'images/default-puja.jpg';
                                $temple = $puja->temple;
                                $location = collect([$temple?->city?->name, $temple?->district?->name, $temple?->state?->name])->filter()->unique()->implode(', ');
                            @endphp
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 mb-4">
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
                                            <p class="our-puja-info"><i class="fa fa-om"></i><strong>Deity:</strong>{{ $puja->deity->name }}</p>
                                        @endif
                                        @if($temple)
                                            <p class="our-puja-info"><i class="fa fa-university"></i><strong>Temple:</strong>{{ $temple->name }}</p>
                                        @endif
                                        @if($location)
                                            <p class="our-puja-info"><i class="fa fa-map-marker"></i>{{ $location }}</p>
                                        @endif
                                        @if($puja->duration)
                                            <p class="our-puja-info"><i class="fa fa-clock-o"></i><strong>Duration:</strong>{{ $puja->duration }}</p>
                                        @endif
                                        <div class="our-puja-badges">
                                            @if($puja->is_online)
                                                <span class="our-puja-badge">Online Puja</span>
                                            @endif
                                            @if($puja->is_offline)
                                                <span class="our-puja-badge">Temple Puja</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('frontend.puja.show', ['slug' => $puja->slug]) }}" class="our-puja-button">View Details <i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="our-puja-empty text-center py-5">
                                    <i class="fa fa-heart-o"></i>
                                    <h4>No Pujas Available</h4>
                                    <p>No pujas match your selected filters.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    @if($pujas->total() > 0)
                        <div class="puja-pagination-wrapper">
                            <div class="puja-pagination-info">Showing <strong>{{ $pujas->firstItem() }}</strong> to <strong>{{ $pujas->lastItem() }}</strong> of <strong>{{ $pujas->total() }}</strong> Pujas</div>
                            @if($pujas->lastPage() > 1)
                                <div class="puja-pagination">
                                    <button type="button" class="puja-page-button" data-page="{{ $pujas->currentPage() - 1 }}" {{ $pujas->onFirstPage() ? 'disabled' : '' }}><i class="fa fa-angle-left"></i> Previous</button>
                                    @php
                                        $currentPage = $pujas->currentPage();
                                        $lastPage = $pujas->lastPage();
                                        $startPage = max(1, $currentPage - 2);
                                        $endPage = min($lastPage, $currentPage + 2);
                                    @endphp
                                    @if($startPage > 1)
                                        <button type="button" class="puja-page-button" data-page="1">1</button>
                                        @if($startPage > 2)
                                            <span class="puja-page-dots">...</span>
                                        @endif
                                    @endif
                                    @for($page = $startPage; $page <= $endPage; $page++)
                                        <button type="button" class="puja-page-button {{ $page == $currentPage ? 'active' : '' }}" data-page="{{ $page }}">{{ $page }}</button>
                                    @endfor
                                    @if($endPage < $lastPage)
                                        @if($endPage < $lastPage - 1)
                                            <span class="puja-page-dots">...</span>
                                        @endif
                                        <button type="button" class="puja-page-button" data-page="{{ $lastPage }}">{{ $lastPage }}</button>
                                    @endif
                                    <button type="button" class="puja-page-button" data-page="{{ $currentPage + 1 }}" {{ $currentPage >= $lastPage ? 'disabled' : '' }}>Next <i class="fa fa-angle-right"></i></button>
                                </div>
                            @endif
                        </div>
                    @endif
                    @endfragment
                </div>
            </div>
        </div>
    </div>
</section>
<style>
.our-puja-section{background:#fff}.our-puja-title{color:#222;font-size:34px;font-weight:700;margin-bottom:12px}.our-puja-subtitle{color:#777;font-size:16px;margin-bottom:0}.puja-filter-card{background:#fff;border:1px solid #eee;border-radius:12px;overflow:visible;box-shadow:0 4px 18px rgba(0,0,0,.06);position:sticky;top:20px;z-index:100}.puja-filter-header{background:#ea0505;padding:16px 18px;border-radius:12px 12px 0 0}.puja-filter-header h4{color:#fff;font-size:18px;font-weight:700;margin:0}.puja-filter-header h4 i{margin-right:7px}.puja-filter-body{padding:20px}.filter-group{margin-bottom:18px}.filter-group label{display:block;color:#333;font-size:14px;font-weight:600;margin-bottom:8px}.searchable-select{position:relative;width:100%}.searchable-select-button{width:100%;height:44px;padding:8px 38px 8px 12px;border:1px solid #ddd;border-radius:6px;background:#fff;color:#555;font-size:14px;text-align:left;position:relative;cursor:pointer;outline:none}.searchable-select-button:hover,.searchable-select.open .searchable-select-button{border-color:#ea0505}.searchable-select-button i{position:absolute;right:13px;top:14px;font-size:16px;color:#666;transition:transform .2s ease}.searchable-select.open .searchable-select-button i{transform:rotate(180deg)}.searchable-select-dropdown{display:none;position:absolute;left:0;right:0;top:49px;background:#fff;border:1px solid #ddd;border-radius:6px;box-shadow:0 8px 25px rgba(0,0,0,.15);z-index:9999;overflow:hidden}.searchable-select.open .searchable-select-dropdown{display:block}.searchable-select-search{display:flex;align-items:center;gap:8px;padding:8px;border-bottom:1px solid #eee;background:#fff}.searchable-select-search i{color:#999;font-size:14px}.searchable-select-search input{width:100%;height:36px;border:1px solid #ddd;border-radius:5px;padding:6px 9px;color:#555;font-size:14px;outline:none}.searchable-select-search input:focus{border-color:#ea0505}.searchable-select-options{max-height:220px;overflow-y:auto}.searchable-option{padding:10px 12px;color:#555;font-size:14px;cursor:pointer;transition:background .15s ease,color .15s ease}.searchable-option:hover{background:#fff0f0;color:#ea0505}.searchable-option.active{background:#ea0505;color:#fff}.searchable-option.hidden{display:none}.no-search-result{padding:12px;color:#999;font-size:14px;text-align:center;display:none}.puja-reset-btn{width:100%;border:1px solid #ea0505;background:#fff;color:#ea0505;border-radius:6px;padding:11px 15px;font-size:14px;font-weight:600;text-align:center;cursor:pointer;transition:all .3s ease}.puja-reset-btn:hover{background:#ea0505;color:#fff}.puja-reset-btn i{margin-right:5px}.puja-loading{display:flex;justify-content:center;align-items:center;min-height:300px}.puja-loading-content{text-align:center}.puja-loading-content i{color:#ea0505;font-size:32px;margin-bottom:12px}.puja-loading-content p{color:#777;font-size:14px;margin:0}.our-puja-card{background:#fff;border:1px solid #eee;border-radius:12px;overflow:hidden;box-shadow:0 4px 18px rgba(0,0,0,.06);transition:transform .3s ease,box-shadow .3s ease}.our-puja-card:hover{transform:translateY(-5px);box-shadow:0 10px 28px rgba(0,0,0,.12)}.our-puja-image-wrap{position:relative;height:230px;overflow:hidden;background:#f7f7f7}.our-puja-image{width:100%;height:100%;object-fit:cover;transition:transform .4s ease}.our-puja-card:hover .our-puja-image{transform:scale(1.05)}.our-puja-category{position:absolute;top:14px;left:14px;padding:7px 12px;background:#ea0505;color:#fff;border-radius:5px;font-size:12px;font-weight:600}.our-puja-content{padding:20px;display:flex;flex-direction:column;height:calc(100% - 230px)}.our-puja-name{color:#222;font-size:20px;font-weight:700;line-height:1.4;margin-bottom:15px}.our-puja-info{display:flex;align-items:flex-start;gap:8px;color:#666;font-size:14px;line-height:1.6;margin-bottom:9px;overflow-wrap:anywhere}.our-puja-info i{color:#ea0505;width:18px;margin-top:4px;margin-right:5px;flex-shrink:0;text-align:center}.our-puja-info strong{color:#333;margin-right:3px;flex-shrink:0}.our-puja-badges{display:flex;flex-wrap:wrap;gap:8px;margin-top:auto;margin-bottom:18px}.our-puja-badge{display:inline-block;padding:6px 10px;background:#fff0f0;color:#ea0505;border-radius:5px;font-size:12px;font-weight:600}.our-puja-button{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:12px 16px;background:#ea0505;color:#fff;border:1px solid #ea0505;border-radius:6px;font-size:14px;font-weight:600;text-decoration:none;transition:background .3s ease,color .3s ease}.our-puja-button:hover{background:#fff;color:#ea0505}.our-puja-button i{font-size:14px}.our-puja-empty{border:1px dashed #ddd;border-radius:10px;background:#fafafa}.our-puja-empty>i{color:#ea0505;font-size:42px;margin-bottom:15px}.our-puja-empty h4{color:#222;font-weight:700}.our-puja-empty p{color:#777;margin-bottom:0}.puja-pagination-wrapper{width:100%;margin-top:10px;padding:20px 0 5px;border-top:1px solid #eee}.puja-pagination-info{color:#777;font-size:14px;text-align:center;margin-bottom:15px}.puja-pagination-info strong{color:#333}.puja-pagination{width:100%;display:flex!important;flex-wrap:wrap;align-items:center;justify-content:center;gap:7px;margin:0;padding:0}.puja-pagination .puja-page-button{width:auto!important;min-width:40px!important;height:40px!important;display:inline-flex!important;align-items:center!important;justify-content:center!important;gap:5px!important;margin:0!important;padding:0 13px!important;border:1px solid #ddd!important;border-radius:6px!important;background:#fff!important;color:#555!important;font-size:14px!important;font-weight:600!important;line-height:1!important;text-decoration:none!important;box-shadow:none!important;outline:none!important;cursor:pointer!important;transition:all .2s ease!important}.puja-pagination .puja-page-button:hover:not(:disabled){border-color:#ea0505!important;background:#fff0f0!important;color:#ea0505!important}.puja-pagination .puja-page-button.active{border-color:#ea0505!important;background:#ea0505!important;color:#fff!important}.puja-pagination .puja-page-button:disabled{opacity:.45!important;cursor:not-allowed!important;background:#f5f5f5!important;color:#999!important;border-color:#ddd!important}.puja-pagination .puja-page-button i{font-size:13px!important;width:auto!important;margin:0!important;color:inherit!important}.puja-pagination .puja-page-dots{width:35px!important;height:40px!important;display:inline-flex!important;align-items:center!important;justify-content:center!important;color:#777!important;font-size:14px!important}@media(max-width:991px){.puja-filter-card{position:static}.our-puja-image-wrap{height:220px}}@media(max-width:767px){.our-puja-title{font-size:28px}.our-puja-image-wrap{height:210px}.our-puja-content{padding:16px}.our-puja-name{font-size:18px}.puja-pagination{gap:5px}.puja-pagination .puja-page-button{min-width:38px!important;height:38px!important;padding:0 9px!important}}@media(max-width:480px){.puja-pagination .puja-page-button{font-size:13px!important}}
</style>
<script>
document.addEventListener('DOMContentLoaded',function(){
    const selectors=document.querySelectorAll('.searchable-select');
    const filters=document.querySelectorAll('.searchable-select input[type="hidden"]');
    const resetButton=document.getElementById('resetFilters');
    const resultsContainer=document.getElementById('pujaResults');
    let controller=null;
    selectors.forEach(function(select){
        const button=select.querySelector('.searchable-select-button');
        const dropdown=select.querySelector('.searchable-select-dropdown');
        const searchInput=select.querySelector('.searchable-select-search input');
        const options=select.querySelectorAll('.searchable-option');
        const hiddenInput=select.querySelector('input[type="hidden"]');
        const selectedText=button.querySelector('span');
        button.addEventListener('click',function(event){
            event.stopPropagation();
            selectors.forEach(function(other){
                if(other!==select){
                    other.classList.remove('open');
                }
            });
            select.classList.toggle('open');
            if(select.classList.contains('open')){
                searchInput.value='';
                options.forEach(function(option){
                    option.classList.remove('hidden');
                });
                setTimeout(function(){
                    searchInput.focus();
                },50);
            }
        });
        searchInput.addEventListener('click',function(event){
            event.stopPropagation();
        });
        searchInput.addEventListener('input',function(){
            const search=searchInput.value.toLowerCase().trim();
            let found=false;
            options.forEach(function(option){
                const text=option.textContent.toLowerCase().trim();
                if(text.includes(search)){
                    option.classList.remove('hidden');
                    found=true;
                }else{
                    option.classList.add('hidden');
                }
            });
            let noResult=select.querySelector('.no-search-result');
            if(!noResult){
                noResult=document.createElement('div');
                noResult.className='no-search-result';
                noResult.textContent='No result found';
                select.querySelector('.searchable-select-options').appendChild(noResult);
            }
            noResult.style.display=found?'none':'block';
        });
        options.forEach(function(option){
            option.addEventListener('click',function(event){
                event.stopPropagation();
                const value=option.getAttribute('data-value');
                const text=option.textContent.trim();
                hiddenInput.value=value;
                selectedText.textContent=text;
                options.forEach(function(item){
                    item.classList.remove('active');
                });
                option.classList.add('active');
                select.classList.remove('open');
                searchInput.value='';
                options.forEach(function(item){
                    item.classList.remove('hidden');
                });
                loadPujas(1);
            });
        });
    });
    document.addEventListener('click',function(){
        selectors.forEach(function(select){
            select.classList.remove('open');
        });
    });
    function getFilterData(page=1){
        const formData=new FormData();
        filters.forEach(function(filter){
            if(filter.value){
                formData.append(filter.name,filter.value);
            }
        });
        formData.append('page',page);
        return formData;
    }
    function showLoading(){
        resultsContainer.innerHTML='<div class="puja-loading"><div class="puja-loading-content"><i class="fa fa-spinner fa-spin"></i><p>Loading Pujas...</p></div></div>';
    }
    function loadPujas(page=1){
        if(controller){
            controller.abort();
        }
        controller=new AbortController();
        showLoading();
        fetch("{{ route('frontend.puja.filter') }}",{
            method:'POST',
            headers:{
                'X-CSRF-TOKEN':"{{ csrf_token() }}",
                'X-Requested-With':'XMLHttpRequest',
                'Accept':'text/html'
            },
            body:getFilterData(page),
            signal:controller.signal
        })
        .then(function(response){
            if(!response.ok){
                throw new Error('Unable to load Pujas');
            }
            return response.text();
        })
        .then(function(html){
            resultsContainer.innerHTML=html;
        })
        .catch(function(error){
            if(error.name==='AbortError'){
                return;
            }
            console.error(error);
            resultsContainer.innerHTML='<div class="our-puja-empty text-center py-5"><i class="fa fa-exclamation-circle"></i><h4>Something went wrong</h4><p>Please try again.</p></div>';
        });
    }
    resetButton.addEventListener('click',function(){
        selectors.forEach(function(select){
            const hiddenInput=select.querySelector('input[type="hidden"]');
            const selectedText=select.querySelector('.searchable-select-button span');
            const options=select.querySelectorAll('.searchable-option');
            const firstOption=options[0];
            hiddenInput.value='';
            selectedText.textContent=firstOption.textContent.trim();
            options.forEach(function(option){
                option.classList.remove('active');
                option.classList.remove('hidden');
            });
            firstOption.classList.add('active');
            select.classList.remove('open');
            select.querySelector('.searchable-select-search input').value='';
        });
        loadPujas(1);
    });
    resultsContainer.addEventListener('click',function(event){
        const button=event.target.closest('.puja-page-button');
        if(!button||button.disabled){
            return;
        }
        const page=parseInt(button.getAttribute('data-page'));
        if(!page||page<1){
            return;
        }
        loadPujas(page);
    });
});
</script>
@endsection