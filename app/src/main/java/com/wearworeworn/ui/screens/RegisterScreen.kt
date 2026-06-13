package com.wearworeworn.ui.screens

import androidx.compose.animation.AnimatedVisibility
import androidx.compose.animation.fadeIn
import androidx.compose.animation.fadeOut
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.text.KeyboardActions
import androidx.compose.foundation.text.KeyboardOptions
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.automirrored.filled.ArrowBack
import androidx.compose.material.icons.filled.Email
import androidx.compose.material.icons.filled.Lock
import androidx.compose.material.icons.filled.Person
import androidx.compose.material.icons.filled.Visibility
import androidx.compose.material.icons.filled.VisibilityOff
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.focus.FocusDirection
import androidx.compose.ui.graphics.Brush
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.platform.LocalFocusManager
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.input.ImeAction
import androidx.compose.ui.text.input.KeyboardType
import androidx.compose.ui.text.input.PasswordVisualTransformation
import androidx.compose.ui.text.input.VisualTransformation
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.wearworeworn.viewmodel.AuthViewModel

@Composable
fun RegisterScreen(
    viewModel: AuthViewModel,
    onRegisterSuccess: () -> Unit,
    onNavigateToLogin: () -> Unit
) {
    val isLoading    = viewModel.isLoading.value
    val errorMessage = viewModel.errorMessage.value
    val focusManager = LocalFocusManager.current

    var name            by remember { mutableStateOf("") }
    var email           by remember { mutableStateOf("") }
    var password        by remember { mutableStateOf("") }
    var passwordConfirm by remember { mutableStateOf("") }
    var passVisible     by remember { mutableStateOf(false) }
    var passConfVisible by remember { mutableStateOf(false) }

    LaunchedEffect(Unit) { viewModel.clearError() }

    Box(
        modifier = Modifier
            .fillMaxSize()
            .background(
                Brush.verticalGradient(listOf(Color(0xFF0A0A0A), Color(0xFF1A1A1A)))
            )
    ) {
        Column(
            modifier = Modifier
                .fillMaxSize()
                .statusBarsPadding()
                .navigationBarsPadding()
                .verticalScroll(rememberScrollState())
                .padding(horizontal = 32.dp)
        ) {
            Spacer(modifier = Modifier.height(16.dp))

            // ── Back button ───────────────────────────────────────────────────
            IconButton(onClick = onNavigateToLogin) {
                Icon(
                    Icons.AutoMirrored.Filled.ArrowBack,
                    contentDescription = "Kembali",
                    tint = Color.White
                )
            }

            Spacer(modifier = Modifier.height(24.dp))

            // ── Header ────────────────────────────────────────────────────────
            Text(
                text       = "Buat Akun Baru",
                fontSize   = 28.sp,
                fontWeight = FontWeight.Black,
                color      = Color.White
            )
            Spacer(modifier = Modifier.height(8.dp))
            Text(
                text     = "Daftarkan diri dan mulai berbelanja",
                fontSize = 14.sp,
                color    = Color(0xFF888888)
            )

            Spacer(modifier = Modifier.height(40.dp))

            // ── Fields ────────────────────────────────────────────────────────
            val fieldColors = TextFieldDefaults.colors(
                focusedContainerColor   = Color(0xFF2A2A2A),
                unfocusedContainerColor = Color(0xFF222222),
                focusedTextColor        = Color.White,
                unfocusedTextColor      = Color.White,
                focusedIndicatorColor   = Color.Transparent,
                unfocusedIndicatorColor = Color.Transparent,
                cursorColor             = Color.White,
                focusedPlaceholderColor   = Color(0xFF666666),
                unfocusedPlaceholderColor = Color(0xFF666666)
            )
            val fieldShape = RoundedCornerShape(16.dp)

            // Nama
            TextField(
                value         = name,
                onValueChange = { name = it; viewModel.clearError() },
                modifier      = Modifier.fillMaxWidth().height(58.dp),
                placeholder   = { Text("Nama Lengkap", fontSize = 14.sp) },
                leadingIcon   = { Icon(Icons.Default.Person, null, tint = Color(0xFF666666), modifier = Modifier.size(20.dp)) },
                colors        = fieldColors,
                shape         = fieldShape,
                singleLine    = true,
                keyboardOptions = KeyboardOptions(imeAction = ImeAction.Next),
                keyboardActions = KeyboardActions(onNext = { focusManager.moveFocus(FocusDirection.Down) })
            )

            Spacer(modifier = Modifier.height(12.dp))

            // Email
            TextField(
                value         = email,
                onValueChange = { email = it; viewModel.clearError() },
                modifier      = Modifier.fillMaxWidth().height(58.dp),
                placeholder   = { Text("Email", fontSize = 14.sp) },
                leadingIcon   = { Icon(Icons.Default.Email, null, tint = Color(0xFF666666), modifier = Modifier.size(20.dp)) },
                colors        = fieldColors,
                shape         = fieldShape,
                singleLine    = true,
                keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Email, imeAction = ImeAction.Next),
                keyboardActions = KeyboardActions(onNext = { focusManager.moveFocus(FocusDirection.Down) })
            )

            Spacer(modifier = Modifier.height(12.dp))

            // Password
            TextField(
                value         = password,
                onValueChange = { password = it; viewModel.clearError() },
                modifier      = Modifier.fillMaxWidth().height(58.dp),
                placeholder   = { Text("Password (min. 8 karakter)", fontSize = 14.sp) },
                leadingIcon   = { Icon(Icons.Default.Lock, null, tint = Color(0xFF666666), modifier = Modifier.size(20.dp)) },
                trailingIcon  = {
                    IconButton(onClick = { passVisible = !passVisible }) {
                        Icon(
                            if (passVisible) Icons.Default.Visibility else Icons.Default.VisibilityOff,
                            null, tint = Color(0xFF666666), modifier = Modifier.size(20.dp)
                        )
                    }
                },
                visualTransformation = if (passVisible) VisualTransformation.None else PasswordVisualTransformation(),
                colors        = fieldColors,
                shape         = fieldShape,
                singleLine    = true,
                keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Password, imeAction = ImeAction.Next),
                keyboardActions = KeyboardActions(onNext = { focusManager.moveFocus(FocusDirection.Down) })
            )

            Spacer(modifier = Modifier.height(12.dp))

            // Konfirmasi Password
            TextField(
                value         = passwordConfirm,
                onValueChange = { passwordConfirm = it; viewModel.clearError() },
                modifier      = Modifier.fillMaxWidth().height(58.dp),
                placeholder   = { Text("Konfirmasi Password", fontSize = 14.sp) },
                leadingIcon   = { Icon(Icons.Default.Lock, null, tint = Color(0xFF666666), modifier = Modifier.size(20.dp)) },
                trailingIcon  = {
                    IconButton(onClick = { passConfVisible = !passConfVisible }) {
                        Icon(
                            if (passConfVisible) Icons.Default.Visibility else Icons.Default.VisibilityOff,
                            null, tint = Color(0xFF666666), modifier = Modifier.size(20.dp)
                        )
                    }
                },
                visualTransformation = if (passConfVisible) VisualTransformation.None else PasswordVisualTransformation(),
                colors        = fieldColors,
                shape         = fieldShape,
                singleLine    = true,
                keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Password, imeAction = ImeAction.Done),
                keyboardActions = KeyboardActions(onDone = {
                    focusManager.clearFocus()
                    viewModel.register(name, email, password, passwordConfirm, onRegisterSuccess)
                })
            )

            // Error
            Spacer(modifier = Modifier.height(8.dp))
            AnimatedVisibility(visible = errorMessage != null, enter = fadeIn(), exit = fadeOut()) {
                Text(
                    text      = errorMessage ?: "",
                    color     = Color(0xFFFF6B6B),
                    fontSize  = 13.sp,
                    textAlign = TextAlign.Center,
                    modifier  = Modifier.fillMaxWidth()
                )
            }

            Spacer(modifier = Modifier.height(24.dp))

            // ── Register Button ───────────────────────────────────────────────
            Button(
                onClick  = {
                    focusManager.clearFocus()
                    viewModel.register(name, email, password, passwordConfirm, onRegisterSuccess)
                },
                enabled  = !isLoading,
                modifier = Modifier.fillMaxWidth().height(56.dp),
                colors   = ButtonDefaults.buttonColors(
                    containerColor         = Color.White,
                    disabledContainerColor = Color(0xFF444444)
                ),
                shape = RoundedCornerShape(16.dp)
            ) {
                if (isLoading) {
                    CircularProgressIndicator(modifier = Modifier.size(22.dp), color = Color.Black, strokeWidth = 2.5.dp)
                } else {
                    Text("DAFTAR", color = Color.Black, fontWeight = FontWeight.Bold, fontSize = 15.sp, letterSpacing = 1.5.sp)
                }
            }

            Spacer(modifier = Modifier.height(24.dp))

            // ── Link ke Login ─────────────────────────────────────────────────
            Row(
                modifier              = Modifier.fillMaxWidth(),
                horizontalArrangement = Arrangement.Center,
                verticalAlignment     = Alignment.CenterVertically
            ) {
                Text("Sudah punya akun? ", color = Color(0xFF666666), fontSize = 13.sp)
                TextButton(onClick = onNavigateToLogin, contentPadding = PaddingValues(0.dp)) {
                    Text("Masuk", color = Color.White, fontSize = 13.sp, fontWeight = FontWeight.Bold)
                }
            }

            Spacer(modifier = Modifier.height(32.dp))
        }
    }
}
