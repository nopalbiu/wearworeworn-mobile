package com.wearworeworn.network

import com.wearworeworn.model.*
import retrofit2.http.*

interface ApiService {

    // ─── Products ─────────────────────────────────────────────────────────────

    @GET("products")
    suspend fun getProducts(): List<Product>

    @GET("products/{id}")
    suspend fun getProductById(@Path("id") id: Int): Product

    @GET("categories")
    suspend fun getCategories(): List<Category>

    @GET("sizes")
    suspend fun getSizes(): List<Size>

    // ─── Auth ─────────────────────────────────────────────────────────────────

    @POST("login")
    suspend fun login(@Body request: LoginRequest): AuthResponse

    @POST("register")
    suspend fun register(@Body request: RegisterRequest): AuthResponse

    @POST("logout")
    suspend fun logout(@Header("Authorization") token: String): Any

    @GET("user")
    suspend fun getUser(@Header("Authorization") token: String): User

    // ─── Cart ─────────────────────────────────────────────────────────────────

    @GET("cart")
    suspend fun getCart(@Header("Authorization") token: String): List<CartItem>

    @POST("cart")
    suspend fun addToCart(
        @Header("Authorization") token: String,
        @Body request: AddToCartRequest
    ): CartItem

    @PATCH("cart/{id}")
    suspend fun updateCartItem(
        @Header("Authorization") token: String,
        @Path("id") id: Int,
        @Body request: UpdateCartRequest
    ): CartItem

    @DELETE("cart/{id}")
    suspend fun deleteCartItem(
        @Header("Authorization") token: String,
        @Path("id") id: Int
    ): Any

    // ─── Orders ───────────────────────────────────────────────────────────────

    @POST("orders")
    suspend fun createOrder(
        @Header("Authorization") token: String,
        @Body request: OrderRequest
    ): OrderResponse

    @GET("orders")
    suspend fun getOrders(@Header("Authorization") token: String): List<Order>
}
