package com.wearworeworn.viewmodel

import android.app.Application
import android.util.Log
import androidx.compose.runtime.State
import androidx.compose.runtime.mutableStateOf
import androidx.lifecycle.AndroidViewModel
import androidx.lifecycle.viewModelScope
import com.wearworeworn.model.Order
import com.wearworeworn.model.OrderRequest
import com.wearworeworn.network.RetrofitClient
import com.wearworeworn.util.SessionManager
import kotlinx.coroutines.launch

class OrderViewModel(application: Application) : AndroidViewModel(application) {

    private val sessionManager = SessionManager(application)

    private val _isLoading = mutableStateOf(false)
    val isLoading: State<Boolean> = _isLoading

    private val _errorMessage = mutableStateOf<String?>(null)
    val errorMessage: State<String?> = _errorMessage

    private val _orderSuccess = mutableStateOf<Order?>(null)
    val orderSuccess: State<Order?> = _orderSuccess

    fun createOrder(
        recipientName:   String,
        shippingAddress: String,
        phone:           String,
        note:            String,
        courier:         String,
        paymentMethod:   String,
        onSuccess:       (Order) -> Unit,
        onError:         (String) -> Unit
    ) {
        if (recipientName.isBlank() || shippingAddress.isBlank() || phone.isBlank() || courier.isBlank() || paymentMethod.isBlank()) {
            onError("Semua field harus diisi.")
            return
        }
        val token = sessionManager.bearerToken() ?: run {
            onError("Sesi habis. Silakan login kembali.")
            return
        }
        viewModelScope.launch {
            _isLoading.value    = true
            _errorMessage.value = null
            try {
                val response = RetrofitClient.instance.createOrder(
                    token,
                    OrderRequest(
                        recipientName   = recipientName.trim(),
                        shippingAddress = shippingAddress.trim(),
                        phone           = phone.trim(),
                        note            = note.trim(),
                        courier         = courier,
                        paymentMethod   = paymentMethod
                    )
                )
                _orderSuccess.value = response.order
                onSuccess(response.order)
            } catch (e: Exception) {
                Log.e("ORDER", "Create error: ${e.message}")
                val msg = "Gagal membuat pesanan. Silakan coba lagi."
                _errorMessage.value = msg
                onError(msg)
            } finally {
                _isLoading.value = false
            }
        }
    }

    fun resetState() {
        _orderSuccess.value = null
        _errorMessage.value = null
    }
}
