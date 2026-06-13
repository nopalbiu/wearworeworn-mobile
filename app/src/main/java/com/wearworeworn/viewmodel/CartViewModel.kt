package com.wearworeworn.viewmodel

import android.app.Application
import android.util.Log
import androidx.compose.runtime.State
import androidx.compose.runtime.mutableStateOf
import androidx.lifecycle.AndroidViewModel
import androidx.lifecycle.viewModelScope
import com.wearworeworn.model.AddToCartRequest
import com.wearworeworn.model.CartItem
import com.wearworeworn.network.RetrofitClient
import com.wearworeworn.util.SessionManager
import kotlinx.coroutines.launch

class CartViewModel(application: Application) : AndroidViewModel(application) {

    private val sessionManager = SessionManager(application)

    private val _cartItems = mutableStateOf<List<CartItem>>(emptyList())
    val cartItems: State<List<CartItem>> = _cartItems

    private val _isLoading = mutableStateOf(false)
    val isLoading: State<Boolean> = _isLoading

    private val _errorMessage = mutableStateOf<String?>(null)
    val errorMessage: State<String?> = _errorMessage

    private val _successMessage = mutableStateOf<String?>(null)
    val successMessage: State<String?> = _successMessage

    val totalPrice: Double get() = _cartItems.value.sumOf { it.subtotal }
    val totalItems: Int    get() = _cartItems.value.sumOf { it.quantity }

    // ─── Load Cart ────────────────────────────────────────────────────────────

    fun loadCart() {
        val token = sessionManager.bearerToken() ?: return
        viewModelScope.launch {
            _isLoading.value = true
            try {
                _cartItems.value = RetrofitClient.instance.getCart(token)
            } catch (e: Exception) {
                Log.e("CART", "Load error: ${e.message}")
                _errorMessage.value = "Gagal memuat keranjang. Periksa koneksi."
            } finally {
                _isLoading.value = false
            }
        }
    }

    // ─── Add to Cart ──────────────────────────────────────────────────────────

    fun addToCart(variantId: Int, quantity: Int, onSuccess: () -> Unit, onError: () -> Unit) {
        val token = sessionManager.bearerToken() ?: run { onError(); return }
        viewModelScope.launch {
            _isLoading.value = true
            try {
                RetrofitClient.instance.addToCart(token, AddToCartRequest(variantId, quantity))
                loadCart()
                _successMessage.value = "Produk berhasil ditambahkan ke keranjang!"
                onSuccess()
            } catch (e: Exception) {
                Log.e("CART", "Add error: ${e.message}")
                _errorMessage.value = "Gagal menambahkan ke keranjang."
                onError()
            } finally {
                _isLoading.value = false
            }
        }
    }

    // ─── Remove Item ──────────────────────────────────────────────────────────

    fun removeFromCart(cartItemId: Int) {
        val token = sessionManager.bearerToken() ?: return
        viewModelScope.launch {
            try {
                RetrofitClient.instance.deleteCartItem(token, cartItemId)
                // Optimistic update — hapus dari state lokal
                _cartItems.value = _cartItems.value.filter { it.id != cartItemId }
            } catch (e: Exception) {
                Log.e("CART", "Remove error: ${e.message}")
                _errorMessage.value = "Gagal menghapus item."
            }
        }
    }

    // ─── Clear cart after checkout ────────────────────────────────────────────

    fun clearLocalCart() {
        _cartItems.value = emptyList()
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    fun clearMessages() {
        _errorMessage.value  = null
        _successMessage.value = null
    }
}
