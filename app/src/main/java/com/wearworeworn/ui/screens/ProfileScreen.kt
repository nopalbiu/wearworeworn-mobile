package com.wearworeworn.ui.screens

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.automirrored.filled.ArrowBack
import androidx.compose.material.icons.automirrored.filled.ExitToApp
import androidx.compose.material.icons.filled.Email
import androidx.compose.material.icons.filled.Person
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Brush
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.wearworeworn.viewmodel.AuthViewModel

@Composable
fun ProfileScreen(
    viewModel: AuthViewModel,
    onBack: () -> Unit,
    onLogout: () -> Unit
) {
    val user = viewModel.currentUser.value
    var showLogoutDialog by remember { mutableStateOf(false) }

    val initials = user?.name
        ?.split(" ")
        ?.take(2)
        ?.joinToString("") { it.first().uppercase() }
        ?: "?"

    if (showLogoutDialog) {
        AlertDialog(
            onDismissRequest   = { showLogoutDialog = false },
            title              = { Text("Keluar?", fontWeight = FontWeight.Bold) },
            text               = { Text("Kamu akan keluar dari akun ini.", color = Color.Gray) },
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
            shape = RoundedCornerShape(20.dp)
        )
    }

    Column(
        modifier = Modifier
            .fillMaxSize()
            .background(Color(0xFFF8F8F8))
    ) {
        Box(
            modifier = Modifier
                .fillMaxWidth()
                .background(
                    Brush.verticalGradient(listOf(Color(0xFF0A0A0A), Color(0xFF2A2A2A)))
                )
                .statusBarsPadding()
                .padding(bottom = 40.dp)
        ) {
            IconButton(
                onClick  = onBack,
                modifier = Modifier.padding(8.dp)
            ) {
                Icon(Icons.AutoMirrored.Filled.ArrowBack, contentDescription = "Kembali", tint = Color.White)
            }

            Column(
                modifier            = Modifier.fillMaxWidth().padding(top = 48.dp),
                horizontalAlignment = Alignment.CenterHorizontally
            ) {
                Box(
                    modifier         = Modifier
                        .size(90.dp)
                        .background(Color.White, CircleShape),
                    contentAlignment = Alignment.Center
                ) {
                    Text(
                        text       = initials,
                        fontSize   = 32.sp,
                        fontWeight = FontWeight.Black,
                        color      = Color.Black
                    )
                }
                Spacer(modifier = Modifier.height(16.dp))
                Text(
                    text       = user?.name ?: "-",
                    fontSize   = 22.sp,
                    fontWeight = FontWeight.Bold,
                    color      = Color.White
                )
                Spacer(modifier = Modifier.height(4.dp))
                Text(
                    text     = user?.email ?: "-",
                    fontSize = 14.sp,
                    color    = Color(0xFFAAAAAA)
                )
            }
        }

        Column(
            modifier = Modifier
                .fillMaxWidth()
                .padding(horizontal = 20.dp)
                .offset(y = (-20).dp)
        ) {
            Card(
                colors    = CardDefaults.cardColors(containerColor = Color.White),
                shape     = RoundedCornerShape(20.dp),
                elevation = CardDefaults.cardElevation(4.dp)
            ) {
                Column(modifier = Modifier.padding(8.dp)) {
                    ProfileInfoRow(
                        icon  = Icons.Default.Person,
                        label = "Nama",
                        value = user?.name ?: "-"
                    )
                    HorizontalDivider(color = Color(0xFFF0F0F0), modifier = Modifier.padding(horizontal = 16.dp))
                    ProfileInfoRow(
                        icon  = Icons.Default.Email,
                        label = "Email",
                        value = user?.email ?: "-"
                    )
                }
            }

            Spacer(modifier = Modifier.height(16.dp))

            Button(
                onClick  = { showLogoutDialog = true },
                modifier = Modifier.fillMaxWidth().height(56.dp),
                colors   = ButtonDefaults.buttonColors(containerColor = Color(0xFFFFEEEE)),
                shape    = RoundedCornerShape(16.dp),
                elevation = ButtonDefaults.buttonElevation(0.dp)
            ) {
                Icon(
                    Icons.AutoMirrored.Filled.ExitToApp,
                    contentDescription = null,
                    tint               = Color(0xFFCC3333)
                )
                Spacer(modifier = Modifier.width(10.dp))
                Text(
                    "Keluar dari Akun",
                    color      = Color(0xFFCC3333),
                    fontWeight = FontWeight.Bold,
                    fontSize   = 15.sp
                )
            }
        }
    }
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
