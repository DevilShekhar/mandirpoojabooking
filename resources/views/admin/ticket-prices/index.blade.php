@extends('admin.layouts.app')
@section('content')
@can('ticket-prices.index')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Ticket Price Management</h4>
                    @can('ticket-prices.create')
                        <a href="{{ route('ticket-prices.create') }}"
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Ticket Price
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button"
                                    class="close"
                                    data-dismiss="alert"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button"
                                    class="close"
                                    data-dismiss="alert"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Temple</th>
                                    <th>Ticket Type</th>
                                    <th>Visitor Category</th>
                                    <th>Minimum Age</th>
                                    <th>Maximum Age</th>
                                    <th>Price (₹)</th>
                                    <th>Created By</th>
                                    <th>Updated By</th>
                                    <th>Status</th>
                                    <th width="160">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ticketPrices as $ticketPrice)
                                    <tr>
                                        <td>
                                            {{ $ticketPrices->firstItem() + $loop->index }}
                                        </td>
                                        <td>
                                            {{ $ticketPrice->temple->name ?? 'N/A' }}
                                        </td>
                                        <td>
                                            @if ($ticketPrice->ticket_type === 'normal')
                                                <span class="badge badge-primary">
                                                    Normal
                                                </span>
                                            @elseif ($ticketPrice->ticket_type === 'vip')
                                                <span class="badge badge-warning">
                                                    VIP
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    {{ ucfirst($ticketPrice->ticket_type) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $ticketPrice->type }}
                                        </td>
                                        <td>
                                            {{ $ticketPrice->min_age }} years
                                        </td>
                                        <td>
                                            @if ($ticketPrice->max_age !== null)
                                                {{ $ticketPrice->max_age }} years
                                            @else
                                                No limit
                                            @endif
                                        </td>
                                        <td>
                                            ₹{{ number_format((float) $ticketPrice->price, 2) }}
                                        </td>
                                        <td>
                                            {{ $ticketPrice->creator->name ?? 'N/A' }}
                                        </td>
                                        <td>
                                            {{ $ticketPrice->updater->name ?? 'N/A' }}
                                        </td>
                                        <td>
                                            @if ($ticketPrice->status)
                                                <span class="badge badge-success">
                                                    Active
                                                </span>
                                            @else
                                                <span class="badge badge-danger">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex">

                                                @can('ticket-prices.view')
                                                    <a href="{{ route('ticket-prices.show', $ticketPrice->id) }}"
                                                       class="btn btn-info btn-sm mr-1"
                                                       title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endcan
                                                @can('ticket-prices.edit')
                                                    <a href="{{ route('ticket-prices.edit', $ticketPrice->id) }}"
                                                       class="btn btn-warning btn-sm mr-1"
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('ticket-prices.delete')
                                                    <form action="{{ route('ticket-prices.destroy', $ticketPrice->id) }}"
                                                          method="POST"
                                                          class="delete-form d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-danger btn-sm"
                                                                title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center">
                                            No ticket prices found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        {{ $ticketPrices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-form').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!confirm('Are you sure you want to delete this ticket price?')) {
                    event.preventDefault();
                }
            });
        });
    });
</script>
@else
    @php abort(403); @endphp
@endcan
@endsection