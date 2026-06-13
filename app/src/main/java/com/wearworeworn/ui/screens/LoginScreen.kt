package com.wearworeworn.ui.screens

import androidx.compose.animation.AnimatedVisibility
import androidx.compose.animation.fadeIn
import androidx.compose.animation.fadeOut
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.text.KeyboardActions
import androidx.compose.foundation.text.KeyboardOptions
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Email
import androidx.compose.material.icons.filled.Lock
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
fun LoginScreen(
    viewModel: AuthViewModel,
    onLoginSuccess: () -> Unit,
    onNavigateToRegister: () -> Unit
) {
    val isLoading    = viewModel.isLoading.value
    val errorMessage = viewModel.errorMessage.value
    val focusManager = LocalFocusManager.current

    var email           by remember { mutableStateOf("") }
    var password        by remember { mutableStateOf("") }
    var passwordVisible by remember { mutableStateOf(false) }

    LaunchedEffect(Unit) { viewModel.clearError() }

    Box(
        modifier = Modifier
            .fillMaxSize()
            .background(
                Brush.verticalGradient(
                    listOf(Color(0xFF0A0A0A), Color(0xFF1A1A1A))
                )
            )
    ) {
        Column(
            modifier = Modifier
                .fillMaxSize()
                .statusBarsPadding()
                .navigationBarsPadding()
                .padding(horizontal = 32.dp),
            verticalArrangement   = Arrangement.Center,
            horizontalAlignment   = Alignment.CenterHorizontally
        ) {

            Text(
                text       = "WearWoreWorn",
                fontSize   = 32.sp,
                fontWeight = FontWeight.Black,
                color      = Color.White,
                letterSpacing = (-1).sp
            )
            Spacer(modifier = Modifier.height(8.dp))
            Text(
                text      = "Masuk untuk melanjutkan belanja",
                fontSize  = 14.sp,
                color     = Color(0xFF888888),
                textAlign = TextAlign.Center
            )

            Spacer(modifier = Modifier.height(48.dp))

            TextField(
                value         = email,
                onValueChange = { email = it; viewModel.clearError() },
                modifier      = Modifier.fillMaxWidth().height(58.dp),
                placeholder   = { Text("Email", color = Color(0xFF666666), fontSize = 14.sp) },
                leadingIcon   = {
                    Icon(Icons.Default.Email, contentDescription = null, tint = Color(0xFF666666), modifier = Modifier.size(20.dp))
                },
                colors = TextFieldDefaults.colors(
                    focusedContainerColor   = Color(0xFF2A2A2A),
                    unfocusedContainerColor = Color(0xFF222222),
                    focusedTextColor        = Color.White,
                    unfocusedTextColor      = Color.White,
                    focusedIndicatorColor   = Color.Transparent,
                    unfocusedIndicatorColor = Color.Transparent,
                    cursorColor             = Color.White
                ),
                shape           = RoundedCornerShape(16.dp),
                singleLine      = true,
                keyboardOptions = KeyboardOptions(
                    keyboardType = KeyboardType.Email,
                    imeAction    = ImeAction.Next
                ),
                keyboardActions = KeyboardActions(
                    onNext = { focusManager.moveFocus(FocusDirection.Down) }
                )
            )

            Spacer(modifier = Modifier.height(12.dp))

            TextField(
                value         = password,
                onValueChange = { password = it; viewModel.clearError() },
                modifier      = Modifier.fillMaxWidth().height(58.dp),
                placeholder   = { Text("Password", color = Color(0xFF666666), fontSize = 14.sp) },
                leadingIcon   = {
                    Icon(Icons.Default.Lock, contentDescription = null, tint = Color(0xFF666666), modifier = Modifier.size(20.dp))
                },
                trailingIcon  = {
                    IconButton(onClick = { passwordVisible = !passwordVisible }) {
                        Icon(
                            imageVector      = if (passwordVisible) Icons.Default.Visibility else Icons.Default.VisibilityOff,
                            contentDescription = null,
                            tint             = Color(0xFF666666),
                            modifier         = Modifier.size(20.dp)
                        )
                    }
                },
                visualTransformation = if (passwordVisible) VisualTransformation.None else PasswordVisualTransformation(),
                colors = TextFieldDefaults.colors(
                    focusedContainerColor   = Color(0xFF2A2A2A),
                    unfocusedContainerColor = Color(0xFF222222),
                    focusedTextColor        = Color.White,
                    unfocusedTextColor      = Color.White,
                    focusedIndicatorColor   = Color.Transparent,
                    unfocusedIndicatorColor = Color.Transparent,
                    cursorColor             = Color.White
                ),
                shape           = RoundedCornerShape(16.dp),
                singleLine      = true,
                keyboardOptions = KeyboardOptions(
                    keyboardType = KeyboardType.Password,
                    imeAction    = ImeAction.Done
                ),
                keyboardActions = KeyboardActions(
                    onDone = {
                        focusManager.clearFocus()
                        viewModel.login(email, password, onLoginSuccess)
                    }
                )
            )

            Spacer(modifier = Modifier.height(8.dp))
            AnimatedVisibility(
                visible = errorMessage != null,
                enter   = fadeIn(),
                exit    = fadeOut()
            ) {
                Text(
                    text      = errorMessage ?: "",
                    color     = Color(0xFFFF6B6B),
                    fontSize  = 13.sp,
                    textAlign = TextAlign.Center,
                    modifier  = Modifier.fillMaxWidth()
                )
            }

            Spacer(modifier = Modifier.height(24.dp))

            Button(
                onClick  = {
                    focusManager.clearFocus()
                    viewModel.login(email, password, onLoginSuccess)
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
                    CircularProgressIndicator(
                        modifier  = Modifier.size(22.dp),
                        color     = Color.Black,
                        strokeWidth = 2.5.dp
                    )
                } else {
                    Text(
                        "MASUK",
                        color      = Color.Black,
                        fontWeight = FontWeight.Bold,
                        fontSize   = 15.sp,
                        letterSpacing = 1.5.sp
                    )
                }
            }

            Spacer(modifier = Modifier.height(32.dp))

            Row(
                verticalAlignment = Alignment.CenterVertically,
                modifier          = Modifier.fillMaxWidth()
            ) {
                HorizontalDivider(modifier = Modifier.weight(1f), color = Color(0xFF333333))
                Text(
                    "  atau  ",
                    color    = Color(0xFF666666),
                    fontSize = 12.sp
                )
                HorizontalDivider(modifier = Modifier.weight(1f), color = Color(0xFF333333))
            }

            Spacer(modifier = Modifier.height(32.dp))

            OutlinedButton(
                onClick  = onNavigateToRegister,
                modifier = Modifier.fillMaxWidth().height(56.dp),
                shape    = RoundedCornerShape(16.dp),
                border   = androidx.compose.foundation.BorderStroke(1.dp, Color(0xFF444444))
            ) {
                Text(
                    "BUAT AKUN BARU",
                    color      = Color.White,
                    fontWeight = FontWeight.Bold,
                    fontSize   = 15.sp,
                    letterSpacing = 1.sp
                )
            }

            Spacer(modifier = Modifier.height(24.dp))

            TextButton(onClick = onLoginSuccess) {
                Text(
                    "Lanjutkan sebagai Tamu",
                    color    = Color(0xFF666666),
                    fontSize = 13.sp
                )
            }
        }
    }
}
