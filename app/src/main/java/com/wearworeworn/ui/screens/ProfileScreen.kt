package com.wearworeworn.ui.screens

import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.text.KeyboardOptions
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.automirrored.filled.ArrowBack
import androidx.compose.material.icons.automirrored.filled.ExitToApp
import androidx.compose.material.icons.filled.*
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Brush
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.input.KeyboardType
import androidx.compose.ui.text.input.PasswordVisualTransformation
import androidx.compose.ui.text.input.VisualTransformation
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.compose.ui.window.Dialog
import com.wearworeworn.viewmodel.AuthViewModel

@Composable
fun ProfileScreen(
    viewModel:          AuthViewModel,
    onBack:             () -> Unit,
    onLogout:           () -> Unit,
    onNavigateToOrders: () -> Unit
) {
    val user = viewModel.currentUser.value
    var showLogoutDialog  by remember { mutableStateOf(false) }
    var showPasswordDialog by remember { mutableStateOf(false) }

    val initials = user?.name
        ?.split(" ")
        ?.take(2)
        ?.joinToString("") { it.first().uppercase() }
        ?: "?"

    // ─── Logout dialog ────────────────────────────────────────────────────
    if (showLogoutDialog) {
        AlertDialog(
            onDismissRequest = { showLogoutDialog = false },
            title            = { Text("Keluar?", fontWeight = FontWeight.Bold) },
            text             = { Text("Kamu akan keluar dari akun ini.", color = Color.Gray) },
            confirmButton = {
                Button(
                    onClick = {
                        showLogoutDialog = false
                        viewModel.logout { onLogout() }
                    },
                    colors = ButtonDefaults.buttonColors(containerColor = Color.Black)
                ) {
                    Text("Keluar", color = Color.White)
                }
            },
            dismissButton = {
                TextButton(onClick = { showLogoutDialog = false }) {
                    Text("Batal", color = Color.Gray)
                }
            },
            containerColor = Color.White,
            shape          = RoundedCornerShape(20.dp)
        )
    }

    // ─── Change Password dialog ───────────────────────────────────────────
    if (showPasswordDialog) {
        ChangePasswordDialog(
            viewModel = viewModel,
            onDismiss = { showPasswordDialog = false }
        )
    }

    Column(
        modifier = Modifier
            .fillMaxSize()
            .background(Color(0xFFF8F8F8))
    ) {
        // ─── Header ──────────────────────────────────────────────────────
        Box(
            modifier = Modifier
                .fillMaxWidth()
                .background(Brush.verticalGradient(listOf(Color(0xFF0A0A0A), Color(0xFF2A2A2A))))
                .statusBarsPadding()
                .padding(bottom = 40.dp)
        ) {
            IconButton(
                onClick  = onBack,
                modifier = Modifier.padding(8.dp).align(Alignment.TopStart)
            ) {
                Icon(Icons.AutoMirrored.Filled.ArrowBack, contentDescription = "Kembali", tint = Color.White)
            }

            Column(
                modifier            = Modifier.fillMaxWidth().padding(top = 48.dp),
                horizontalAlignment = Alignment.CenterHorizontally
            ) {
                Box(
                    modifier         = Modifier.size(90.dp).background(Color.White, CircleShape),
                    contentAlignment = Alignment.Center
                ) {
                    Text(initials, fontSize = 32.sp, fontWeight = FontWeight.Black, color = Color.Black)
                }
                Spacer(modifier = Modifier.height(16.dp))
                Text(user?.name ?: "-", fontSize = 22.sp, fontWeight = FontWeight.Bold, color = Color.White)
                Spacer(modifier = Modifier.height(4.dp))
                Text(user?.email ?: "-", fontSize = 14.sp, color = Color(0xFFAAAAAA))
            }
        }

        // ─── Content ─────────────────────────────────────────────────────
        Column(
            modifier = Modifier
                .fillMaxWidth()
                .padding(horizontal = 20.dp)
                .offset(y = (-20).dp)
        ) {
            // Info card
            Card(
                colors    = CardDefaults.cardColors(containerColor = Color.White),
                shape     = RoundedCornerShape(20.dp),
                elevation = CardDefaults.cardElevation(4.dp)
            ) {
                Column(modifier = Modifier.padding(8.dp)) {
                    ProfileInfoRow(icon = Icons.Default.Person, label = "Nama",  value = user?.name  ?: "-")
                    HorizontalDivider(color = Color(0xFFF0F0F0), modifier = Modifier.padding(horizontal = 16.dp))
                    ProfileInfoRow(icon = Icons.Default.Email,  label = "Email", value = user?.email ?: "-")
                }
            }

            Spacer(modifier = Modifier.height(12.dp))

            // My Orders button
            Card(
                modifier  = Modifier.fillMaxWidth().clickable { onNavigateToOrders() },
                colors    = CardDefaults.cardColors(containerColor = Color.White),
                shape     = RoundedCornerShape(16.dp),
                elevation = CardDefaults.cardElevation(2.dp)
            ) {
                Row(
                    modifier          = Modifier.fillMaxWidth().padding(horizontal = 16.dp, vertical = 16.dp),
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    Icon(Icons.Default.ShoppingBag, contentDescription = null, tint = Color.Black, modifier = Modifier.size(22.dp))
                    Spacer(modifier = Modifier.width(14.dp))
                    Text("My Orders", color = Color.Black, fontWeight = FontWeight.SemiBold, fontSize = 15.sp, modifier = Modifier.weight(1f))
                    Icon(Icons.Default.ChevronRight, contentDescription = null, tint = Color.Gray, modifier = Modifier.size(20.dp))
                }
            }

            Spacer(modifier = Modifier.height(12.dp))

            // Change Password button
            Card(
                modifier  = Modifier.fillMaxWidth().clickable { showPasswordDialog = true },
                colors    = CardDefaults.cardColors(containerColor = Color.White),
                shape     = RoundedCornerShape(16.dp),
                elevation = CardDefaults.cardElevation(2.dp)
            ) {
                Row(
                    modifier          = Modifier.fillMaxWidth().padding(horizontal = 16.dp, vertical = 16.dp),
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    Icon(Icons.Default.Lock, contentDescription = null, tint = Color.Black, modifier = Modifier.size(22.dp))
                    Spacer(modifier = Modifier.width(14.dp))
                    Text("Ubah Password", color = Color.Black, fontWeight = FontWeight.SemiBold, fontSize = 15.sp, modifier = Modifier.weight(1f))
                    Icon(Icons.Default.ChevronRight, contentDescription = null, tint = Color.Gray, modifier = Modifier.size(20.dp))
                }
            }

            Spacer(modifier = Modifier.height(16.dp))

            // Logout button
            Button(
                onClick   = { showLogoutDialog = true },
                modifier  = Modifier.fillMaxWidth().height(56.dp),
                colors    = ButtonDefaults.buttonColors(containerColor = Color(0xFFFFEEEE)),
                shape     = RoundedCornerShape(16.dp),
                elevation = ButtonDefaults.buttonElevation(0.dp)
            ) {
                Icon(Icons.AutoMirrored.Filled.ExitToApp, contentDescription = null, tint = Color(0xFFCC3333))
                Spacer(modifier = Modifier.width(10.dp))
                Text("Keluar dari Akun", color = Color(0xFFCC3333), fontWeight = FontWeight.Bold, fontSize = 15.sp)
            }
        }
    }
}

