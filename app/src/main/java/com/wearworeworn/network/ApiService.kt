package com.wearworeworn.network

import com.wearworeworn.model.Category
import com.wearworeworn.model.Product
import com.wearworeworn.model.Size
import retrofit2.http.GET
import retrofit2.http.Path

interface ApiService {
    @GET("products")
    suspend fun getProducts(): List<Product>

    @GET("products/{id}")
    suspend fun getProductById(@Path("id") id: Int): Product

    @GET("categories")
    suspend fun getCategories(): List<Category>

    @GET("sizes")
    suspend fun getSizes(): List<Size>
}
