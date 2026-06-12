package com.wearworeworn.model

import com.google.gson.annotations.SerializedName

data class OrderRequest(
    @SerializedName("nama_penerima")     val recipientName:   String,
    @SerializedName("alamat_pengiriman") val shippingAddress: String,
    @SerializedName("no_hp")            val phone:           String,
    @SerializedName("catatan")          val note:            String
)

data class Order(
    @SerializedName("id_order")    val id:         Int,
    @SerializedName("total_harga") val totalPrice: Double,
    @SerializedName("status")      val status:     String,
    @SerializedName("created_at")  val createdAt:  String
)

data class OrderResponse(
    @SerializedName("message") val message: String,
    @SerializedName("order")   val order:   Order
)
