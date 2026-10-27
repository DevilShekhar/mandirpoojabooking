@extends('frontend.layouts.app')
@section('title', $temple->name)
@section('meta_keyword', $temple->name . ', Temple, Hindu Temple, India Temple')
@section('meta_description', 'Explore ' . $temple->name . ' temple details, location and information.')
@section('content')

<style>
.temple-detail-page {
    --temple-red: #a80f17;
    --temple-dark-red: #7e0b11;
    --temple-gold: #c47c20;
    --temple-light-gold: #f4d49d;
    --temple-cream: #faf6ed;
    --temple-border: #eee2d2;
    --temple-text: #30251f;
    --temple-muted: #8c8177;
    background: #fbf8f2;
    color: var(--temple-text);
    overflow: hidden;
}

.temple-hero {
    position: relative;
    min-height: 510px;
    display: flex;
    align-items: center;
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
}

.temple-hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background:
        linear-gradient(90deg,
            rgba(20, 9, 5, .88) 0%,
            rgba(20, 9, 5, .62) 38%,
            rgba(20, 9, 5, .18) 72%,
            rgba(20, 9, 5, .05) 100%),
        linear-gradient(0deg,
            rgba(20, 9, 5, .45),
            transparent 60%);
}

.temple-hero::after {
    content: "";
    position: absolute;
    inset: 0;
    opacity: .16;
    background-image:
        radial-gradient(circle at 7% 28%,
            transparent 0,
            transparent 58px,
            rgba(236, 184, 88, .75) 59px,
            rgba(236, 184, 88, .75) 60px,
            transparent 61px),
        radial-gradient(circle at 94% 72%,
            transparent 0,
            transparent 95px,
            rgba(236, 184, 88, .5) 96px,
            rgba(236, 184, 88, .5) 97px,
            transparent 98px);
    pointer-events: none;
}

.temple-hero-content {
    position: relative;
    z-index: 2;
    width: 100%;
    padding: 80px 0 90px;
}

.temple-om-symbol {
    color: #f2bd5d;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 34px;
    line-height: 1;
    margin-bottom: 12px;
}

.temple-hero-eyebrow {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #f0c16b;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 3px;
    text-transform: uppercase;
    margin-bottom: 16px;
}

.temple-hero-eyebrow::before {
    content: "";
    width: 36px;
    height: 1px;
    background: #e4ad4d;
}

.temple-hero-title {
    max-width: 800px;
    color: #fff;
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(45px, 6vw, 76px);
    line-height: .98;
    letter-spacing: -2px;
    font-weight: 700;
    margin: 0 0 18px;
}

.temple-hero-location {
    display: flex;
    align-items: center;
    gap: 9px;
    color: rgba(255, 255, 255, .9);
    font-size: 15px;
}

.temple-hero-location i {
    color: #f1b94f;
    font-size: 18px;
}

.temple-main-section {
    position: relative;
    z-index: 5;
    margin-top: -35px;
    padding-bottom: 55px;
}

.temple-main-image-card {
    background: #fff;
    padding: 9px;
    border-radius: 17px;
    box-shadow: 0 20px 55px rgba(50, 29, 16, .12);
}

.temple-main-image {
    display: block;
    width: 100%;
    height: 515px;
    object-fit: cover;
    border-radius: 11px;
}

.temple-details-card {
    position: relative;
    height: 100%;
    background: #fff;
    border-radius: 17px;
    padding: 30px;
    box-shadow: 0 20px 55px rgba(50, 29, 16, .1);
    overflow: hidden;
}

.temple-details-card::after {
    content: "";
    position: absolute;
    width: 190px;
    height: 190px;
    right: -95px;
    top: 55px;
    border: 1px solid rgba(196, 124, 32, .16);
    border-radius: 50%;
}

.temple-details-card::before {
    content: "";
    position: absolute;
    width: 130px;
    height: 130px;
    right: -65px;
    top: 85px;
    border: 1px solid rgba(196, 124, 32, .1);
    border-radius: 50%;
}

.temple-details-header {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding-bottom: 17px;
    border-bottom: 1px solid var(--temple-border);
}

.temple-details-heading {
    display: flex;
    align-items: center;
    gap: 12px;
}

.temple-heading-icon {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 11px;
    background: #fff6e6;
    color: var(--temple-gold);
    font-size: 21px;
}

.temple-details-heading h2 {
    color: #25201c;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 29px;
    line-height: 1;
    margin: 0;
    font-weight: 700;
}

.temple-details-heading h2 span {
    color: var(--temple-red);
}

.temple-verified {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 10px;
    background: #e9f7ed;
    color: #16803a;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.temple-detail-list {
    position: relative;
    z-index: 2;
}

.temple-detail-item {
    display: flex;
    gap: 14px;
    padding: 15px 0;
    border-bottom: 1px solid #eee7de;
}

.temple-detail-icon {
    width: 40px;
    height: 40px;
    flex: 0 0 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #fff7e9;
    color: var(--temple-gold);
    font-size: 15px;
}

.temple-detail-content {
    width: 100%;
    min-width: 0;
}

.temple-detail-label {
    display: block;
    color: #999089;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 4px;
}

.temple-detail-value {
    display: block;
    color: #2e2722;
    font-size: 14px;
    line-height: 1.55;
    font-weight: 500;
}

.temple-coordinate-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.temple-coordinate {
    padding: 9px 11px;
    border: 1px solid #eee5d9;
    background: #faf8f4;
    border-radius: 8px;
}

.temple-coordinate small {
    display: block;
    color: #a29a92;
    font-size: 9px;
    text-transform: uppercase;
    letter-spacing: .7px;
    margin-bottom: 3px;
}

.temple-coordinate strong {
    display: block;
    color: #332923;
    font-size: 12px;
}

.temple-status-active {
    color: #198754;
}

.temple-status-inactive {
    color: #dc3545;
}

.temple-status-icon {
    font-size: 7px;
    vertical-align: middle;
}

.temple-map-button {
    position: relative;
    z-index: 3;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    width: 100%;
    margin-top: 17px;
    padding: 13px 16px;
    background: var(--temple-red);
    border-radius: 7px;
    color: #fff;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
    transition: all .3s ease;
}

.temple-map-button:hover {
    color: #fff;
    background: var(--temple-dark-red);
    transform: translateY(-2px);
}

