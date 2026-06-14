package com.wearworeworn.viewmodel

import android.app.Application
import android.util.Log
import androidx.compose.runtime.State
import androidx.compose.runtime.mutableStateOf
import androidx.lifecycle.AndroidViewModel
import androidx.lifecycle.viewModelScope
import com.wearworeworn.model.DirectBuyRequest
import com.wearworeworn.model.Order
import com.wearworeworn.model.OrderDetail
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

    private val _orders = mutableStateOf<List<Order>>(emptyList())
    val orders: State<List<Order>> = _orders

    private val _isOrdersLoading = mutableStateOf(false)
    val isOrdersLoading: State<Boolean> = _isOrdersLoading

    private val _orderDetail = mutableStateOf<OrderDetail?>(null)
    val orderDetail: State<OrderDetail?> = _orderDetail

    private val _isDetailLoading = mutableStateOf(false)
    val isDetailLoading: State<Boolean> = _isDetailLoading

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

    fun directBuy(
        recipientName:   String,
        shippingAddress: String,
        phone:           String,
        note:            String,
        courier:         String,
        paymentMethod:   String,
        variantId:       Int,
        qty:             Int,
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
                val response = RetrofitClient.instance.directBuy(
                    token,
                    DirectBuyRequest(
                        recipientName   = recipientName.trim(),
                        shippingAddress = shippingAddress.trim(),
                        phone           = phone.trim(),
                        note            = note.trim(),
                        courier         = courier,
                        paymentMethod   = paymentMethod,
                        variantId       = variantId,
                        qty             = qty
                    )
                )
                _orderSuccess.value = response.order
                onSuccess(response.order)
            } catch (e: Exception) {
                Log.e("ORDER", "Direct buy error: ${e.message}")
                val msg = "Gagal membuat pesanan. Silakan coba lagi."
                _errorMessage.value = msg
                onError(msg)
            } finally {
                _isLoading.value = false
            }
        }
    }

    fun loadOrders() {
        val token = sessionManager.bearerToken() ?: return
        viewModelScope.launch {
            _isOrdersLoading.value = true
            _errorMessage.value = null
            try {
                _orders.value = RetrofitClient.instance.getOrders(token)
            } catch (e: Exception) {
                val errorMsg = "Load orders error: ${e.message}"
                Log.e("ORDER", errorMsg)
                _errorMessage.value = errorMsg
            } finally {
                _isOrdersLoading.value = false
            }
        }
    }

    fun loadOrderDetail(orderId: Int) {
        val token = sessionManager.bearerToken() ?: return
        _orderDetail.value = null
        viewModelScope.launch {
            _isDetailLoading.value = true
            try {
                _orderDetail.value = RetrofitClient.instance.getOrderDetail(token, orderId)
            } catch (e: Exception) {
                Log.e("ORDER", "Load order detail error: ${e.message}")
                // Fallback: build minimal detail from orders list
                _orders.value.find { it.id == orderId }?.let { order ->
                    _orderDetail.value = OrderDetail(
                        id          = order.id,
                        totalPrice  = order.totalPrice,
                        status      = order.status,
                        createdAt   = order.createdAt ?: ""
                    )
                }
            } finally {
                _isDetailLoading.value = false
            }
        }
    }
}