// ─── Change Password Dialog ───────────────────────────────────────────────────
@Composable
private fun ChangePasswordDialog(
    viewModel: AuthViewModel,
    onDismiss: () -> Unit
) {
    var currentPwd  by remember { mutableStateOf("") }
    var newPwd      by remember { mutableStateOf("") }
    var confirmPwd  by remember { mutableStateOf("") }
    var showCurrent by remember { mutableStateOf(false) }
    var showNew     by remember { mutableStateOf(false) }
    var showConfirm by remember { mutableStateOf(false) }
    var errorMsg    by remember { mutableStateOf<String?>(null) }
    var successMsg  by remember { mutableStateOf<String?>(null) }
    val isLoading   = viewModel.isLoading.value

    Dialog(onDismissRequest = onDismiss) {
        Card(
            colors    = CardDefaults.cardColors(containerColor = Color.White),
            shape     = RoundedCornerShape(24.dp),
            elevation = CardDefaults.cardElevation(8.dp)
        ) {
            Column(modifier = Modifier.padding(24.dp)) {
                Text("Ubah Password", fontSize = 20.sp, fontWeight = FontWeight.Black, color = Color.Black)

                Spacer(modifier = Modifier.height(20.dp))

                PasswordField(label = "Password Saat Ini", value = currentPwd, onValueChange = { currentPwd = it }, showPassword = showCurrent, onToggleShow = { showCurrent = !showCurrent })
                Spacer(modifier = Modifier.height(12.dp))
                PasswordField(label = "Password Baru",     value = newPwd,     onValueChange = { newPwd     = it }, showPassword = showNew,     onToggleShow = { showNew     = !showNew     })
                Spacer(modifier = Modifier.height(12.dp))
                PasswordField(label = "Konfirmasi Password Baru", value = confirmPwd, onValueChange = { confirmPwd = it }, showPassword = showConfirm, onToggleShow = { showConfirm = !showConfirm })

                if (errorMsg != null) {
                    Spacer(modifier = Modifier.height(10.dp))
                    Text(errorMsg!!, color = Color(0xFFEF5350), fontSize = 13.sp)
                }
                if (successMsg != null) {
                    Spacer(modifier = Modifier.height(10.dp))
                    Text(successMsg!!, color = Color(0xFF4CAF50), fontSize = 13.sp)
                }

                Spacer(modifier = Modifier.height(20.dp))

                Row(modifier = Modifier.fillMaxWidth(), horizontalArrangement = Arrangement.spacedBy(10.dp)) {
                    OutlinedButton(
                        onClick  = onDismiss,
                        modifier = Modifier.weight(1f).height(48.dp),
                        shape    = RoundedCornerShape(12.dp)
                    ) {
                        Text("Batal")
                    }
                    Button(
                        onClick  = {
                            errorMsg   = null
                            successMsg = null
                            viewModel.changePassword(
                                currentPwd, newPwd, confirmPwd,
                                onSuccess = {
                                    successMsg = "Password berhasil diubah!"
                                    currentPwd = ""; newPwd = ""; confirmPwd = ""
                                },
                                onError = { errorMsg = it }
                            )
                        },
                        modifier = Modifier.weight(1f).height(48.dp),
                        colors   = ButtonDefaults.buttonColors(containerColor = Color.Black),
                        shape    = RoundedCornerShape(12.dp),
                        enabled  = !isLoading
                    ) {
                        if (isLoading) {
                            CircularProgressIndicator(color = Color.White, modifier = Modifier.size(18.dp), strokeWidth = 2.dp)
                        } else {
                            Text("Simpan", color = Color.White, fontWeight = FontWeight.Bold)
                        }
                    }
                }
            }
        }
    }
}

