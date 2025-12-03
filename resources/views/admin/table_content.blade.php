@forelse($orders as $order)
<tr class="{{ $order->status == 'pending' ? 'table-active border border-warning border-3' : ($order->status == 'cancelled' ? 'row-cancelled' : '') }}">
    <td class="fs-4 fw-bold text-warning">{{ $order->table_number }}</td>
    <td class="fw-bold">{{ $order->customer_name }}</td>
    <td class="text-start">
        <ul class="list-unstyled mb-0 small">
            @foreach($order->items as $item)
                <li class="mb-1"><span class="badge bg-primary rounded-pill me-1">{{ $item->quantity }}x</span> {{ $item->product->name }}</li>
            @endforeach
        </ul>
    </td>
    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>

    <td class="small">
        <div class="text-muted">{{ $order->created_at->setTimezone('Asia/Jakarta')->format('d M Y') }}</div>
        <div class="fw-bold">{{ $order->created_at->setTimezone('Asia/Jakarta')->format('H:i') }} WIB</div>
    </td>

    <td>
        @if($order->status == 'pending')
            <div class="d-flex gap-2 justify-content-center">
                <form action="{{ route('admin.complete', $order->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-success btn-sm fw-bold shadow-sm" title="Selesai"><i class="fa fa-check"></i></button>
                </form>
                <form action="{{ route('admin.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?')">
                    @csrf
                    <button class="btn btn-danger btn-sm fw-bold shadow-sm" title="Batal"><i class="fa fa-times"></i></button>
                </form>
            </div>
            <div class="badge bg-danger blink mt-2">BARU MASUK!</div>
        @elseif($order->status == 'completed')
            <span class="badge bg-success w-100 py-2"><i class="fa fa-check-double me-1"></i> Beres</span>
        @elseif($order->status == 'cancelled')
            <span class="badge bg-secondary w-100 py-2"><i class="fa fa-ban me-1"></i> Dibatalkan</span>
        @endif
    </td>
</tr>
@empty
<tr><td colspan="6" class="py-5 text-muted"><i class="fa fa-coffee fa-3x mb-3"></i><br>Belum ada pesanan masuk.</td></tr>
@endforelse
