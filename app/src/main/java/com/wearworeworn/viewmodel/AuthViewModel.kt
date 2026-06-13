package com.wearworeworn.viewmodel

import android.app.Application
import android.util.Log
import androidx.compose.runtime.State
import androidx.compose.runtime.mutableStateOf
import androidx.lifecycle.AndroidViewModel
import androidx.lifecycle.viewModelScope
import com.wearworeworn.model.LoginRequest
import com.wearworeworn.model.RegisterRequest
import com.wearworeworn.model.User
import com.wearworeworn.network.RetrofitClient
import com.wearworeworn.util.SessionManager
import kotlinx.coroutines.launch

class AuthViewModel(application: Application) : AndroidViewModel(application) {

    private val sessionManager = SessionManager(application)

    private val _isLoggedIn = mutableStateOf(sessionManager.isLoggedIn())
    val isLoggedIn: State<Boolean> = _isLoggedIn

    private val _currentUser = mutableStateOf<User?>(null)
    val currentUser: State<User?> = _currentUser

    private val _isLoading = mutableStateOf(false)
    val isLoading: State<Boolean> = _isLoading

    private val _errorMessage = mutableStateOf<String?>(null)
    val errorMessage: State<String?> = _errorMessage

    init {
        // Restore user info from saved session
        if (sessionManager.isLoggedIn()) {
            val id    = sessionManager.getUserId()
            val name  = sessionManager.getUserName()
            val email = sessionManager.getUserEmail()
            if (name != null && email != null) {
                _currentUser.value = User(id, name, email)
            }
        }
    }

    // ─── Login ────────────────────────────────────────────────────────────────

    fun login(email: String, password: String, onSuccess: () -> Unit) {
        if (email.isBlank() || password.isBlank()) {
            _errorMessage.value = "Email dan password tidak boleh kosong."
            return
        }
        viewModelScope.launch {
            _isLoading.value   = true
            _errorMessage.value = null
            try {
                val resp = RetrofitClient.instance.login(LoginRequest(email.trim(), password))
                sessionManager.saveSession(resp.token, resp.user.id, resp.user.name, resp.user.email)
                _currentUser.value = resp.user
                _isLoggedIn.value  = true
                onSuccess()
            } catch (e: Exception) {
                Log.e("AUTH", "Login error: ${e.message}")
                _errorMessage.value = "Email atau password salah. Silakan coba lagi."
            } finally {
                _isLoading.value = false
            }
        }
    }

    // ─── Register ─────────────────────────────────────────────────────────────

    fun register(
        name:            String,
        email:           String,
        password:        String,
        passwordConfirm: String,
        onSuccess:       () -> Unit
    ) {
        if (name.isBlank() || email.isBlank() || password.isBlank()) {
            _errorMessage.value = "Semua kolom harus diisi."
            return
        }
        if (password != passwordConfirm) {
            _errorMessage.value = "Konfirmasi password tidak cocok."
            return
        }
        if (password.length < 8) {
            _errorMessage.value = "Password minimal 8 karakter."
            return
        }
        viewModelScope.launch {
            _isLoading.value    = true
            _errorMessage.value = null
            try {
                val resp = RetrofitClient.instance.register(
                    RegisterRequest(name.trim(), email.trim(), password, passwordConfirm)
                )
                sessionManager.saveSession(resp.token, resp.user.id, resp.user.name, resp.user.email)
                _currentUser.value = resp.user
                _isLoggedIn.value  = true
                onSuccess()
            } catch (e: Exception) {
                Log.e("AUTH", "Register error: ${e.message}")
                _errorMessage.value = "Gagal mendaftar. Pastikan email belum terdaftar."
            } finally {
                _isLoading.value = false
            }
        }
    }

    // ─── Logout ───────────────────────────────────────────────────────────────

    fun logout(onSuccess: () -> Unit) {
        viewModelScope.launch {
            val token = sessionManager.bearerToken()
            if (token != null) {
                try { RetrofitClient.instance.logout(token) } catch (_: Exception) {}
            }
            sessionManager.clearSession()
            _isLoggedIn.value  = false
            _currentUser.value = null
            onSuccess()
        }
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    fun clearError() { _errorMessage.value = null }

    /** Mengembalikan token siap pakai (prefix Bearer) atau null */
    fun bearerToken(): String? = sessionManager.bearerToken()
}
