package com.wearworeworn

import androidx.multidex.MultiDexApplication

/**
 * Custom Application class yang mengaktifkan MultiDex.
 * Diperlukan agar semua class (termasuk MainActivity) bisa di-load
 * ketika jumlah method melampaui batas 65.536 per DEX file.
 */
class WearWoreWornApp : MultiDexApplication()
