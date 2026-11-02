@can('puja-review-rating')

@extends('admin.layouts.app')
@section('content')
<section class="puja-review-section py-5">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="puja-review-header">
                    <div>
                        <h2 class="puja-review-title">Puja Reviews & Ratings</h2>
                        <p class="puja-review-subtitle">
                            Manage devotee reviews and ratings for your sacred pujas.
                        </p>
                    </div>
                    <div class="puja-review-total">
                        <i class="fa fa-comments"></i>
                        <span>{{ $reviews->total() }}</span>
                        {{ $reviews->total() == 1 ? 'Review' : 'Reviews' }}
                    </div>
                </div>
            </div>
        </div>
        <div class="puja-review-card">
            <div class="puja-review-card-header">
                <div>
                    <h3>
                        <i class="fa fa-list-alt mr-2"></i>
                        Devotee Reviews
                    </h3>
                    <p>Review submissions and approval status</p>
                </div>
                <div class="puja-review-icon">
                    <i class="fa fa-star"></i>
                </div>
            </div>
            @if($reviews->count() > 0)
                <div class="table-responsive">
                    <table class="table puja-review-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>
                                    <i class="fa fa-university mr-1"></i>
                                    Temple
                                </th>
                                <th>
                                    <i class="fa fa-pray mr-1"></i>
                                    Puja
                                </th>
                                <th>
                                    <i class="fa fa-user mr-1"></i>
                                    Devotee
                                </th>
                                <th>
                                    <i class="fa fa-star mr-1"></i>
                                    Rating
                                </th>
                                <th>
                                    <i class="fa fa-comment mr-1"></i>
                                    Review
                                </th>
                                <th>
                                    <i class="fa fa-calendar mr-1"></i>
                                    Date
                                </th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                                <tr>
                                    <td>
                                        <span class="review-number">
                                            {{ $reviews->firstItem() + $loop->index }}
                                        </span>
                                    </td>
                                    <td>
                                        @if(optional(optional($review->puja)->temple)->name)
                                            <div class="temple-info">
                                                <div class="temple-icon">
                                                    <i class="fa fa-university"></i>
                                                </div>
                                                <div class="temple-name">
                                                    {{ $review->puja->temple->name }}
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Temple not found</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($review->puja)
                                            <div class="puja-name">
                                                {{ $review->puja->name }}
                                            </div>
                                            @if($review->puja->puja_code)
                                                <div class="puja-code">
                                                    <i class="fa fa-tag"></i>
                                                    {{ $review->puja->puja_code }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-muted">Puja not found</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="devotee-name">
                                            <i class="fa fa-user-circle mr-1"></i>
                                            {{ optional($review->user)->name ?? 'Devotee' }}
                                        </div>
                                        @if(optional($review->user)->email)
                                            <div class="devotee-email">
                                                <i class="fa fa-envelope mr-1"></i>
                                                {{ $review->user->email }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="review-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fa fa-star"></i>
                                                @else
                                                    <i class="fa fa-star-o"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <div class="rating-value">
                                            {{ $review->rating }}/5
                                        </div>
                                    </td>
                                    <td>
                                        <div class="review-message">
                                            {{ $review->review }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="review-date">
                                            {{ $review->created_at ? $review->created_at->format('d M Y') : '-' }}
                                        </div>
                                        <div class="review-time">
                                            {{ $review->created_at ? $review->created_at->format('h:i A') : '' }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($review->status == 0)
                                            <span class="review-status pending">
                                                <i class="fa fa-clock-o"></i>
                                                Pending
                                            </span>
                                        @elseif($review->status == 1)
                                            <span class="review-status approved">
                                                <i class="fa fa-check-circle"></i>
                                                Approved
                                            </span>
                                        @elseif($review->status == 2)
                                            <span class="review-status rejected">
                                                <i class="fa fa-times-circle"></i>
                                                Rejected
                                            </span>
                                        @else
                                            <span class="review-status unknown">
                                                Unknown
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="review-actions">
                                            @if($review->status == 0)
                                                <form
                                                    action="{{ route('admin.puja-reviews.approve', $review->id) }}"
                                                    method="POST"
                                                    class="review-action-form sweet-alert-confirm"
                                                    data-alert-title="Approve Review?"
                                                    data-alert-text="Are you sure you want to approve this review?"
                                                    data-alert-icon="question"
                                                    data-confirm-text="Yes, approve"
                                                >
                                                    @csrf
                                                    <button type="submit" class="review-approve-btn">
                                                        <i class="fa fa-check"></i>
                                                        Approve
                                                    </button>
                                                </form>
                                                <form
                                                    action="{{ route('admin.puja-reviews.reject', $review->id) }}"
                                                    method="POST"
                                                    class="review-action-form sweet-alert-confirm"
                                                    data-alert-title="Reject Review?"
                                                    data-alert-text="Are you sure you want to reject this review?"
                                                    data-alert-icon="warning"
                                                    data-confirm-text="Yes, reject"
                                                >
                                                    @csrf
                                                    <button type="submit" class="review-reject-btn">
                                                        <i class="fa fa-times"></i>
                                                        Reject
                                                    </button>
                                                </form>
                                            @elseif($review->status == 1)
                                                <span class="approved-label">
                                                    <i class="fa fa-check-circle"></i>
                                                    Approved
                                                </span>
                                            @elseif($review->status == 2)
                                                <span class="rejected-label">
                                                    <i class="fa fa-times-circle"></i>
                                                    Rejected
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($reviews->hasPages())
                    <div class="puja-review-pagination">
                        <div class="pagination-info">
                            Showing
                            <strong>{{ $reviews->firstItem() }}</strong>
                            -
                            <strong>{{ $reviews->lastItem() }}</strong>
                            of
                            <strong>{{ $reviews->total() }}</strong>
                            reviews
                        </div>
                        <div>
                            {{ $reviews->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div class="review-empty">
                    <div class="review-empty-icon">
                        <i class="fa fa-comments-o"></i>
                    </div>
                    <h4>No Reviews Yet</h4>
                    <p>
                        No devotee reviews have been submitted for any puja yet.
                    </p>
                </div>
            @endif
        </div>
    </div>
</section>
<style>
    .puja-review-section {
        background: #fff;
        min-height: 100vh;
    }
    .puja-review-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }
    .puja-review-title {
        color: #222;
        font-size: 34px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .puja-review-subtitle {
        color: #777;
        font-size: 15px;
        margin-bottom: 0;
    }
    .puja-review-total {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        background: #fff0f0;
        color: #ea0505;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }
    .puja-review-total span {
        font-size: 18px;
        font-weight: 700;
    }
    .puja-review-card {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
    }
    .puja-review-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 22px 24px;
        border-bottom: 1px solid #eee;
    }
    .puja-review-card-header h3 {
        color: #222;
        font-size: 20px;
        font-weight: 700;
        margin: 0 0 5px;
    }
    .puja-review-card-header p {
        color: #888;
        font-size: 13px;
        margin: 0;
    }
    .puja-review-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        background: #fff0f0;
        color: #ea0505;
        border-radius: 50%;
        font-size: 20px;
    }
    .puja-review-table {
        margin-bottom: 0;
    }
    .puja-review-table thead th {
        padding: 15px 16px;
        background: #fafafa;
        border-top: 0;
        border-bottom: 1px solid #eee;
        color: #555;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
        text-transform: uppercase;
        vertical-align: middle;
    }
    .puja-review-table tbody td {
        padding: 17px 16px;
        border-top: 1px solid #f1f1f1;
        color: #555;
        font-size: 13px;
        vertical-align: middle;
    }
    .puja-review-table tbody tr {
        transition: *background* 0.2s ease;
    }
    .puja-review-table tbody tr:hover {
        background: #fffaf7;
    }
    .review-number {
        color: #999;
        font-weight: 600;
    }
    .temple-info {
        display: flex;
        align-items: center;
        gap: 9px;
        min-width: 150px;
    }
    .temple-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        flex-shrink: 0;
        background: #fff0e8;
        color: #e87528;
        border-radius: 8px;
        font-size: 15px;
    }
    .temple-name {
        color: #333;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }
    .puja-name {
        color: #222;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.5;
    }
    .puja-code {
        color: #999;
        font-size: 11px;
        margin-top: 4px;
    }
    .devotee-name {
        color: #333;
        font-size: 14px;
        font-weight: 600;
    }
    .devotee-email {
        color: #999;
        font-size: 11px;
        margin-top: 4px;
        overflow-wrap: anywhere;
    }
    .review-stars {
        color: #ffc107;
        font-size: 15px;
        white-space: nowrap;
    }
    .review-stars .fa-star-o {
        color: #ccc;
    }
    .rating-value {
        color: #888;
        font-size: 11px;
        margin-top: 4px;
    }
    .review-message {
        max-width: 300px;
        color: #666;
        font-size: 13px;
        line-height: 1.7;
        overflow-wrap: anywhere;
    }
    .review-date {
        color: #444;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }
    .review-time {
        color: #999;
        font-size: 11px;
        margin-top: 4px;
        white-space: nowrap;
    }
    .review-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 10px;
        border-radius: 5px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }
    .review-status.pending {
        background: #fff7e6;
        color: #c47b00;
    }
    .review-status.approved {
        background: #eef9f0;
        color: #348044;
    }
    .review-status.rejected {
        background: #fff0f0;
        color: #d33;
    }
    .review-status.unknown {
        background: #f1f1f1;
        color: #777;
    }
    .review-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
    }
    .review-action-form {
        margin: 0;
    }
    .review-approve-btn,
    .review-reject-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 7px 10px;
        border-radius: 5px;
        font-size: 11px;
        font-weight: 600;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .review-approve-btn {
        background: #348044;
        color: #fff;
        border-color: #348044;
    }
    .review-approve-btn:hover {
        background: #286b36;
        border-color: #286b36;
        color: #fff;
    }
    .review-reject-btn {
        background: #fff;
        color: #d33;
        border-color: #d33;
    }
    .review-reject-btn:hover {
        background: #d33;
        color: #fff;
    }
    .approved-label {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #348044;
        font-size: 12px;
        font-weight: 600;
    }
    .rejected-label {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #d33;
        font-size: 12px;
        font-weight: 600;
    }
    .puja-review-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 20px 24px;
        border-top: 1px solid #eee;
    }
    .pagination-info {
        color: #777;
        font-size: 13px;
    }
    .pagination-info strong {
        color: #333;
    }
    .review-empty {
        padding: 70px 20px;
        text-align: center;
    }
    .review-empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 70px;
        height: 70px;
        margin: 0 auto 18px;
        background: #fff0f0;
        color: #ea0505;
        border-radius: 50%;
        font-size: 28px;
    }
    .review-empty h4 {
        color: #222;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .review-empty p {
        color: #777;
        font-size: 14px;
        margin: 0;
    }
    @media (max-width: 991px) {
        .puja-review-header {
            align-items: flex-start;
        }
        .review-message {
            max-width: 220px;
        }
    }
    @media (max-width: 767px) {
        .puja-review-section {
            padding-top: 30px !important;
        }
        .puja-review-title {
            font-size: 27px;
        }
        .puja-review-header {
            flex-direction: column;
        }
        .puja-review-card-header {
            padding: 18px;
        }
        .puja-review-pagination {
            flex-direction: column;
            align-items: flex-start;
        }
        .review-message {
            max-width: 200px;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const successMessage = @json(session('success'));
    const errorMessage = @json(session('error'));
    const validationErrors = @json($errors->all());

    if (typeof Swal === 'undefined') {
        return;
    }

    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: successMessage,
            confirmButtonText: 'OK',
            confirmButtonColor: '#348044'
        });
    }

    if (errorMessage) {
        Swal.fire({
            icon: 'error',
            title: 'Something went wrong',
            text: errorMessage,
            confirmButtonText: 'OK',
            confirmButtonColor: '#d33'
        });
    }

    if (validationErrors && validationErrors.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Please check the following',
            html: '<ul style="text-align:left;padding-left:20px;margin:0;">' +
                validationErrors.map(function (error) {
                    return '<li>' + escapeHtml(error) + '</li>';
                }).join('') +
                '</ul>',
            confirmButtonText: 'OK',
            confirmButtonColor: '#d33'
        });
    }

    document.querySelectorAll('.sweet-alert-confirm').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            if (form.dataset.submitting === 'true') {
                return;
            }

            Swal.fire({
                title: form.dataset.alertTitle || 'Are you sure?',
                text: form.dataset.alertText || 'Please confirm this action.',
                icon: form.dataset.alertIcon || 'question',
                showCancelButton: true,
                confirmButtonColor: form.dataset.alertIcon === 'warning' ? '#d33' : '#348044',
                cancelButtonColor: '#6c757d',
                confirmButtonText: form.dataset.confirmText || 'Yes, continue',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.dataset.submitting = 'true';
                    form.submit();
                }
            });
        });
    });

    function escapeHtml(value) {
        return String(value).replace(/[&<>"']/g, function (character) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[character];
        });
    }
});
</script>

@endsection
@else
    @php
        abort(403);
    @endphp
@endcan