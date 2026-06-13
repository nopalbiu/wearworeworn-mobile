package com.wearworeworn.util

import android.content.Context
import android.content.SharedPreferences

class SessionManager(context: Context) {

    private val prefs: SharedPreferences =
        context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)

    companion object {
        private const val PREF_NAME  = "WearWoreWornSession"
        private const val KEY_TOKEN  = "auth_token"
        private const val KEY_ID     = "user_id"
        private const val KEY_NAME   = "user_name"
        private const val KEY_EMAIL  = "user_email"
    }

    fun saveSession(token: String, userId: Int, name: String, email: String) {
        prefs.edit()
            .putString(KEY_TOKEN, token)
            .putInt(KEY_ID, userId)
            .putString(KEY_NAME, name)
            .putString(KEY_EMAIL, email)
            .apply()
    }

    fun getToken(): String?  = prefs.getString(KEY_TOKEN, null)
    fun getUserId(): Int     = prefs.getInt(KEY_ID, -1)
    fun getUserName(): String? = prefs.getString(KEY_NAME, null)
    fun getUserEmail(): String? = prefs.getString(KEY_EMAIL, null)
    fun isLoggedIn(): Boolean = getToken() != null

    fun clearSession() = prefs.edit().clear().apply()

    fun bearerToken(): String? = getToken()?.let { "Bearer $it" }
}
