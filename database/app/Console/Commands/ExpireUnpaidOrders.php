<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpireUnpaidOrders extends Command
{
    protected $signature = 'orders:expire-unpaid';

    protected $description = 'Batalkan order yang belum dibayar setelah 24 jam dan kembalikan stok';

    public function handle(): int
    {
        $batasWaktu = now()->subHours(24);

        $expiredOrders = Order::with(['items.variant', 'payment'])
            ->where('status_pesanan', 'menunggu_pembayaran')
            ->where('tanggal_order', '<=', $batasWaktu)
            ->get();

        if ($expiredOrders->isEmpty()) {
            $this->info('Tidak ada order yang perlu di-expire.');
            return Command::SUCCESS;
        }

        $this->info("Ditemukan {$expiredOrders->count()} order yang expired.");

        foreach ($expiredOrders as $order) {
            DB::transaction(function () use ($order) {
                // 1. Kembalikan stok
                foreach ($order->items as $item) {
                    if ($item->variant) {
                        $item->variant->increment('stok', $item->qty);
                    }
                }

                // 2. Update status order
                $order->update([
                    'status_pesanan' => 'dibatalkan',
                ]);

                // 3. Update status payment
                if ($order->payment) {
                    $order->payment->update([
                        'status_pembayaran' => 'expired',
                    ]);
                }
            });

            $this->info("Order #{$order->id_order} telah dibatalkan. Stok dikembalikan.");
        }

        $this->info('Selesai.');
        return Command::SUCCESS;
    }
}
