package com.wearworeworn.util

import java.text.NumberFormat
import java.util.Locale

object Formatter {
    /**
     * Mengubah nominal Double menjadi format Rupiah tanpa desimal .0
     * Contoh: 150000.0 -> Rp 150.000
     */
    fun formatRupiah(amount: Double): String {
        val format = NumberFormat.getCurrencyInstance(Locale("in", "ID"))
        format.maximumFractionDigits = 0 // Menghilangkan .00 atau .0
        return format.format(amount).replace("Rp", "Rp ")
    }
}
