package com.wearworeworn.model

import com.google.gson.annotations.SerializedName

data class CartItem(
    @SerializedName("id")       val id:       Int,
    @SerializedName("product")  val product:  Product,
    @SerializedName("variant")  val variant:  ProductVariant,
    @SerializedName("quantity") val quantity: Int
) {
    val subtotal: Double get() = product.price * quantity
}

data class AddToCartRequest(
    @SerializedName("id_variant") val variantId: Int,
    @SerializedName("quantity")   val quantity:  Int
)

data class UpdateCartRequest(
    @SerializedName("quantity") val quantity: Int
)
