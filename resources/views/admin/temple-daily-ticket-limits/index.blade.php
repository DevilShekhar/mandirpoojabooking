
@extends('admin.layouts.app')

@section('content')
@can('temple-daily-ticket-limits.index')
<div class="section">
    <div class="section-header">
        <h1>Daily Ticket Limits</h1>

        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">Daily Ticket Limits</div>
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
                                <a href="{{ route('temple-daily-ticket-limits.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add Daily Limit
                                </a>
                            </div>
                        @endcan
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="daily-ticket-limits-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
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
                                            <td>{{ $limits->firstItem() + $loop->index }}</td>

                                            <td>
                                                {{ $limit->temple->name ?? 'N/A' }}
                                            </td>

                                            <td>
                                                @if($limit->ticket_type === 'normal')
                                                    <span class="badge badge-info">Normal</span>
                                                @else
                                                    <span class="badge badge-warning">VIP</span>
                                                @endif
                                            </td>

                                            <td>{{ number_format($limit->daily_limit) }}</td>

                                            <td>
                                                {{ $limit->created_at ? $limit->created_at->format('d-m-Y h:i A') : 'N/A' }}
                                            </td>

                                            <td>
                                                <div class="d-flex">
                                                    @can('temple-daily-ticket-limits.edit')
                                                        <a
                                                            href="{{ route('temple-daily-ticket-limits.edit', $limit->id) }}"
                                                            class="btn btn-sm btn-primary mr-2"
                                                            title="Edit"
                                                        >
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

                        <div class="mt-3">
                            {{ $limits->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
    @php abort(403); @endphp
@endcan
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('.delete-limit-form').on('submit', function (e) {
            if (!confirm('Are you sure you want to delete this daily ticket limit?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush