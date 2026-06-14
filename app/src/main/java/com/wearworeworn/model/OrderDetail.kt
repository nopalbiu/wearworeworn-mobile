package com.wearworeworn.model

import com.google.gson.annotations.SerializedName

data class OrderItem(
    @SerializedName("id")           val id:          Int     = 0,
    @SerializedName("product_name") val productName: String  = "-",
    @SerializedName("size")         val size:        String? = null,
    @SerializedName("quantity")     val quantity:    Int     = 1,
    @SerializedName("price")        val price:       Double  = 0.0,
    @SerializedName("subtotal")     val subtotal:    Double  = 0.0
)

data class OrderDetail(
    @SerializedName("id_order")         val id:             Int,
    @SerializedName("total_harga")      val totalPrice:     Double,
    @SerializedName("status")           val status:         String,
    @SerializedName("created_at")       val createdAt:      String,
    // Shipping
    @SerializedName("nama_penerima")    val recipientName:  String  = "-",
    @SerializedName("no_hp")           val phone:          String  = "-",
    @SerializedName("alamat_pengiriman") val deliveryAddress: String = "-",
    @SerializedName("kurir")            val courier:        String  = "-",
    // Payment breakdown
    @SerializedName("subtotal_produk")  val productSubtotal: Double = 0.0,
    @SerializedName("ongkir")           val shippingFee:     Double = 0.0,
    @SerializedName("asuransi")         val shippingInsurance: Double = 0.0,
    // Items
    @SerializedName("items")            val items: List<OrderItem> = emptyList()
)