.temple-heritage-section {
    position: relative;
    overflow: hidden;
    padding: 35px 0;
    background:
        linear-gradient(90deg,
            #8d0c13 0%,
            #ae151c 60%,
            #8b0d13 100%);
}

.temple-heritage-section::before {
    content: "";
    position: absolute;
    inset: 0;
    opacity: .13;
    background-image:
        radial-gradient(circle at 8% 50%,
            transparent 0,
            transparent 65px,
            #f4cf86 66px,
            #f4cf86 67px,
            transparent 68px),
        radial-gradient(circle at 18% 50%,
            transparent 0,
            transparent 45px,
            #f4cf86 46px,
            #f4cf86 47px,
            transparent 48px),
        radial-gradient(circle at 92% 50%,
            transparent 0,
            transparent 80px,
            #f4cf86 81px,
            #f4cf86 82px,
            transparent 83px);
}

.temple-heritage-content {
    position: relative;
    z-index: 2;
}

.temple-heritage-eyebrow {
    color: #f1c45e;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 7px;
}

.temple-heritage-title {
    color: #fff;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 29px;
    margin: 0 0 6px;
}

.temple-heritage-text {
    max-width: 620px;
    color: rgba(255, 255, 255, .83);
    font-size: 12px;
    line-height: 1.7;
    margin: 0;
}

.temple-heritage-features {
    display: flex;
    justify-content: flex-end;
    height: 100%;
}

.temple-heritage-feature {
    min-width: 115px;
    padding: 3px 20px;
    text-align: center;
    border-left: 1px solid rgba(255, 255, 255, .2);
}

.temple-heritage-feature-icon {
    display: block;
    color: #f2c45f;
    font-size: 23px;
    margin-bottom: 7px;
}

.temple-heritage-feature-text {
    display: block;
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    line-height: 1.35;
}

.temple-location-section {
    padding: 55px 0 65px;
    background: #fffdf9;
}

.temple-location-heading {
    display: flex;
    align-items: center;
    gap: 13px;
    margin-bottom: 18px;
}

.temple-location-heading-icon {
    width: 43px;
    height: 43px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 9px;
    background: #fff6e6;
    color: var(--temple-gold);
    font-size: 20px;
}

.temple-location-heading h2 {
    color: #28211c;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 29px;
    line-height: 1;
    margin: 0;
}

.temple-location-heading p {
    color: #91877f;
    font-size: 12px;
    margin: 5px 0 0;
}

.temple-map-wrapper {
    height: 340px;
    overflow: hidden;
    border: 1px solid #e8ded1;
    border-radius: 14px;
    box-shadow: 0 15px 45px rgba(47, 31, 20, .08);
}

.temple-map-wrapper iframe {
    width: 100%;
    height: 100%;
    border: 0;
}

.temple-no-map {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #faf7f2;
    text-align: center;
}

.temple-no-map-icon {
    color: var(--temple-gold);
    font-size: 35px;
    margin-bottom: 12px;
}

.temple-no-map h4 {
    color: #332923;
    margin-bottom: 5px;
}

.temple-no-map p {
    color: #91877f;
    font-size: 13px;
    margin: 0;
}

.temple-footer {
    padding: 28px 0;
    background: #fff;
    border-top: 1px solid #eee4d8;
}

.temple-footer-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.temple-back-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 20px;
    border: 1px solid #d9cec2;
    border-radius: 7px;
    background: #fff;
    color: #302823;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    transition: all .3s ease;
}

.temple-back-button:hover {
    color: var(--temple-red);
    border-color: var(--temple-red);
}

.temple-created {
    display: flex;
    align-items: center;
    gap: 11px;
}

.temple-created-icon {
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #fff6e5;
    color: var(--temple-gold);
}

.temple-created-label {
    display: block;
    color: #968d85;
    font-size: 10px;
    margin-bottom: 2px;
}

.temple-created-date {
    display: block;
    color: #332923;
    font-size: 12px;
    font-weight: 700;
}

@media (max-width: 991px) {
    .temple-hero {
        min-height: 470px;
    }

    .temple-hero-content {
        padding: 70px 0 70px;
    }

    .temple-main-section {
        margin-top: -20px;
    }

    .temple-details-card {
        margin-top: 25px;
    }

    .temple-heritage-features {
        justify-content: flex-start;
        margin-top: 25px;
    }

    .temple-heritage-feature:first-child {
        padding-left: 0;
        border-left: 0;
    }
}

@media (max-width: 767px) {
    .temple-hero {
        min-height: 410px;
    }

    .temple-hero-content {
        padding: 60px 0 55px;
    }

    .temple-hero-title {
        font-size: 43px;
        letter-spacing: -1px;
    }

    .temple-hero-location {
        font-size: 13px;
    }

    .temple-main-image {
        height: 360px;
    }

    .temple-details-card {
        padding: 24px 20px;
    }

    .temple-details-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .temple-details-heading h2 {
        font-size: 27px;
    }

    .temple-coordinate-grid {
        grid-template-columns: 1fr;
    }

    .temple-heritage-section {
        padding: 35px 0;
    }

    .temple-heritage-features {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        width: 100%;
    }

    .temple-heritage-feature {
        min-width: 0;
        padding: 5px 8px;
    }

    .temple-location-section {
        padding: 45px 0 50px;
    }

    .temple-map-wrapper {
        height: 290px;
    }

    .temple-footer-content {
        align-items: flex-start;
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .temple-hero-title {
        font-size: 37px;
    }

    .temple-main-image {
        height: 300px;
    }

    .temple-heritage-feature-icon {
        font-size: 19px;
    }

    .temple-heritage-feature-text {
        font-size: 9px;
    }

    .temple-location-heading h2 {
        font-size: 25px;
    }
}
.temple-puja-section {
    padding: 65px 0 70px;
    background: #fbf8f2;
    overflow: hidden;
}

.temple-puja-top {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 25px;
    margin-bottom: 30px;
}

.temple-puja-heading {
    display: flex;
    align-items: center;
    gap: 14px;
}

.temple-puja-heading-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 12px;
    background: #fff6e6;
    color: var(--temple-gold);
    font-size: 22px;
    box-shadow: 0 8px 20px rgba(196, 124, 32, .08);
}

.temple-puja-eyebrow {
    display: block;
    color: var(--temple-gold);
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 5px;
}

.temple-puja-heading h2 {
    color: #28211c;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 31px;
    line-height: 1.1;
    margin: 0;
    font-weight: 700;
}

.temple-puja-heading p {
    color: #91877f;
    font-size: 12px;
    margin: 6px 0 0;
}

.temple-puja-controls {
    display: flex;
    align-items: center;
    gap: 8px;
}

.temple-puja-controls button {
    width: 43px;
    height: 43px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e2d5c6;
    border-radius: 50%;
    background: #fff;
    color: #6f6258;
    font-size: 17px;
    cursor: pointer;
    transition: all .3s ease;
}

.temple-puja-controls button:hover {
    color: #fff;
    background: var(--temple-red);
    border-color: var(--temple-red);
    transform: translateY(-2px);
}

.temple-puja-slider {
    width: 100%;
    overflow: hidden;
}

.temple-puja-track {
    display: flex;
    gap: 22px;
    transition: transform .65s cubic-bezier(.4, 0, .2, 1);
    will-change: transform;
}

.temple-puja-slide {
    flex: 0 0 calc((100% - 44px) / 3);
    min-width: 0;
}

.temple-puja-card {
    height: 100%;
    overflow: hidden;
    background: #fff;
    border: 1px solid #eee2d2;
    border-radius: 17px;
    box-shadow: 0 15px 45px rgba(50, 29, 16, .08);
    transition: all .35s ease;
}

.temple-puja-card:hover {
    border-color: #dfc59e;
    box-shadow: 0 22px 55px rgba(50, 29, 16, .14);
    transform: translateY(-6px);
}

