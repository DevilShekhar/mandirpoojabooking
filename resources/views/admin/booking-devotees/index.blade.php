@can('booking-devotees.index')
@extends('admin.layouts.app')

@section('title', 'Booking Devotees')

@section('content')

<section class="section">

    <div class="section-body">

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header">

                        <h4>Booking Devotees</h4>

                        <div class="card-header-action">

                            <a href="{{ route('booking-devotees.create') }}"
                               class="btn btn-primary">

                                <i class="fas fa-plus"></i>
                                Add Devotee

                            </a>

                        </div>

                    </div>

                    <div class="card-body">

                        @if(session('success'))

                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>

                        @endif

                        <div class="table-responsive">

                            <table class="table table-striped">

                                <thead>

                                    <tr>
                                        <th>#</th>
                                        <th>Booking</th>
                                        <th>Devotee</th>
                                        <th>Relation</th>
                                        <th>Gender</th>
                                        <th>Mobile</th>
                                        <th>Gotra</th>
                                        <th>Primary</th>
                                        <th>Actions</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($bookingDevotees as $devotee)

                                        <tr>

                                            <td>
                                                {{ $loop->iteration }}
                                            </td>

                                            <td>
                                                <strong>
                                                    {{ $devotee->booking->booking_number ?? 'Booking #' . $devotee->booking_id }}
                                                </strong>
                                            </td>

                                            <td>
                                                {{ $devotee->name }}
                                            </td>

                                            <td>
                                                {{ $devotee->relation ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $devotee->gender ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $devotee->mobile ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $devotee->gotra ?? '-' }}
                                            </td>

                                            <td>

                                                @if($devotee->is_primary)

                                                    <span class="badge badge-success">
                                                        Primary
                                                    </span>

                                                @else

                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>

                                                @endif

                                            </td>

                                            <td>

                                                <a href="{{ route('booking-devotees.show', $devotee) }}"
                                                   class="btn btn-sm btn-info">

                                                    <i class="fas fa-eye"></i>

                                                </a>

                                                <a href="{{ route('booking-devotees.edit', $devotee) }}"
                                                   class="btn btn-sm btn-warning">

                                                    <i class="fas fa-edit"></i>

                                                </a>

                                                <form action="{{ route('booking-devotees.destroy', $devotee) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this devotee?');">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger">

                                                        <i class="fas fa-trash"></i>

                                                    </button>

                                                </form>

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="9"
                                                class="text-center">

                                                No booking devotees found.

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

</section>

@endsection
@else
    @php
        abort(403);
    @endphp
@endcan
