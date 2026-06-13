package com.wearworeworn.model

import com.google.gson.annotations.SerializedName

data class User(
    @SerializedName("id")    val id:    Int,
    @SerializedName("name")  val name:  String,
    @SerializedName("email") val email: String
)

data class LoginRequest(
    @SerializedName("email")    val email:    String,
    @SerializedName("password") val password: String
)

data class RegisterRequest(
    @SerializedName("name")                  val name:                String,
    @SerializedName("email")                 val email:               String,
    @SerializedName("password")              val password:            String,
    @SerializedName("password_confirmation") val passwordConfirmation: String
)

data class AuthResponse(
    @SerializedName("token") val token: String,
    @SerializedName("user")  val user:  User
)
