package com.wearworeworn.model

import com.google.gson.annotations.SerializedName

data class Category(
    @SerializedName("id_category") val id: Int,
    @SerializedName("nama_category") val name: String
)

data class Size(
    @SerializedName("id_size") val id: Int,
    @SerializedName("nama_size") val name: String
)