.temple-puja-image {
    position: relative;
    width: 100%;
    height: 235px;
    overflow: hidden;
    background: #fff6e6;
}

.temple-puja-image img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    transition: transform .6s ease;
}

.temple-puja-card:hover .temple-puja-image img {
    transform: scale(1.07);
}

.temple-puja-image-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--temple-gold);
    font-size: 52px;
}

.temple-puja-image-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        180deg,
        rgba(20, 9, 5, .05) 35%,
        rgba(20, 9, 5, .55) 100%
    );
    pointer-events: none;
}

.temple-puja-badge {
    position: absolute;
    left: 15px;
    bottom: 15px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 11px;
    border-radius: 20px;
    background: rgba(255, 255, 255, .94);
    color: var(--temple-red);
    font-size: 9px;
    font-weight: 700;
    letter-spacing: .4px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, .12);
}

.temple-puja-badge i {
    color: var(--temple-gold);
    font-size: 10px;
}

.temple-puja-card-content {
    padding: 22px;
}

.temple-puja-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 9px;
}

.temple-puja-title {
    color: #2e2722;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 21px;
    line-height: 1.3;
    font-weight: 700;
    margin: 0;
}

.temple-puja-code {
    flex-shrink: 0;
    padding: 5px 7px;
    border-radius: 5px;
    background: #faf6ed;
    color: #9a8978;
    font-size: 8px;
    font-weight: 700;
    letter-spacing: .5px;
}

.temple-puja-description {
    min-height: 40px;
    color: #8c8177;
    font-size: 12px;
    line-height: 1.7;
    margin: 0 0 18px;
}

.temple-puja-info {
    display: grid;
    grid-template-columns: 1fr;
    gap: 9px;
    padding: 14px 0;
    margin-bottom: 17px;
    border-top: 1px solid #eee7de;
    border-bottom: 1px solid #eee7de;
}

.temple-puja-info-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.temple-puja-info-icon {
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 8px;
    background: #fff7e9;
    color: var(--temple-gold);
    font-size: 13px;
}

.temple-puja-info-item div:last-child {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.temple-puja-info-item span {
    color: #999089;
    font-size: 8px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.temple-puja-info-item strong {
    color: #332923;
    font-size: 12px;
    font-weight: 700;
}

.temple-puja-button {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    width: 100%;
    padding: 13px 16px;
    background: var(--temple-red);
    border-radius: 8px;
    color: #fff;
    text-decoration: none;
    font-size: 11px;
    font-weight: 700;
    transition: all .3s ease;
}

.temple-puja-button:hover {
    color: #fff;
    background: var(--temple-dark-red);
    transform: translateY(-2px);
}

.temple-puja-button i {
    font-size: 14px;
    transition: transform .3s ease;
}

.temple-puja-button:hover i {
    transform: translateX(4px);
}

.temple-puja-slider-bottom {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    margin-top: 25px;
}

.temple-puja-dots {
    display: flex;
    align-items: center;
    gap: 6px;
}

.temple-puja-dot {
    width: 7px;
    height: 7px;
    padding: 0;
    border: 0;
    border-radius: 50%;
    background: #d8cabb;
    cursor: pointer;
    transition: all .3s ease;
}

.temple-puja-dot.active {
    width: 22px;
    border-radius: 10px;
    background: var(--temple-red);
}

.temple-puja-slide-text {
    color: #9b9086;
    font-size: 9px;
    letter-spacing: .3px;
}

.temple-no-puja {
    padding: 55px 20px;
    background: #fff;
    border: 1px solid #eee2d2;
    border-radius: 17px;
    text-align: center;
}

.temple-no-puja-icon {
    color: var(--temple-gold);
    font-size: 40px;
    margin-bottom: 12px;
}

.temple-no-puja h4 {
    color: #332923;
    font-family: Georgia, "Times New Roman", serif;
    margin-bottom: 6px;
}

.temple-no-puja p {
    color: #91877f;
    font-size: 13px;
    margin: 0;
}

@media (max-width: 991px) {
    .temple-puja-slide {
        flex: 0 0 calc((100% - 22px) / 2);
    }

    .temple-puja-image {
        height: 220px;
    }
}

@media (max-width: 767px) {
    .temple-puja-section {
        padding: 50px 0 55px;
    }

    .temple-puja-top {
        align-items: flex-start;
    }

    .temple-puja-heading h2 {
        font-size: 27px;
    }

    .temple-puja-controls {
        display: none;
    }

    .temple-puja-slide {
        flex: 0 0 100%;
    }

    .temple-puja-image {
        height: 230px;
    }

    .temple-puja-card-content {
        padding: 20px;
    }

    .temple-puja-slider-bottom {
        margin-top: 20px;
    }

    .temple-puja-slide-text {
        display: none;
    }
}

.ticket-booking-trigger {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    margin-top: 14px;
    padding: 14px 18px;
    border: 0;
    border-radius: 8px;
    background: #a80f17;
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: background .2s ease, transform .2s ease;
}
.ticket-booking-trigger:hover {
    background: #7e0b11;
    color: #fff;
    transform: translateY(-1px);
}
.ticket-modal {
    position: fixed;
    inset: 0;
    z-index: 99999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 18px;
}
.ticket-modal.is-open {
    display: flex;
}
.ticket-modal-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(20, 12, 8, .72);
}
.ticket-modal-dialog {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    width: min(1080px, 100%);
    height: calc(100vh - 36px);
    max-height: calc(100vh - 36px);
    min-height: 0;
    overflow: hidden;
    border-radius: 16px;
    background: #fffdf9;
    box-shadow: 0 25px 90px rgba(0, 0, 0, .3);
}
.ticket-modal-dialog > form {
    display: flex;
    flex: 1 1 auto;
    flex-direction: column;
    min-height: 0;
    overflow: hidden;
}
.ticket-modal-body {
    flex: 1 1 auto;
    min-height: 0;
    overflow-y: auto;
    overflow-x: hidden;
    overscroll-behavior: contain;
    -webkit-overflow-scrolling: touch;
    scrollbar-gutter: stable;
}

.ticket-modal-header {
    position: relative;
    flex: 0 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 22px 28px;
    color: #fff;
    background: linear-gradient(120deg, #8f0c13, #b3131b);
}
.ticket-modal-header h2 {
    margin: 0;
    color: #fff;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 25px;
}
.ticket-modal-header p {
    margin: 5px 0 0;
    color: rgba(255, 255, 255, .8);
    font-size: 12px;
}
.ticket-modal-close {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    flex: 0 0 38px;
    border: 1px solid rgba(255,255,255,.35);
    border-radius: 50%;
    background: transparent;
    color: #fff;
    font-size: 23px;
    cursor: pointer;
}
.ticket-modal-body {
    flex: 1 1 auto;
    min-height: 0;
    overflow-y: auto;
    overflow-x: hidden;
    overscroll-behavior: contain;
    scrollbar-gutter: stable;
    padding: 26px 28px;
    scroll-behavior: smooth;
}
.ticket-booking-top-fields {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    gap: 18px;
    padding-bottom: 24px;
    border-bottom: 1px solid #eee2d2;
}
.ticket-field {
    min-width: 0;
}
.ticket-field label {
    display: block;
    margin-bottom: 7px;
    color: #44352b;
    font-size: 12px;
    font-weight: 700;
}
.ticket-field label span {
    color: #a80f17;
}
.ticket-field input,
.ticket-field select {
    display: block;
    width: 100%;
    min-height: 44px;
    padding: 10px 12px;
    border: 1px solid #ded2c4;
    border-radius: 7px;
    background: #fff;
    color: #30251f;
    font-size: 13px;
}
.ticket-field input:focus,
.ticket-field select:focus {
    outline: 0;
    border-color: #a80f17;
    box-shadow: 0 0 0 3px rgba(168, 15, 23, .08);
}
.ticket-member-list {
    display: grid;
    gap: 18px;
    padding-top: 24px;
}
.ticket-member-card {
    padding: 22px;
    border: 1px solid #e9dfd3;
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 5px 18px rgba(58, 36, 19, .035);
}
.ticket-member-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 14px;
    border-bottom: 1px solid #f0e8df;
}
.ticket-member-card-header h3 {
    margin: 0;
    color: #332923;
    font-size: 16px;
    font-weight: 700;
}
.ticket-member-header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}
.ticket-member-remove {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    min-height: 32px;
    padding: 6px 10px;
    border: 1px solid #f0c9c9;
    border-radius: 7px;
    background: #fff7f7;
    color: #a80f17;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
}
.ticket-member-remove:hover {
    background: #a80f17;
    border-color: #a80f17;
    color: #fff;
}
.ticket-member-price {
    color: #a80f17;
    font-size: 13px;
    font-weight: 800;
    white-space: nowrap;
}
.ticket-member-fields {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
}
.ticket-member-fields .ticket-field-full {
    grid-column: 1 / -1;
}
.ticket-price-note {
    margin: 6px 0 0;
    color: #8c8177;
    font-size: 11px;
}
.ticket-modal-footer {
    position: relative;
    flex: 0 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 18px 28px;
    border-top: 1px solid #eee2d2;
    background: #fff;
}
.ticket-total-label {
    display: block;
    color: #8c8177;
    font-size: 11px;
}
.ticket-total-amount {
    display: block;
    margin-top: 3px;
    color: #a80f17;
    font-size: 23px;
    font-weight: 800;
}
.ticket-modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}
.ticket-modal-actions button {
    min-height: 43px;
    padding: 11px 18px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}
