package com.wearworeworn.network

import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory

object RetrofitClient {
    // Pastikan IP di bawah ini sama dengan IP Laptop Anda dan tambahkan port :8000
    private const val BASE_URL = "http://192.168.18.2:8000/api/"

    private val retrofit: Retrofit by lazy {
        Retrofit.Builder()
            .baseUrl(BASE_URL)
            .addConverterFactory(GsonConverterFactory.create())
            .build()
    }

    val instance: ApiService by lazy {
        retrofit.create(ApiService::class.java)
    }
}
