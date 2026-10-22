@can('santan-event-view')
@extends('admin.layouts.app')

@section('content')
@php
    $previousMonth = $currentMonth->copy()->subMonth()->format('Y-m');
    $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');

    $bookingData = $bookingsByDate->map(function ($items) {
        return $items->values();
    });
@endphp

<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="mb-1">Sanatan Events Calendar</h3>
            <p class="text-muted mb-0">Date-wise event booking management</p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('admin.sanatan-events-calendar.index', ['month' => $previousMonth]) }}" class="btn btn-outline-primary">&larr; Previous</a>
            <h5 class="mb-0">{{ $currentMonth->format('F Y') }}</h5>
            <a href="{{ route('admin.sanatan-events-calendar.index', ['month' => $nextMonth]) }}" class="btn btn-outline-primary">Next &rarr;</a>
            <a href="{{ route('admin.sanatan-events-calendar.index') }}" class="btn btn-secondary">Current Month</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="status-summary all-summary">
                <span>Total Bookings</span>
                <h3 id="totalCount">{{ $bookings->count() }}</h3>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="status-summary pending-summary">
                <span>Pending</span>
                <h3>{{ $statusCounts['pending'] }}</h3>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="status-summary confirmed-summary">
                <span>Confirmed</span>
                <h3>{{ $statusCounts['confirmed'] }}</h3>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="status-summary cancelled-summary">
                <span>Cancelled</span>
                <h3>{{ $statusCounts['cancelled'] }}</h3>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <h5 class="mb-0">Booking Calendar</h5>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <label for="statusFilter" class="mb-0">Booking Status:</label>
                    <select id="statusFilter" class="form-select" style="width:auto">
                        <option value="all">All Bookings</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="calendar-legend mt-3">
                <span><i class="legend-dot pending-dot"></i> Pending</span>
                <span><i class="legend-dot confirmed-dot"></i> Confirmed</span>
                <span><i class="legend-dot cancelled-dot"></i> Cancelled</span>
            </div>
        </div>

        <div class="card-body p-2 p-md-3">
            <div class="calendar-grid calendar-header">
                @foreach (['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $weekday)
                    <div class="calendar-weekday">{{ $weekday }}</div>
                @endforeach
            </div>

            <div class="calendar-grid">
                @foreach ($calendarDays as $day)
                    <button type="button"
                        class="calendar-day {{ $day['isCurrentMonth'] ? '' : 'other-month' }} {{ $day['isToday'] ? 'today' : '' }}"
                        data-date="{{ $day['date'] }}">
                        <span class="day-number">{{ $day['day'] }}</span>
                        <span class="day-bookings"></span>
                        <span class="day-preview"></span>
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="calendar-modal" id="bookingModal" aria-hidden="true">
    <div class="calendar-modal-backdrop" data-close-modal></div>
    <div class="calendar-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
        <div class="modal-header-custom">
            <div>
                <h4 id="modalTitle" class="mb-1">Booking Details</h4>
                <p id="modalDate" class="text-muted mb-0"></p>
            </div>
            <button type="button" class="modal-close" data-close-modal aria-label="Close">&times;</button>
        </div>
        <div class="modal-body-custom" id="bookingDetails"></div>
        <div class="modal-footer-custom">
            <button type="button" class="btn btn-secondary" data-close-modal>Close</button>
        </div>
    </div>
</div>

<style>
.status-summary {
    padding: 18px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    background: #fff;
}
.status-summary span {
    color: #64748b;
    font-size: 14px;
}
.status-summary h3 {
    margin: 8px 0 0;
    font-weight: 700;
}
.pending-summary {
    background: #fffbeb;
    border-color: #fde68a;
}
.confirmed-summary {
    background: #f0fdf4;
    border-color: #bbf7d0;
}
.cancelled-summary {
    background: #fef2f2;
    border-color: #fecaca;
}
.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, minmax(0, 1fr));
    gap: 5px;
}
.calendar-weekday {
    padding: 12px 4px;
    text-align: center;
    font-size: 13px;
    font-weight: 700;
    color: #475569;
    background: #f1f5f9;
    border-radius: 6px;
}
.calendar-day {
    min-width: 0;
    min-height: 125px;
    padding: 9px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 7px;
    text-align: left;
    color: #1e293b;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 7px;
    cursor: pointer;
    overflow: hidden;
}
.calendar-day:hover {
    background: #eff6ff;
    border-color: #60a5fa;
}
.calendar-day.other-month {
    background: #f8fafc;
    color: #94a3b8;
}
.calendar-day.today {
    border: 2px solid #2563eb;
}
.day-number {
    font-weight: 700;
    font-size: 16px;
}
.day-bookings {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
}
.status-count {
    display: inline-block;
    padding: 3px 6px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 700;
}
.pending-count {
    background: #fef3c7;
    color: #92400e;
}
.confirmed-count {
    background: #dcfce7;
    color: #166534;
}
.cancelled-count {
    background: #fee2e2;
    color: #991b1b;
}
.day-preview {
    width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-size: 11px;
}
.calendar-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 18px;
    color: #475569;
    font-size: 13px;
}
.legend-dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    margin-right: 5px;
    border-radius: 50%;
}
.pending-dot {
    background: #d97706;
}
.confirmed-dot {
    background: #16a34a;
}
.cancelled-dot {
    background: #dc2626;
}
.calendar-modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 1050;
    align-items: center;
    justify-content: center;
    padding: 15px;
}
.calendar-modal.show {
    display: flex;
}
.calendar-modal-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, .6);
}
.calendar-modal-dialog {
    position: relative;
    width: 100%;
    max-width: 850px;
    max-height: 85vh;
    overflow-y: auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, .2);
}
.modal-header-custom,
.modal-footer-custom {
    padding: 18px 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e2e8f0;
}
.modal-footer-custom {
    justify-content: flex-end;
    border-top: 1px solid #e2e8f0;
    border-bottom: 0;
}
.modal-body-custom {
    padding: 20px 22px;
}
.modal-close {
    border: 0;
    background: transparent;
    font-size: 30px;
    cursor: pointer;
    color: #64748b;
}
.booking-card {
    padding: 16px;
    margin-bottom: 14px;
    border: 1px solid #e2e8f0;
    border-radius: 9px;
}
.booking-card:last-child {
    margin-bottom: 0;
}
.booking-card h5 {
    font-size: 16px;
    margin-bottom: 14px;
}
.booking-info {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
}
.booking-info small {
    display: block;
    color: #64748b;
    margin-bottom: 4px;
}
.booking-info strong {
    overflow-wrap: anywhere;
}
.booking-status {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 5px;
    font-size: 12px;
    font-weight: 700;
}
.status-pending {
    color: #92400e;
    background: #fef3c7;
}
.status-confirmed {
    color: #166534;
    background: #dcfce7;
}
.status-cancelled {
    color: #991b1b;
    background: #fee2e2;
}
.status-other {
    color: #334155;
    background: #e2e8f0;
}
@media (max-width: 768px) {
    .calendar-grid {
        gap: 3px;
    }
    .calendar-weekday {
        padding: 8px 1px;
        font-size: 10px;
    }
    .calendar-day {
        min-height: 85px;
        padding: 5px;
        gap: 5px;
    }
    .day-number {
        font-size: 13px;
    }
    .status-count,
    .day-preview {
        font-size: 9px;
    }
    .booking-info {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const bookingsByDate = @json($bookingData);
    const modal = document.getElementById('bookingModal');
    const modalDate = document.getElementById('modalDate');
    const bookingDetails = document.getElementById('bookingDetails');
    const statusFilter = document.getElementById('statusFilter');
    const totalCount = document.getElementById('totalCount');

    let selectedDate = null;

    function escapeHtml(value) {
        return String(value ?? 'N/A').replace(/[&<>"']/g, function (character) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[character];
        });
    }

    function normalizedStatus(value) {
        return String(value ?? 'pending').trim().toLowerCase();
    }

    function statusLabel(value) {
        const status = normalizedStatus(value);

        if (status === 'confirmed') {
            return 'Confirmed';
        }

        if (status === 'cancelled') {
            return 'Cancelled';
        }

        if (status === 'pending') {
            return 'Pending';
        }

        return value || 'Not specified';
    }

    function filteredBookings(date) {
        const bookings = bookingsByDate[date] || [];
        const selectedStatus = statusFilter.value;

        if (selectedStatus === 'all') {
            return bookings;
        }

        return bookings.filter(function (booking) {
            return normalizedStatus(booking.booking_status) === selectedStatus;
        });
    }

    function formatDate(dateString) {
        const parts = dateString.split('-');
        const date = new Date(
            Number(parts[0]),
            Number(parts[1]) - 1,
            Number(parts[2])
        );

        return date.toLocaleDateString('en-IN', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }

    function formatTime(value) {
        if (!value) {
            return 'N/A';
        }

        const match = String(value).match(/^(\d{1,2}):(\d{2})/);

        if (!match) {
            return value;
        }

        const date = new Date();
        date.setHours(Number(match[1]), Number(match[2]), 0, 0);

        return date.toLocaleTimeString('en-IN', {
            hour: 'numeric',
            minute: '2-digit'
        });
    }

    function formatAmount(booking) {
        if (booking.amount === null || booking.amount === undefined) {
            return 'N/A';
        }

        return `${booking.currency || 'INR'} ${booking.amount}`;
    }

    function info(label, value) {
        return `
            <div>
                <small>${escapeHtml(label)}</small>
                <strong>${escapeHtml(value)}</strong>
            </div>
        `;
    }

    function statusBadge(value) {
        const status = normalizedStatus(value);
        let cssClass = 'status-other';

        if (status === 'pending') {
            cssClass = 'status-pending';
        } else if (status === 'confirmed') {
            cssClass = 'status-confirmed';
        } else if (status === 'cancelled') {
            cssClass = 'status-cancelled';
        }

        return `<span class="booking-status ${cssClass}">${escapeHtml(statusLabel(value))}</span>`;
    }

    function renderCalendar() {
        let visibleTotal = 0;

        document.querySelectorAll('.calendar-day').forEach(function (day) {
            const date = day.dataset.date;
            const bookings = filteredBookings(date);
            const counts = {
                pending: 0,
                confirmed: 0,
                cancelled: 0
            };

            bookings.forEach(function (booking) {
                const status = normalizedStatus(booking.booking_status);

                if (Object.prototype.hasOwnProperty.call(counts, status)) {
                    counts[status]++;
                }
            });

            visibleTotal += bookings.length;

            const countContainer = day.querySelector('.day-bookings');
            const previewContainer = day.querySelector('.day-preview');

            countContainer.innerHTML = '';
            previewContainer.textContent = '';

            if (bookings.length > 0) {
                if (counts.pending) {
                    countContainer.innerHTML += `<span class="status-count pending-count">${counts.pending} Pending</span>`;
                }

                if (counts.confirmed) {
                    countContainer.innerHTML += `<span class="status-count confirmed-count">${counts.confirmed} Confirmed</span>`;
                }

                if (counts.cancelled) {
                    countContainer.innerHTML += `<span class="status-count cancelled-count">${counts.cancelled} Cancelled</span>`;
                }

                if (statusFilter.value === 'all') {
                    previewContainer.textContent = bookings[0].event_name || 'Sanatan Event';
                } else {
                    previewContainer.textContent = `${bookings.length} matching booking(s)`;
                }
            } else {
                previewContainer.textContent = 'No matching bookings';
            }
        });

        totalCount.textContent = visibleTotal;

        if (selectedDate && modal.classList.contains('show')) {
            renderModalBookings(selectedDate);
        }
    }

    function renderModalBookings(date) {
        const bookings = filteredBookings(date);

        modalDate.textContent = formatDate(date);

        if (bookings.length === 0) {
            bookingDetails.innerHTML = `
                <div class="text-center py-4">
                    <p class="text-muted mb-0">No bookings found for this date and status.</p>
                </div>
            `;
            return;
        }

        bookingDetails.innerHTML = bookings.map(function (booking) {
            return `
                <div class="booking-card">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                        <h5 class="mb-0">Booking #${escapeHtml(booking.id)} - ${escapeHtml(booking.event_name || 'Sanatan Event')}</h5>
                        ${statusBadge(booking.booking_status)}
                    </div>
                    <div class="booking-info">
                        ${info('Customer Name', booking.customer_name)}
                        ${info('Customer Email', booking.customer_email)}
                        ${info('Customer Phone', booking.customer_phone)}
                        ${info('Event Creator', booking.event_creator_name)}
                        ${info('Event Date', booking.event_date)}
                        ${info('Event Time', formatTime(booking.event_time))}
                        ${info('Amount', formatAmount(booking))}
                        ${info('Payment Status', booking.payment_status)}
                        ${info('Booking Status', statusLabel(booking.booking_status))}
                        ${info('Cancellation Reason', booking.cancellation_reason)}
                        ${info('Cancelled By', booking.cancelled_by_name)}
                        ${info('Cancelled At', booking.cancelled_at)}
                        ${info('Refund Status', booking.refund_status)}
                        ${info('Refund Amount', booking.refund_amount)}
                        ${info('Razorpay Refund ID', booking.razorpay_refund_id)}
                        ${info('Booked At', booking.created_at)}
                    </div>
                </div>
            `;
        }).join('');
    }

    function openModal(date) {
        selectedDate = date;
        renderModalBookings(date);
        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        selectedDate = null;
    }

    document.querySelectorAll('.calendar-day').forEach(function (day) {
        day.addEventListener('click', function () {
            openModal(this.dataset.date);
        });
    });

    statusFilter.addEventListener('change', renderCalendar);

    modal.querySelectorAll('[data-close-modal]').forEach(function (element) {
        element.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal.classList.contains('show')) {
            closeModal();
        }
    });

    renderCalendar();
});
</script>
@endsection
@else
    @php
        abort(403);
    @endphp
@endcan