.ticket-cancel-button {
    border: 1px solid #d9cec2;
    background: #fff;
    color: #44352b;
}
.ticket-continue-button {
    border: 1px solid #a80f17;
    background: #a80f17;
    color: #fff;
}
.ticket-continue-button:hover {
    background: #7e0b11;
}
.ticket-form-message {
    display: none;
    margin-top: 14px;
    padding: 12px 14px;
    border-radius: 7px;
    background: #fff3cd;
    color: #664d03;
    font-size: 12px;
}
.ticket-form-message.is-visible {
    display: block;
}
body.ticket-modal-open {
    overflow: hidden;
}
@media (max-width: 600px) {
    .ticket-modal {
        padding: 8px;
    }
    .ticket-modal-dialog {
        height: calc(100vh - 16px);
        max-height: calc(100vh - 16px);
        border-radius: 12px;
    }
    .ticket-modal-header,
    .ticket-modal-body,
    .ticket-modal-footer {
        padding: 16px;
    }
    .ticket-modal-header h2 {
        font-size: 21px;
    }
    .ticket-booking-top-fields,
    .ticket-member-fields {
        grid-template-columns: 1fr;
    }
    .ticket-member-fields .ticket-field-full {
        grid-column: auto;
    }
    .ticket-modal-footer {
        align-items: flex-start;
        flex-direction: column;
    }
    .ticket-modal-actions {
        width: 100%;
    }
    .ticket-modal-actions button {
        flex: 1;
    }
}


