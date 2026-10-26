@can('ticket-prices.view')
    @extends('admin.layouts.app')
    @section('content')
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">View Ticket Price</h4>
                            <div>
                                @can('ticket-prices.edit')
                                <a href="{{ route('ticket-prices.edit', $ticketPrice->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @endcan
                                <a href="{{ route('ticket-prices.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="mb-3">Ticket Information</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th style="width: 25%;">Ticket Price ID</th>
                                            <td>{{ $ticketPrice->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Temple Name</th>
                                            <td>{{ $ticketPrice->temple->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Temple Code</th>
                                            <td>{{ $ticketPrice->temple->temple_code ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ticket Type</th>
                                            <td>
                                                @if ($ticketPrice->ticket_type === 'normal')
                                                <span class="badge badge-primary">
                                                    Normal Ticket
                                                </span>
                                                @elseif ($ticketPrice->ticket_type === 'vip')
                                                <span class="badge badge-warning">
                                                    VIP Ticket
                                                </span>
                                                @else
                                                <span class="badge badge-secondary">
                                                    {{ ucfirst($ticketPrice->ticket_type) }}
                                                </span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Visitor Category</th>
                                            <td>{{ $ticketPrice->type }}</td>
                                        </tr>
                                        <tr>
                                            <th>Minimum Age</th>
                                            <td>{{ $ticketPrice->min_age }} years</td>
                                        </tr>
                                        <tr>
                                            <th>Maximum Age</th>
                                            <td>
                                                @if ($ticketPrice->max_age !== null)
                                                {{ $ticketPrice->max_age }} years
                                                @else
                                                No maximum age limit
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Ticket Price</th>
                                            <td>
                                                <strong>
                                                    ₹{{ number_format((float) $ticketPrice->price, 2) }}
                                                </strong>
                                                @if ((float) $ticketPrice->price === 0.0)
                                                <span class="badge badge-success ml-2">
                                                    Free
                                                </span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
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
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <h5 class="mb-3 mt-4">Record Information</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th style="width: 25%;">Created By</th>
                                            <td>{{ $ticketPrice->creator->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Updated By</th>
                                            <td>{{ $ticketPrice->updater->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>
                                                {{ $ticketPrice->created_at
                                                    ? $ticketPrice->created_at->format('d-m-Y h:i A')
                                                    : 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Updated At</th>
                                            <td>
                                                {{ $ticketPrice->updated_at
                                                    ? $ticketPrice->updated_at->format('d-m-Y h:i A')
                                                    : 'N/A' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('ticket-prices.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                            @can('ticket-prices.edit')
                            <a href="{{ route('ticket-prices.edit', $ticketPrice->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Ticket Price
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@else
    @php abort(403); @endphp
@endcan