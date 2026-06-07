package com.wearworeworn.model

import com.google.gson.annotations.SerializedName

data class Product(
    @SerializedName("id_product") val id: Int,
    @SerializedName("nama_product") val name: String,
    @SerializedName("deskripsi") val description: String?,
    @SerializedName("material_pakaian") val material: String?,
    @SerializedName("url_size_chart") val sizeChartUrl: String?,
    @SerializedName("harga") val price: Double,
    @SerializedName("images") val images: List<ProductImage> = emptyList(),
    @SerializedName("variants") val variants: List<ProductVariant> = emptyList(),
    @SerializedName("categories") val categories: List<Category> = emptyList()
)

data class ProductImage(
    @SerializedName("id_image") val id: Int,
    @SerializedName("id_product") val productId: Int,
    @SerializedName("url_gambar") val imageUrl: String,
    @SerializedName("is_primary") val isPrimary: Boolean
)

data class ProductVariant(
    @SerializedName("id_variant") val id: Int,
    @SerializedName("stok") val stock: Int,
    @SerializedName("size") val size: SizeInfo? = null
)

data class SizeInfo(
    @SerializedName("id_size") val id: Int,
    @SerializedName("nama_size") val name: String
)