.ticket-modal-dialog {
    width: min(1500px, 100%);
}
.ticket-member-list {
    gap: 10px;
    padding-top: 16px;
}
.ticket-member-card {
    padding: 14px;
    border-radius: 10px;
    box-shadow: none;
}
.ticket-member-card-header {
    margin-bottom: 12px;
    padding-bottom: 10px;
}
.ticket-member-fields {
    display: grid;
    grid-template-columns: minmax(150px, 1.3fr) minmax(135px, 1fr) minmax(170px, 1.25fr) minmax(105px, .8fr) minmax(135px, 1fr) minmax(100px, .7fr) auto;
    align-items: end;
    gap: 10px;
}
.ticket-member-fields .ticket-field-full {
    grid-column: auto;
}
.ticket-member-fields .ticket-field label {
    font-size: 11px;
    margin-bottom: 5px;
    white-space: nowrap;
}
.ticket-member-fields .ticket-field input,
.ticket-member-fields .ticket-field select {
    min-height: 40px;
    padding: 8px 9px;
    font-size: 12px;
}
.ticket-member-fields .ticket-field input[type="file"] {
    padding: 6px;
    font-size: 11px;
}
.ticket-member-fields .ticket-member-action {
    display: flex;
    align-items: end;
}
.ticket-member-fields .ticket-member-action button {
    min-height: 40px;
    white-space: nowrap;
}
.ticket-member-card-header h3 {
    font-size: 14px;
}
.ticket-member-price {
    font-size: 12px;
}
.ticket-add-member-row {
    display: flex;
    justify-content: flex-end;
    margin-top: 12px;
}
.ticket-add-member-button {
    min-height: 40px;
    padding: 9px 16px;
    border: 1px solid #a80f17;
    border-radius: 7px;
    background: #fff;
    color: #a80f17;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}
.ticket-add-member-button:hover {
    background: #a80f17;
    color: #fff;
}
@media (max-width: 1100px) {
    .ticket-modal-dialog { width: min(100%, 980px); }
    .ticket-member-fields {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
    .ticket-member-fields .ticket-member-action { align-items: end; }
}
@media (max-width: 600px) {
    .ticket-member-fields { grid-template-columns: 1fr; }
}


.ticket-member-count-label {
    color: #76685d;
    font-size: 13px;
}
.ticket-member-count-label strong {
    color: var(--temple-red);
}
@media (max-width: 767px) {
    .ticket-booking-top-fields {
        grid-template-columns: minmax(0, 1fr);
    }
}
</style>

<div class="temple-detail-page">
    <section class="temple-hero"  style="background-image:url('{{ $temple->banner_image ? asset('storage/' . $temple->banner_image) : asset('assets/frontend/assets/images/temple-placeholder.jpg') }}');">
        <div class="container">
            <div class="temple-hero-content">
                <div class="temple-hero-eyebrow">
                    A Divine Destination
                </div>
                <h1 class="temple-hero-title">
                    {{ $temple->name }}
                </h1>
                <div class="temple-hero-location">
                    <i class="fa fa-map-marker"></i>
                    <span>
                        {{ $temple->city->name ?? '' }}
                        @if($temple->district)
                        , {{ $temple->district->name }}
                        @endif
                        @if($temple->state)
                        , {{ $temple->state->name }}
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </section>
    <section class="temple-main-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-12">
                    <div class="temple-main-image-card">
                        <img src="{{ $temple->main_image ? asset('storage/' . $temple->main_image) : asset('assets/frontend/assets/images/temple-placeholder.jpg') }}"
                            alt="{{ $temple->name }}" class="temple-main-image">
                    </div>
                </div>
                <div class="col-lg-5 col-md-12">
                    <div class="temple-details-card">
                        <div class="temple-details-header">
                            <div class="temple-details-heading">
                                <div class="temple-heading-icon">
                                    <i class="fa fa-university"></i>
                                </div>
                                <h2>
                                    Temple <span>Details</span>
                                </h2>
                            </div>
                            @if($temple->is_verified)
                            <span class="temple-verified">
                                <i class="fa fa-check-circle"></i>
                                Verified Temple
                            </span>
                            @endif
                        </div>
                        <div class="temple-detail-list">
                            <div class="temple-detail-item">
                                <div class="temple-detail-icon">
                                    <i class="fa fa-qrcode"></i>
                                </div>
                                <div class="temple-detail-content">
                                    <span class="temple-detail-label">
                                        Temple Code
                                    </span>
                                    <span class="temple-detail-value">
                                        {{ $temple->temple_code ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                            <div class="temple-detail-item">
                                <div class="temple-detail-icon">
                                    <i class="fa fa-map-marker"></i>
                                </div>
                                <div class="temple-detail-content">
                                    <span class="temple-detail-label">
                                        Location
                                    </span>
                                    <span class="temple-detail-value">
                                        {{ $temple->city->name ?? 'N/A' }}
                                        @if($temple->district)
                                        , {{ $temple->district->name }}
                                        @endif
                                        @if($temple->state)
                                        , {{ $temple->state->name }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="temple-detail-item">
                                <div class="temple-detail-icon">
                                    <i class="fa fa-home"></i>
                                </div>
                                <div class="temple-detail-content">
                                    <span class="temple-detail-label">
                                        Address
                                    </span>
                                    <span class="temple-detail-value">
                                        {{ $temple->address ?? 'Address not available' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if($temple->latitude && $temple->longitude)
                        <a href="https://www.google.com/maps?q={{ $temple->latitude }},{{ $temple->longitude }}"
                            target="_blank" class="temple-map-button">
                            <i class="fa fa-map"></i>
                            <span>
                                View Location on Google Maps
                            </span>
                            <i class="fa fa-arrow-right"></i>
                        </a>
                        @endif

                        <button type="button" class="ticket-booking-trigger" id="openTicketBooking">
                            <i class="fa fa-ticket"></i>
                            <span>Book Temple Tickets</span>
                            <i class="fa fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

<div class="ticket-modal" id="ticketBookingModal" aria-hidden="true">
    <div class="ticket-modal-backdrop" data-close-ticket-modal></div>
    <div class="ticket-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="ticketBookingTitle">
        <div class="ticket-modal-header">
            <div>
                <h2 id="ticketBookingTitle">Book Tickets</h2>
                <p>{{ $temple->name }} · Add visitor details to continue</p>
            </div>
            <button type="button" class="ticket-modal-close" aria-label="Close popup" data-close-ticket-modal>&times;</button>
        </div>
        <form
            id="ticketBookingForm"
            action="{{ route('frontend.ticket-cart.store', $temple->id) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            <div class="ticket-modal-body">
                <div class="ticket-booking-top-fields">
                    <div class="ticket-field">
                        <label for="bookingDate">Visit Date <span>*</span></label>
                        <input type="date" id="bookingDate" name="booking_date" required>
                    </div>
                    <input type="hidden" id="memberCount" name="member_count" value="1">
                </div>
                <div class="ticket-member-list" id="ticketMemberList"></div>
                <div class="ticket-add-member-row">
                    <span class="ticket-member-count-label"><strong id="ticketMemberCountDisplay">1</strong> member added</span>
                    <button type="button" class="ticket-add-member-button" id="addTicketMember"><i class="fa fa-plus"></i> Add Member</button>
                </div>
                <div class="ticket-form-message" id="ticketFormMessage" role="status"></div>
            </div>
            <div class="ticket-modal-footer">
                <div>
                    <span class="ticket-total-label">Estimated Total</span>
                    <strong class="ticket-total-amount" id="ticketTotalAmount">₹0.00</strong>
                </div>
                <div class="ticket-modal-actions">
                    <button type="button" class="ticket-cancel-button" data-close-ticket-modal>Cancel</button>
                    <button type="submit" class="ticket-continue-button">Continue</button>
                </div>
            </div>
        </form>
    </div>
</div>

    <section class="temple-heritage-section">
        <div class="container">
            <div class="temple-heritage-content">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-12">
                        <div class="temple-heritage-eyebrow">
                            A Sacred Landmark
                        </div>
                        <h2 class="temple-heritage-title">
                            {{ $temple->name }}
                        </h2>
                        <p class="temple-heritage-text">
                            Explore the temple location and essential temple information
                            through our devotional platform.
                        </p>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="temple-heritage-features">
                            <div class="temple-heritage-feature">
                                <i class="fa fa-university temple-heritage-feature-icon"></i>
                                <span class="temple-heritage-feature-text">
                                    Spiritual
                                    <br>
                                    Heritage
                                </span>
                            </div>
                            <div class="temple-heritage-feature">
                                <i class="fa fa-star temple-heritage-feature-icon"></i>
                                <span class="temple-heritage-feature-text">
                                    Divine
                                    <br>
                                    Destination
                                </span>
                            </div>
                            <div class="temple-heritage-feature">
                                <i class="fa fa-map-marker temple-heritage-feature-icon"></i>
                                <span class="temple-heritage-feature-text">
                                    Pilgrimage
                                    <br>
                                    Destination
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="temple-location-section">
        <div class="container">
            <div class="temple-location-heading">
                <div class="temple-location-heading-icon">
                   <i class="fa fa-university"></i>
                </div>
                <div>
                    <h2>
                        Temple Location
                    </h2>
                    <p>
                        Find the exact location of {{ $temple->name }} on the map.
                    </p>
                </div>
            </div>
            @if($temple->latitude && $temple->longitude)
            <div class="temple-map-wrapper">
                <iframe
                    src="https://www.google.com/maps?q={{ $temple->latitude }},{{ $temple->longitude }}&output=embed"
                    loading="lazy" allowfullscreen>
                </iframe>
            </div>
            @else
            <div class="temple-map-wrapper">
                <div class="temple-no-map">
                    <div>
                        <div class="temple-no-map-icon">
                            <i class="fa fa-map-marker"></i>
                        </div>
                        <h4>
                            Location Not Available
                        </h4>
                        <p>
                            Temple coordinates have not been added yet.
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
    <section class="temple-puja-section">
    <div class="container">
        <div class="temple-puja-top">
            <div class="temple-puja-heading">
                <div class="temple-puja-heading-icon">
                    <i class="fa fa-om"></i>
                </div>

                <div>
                    <span class="temple-puja-eyebrow">Divine Services</span>
                    <h2>Temple Pujas</h2>
                    <p>
                        Discover sacred pujas available at {{ $temple->name }}.
                    </p>
                </div>
            </div>

            @if($temple->pujas->count() > 3)
                <div class="temple-puja-controls">
                    <button type="button" class="temple-puja-prev">
                        <i class="fa fa-angle-left"></i>
                    </button>

                    <button type="button" class="temple-puja-next">
                        <i class="fa fa-angle-right"></i>
                    </button>
                </div>
            @endif
        </div>

        @if($temple->pujas->count())
            <div class="temple-puja-slider">
                <div class="temple-puja-track">
                    @foreach($temple->pujas as $puja)
                        <div class="temple-puja-slide">
                            <div class="temple-puja-card">
                                <div class="temple-puja-image">
                                    @if($puja->image)
                                        <img src="{{ asset('storage/' . $puja->image) }}"
                                             alt="{{ $puja->name }}">
                                    @else
                                        <div class="temple-puja-image-placeholder">
                                            <i class="fa fa-om"></i>
                                        </div>
                                    @endif

                                    <div class="temple-puja-image-overlay"></div>

                                    <div class="temple-puja-badge">
                                        <i class="fa fa-star"></i>
                                        Sacred Puja
                                    </div>
                                </div>

                                <div class="temple-puja-card-content">
                                    <div class="temple-puja-card-header">
                                        <h3 class="temple-puja-title">
                                            {{ $puja->name }}
                                        </h3>

                                        @if($puja->puja_code)
                                            <span class="temple-puja-code">
                                                {{ $puja->puja_code }}
                                            </span>
                                        @endif
                                    </div>

                                    @if($puja->description)
                                        <p class="temple-puja-description">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($puja->description), 105) }}
                                        </p>
                                    @endif

                                    <div class="temple-puja-info">
                                        <div class="temple-puja-info-item">
                                            <div class="temple-puja-info-icon">
                                                <i class="fa fa-user"></i>
                                            </div>

                                            <div>
                                                <span>Pandit</span>
                                                <strong>
                                                    {{ $puja->creator->name ?? 'N/A' }}
                                                </strong>
                                            </div>
                                        </div>

                                        @if($puja->duration)
                                            <div class="temple-puja-info-item">
                                                <div class="temple-puja-info-icon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>

                                                <div>
                                                    <span>Duration</span>
                                                    <strong>
                                                        {{ $puja->duration }}
                                                    </strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <a href="{{ route('frontend.puja.show', $puja->slug) }}"
                                       class="temple-puja-button">
                                        <span>View Puja Details</span>
                                        <i class="fa fa-long-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($temple->pujas->count() > 3)
                <div class="temple-puja-slider-bottom">
                    <div class="temple-puja-dots"></div>

                    <span class="temple-puja-slide-text">
                        Swipe to explore more sacred pujas
                    </span>
                </div>
            @endif
        @else
            <div class="temple-no-puja">
                <div class="temple-no-puja-icon">
                    <i class="fa fa-om"></i>
                </div>

                <h4>No Pujas Available</h4>

                <p>
                    There are currently no pujas available for this temple.
                </p>
            </div>
        @endif
    </div>
</section>
    <footer class="temple-footer">
        <div class="container">
            <div class="temple-footer-content">
                <a href="{{ route('frontend.our-temples') }}" class="temple-back-button">
                    <i class="fa fa-arrow-left"></i>
                    Back to Our Temples
                </a>
                <div class="temple-created">
                    <div class="temple-created-icon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <div>
                        <span class="temple-created-label">
                            Temple registered on
                        </span>
                        <span class="temple-created-date">
                            {{ $temple->created_at ? $temple->created_at->format('d M Y') : 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('ticketBookingModal');
    const openButton = document.getElementById('openTicketBooking');
    const form = document.getElementById('ticketBookingForm');
    const memberCount = document.getElementById('memberCount');
    const memberList = document.getElementById('ticketMemberList');
    const bookingDate = document.getElementById('bookingDate');
    if (bookingDate) bookingDate.min = new Date().toISOString().split('T')[0];
    const totalAmount = document.getElementById('ticketTotalAmount');
    const message = document.getElementById('ticketFormMessage');

    if (!modal || !openButton || !form || !memberCount || !memberList) {
        return;
    }

    const ticketPrices = @json(($ticketPrices ?? collect())->values());
    function normalizeValue(value) {
        return String(value ?? '').trim().toLowerCase().replace(/[_-]+/g, ' ').replace(/\s+/g, ' ');
    }

    const prices = (Array.isArray(ticketPrices) ? ticketPrices : Object.values(ticketPrices || {})).map(function (price) {
        return {
            id: price.id,
            ticket_type: normalizeValue(price.ticket_type),
            type: normalizeValue(price.type),
            min_age: Number(price.min_age ?? 0),
            max_age: price.max_age === null || price.max_age === '' || typeof price.max_age === 'undefined'
                ? 120
                : Number(price.max_age),
            price: Number(String(price.price ?? 0).replace(/,/g, '')),
            status: price.status
        };
    }).filter(function (price) {
        return Number.isFinite(price.price) && price.price >= 0 &&
            (!('status' in price) || price.status === true || price.status === 1 || price.status === '1');
    });

    const visitorTypes = ['Adult', 'Child', 'Senior Citizen'];
    const ticketTypes = ['normal', 'vip'];

    function openModal() {
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('ticket-modal-open');
        renderMembers();
    }

    function closeModal() {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('ticket-modal-open');
        message.classList.remove('is-visible');
    }

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (character) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[character];
        });
    }

    function getMemberPrice(ticketType, visitorType, age) {
        if (age === '' || age === null || typeof age === 'undefined') {
            return null;
        }

        const selectedAge = Number(age);
        const matched = prices.find(function (price) {
            return price.ticket_type === normalizeValue(ticketType) &&
                price.type === normalizeValue(visitorType) &&
                selectedAge >= price.min_age &&
                selectedAge <= price.max_age;
        });

        return matched || null;
    }

    function getAgeOptions(ticketType, visitorType, selectedAge) {
        const applicable = prices.filter(function (price) {
            return price.ticket_type === normalizeValue(ticketType) &&
                price.type === normalizeValue(visitorType);
        });
        const ages = new Set();

        applicable.forEach(function (price) {
            const min = Math.max(0, price.min_age);
            const max = Math.min(120, price.max_age);
            for (let age = min; age <= max; age++) {
                ages.add(age);
            }
        });

        const sortedAges = Array.from(ages).sort(function (a, b) {
            return a - b;
        });

        let options = '<option value="">Select age</option>';
        sortedAges.forEach(function (age) {
            options += '<option value="' + age + '"' + (String(age) === String(selectedAge) ? ' selected' : '') + '>' + age + ' years</option>';
        });

        if (sortedAges.length === 0) {
            options += '<option value="" disabled>No age range configured</option>';
        }

        return options;
    }

    function renderMembers() {
        const count = Number(memberCount.value) || 1;
        const previous = Array.from(memberList.querySelectorAll('.ticket-member-card')).map(function (card) {
            return {
                name: card.querySelector('[data-field="name"]')?.value || '',
                mobile: card.querySelector('[data-field="mobile"]')?.value || '',
                aadhaar: card.querySelector('[data-field="aadhaar"]')?.value || '',
                ticketType: card.querySelector('[data-field="ticket_type"]')?.value || 'normal',
                visitorType: card.querySelector('[data-field="visitor_type"]')?.value || 'Adult',
                age: card.querySelector('[data-field="age"]')?.value || ''
            };
        });

        memberList.innerHTML = '';

        for (let index = 0; index < count; index++) {
            const old = previous[index] || {
                name: '',
                mobile: '',
                aadhaar: '',
                ticketType: 'normal',
                visitorType: 'Adult',
                age: ''
            };

            const card = document.createElement('section');
            card.className = 'ticket-member-card';
            card.dataset.memberIndex = index;
            card.innerHTML = `
                <div class="ticket-member-card-header">
                    <h3>Member ${index + 1}</h3>
                    <div class="ticket-member-header-actions">
                        <span class="ticket-member-price" data-member-price>Price: ₹0.00</span><input type="hidden" data-price-display value="Select ticket details">
                        <button type="button" class="ticket-member-remove" data-remove-member aria-label="Remove member ${index + 1}">
                            <i class="fa fa-trash"></i><span>Remove</span>
                        </button>
                    </div>
                </div>
                <div class="ticket-member-fields">
                    <div class="ticket-field">
                        <label for="member_${index}_name">Full Name <span>*</span></label>
                        <input id="member_${index}_name" data-field="name" name="members[${index}][name]" type="text" maxlength="150" value="${escapeHtml(old.name)}" autocomplete="name" required>
                    </div>
                    <div class="ticket-field">
                        <label for="member_${index}_aadhaar">Aadhaar Number <span>*</span></label>
                        <input id="member_${index}_aadhaar" data-field="aadhaar" name="members[${index}][aadhaar_number]" type="text" inputmode="numeric" pattern="[0-9]{12}" maxlength="12" minlength="12" value="${escapeHtml(old.aadhaar)}" required>
                    </div>
                    <div class="ticket-field">
                        <label for="member_${index}_aadhaar_photo">Aadhaar Photo <span>*</span></label>
                        <input id="member_${index}_aadhaar_photo" name="members[${index}][aadhaar_photo]" type="file" accept="image/jpeg,image/png,image/webp" required>
                    </div>
                    <div class="ticket-field">
                        <label for="member_${index}_ticket_type">Ticket Type <span>*</span></label>
                        <select id="member_${index}_ticket_type" data-field="ticket_type" name="members[${index}][ticket_type]" required>
                            ${ticketTypes.map(function (type) {
                                return '<option value="' + type + '"' + (old.ticketType === type ? ' selected' : '') + '>' + type.toUpperCase() + '</option>';
                            }).join('')}
                        </select>
                    </div>
                    <div class="ticket-field">
                        <label for="member_${index}_visitor_type">Visitor Type <span>*</span></label>
                        <select id="member_${index}_visitor_type" data-field="visitor_type" name="members[${index}][type]" required>
                            ${visitorTypes.map(function (type) {
                                return '<option value="' + type + '"' + (old.visitorType === type ? ' selected' : '') + '>' + type + '</option>';
                            }).join('')}
                        </select>
                    </div>
                    <div class="ticket-field">
                        <label for="member_${index}_age">Age <span>*</span></label>
                        <select id="member_${index}_age" data-field="age" name="members[${index}][age]" required>
                            ${getAgeOptions(old.ticketType, old.visitorType, old.age)}
                        </select>
                    </div>
                    <div class="ticket-member-action">
                        <button type="button" class="ticket-member-remove" data-remove-member aria-label="Remove member ${index + 1}">
                            <i class="fa fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            `;

            memberList.appendChild(card);
            updateMemberCard(card, old.age);
        }

        const countDisplay = document.getElementById('ticketMemberCountDisplay');
        if (countDisplay) countDisplay.textContent = String(count);
        updateTotal();
    }

    function updateMemberCard(card, previousAge) {
        const ticketType = card.querySelector('[data-field="ticket_type"]');
        const visitorType = card.querySelector('[data-field="visitor_type"]');
        const age = card.querySelector('[data-field="age"]');
        const priceLabel = card.querySelector('[data-member-price]');
        const priceDisplay = card.querySelector('[data-price-display]');
        const ageNote = card.querySelector('[data-age-note]');

        const currentAge = previousAge !== undefined ? previousAge : age.value;
        age.innerHTML = getAgeOptions(ticketType.value, visitorType.value, currentAge);

        const price = getMemberPrice(ticketType.value, visitorType.value, age.value);

        if (price) {
            priceLabel.textContent = 'Price: ₹' + (Number(price.price) || 0).toFixed(2);
            priceDisplay.value = '₹' + (Number(price.price) || 0).toFixed(2);
            if (ageNote) ageNote.textContent = 'Eligible age range: ' + price.min_age + '–' + (price.max_age >= 120 ? '120+' : price.max_age) + ' years';
        } else {
            priceLabel.textContent = 'Price: Not available';
            priceDisplay.value = 'No matching price';
            if (ageNote) ageNote.textContent = 'No active ticket price is configured for this selection.';
        }
        updateTotal();
    }


    function reindexMembers() {
        const cards = Array.from(memberList.querySelectorAll('.ticket-member-card'));
        cards.forEach(function (card, index) {
            card.dataset.memberIndex = index;
            const heading = card.querySelector('.ticket-member-card-header h3');
            if (heading) heading.textContent = 'Member ' + (index + 1);
            card.querySelectorAll('[id]').forEach(function (field) {
                const suffix = field.id.replace(/^member_\d+_/, '');
                if (suffix !== field.id) field.id = 'member_' + index + '_' + suffix;
            });
            card.querySelectorAll('[name]').forEach(function (field) {
                field.name = field.name.replace(/members\[\d+\]/, 'members[' + index + ']');
            });
            card.querySelectorAll('label[for]').forEach(function (label) {
                label.htmlFor = label.htmlFor.replace(/^member_\d+_/, 'member_' + index + '_');
            });
        });
        memberCount.value = String(Math.max(1, cards.length));
        const countDisplay = document.getElementById('ticketMemberCountDisplay');
        if (countDisplay) countDisplay.textContent = String(cards.length);
        Array.from(memberCount.options).forEach(function (option) {
            option.disabled = Number(option.value) > cards.length && cards.length < 1;
        });
        updateTotal();
    }

    function updateTotal() {
        let total = 0;

        memberList.querySelectorAll('.ticket-member-card').forEach(function (card) {
            const price = getMemberPrice(
                card.querySelector('[data-field="ticket_type"]').value,
                card.querySelector('[data-field="visitor_type"]').value,
                card.querySelector('[data-field="age"]').value
            );

            if (price) {
                total += Number(price.price) || 0;
            }
        });

        totalAmount.textContent = '₹' + total.toFixed(2);
    }

    openButton.addEventListener('click', openModal);
    modal.querySelectorAll('[data-close-ticket-modal]').forEach(function (element) {
        element.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal.classList.contains('is-open')) {
            closeModal();
        }
    });

    const addMemberButton = document.getElementById('addTicketMember');
    if (addMemberButton) {
        addMemberButton.addEventListener('click', function () {
            const currentCount = memberList.querySelectorAll('.ticket-member-card').length;
            if (currentCount >= 10) {
                message.textContent = 'You can add a maximum of 10 members per booking.';
                message.classList.add('is-visible');
                return;
            }
            memberCount.value = String(currentCount + 1);
            renderMembers();
            message.classList.remove('is-visible');

            requestAnimationFrame(function () {
                const newMember = memberList.querySelectorAll('.ticket-member-card')[currentCount];
                const scrollBody = modal.querySelector('.ticket-modal-body');

                if (newMember && scrollBody) {
                    const bodyRect = scrollBody.getBoundingClientRect();
                    const memberRect = newMember.getBoundingClientRect();
                    const targetScrollTop = scrollBody.scrollTop + memberRect.bottom - bodyRect.bottom + 24;

                    scrollBody.scrollTo({
                        top: Math.max(0, targetScrollTop),
                        behavior: 'smooth'
                    });

                    const nameInput = newMember.querySelector('[data-field="name"]');
                    if (nameInput) {
                        window.setTimeout(function () {
                            nameInput.focus({ preventScroll: true });
                        }, 250);
                    }
                }
            });
        });
    }

    memberList.addEventListener('click', function (event) {
        const removeButton = event.target.closest('[data-remove-member]');
        if (!removeButton) return;
        const cards = memberList.querySelectorAll('.ticket-member-card');
        if (cards.length <= 1) {
            message.textContent = 'At least one visitor is required for a ticket booking.';
            message.classList.add('is-visible');
            return;
        }
        removeButton.closest('.ticket-member-card').remove();
        message.classList.remove('is-visible');
        reindexMembers();
    });

    memberList.addEventListener('change', function (event) {
        const card = event.target.closest('.ticket-member-card');
        if (!card) {
            return;
        }

        if (event.target.matches('[data-field="ticket_type"], [data-field="visitor_type"]')) {
            updateMemberCard(card, '');
            return;
        }

        if (event.target.matches('[data-field="age"]')) {
            updateMemberCard(card, event.target.value);
        }
    });

    memberList.addEventListener('input', function (event) {
        if (event.target.matches('[data-field="ticket_type"], [data-field="visitor_type"], [data-field="age"]')) {
            const card = event.target.closest('.ticket-member-card');
            if (card) {
                updateMemberCard(card);
            }
        }
    });

    const today = new Date();
    const localDate = new Date(today.getTime() - today.getTimezoneOffset() * 60000).toISOString().split('T')[0];
    bookingDate.min = localDate;

    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        message.classList.remove('is-visible');

        if (!form.reportValidity()) {
            return;
        }

        const cards = Array.from(memberList.querySelectorAll('.ticket-member-card'));
        const invalidPrice = cards.some(function (card) {
            return !getMemberPrice(
                card.querySelector('[data-field="ticket_type"]').value,
                card.querySelector('[data-field="visitor_type"]').value,
                card.querySelector('[data-field="age"]').value
            );
        });

        if (invalidPrice) {
            message.textContent = 'A ticket price is not configured for one or more selected members. Please choose another category, ticket type, or age.';
            message.classList.add('is-visible');
            return;
        }

        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.textContent = 'Please wait...';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            const result = await response.json();

            if (!response.ok) {
                const errorMessage = result.errors
                    ? Object.values(result.errors).flat().join(' ')
                    : (result.message || 'Unable to add members to the cart.');
                throw new Error(errorMessage);
            }

            if (result.redirect_url) {
                window.location.href = result.redirect_url;
                return;
            }

            message.textContent = result.message || 'Members added successfully.';
            message.classList.add('is-visible');
        } catch (error) {
            message.textContent = error.message || 'Something went wrong. Please try again.';
            message.classList.add('is-visible');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = originalButtonText;
        }
    });

    renderMembers();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const slider = document.querySelector('.temple-puja-slider');
    const track = document.querySelector('.temple-puja-track');
    const slides = document.querySelectorAll('.temple-puja-slide');
    const prevButton = document.querySelector('.temple-puja-prev');
    const nextButton = document.querySelector('.temple-puja-next');
    const dotsContainer = document.querySelector('.temple-puja-dots');

    if (!slider || !track || slides.length <= 1) {
        return;
    }

    let currentIndex = 0;
    let autoSlide;

    function getSlidesPerView() {
        if (window.innerWidth <= 767) {
            return 1;
        }

        if (window.innerWidth <= 991) {
            return 2;
        }

        return 3;
    }

    function getMaxIndex() {
        return Math.max(0, slides.length - getSlidesPerView());
    }

    function updateSlider() {
        const slideWidth = slides[0].getBoundingClientRect().width;
        const gap = parseFloat(getComputedStyle(track).gap) || 0;

        currentIndex = Math.min(currentIndex, getMaxIndex());

        track.style.transform =
            `translateX(-${currentIndex * (slideWidth + gap)}px)`;

        updateDots();
    }

    function createDots() {
        if (!dotsContainer || slides.length <= getSlidesPerView()) {
            return;
        }

        dotsContainer.innerHTML = '';

        const totalDots = getMaxIndex() + 1;

        for (let i = 0; i < totalDots; i++) {
            const dot = document.createElement('button');

            dot.type = 'button';
            dot.className = 'temple-puja-dot';
            dot.setAttribute('aria-label', `Go to puja slide ${i + 1}`);

            dot.addEventListener('click', function () {
                currentIndex = i;
                updateSlider();
                restartAutoSlide();
            });

            dotsContainer.appendChild(dot);
        }

        updateDots();
    }

    function updateDots() {
        if (!dotsContainer) {
            return;
        }

        const dots = dotsContainer.querySelectorAll('.temple-puja-dot');

        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });
    }

    function nextSlide() {
        const maxIndex = getMaxIndex();

        if (currentIndex >= maxIndex) {
            currentIndex = 0;
        } else {
            currentIndex++;
        }

        updateSlider();
    }

    function previousSlide() {
        const maxIndex = getMaxIndex();

        if (currentIndex <= 0) {
            currentIndex = maxIndex;
        } else {
            currentIndex--;
        }

        updateSlider();
    }

    function startAutoSlide() {
        if (getMaxIndex() <= 0) {
            return;
        }

        autoSlide = setInterval(nextSlide, 4000);
    }

    function stopAutoSlide() {
        clearInterval(autoSlide);
    }

    function restartAutoSlide() {
        stopAutoSlide();
        startAutoSlide();
    }

    if (prevButton) {
        prevButton.addEventListener('click', function () {
            previousSlide();
            restartAutoSlide();
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', function () {
            nextSlide();
            restartAutoSlide();
        });
    }

    slider.addEventListener('mouseenter', stopAutoSlide);
    slider.addEventListener('mouseleave', startAutoSlide);

    let resizeTimer;

    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);

        resizeTimer = setTimeout(function () {
            createDots();
            updateSlider();
        }, 150);
    });

    createDots();
    updateSlider();
    startAutoSlide();
});
</script>
@endsection