@Composable
private fun PasswordField(
    label:         String,
    value:         String,
    onValueChange: (String) -> Unit,
    showPassword:  Boolean,
    onToggleShow:  () -> Unit
) {
    Text(label, fontSize = 12.sp, color = Color.Gray, fontWeight = FontWeight.Medium)
    Spacer(modifier = Modifier.height(6.dp))
    TextField(
        value                = value,
        onValueChange        = onValueChange,
        modifier             = Modifier.fillMaxWidth(),
        singleLine           = true,
        visualTransformation = if (showPassword) VisualTransformation.None else PasswordVisualTransformation(),
        keyboardOptions      = KeyboardOptions(keyboardType = KeyboardType.Password),
        trailingIcon = {
            IconButton(onClick = onToggleShow) {
                Icon(
                    imageVector        = if (showPassword) Icons.Default.VisibilityOff else Icons.Default.Visibility,
                    contentDescription = null,
                    tint               = Color.Gray
                )
            }
        },
        colors = TextFieldDefaults.colors(
            focusedContainerColor   = Color(0xFFF5F5F5),
            unfocusedContainerColor = Color(0xFFF5F5F5),
            focusedIndicatorColor   = Color.Transparent,
            unfocusedIndicatorColor = Color.Transparent,
            cursorColor             = Color.Black
        ),
        shape = RoundedCornerShape(12.dp)
    )
}

@Composable
private fun ProfileInfoRow(
    icon:  androidx.compose.ui.graphics.vector.ImageVector,
    label: String,
    value: String
) {
    Row(
        modifier          = Modifier.fillMaxWidth().padding(horizontal = 16.dp, vertical = 14.dp),
        verticalAlignment = Alignment.CenterVertically
    ) {
        Icon(icon, contentDescription = null, tint = Color.Gray, modifier = Modifier.size(20.dp))
        Spacer(modifier = Modifier.width(14.dp))
        Column {
            Text(label, fontSize = 11.sp, color = Color.Gray, fontWeight = FontWeight.Medium)
            Spacer(modifier = Modifier.height(2.dp))
            Text(value, fontSize = 15.sp, fontWeight = FontWeight.SemiBold, color = Color.Black)
        }
    }
}
