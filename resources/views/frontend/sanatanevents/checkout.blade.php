@extends('frontend.layouts.app')
@section('title', 'Checkout - Sanatan Events')
@section('content')
@php
$user = auth()->user();
$eventName = $sanatan->name ?? 'Sanatan Event';
$price = (float) ($sanatan->price ?? 0);
$languages = $languages ?? [];
$image = $sanatan->image ?? null;
$imageUrl = $image
? (\Illuminate\Support\Str::startsWith($image, ['http://', 'https://'])
? $image
: asset('storage/' . ltrim($image, '/')))
: null;
$creatorName = $creatorNames[$sanatan->created_by] ?? 'Sanatan Team';
@endphp
<style>
.se-checkout {
    --se-ink: #182235;
    --se-muted: #707b8d;
    --se-line: #e5e9f1;
    --se-primary: #5b45e0;
    --se-primary-dark: #4834c6;
    --se-bg: #f5f7fb;
    padding: 42px 0 64px;
    background: var(--se-bg);
    color: var(--se-ink);
}
.se-checkout * {
    box-sizing: border-box;
}
.se-checkout .container {
    max-width: 1180px;
}
.se-checkout-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 28px;
}
.se-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--se-primary);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
    margin-bottom: 10px;
}
.se-title {
    font-size: clamp(28px, 3vw, 38px);
    font-weight: 850;
    letter-spacing: -.04em;
    margin: 0 0 8px;
}
.se-subtitle {
    color: var(--se-muted);
    margin: 0;
    font-size: 15px;
}
.se-secure {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    white-space: nowrap;
    padding: 10px 14px;
    border: 1px solid #d8eee2;
    border-radius: 999px;
    background: #effaf4;
    color: #167448;
    font-size: 12px;
    font-weight: 800;
}
.se-panel {
    background: #fff;
    border: 1px solid var(--se-line);
    border-radius: 18px;
    box-shadow: 0 8px 28px rgba(23, 32, 51, .045);
    overflow: hidden;
    margin-bottom: 20px;
}
.se-panel-header {
    padding: 21px 24px;
    border-bottom: 1px solid var(--se-line);
}
.se-panel-title {
    display: flex;
    align-items: center;
    gap: 11px;
    margin: 0;
    font-size: 17px;
    font-weight: 800;
}
.se-panel-icon {
    width: 38px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #f0edff;
    color: var(--se-primary);
    border-radius: 11px;
    font-size: 16px;
}
.se-panel-body {
    padding: 24px;
}
.se-event-cover {
    position: relative;
    overflow: hidden;
    border-radius: 13px;
    background: #f0edff;
}
.se-event-image {
    display: block;
    width: 100%;
    height: 250px;
    object-fit: cover;
}
.se-event-placeholder {
    min-height: 210px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--se-primary);
    font-size: 56px;
    background: linear-gradient(135deg, #f0edff, #f9f7ff);
}
.se-event-badge {
    position: absolute;
    left: 14px;
    top: 14px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border-radius: 999px;
    padding: 7px 11px;
    color: #fff;
    background: rgba(24, 34, 53, .78);
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    backdrop-filter: blur(8px);
}
.se-event-name {
    font-size: 24px;
    line-height: 1.3;
    font-weight: 850;
    letter-spacing: -.025em;
    margin: 19px 0 8px;
}
.se-event-description {
    color: var(--se-muted);
    line-height: 1.7;
    font-size: 14px;
    margin-bottom: 19px;
}
.se-detail-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 11px;
}
.se-detail {
    min-width: 0;
    padding: 12px 13px;
    border: 1px solid #edf0f5;
    border-radius: 11px;
    background: #fbfcfe;
}
.se-detail-label {
    display: block;
    color: var(--se-muted);
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-bottom: 5px;
}
.se-detail-value {
    display: block;
    color: var(--se-ink);
    font-size: 13px;
    font-weight: 750;
    overflow-wrap: anywhere;
}
.se-account-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}
.se-field-label {
    display: block;
    color: #596477;
    font-size: 12px;
    font-weight: 800;
    margin-bottom: 7px;
}
.se-field-value {
    min-height: 46px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 13px;
    border: 1px solid var(--se-line);
    border-radius: 10px;
    background: #f9fafc;
    color: var(--se-ink);
    font-size: 13px;
    overflow-wrap: anywhere;
}
.se-field-value i {
    width: 16px;
    color: #8e98a9;
    text-align: center;
}
.se-note {
    color: var(--se-muted);
    font-size: 12px;
    line-height: 1.65;
    margin: 14px 0 0;
}
.se-summary {
    position: sticky;
    top: 24px;
}
.se-summary-event {
    display: flex;
    align-items: center;
    gap: 13px;
    padding-bottom: 19px;
    border-bottom: 1px solid var(--se-line);
}
.se-thumb,
.se-thumb-placeholder {
    flex: 0 0 66px;
    width: 66px;
    height: 66px;
    border-radius: 11px;
    object-fit: cover;
    background: #f0edff;
}
.se-thumb-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--se-primary);
    font-size: 23px;
}
.se-summary-name {
    font-size: 14px;
    line-height: 1.45;
    font-weight: 850;
    margin: 0 0 5px;
}
.se-muted-small {
    color: var(--se-muted);
    font-size: 12px;
    margin: 0;
}
.se-price-row {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    padding: 13px 0;
    color: var(--se-muted);
    font-size: 13px;
}
.se-price-row strong {
    color: var(--se-ink);
    font-weight: 800;
    text-align: right;
}
.se-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    padding: 18px 0 6px;
    border-top: 1px solid var(--se-line);
}
.se-total-label {
    font-size: 14px;
    font-weight: 850;
}
.se-total-amount {
    color: var(--se-primary);
    font-size: 27px;
    font-weight: 900;
    letter-spacing: -.04em;
}
.se-price-note {
    color: var(--se-muted);
    font-size: 11px;
    line-height: 1.6;
    margin: 8px 0 18px;
}
.se-payment-intro {
    color: var(--se-muted);
    font-size: 13px;
    line-height: 1.6;
    margin: 0 0 16px;
}
.se-payment-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
.se-payment-option {
    position: relative;
    display: flex;
    align-items: center;
    gap: 12px;
    min-height: 76px;
    width: 100%;
    padding: 14px;
    border: 1px solid var(--se-line);
    border-radius: 13px;
    background: #fff;
    color: var(--se-ink);
    text-align: left;
    cursor: pointer;
    transition: border-color .18s, background .18s, box-shadow .18s;
}
.se-payment-option:hover {
    border-color: #b8adff;
    background: #fcfbff;
}
.se-payment-option.is-selected {
    border-color: var(--se-primary);
    background: #f7f5ff;
    box-shadow: 0 0 0 2px rgba(91, 69, 224, .09);
}
.se-payment-option:focus-visible {
    outline: 3px solid rgba(91, 69, 224, .22);
    outline-offset: 2px;
}
.se-payment-icon {
    flex: 0 0 42px;
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 11px;
    background: #f0edff;
    color: var(--se-primary);
    font-size: 17px;
}
.se-payment-copy {
    min-width: 0;
    flex: 1;
}
.se-payment-name {
    display: block;
    font-size: 13px;
    font-weight: 850;
    margin-bottom: 4px;
}
.se-payment-desc {
    display: block;
    color: var(--se-muted);
    font-size: 11px;
    line-height: 1.4;
}
.se-payment-check {
    flex: 0 0 18px;
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #cbd2df;
    border-radius: 50%;
    color: transparent;
    font-size: 10px;
}
.se-payment-option.is-selected .se-payment-check {
    border-color: var(--se-primary);
    background: var(--se-primary);
    color: #fff;
}
.se-primary-btn {
    display: flex;
    width: 100%;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 51px;
    padding: 13px 18px;
    border: 0;
    border-radius: 11px;
    background: var(--se-primary);
    color: #fff !important;
    text-decoration: none;
    font-size: 14px;
    font-weight: 850;
    cursor: pointer;
    transition: background .2s, transform .2s;
}
.se-primary-btn:hover {
    background: var(--se-primary-dark);
    transform: translateY(-1px);
}
.se-primary-btn:disabled {
    opacity: .5;
    cursor: not-allowed;
    transform: none;
}
.se-secondary-btn {
    display: flex;
    width: 100%;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 45px;
    padding: 11px 16px;
    border: 1px solid var(--se-line);
    border-radius: 11px;
    background: #fff;
    color: var(--se-ink) !important;
    text-decoration: none;
    font-size: 13px;
    font-weight: 850;
    margin-top: 10px;
}
.se-secondary-btn:hover {
    background: #f7f8fc;
}
.se-security {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    margin-top: 16px;
    color: var(--se-muted);
    font-size: 11px;
    line-height: 1.6;
}
.se-security i {
    color: #198754;
    margin-top: 2px;
}
.se-selection-message {
    display: none;
    margin: 13px 0 0;
    padding: 10px 12px;
    border-radius: 9px;
    background: #fff5e6;
    color: #85570c;
    font-size: 12px;
}
.se-selection-message.show {
    display: block;
}
.se-alert {
    border-radius: 11px;
    font-size: 13px;
}
@media (max-width: 991px) {
    .se-summary {
        position: static;
    }
    .se-checkout-header {
        align-items: flex-start;
        flex-direction: column;
    }
}
@media (max-width: 575px) {
    .se-checkout {
        padding: 28px 0 42px;
    }
    .se-panel-header,
    .se-panel-body {
        padding: 18px;
    }
    .se-event-image {
        height: 205px;
    }
    .se-detail-grid,
    .se-account-grid,
    .se-payment-options {
        grid-template-columns: 1fr;
    }
    .se-title {
        font-size: 28px;
    }
    .se-secure {
        white-space: normal;
    }
}
</style>
<section class="se-checkout">
    <div class="container">
        <div class="se-checkout-header">
            <div>
                <div class="se-eyebrow"><i class="fas fa-om"></i> Sanatan Events</div>
                <h1 class="se-title">Checkout</h1>
                <p class="se-subtitle">Review your booking and select your preferred payment method.</p>
            </div>
            <div class="se-secure"><i class="fas fa-shield-alt"></i> Secure checkout</div>
        </div>
        @if ($errors->any())
        <div class="alert alert-danger se-alert mb-4">
            <strong>Please review the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if (session('success'))
        <div class="alert alert-success se-alert mb-4">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger se-alert mb-4">{{ session('error') }}</div>
        @endif
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="se-panel">
                    <div class="se-panel-header">
                        <h2 class="se-panel-title"><span class="se-panel-icon"><i
                                    class="fas fa-calendar-check"></i></span> Selected event</h2>
                    </div>
                    <div class="se-panel-body">
                        <div class="se-event-cover">
                            @if ($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $eventName }}" class="se-event-image"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="se-event-placeholder" style="display:none;"><i class="fas fa-om"></i></div>
                            @else
                            <div class="se-event-placeholder"><i class="fas fa-om"></i></div>
                            @endif
                            <span class="se-event-badge"><i class="fas fa-check-circle"></i> Your selection</span>
                        </div>
                        <h3 class="se-event-name">{{ $eventName }}</h3>
                        <p class="se-event-description">
                            {{ $sanatan->short_description ?? 'Review the details of your selected spiritual event before payment.' }}
                        </p>
                        <div class="se-detail-grid">
                            <div class="se-detail">
                                <span class="se-detail-label">Program type</span>
                                <span class="se-detail-value">{{ $sanatan->program_type ?? 'Not specified' }}</span>
                            </div>
                            <div class="se-detail">
                                <span class="se-detail-label">Duration</span>
                                <span class="se-detail-value">{{ $sanatan->duration ?? 'Not specified' }}</span>
                            </div>
                            <div class="se-detail">
                                <span class="se-detail-label">Organizer</span>
                                <span class="se-detail-value">{{ $creatorName }}</span>
                            </div>
                            <div class="se-detail">
                                <span class="se-detail-label">Language</span>
                                <span
                                    class="se-detail-value">{{ count($languages) ? implode(', ', $languages) : 'Not specified' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="se-panel">
                    <div class="se-panel-header">
                        <h2 class="se-panel-title"><span class="se-panel-icon"><i class="fas fa-user"></i></span>
                            Customer details</h2>
                    </div>
                    <div class="se-panel-body">
                        <div class="se-account-grid">
                            <div>
                                <label class="se-field-label">Full name</label>
                                <div class="se-field-value"><i
                                        class="fas fa-user"></i>{{ $user->name ?? 'Not available' }}</div>
                            </div>
                            <div>
                                <label class="se-field-label">Email address</label>
                                <div class="se-field-value"><i
                                        class="fas fa-envelope"></i>{{ $user->email ?? 'Not available' }}</div>
                            </div>
                            @if (!empty($user->phone))
                            <div>
                                <label class="se-field-label">Phone number</label>
                                <div class="se-field-value"><i class="fas fa-phone-alt"></i>{{ $user->phone }}</div>
                            </div>
                            @endif
                        </div>
                        <p class="se-note"><i class="fas fa-info-circle me-1"></i> Customer information is taken from
                            your account profile.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <aside class="se-panel se-summary">
                    <div class="se-panel-header">
                        <h2 class="se-panel-title"><span class="se-panel-icon"><i class="fas fa-receipt"></i></span>
                            Order summary</h2>
                    </div>
                    <div class="se-panel-body">
                        <div class="se-summary-event">
                            @if ($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $eventName }}" class="se-thumb"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="se-thumb-placeholder" style="display:none;"><i class="fas fa-om"></i></div>
                            @else
                            <div class="se-thumb-placeholder"><i class="fas fa-om"></i></div>
                            @endif
                            <div>
                                <p class="se-summary-name">{{ $eventName }}</p>
                                <p class="se-muted-small">Sanatan event booking</p>
                            </div>
                        </div>
                        <div class="se-price-row">
                            <span>Event price</span>
                            <strong>₹{{ number_format($price, 2) }}</strong>
                        </div>
                        <div class="se-price-row">
                            <span>Quantity</span>
                            <strong>1</strong>
                        </div>
                        <div class="se-total">
                            <span class="se-total-label">Total payable</span>
                            <span class="se-total-amount">₹{{ number_format($price, 2) }}</span>
                        </div>
                        <p class="se-price-note">Review the event and total amount, then confirm your selected payment
                            method.</p>
                        <button type="button" class="se-primary-btn mt-3" id="confirm-booking-btn">
                            Confirm booking <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>
@endsection