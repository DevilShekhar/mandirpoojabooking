@extends('admin.layouts.app')
@section('title', 'Daily Ticket Limits')

@section('content')
@can('temple-daily-ticket-limits.index')
<div class="section">
    <div class="section-header">
        <h1>Daily Ticket Limits</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">
                Daily Ticket Limits
            </div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Temple Daily Ticket Limits</h4>
                        @can('temple-daily-ticket-limits.create')
                            <div class="card-header-action">
                                <a href="{{ route('temple-daily-ticket-limits.create') }}"
                                   class="btn btn-primary">
                                    <i class="fas fa-plus"></i>
                                    Add Daily Limit
                                </a>
                            </div>
                        @endcan
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show"
                                 role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show"
                                 role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Temple Name</th>
                                        <th>Ticket Type</th>
                                        <th>Maximum Tickets Per Day</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($limits as $limit)
                                        <tr>
                                            <td class="text-center">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $limit->temple->name ?? 'N/A' }}
                                            </td>
                                            <td>
                                                @if($limit->ticket_type === 'normal')
                                                    <span class="badge badge-info">
                                                        Normal
                                                    </span>
                                                @elseif($limit->ticket_type === 'vip')
                                                    <span class="badge badge-warning">
                                                        VIP
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">
                                                        {{ ucfirst($limit->ticket_type ?? 'N/A') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>
                                                    {{ number_format($limit->daily_limit) }}
                                                </strong>
                                            </td>
                                            <td>
                                                {{ $limit->created_at
                                                    ? $limit->created_at->format('d-m-Y h:i A')
                                                    : 'N/A' }}
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('temple-daily-ticket-limits.edit')
                                                        <a href="{{ route('temple-daily-ticket-limits.edit', $limit->id) }}"
                                                           class="btn btn-sm btn-primary mr-2"
                                                           title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                No daily ticket limits found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
    @php
        abort(403);
    @endphp
@endcan
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#table-1').DataTable({
            ordering: true,
            searching: true,
            paging: true,
            info: true,
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'All']
            ],
            columnDefs: [
                {
                    orderable: false,
                    targets: [0, 5]
                }
            ],
            order: [[1, 'asc']]
        });
    });
</script>
@endpush