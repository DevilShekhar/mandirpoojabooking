@can('puja-review-rating')
@extends('admin.layouts.app')
@section('title', 'Puja Reviews & Ratings')
@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Puja Reviews & Ratings</h4>
                        <div class="card-header-action">
                            <span class="badge badge-primary">
                                Total Reviews: {{ $totalReviews }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="alert alert-primary">
                                    <strong>Total Reviews:</strong>
                                    {{ $totalReviews }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-info">
                                    <strong>Average Rating:</strong>
                                    {{ number_format($averageRating, 1) }}/5
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="alert alert-success">
                                    <strong>Approved:</strong>
                                    {{ $approvedReviews }}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="alert alert-warning">
                                    <strong>Pending:</strong>
                                    {{ $pendingReviews }}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="alert alert-danger">
                                    <strong>Rejected:</strong>
                                    {{ $rejectedReviews }}
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Temple</th>
                                        <th>Puja</th>
                                        <th>Devotee</th>
                                        <th>Rating</th>
                                        <th>Review</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reviews as $review)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            @if(optional(optional($review->puja)->temple)->name)
                                                <strong>
                                                    {{ $review->puja->temple->name }}
                                                </strong>
                                            @else
                                                <span class="text-muted">
                                                    Temple not found
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($review->puja)
                                                <strong>
                                                    {{ $review->puja->name }}
                                                </strong>
                                                @if($review->puja->puja_code)
                                                    <div class="text-muted">
                                                        <small>
                                                            {{ $review->puja->puja_code }}
                                                        </small>
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-muted">
                                                    Puja not found
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>
                                                {{ optional($review->user)->name ?? 'Devotee' }}
                                            </strong>
                                            @if(optional($review->user)->email)
                                                <div class="text-muted">
                                                    <small>
                                                        {{ $review->user->email }}
                                                    </small>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="fa fa-star"></i>
                                                    @else
                                                        <i class="fa fa-star-o"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <small class="text-muted">
                                                {{ $review->rating }}/5
                                            </small>
                                        </td>
                                        <td>
                                            {{ $review->review }}
                                        </td>
                                        <td>
                                            @if($review->created_at)
                                                {{ $review->created_at->format('d M Y') }}
                                                <div class="text-muted">
                                                    <small>
                                                        {{ $review->created_at->format('h:i A') }}
                                                    </small>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($review->status == 0)
                                                <span class="badge badge-warning">
                                                    Pending
                                                </span>
                                            @elseif($review->status == 1)
                                                <span class="badge badge-success">
                                                    Approved
                                                </span>
                                            @elseif($review->status == 2)
                                                <span class="badge badge-danger">
                                                    Rejected
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    Unknown
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center">
                                                @if($review->status == 0)
                                                    <form action="{{ route('admin.puja-reviews.approve', $review->id) }}"
                                                        method="POST" class="d-inline approve-review-form mr-1">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm"
                                                            title="Approve">
                                                            <i class="fas fa-check"></i>
                                                            Approve
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.puja-reviews.reject', $review->id) }}"
                                                        method="POST" class="d-inline reject-review-form">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Reject">
                                                            <i class="fas fa-times"></i>
                                                            Reject
                                                        </button>
                                                    </form>
                                                @elseif($review->status == 1)
                                                    <span class="badge badge-success">
                                                        Approved
                                                    </span>
                                                @elseif($review->status == 2)
                                                    <span class="badge badge-danger">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
$(document).ready(function () {
    if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#table-1')) {
        $('#table-1').DataTable({
            ordering: true,
            order: [],
            searching: true,
            paging: true,
            info: true,
            pageLength: 10,
            lengthMenu: [
                [10, 20, 25, 50, 100, -1],
                [10, 20, 25, 50, 100, 'All']
            ],
            columnDefs: [
                {
                   orderable: false,
                   targets: [0, 8]
                }
            ],
            language: {
                emptyTable: "No reviews found.",
                zeroRecords: "No matching reviews found.",
                search: "Search Reviews:",
                lengthMenu: "Show _MENU_ reviews",
                info: "Showing _START_ to _END_ of _TOTAL_ reviews",
                infoEmpty: "Showing 0 to 0 of 0 reviews",
                paginate: {
                    previous: "Previous",
                    next: "Next"
                }
            }
        });
    }
});
$(document).on('submit', '.approve-review-form', function(e) {
    e.preventDefault();
    let form = this;
    Swal.fire({
        title: 'Approve Review?',
        text: 'Are you sure you want to approve this review?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#47c363',
        cancelButtonColor: '#6777ef',
        confirmButtonText: 'Yes, approve',
        cancelButtonText: 'Cancel'
    }).then(function(result) {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});
$(document).on('submit', '.reject-review-form', function(e) {
    e.preventDefault();
    let form = this;
    Swal.fire({
        title: 'Reject Review?',
        text: 'Are you sure you want to reject this review?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#fc544b',
        cancelButtonColor: '#6777ef',
        confirmButtonText: 'Yes, reject',
        cancelButtonText: 'Cancel'
    }).then(function(result) {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});
</script>
@if(session('success'))
<script>
$(document).ready(function() {
    Swal.fire({
        title: 'Success!',
        text: @json(session('success')),
        icon: 'success',
        confirmButtonColor: '#6777ef',
        confirmButtonText: 'OK'
    });
});
</script>
@endif
@if(session('error'))
<script>
$(document).ready(function() {
    Swal.fire({
        title: 'Error!',
        text: @json(session('error')),
        icon: 'error',
        confirmButtonColor: '#fc544b',
        confirmButtonText: 'OK'
    });
});
</script>
@endif
@endpush
@else
    @php
        abort(403);
    @endphp
@endcan