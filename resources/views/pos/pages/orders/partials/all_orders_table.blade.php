@if($orders->count() > 0)
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Date & Time</th>
                    <th>Table</th>
                    <th>Waiter</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Status</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td class="fw-bold text-dark">#{{ $order->order_number }}</td>
                    <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                    <td>
                        <span class="badge bg-light text-dark border">
                            {{ $order->table?->table_number ?? '—' }}
                        </span>
                    </td>
                    <td>{{ $order->waiter?->name ?? 'Unassigned' }}</td>
                    <td>{{ $order->customer_name ?: 'Walk-in Customer' }}</td>
                    <td>
                        <span class="badge bg-secondary rounded-pill">{{ $order->items->count() }} items</span>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="text-end amount-text">Rs {{ number_format($order->total_amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="pagination-wrapper d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="text-muted small">
            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
        </div>
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="text-center py-5">
        <i class="fa-solid fa-receipt text-muted mb-3" style="font-size: 48px; opacity: 0.3;"></i>
        <h5 class="text-muted">No Orders Found</h5>
        <p class="text-muted small mb-0">Try adjusting your search or filter criteria.</p>
    </div>
@endif