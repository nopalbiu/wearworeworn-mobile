package com.wearworeworn.viewmodel

import android.app.Application
import android.util.Log
import androidx.compose.runtime.State
import androidx.compose.runtime.mutableStateOf
import androidx.lifecycle.AndroidViewModel
import androidx.lifecycle.viewModelScope
import com.wearworeworn.model.*
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

    // Direct buy state (terpisah dari keranjang)
    private val _directBuyItem = mutableStateOf<CartItem?>(null)
    val directBuyItem: State<CartItem?> = _directBuyItem

    fun loadCart() {
        val token = sessionManager.bearerToken() ?: return
        viewModelScope.launch {
            _isLoading.value = true
            try {
                val serverItems = RetrofitClient.instance.getCart(token)
                _cartItems.value = serverItems
            } catch (e: Exception) {
                Log.e("CART", "Load error: ${e.message}")
            } finally {
                _isLoading.value = false
            }
        }
    }

    fun addToCart(variantId: Int, quantity: Int, product: Product, variant: ProductVariant, onSuccess: () -> Unit, onError: () -> Unit) {
        val token = sessionManager.bearerToken()
        
        val currentItems = _cartItems.value.toMutableList()
        val existingIndex = currentItems.indexOfFirst { it.variant.id == variantId }
        
        if (existingIndex != -1) {
            val item = currentItems[existingIndex]
            currentItems[existingIndex] = item.copy(quantity = item.quantity + quantity)
        } else {
            currentItems.add(CartItem(
                id = -(100..9999).random(),
                product = product,
                variant = variant,
                quantity = quantity
            ))
        }
        _cartItems.value = currentItems
        _successMessage.value = "Ditambahkan ke keranjang"
        onSuccess()

        if (token != null) {
            viewModelScope.launch {
                try {
                    RetrofitClient.instance.addToCart(token, AddToCartRequest(variantId, quantity))
                } catch (e: Exception) {
                    Log.e("CART", "Sync error: ${e.message}")
                }
            }
        }
    }

    fun updateQuantity(cartItemId: Int, newQuantity: Int) {
        if (newQuantity < 1) return
        
        _cartItems.value = _cartItems.value.map {
            if (it.id == cartItemId) it.copy(quantity = newQuantity) else it
        }

        if (cartItemId > 0) {
            val token = sessionManager.bearerToken() ?: return
            viewModelScope.launch {
                try {
                    RetrofitClient.instance.updateCartItem(token, cartItemId, UpdateCartRequest(newQuantity))
                } catch (e: Exception) {
                    Log.e("CART", "Update error: ${e.message}")
                }
            }
        }
    }

    fun removeFromCart(cartItemId: Int) {
        _cartItems.value = _cartItems.value.filter { it.id != cartItemId }

        if (cartItemId > 0) {
            val token = sessionManager.bearerToken() ?: return
            viewModelScope.launch {
                try {
                    RetrofitClient.instance.deleteCartItem(token, cartItemId)
                } catch (e: Exception) {
                    Log.e("CART", "Remove error: ${e.message}")
                }
            }
        }
    }

    fun prepareDirectPurchase(product: Product, variant: ProductVariant, quantity: Int) {
        val directItem = CartItem(
            id = -1,
            product = product,
            variant = variant,
            quantity = quantity
        )
        _cartItems.value = listOf(directItem)
    }

    fun clearLocalCart() {
        _cartItems.value = emptyList()
    }

    fun setDirectBuy(product: Product, variant: ProductVariant, quantity: Int) {
        _directBuyItem.value = CartItem(
            id = -1,
            product = product,
            variant = variant,
            quantity = quantity
        )
    }

    fun clearDirectBuy() {
        _directBuyItem.value = null
    }

    fun clearMessages() {
        _errorMessage.value  = null
        _successMessage.value = null
    }
}
