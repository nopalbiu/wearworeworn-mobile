package com.wearworeworn.ui.screens

import android.content.Intent
import android.net.Uri
import android.util.Log
import androidx.compose.foundation.BorderStroke
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.automirrored.filled.ArrowBack
import androidx.compose.material.icons.filled.CheckCircle
import androidx.compose.material.icons.filled.ContentCopy
import androidx.compose.material.icons.filled.Home
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.platform.LocalClipboardManager
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.text.AnnotatedString
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.wearworeworn.util.Formatter
import kotlinx.coroutines.delay
import java.text.SimpleDateFormat
import java.util.Locale
import java.util.TimeZone
import java.util.concurrent.TimeUnit

/**
 * Menghitung sisa detik dari deadline pembayaran (24 jam setelah created_at).
 * Jika createdAt kosong/null atau gagal di-parse, fallback ke 24 jam penuh.
 */
private fun calculateRemainingSeconds(createdAt: String?): Long {
    if (createdAt.isNullOrBlank()) return 24 * 3600L

    return try {
        // Format dari Laravel: "2026-06-14T10:00:00.000000Z" atau "2026-06-14 10:00:00"
        val format = if (createdAt.contains("T")) {
            SimpleDateFormat("yyyy-MM-dd'T'HH:mm:ss", Locale.getDefault())
        } else {
            SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault())
        }
        format.timeZone = TimeZone.getTimeZone("UTC")

        val orderTime = format.parse(createdAt)
        if (orderTime != null) {
            val deadlineMs = orderTime.time + (24 * 3600 * 1000L) // 24 jam setelah order dibuat
            val nowMs = System.currentTimeMillis()
            val remainingMs = deadlineMs - nowMs
            if (remainingMs > 0) (remainingMs / 1000).coerceAtMost(24 * 3600L) else 0L
        } else {
            24 * 3600L // fallback
        }
    } catch (e: Exception) {
        Log.e("CheckoutSuccess", "Failed to parse createdAt: $createdAt", e)
        24 * 3600L // fallback
    }
}

@Composable
fun CheckoutSuccessScreen(
    orderId:        Int,
    totalPrice:     Double,
    createdAt:      String? = null,
    isFromMyOrders: Boolean = false,
    onBack:         () -> Unit
) {
    val context          = LocalContext.current
    val clipboardManager = LocalClipboardManager.current

    // Hitung sisa waktu berdasarkan created_at dari server
    var remainingSeconds by remember(createdAt) {
        mutableStateOf(calculateRemainingSeconds(createdAt))
    }

    LaunchedEffect(createdAt) {
        while (remainingSeconds > 0) {
            delay(1000)
            remainingSeconds--
        }
    }

    val hours = TimeUnit.SECONDS.toHours(remainingSeconds)
    val minutes = TimeUnit.SECONDS.toMinutes(remainingSeconds) % 60
    val seconds = remainingSeconds % 60
    val timerText = String.format("%02d:%02d:%02d", hours, minutes, seconds)

    val accountNumber = "1234567890"
    val accountHolder = "WearWoreWorn Store"
    val bankName = "BCA"

    Scaffold(
        containerColor = Color.White
    ) { innerPadding ->
        Column(
            modifier = Modifier
                .fillMaxSize()
                .padding(innerPadding)
                .padding(24.dp),
            horizontalAlignment = Alignment.CenterHorizontally,
            verticalArrangement = Arrangement.Center
        ) {
            Icon(
                Icons.Default.CheckCircle,
                contentDescription = null,
                tint = Color(0xFF4CAF50),
                modifier = Modifier.size(80.dp)
            )
            
            Spacer(modifier = Modifier.height(16.dp))
            
            Text(
                "Pesanan Berhasil!",
                fontSize = 24.sp,
                fontWeight = FontWeight.Bold
            )
            
            Text(
                "Segera lakukan pembayaran sebelum waktu habis",
                fontSize = 14.sp,
                color = Color.Gray,
                textAlign = TextAlign.Center
            )

            Spacer(modifier = Modifier.height(24.dp))

            Card(
                colors = CardDefaults.cardColors(containerColor = Color(0xFFFFF3E0)),
                shape = RoundedCornerShape(12.dp)
            ) {
                Column(
                    modifier = Modifier.padding(horizontal = 32.dp, vertical = 12.dp),
                    horizontalAlignment = Alignment.CenterHorizontally
                ) {
                    Text("Batas Waktu Pembayaran", fontSize = 12.sp, color = Color(0xFFE65100))
                    Text(
                        timerText,
                        fontSize = 28.sp,
                        fontWeight = FontWeight.Black,
                        color = Color(0xFFE65100)
                    )
                }
            }

            Spacer(modifier = Modifier.height(32.dp))

            Card(
                modifier = Modifier.fillMaxWidth(),
                colors = CardDefaults.cardColors(containerColor = Color(0xFFF5F5F5)),
                shape = RoundedCornerShape(16.dp)
            ) {
                Column(modifier = Modifier.padding(16.dp)) {
                    Text("Detail Pembayaran", fontWeight = FontWeight.Bold, fontSize = 14.sp)
                    Spacer(modifier = Modifier.height(12.dp))
                    
                    Row(Modifier.fillMaxWidth(), horizontalArrangement = Arrangement.SpaceBetween) {
                        Text("Total Tagihan", color = Color.Gray)
                        Text(Formatter.formatRupiah(totalPrice), fontWeight = FontWeight.Bold)
                    }
                    
                    HorizontalDivider(Modifier.padding(vertical = 12.dp))
                    
                    Text("Transfer ke Rekening:", color = Color.Gray, fontSize = 12.sp)
                    Spacer(modifier = Modifier.height(4.dp))
                    Text("$bankName - $accountHolder", fontWeight = FontWeight.SemiBold)
                    
                    Row(
                        modifier = Modifier.fillMaxWidth(),
                        verticalAlignment = Alignment.CenterVertically,
                        horizontalArrangement = Arrangement.SpaceBetween
                    ) {
                        Text(
                            accountNumber,
                            fontSize = 22.sp,
                            fontWeight = FontWeight.Black,
                            letterSpacing = 1.sp
                        )
                        IconButton(onClick = {
                            clipboardManager.setText(AnnotatedString(accountNumber))
                        }) {
                            Icon(Icons.Default.ContentCopy, contentDescription = "Salin", modifier = Modifier.size(20.dp))
                        }
                    }
                }
            }

            Spacer(modifier = Modifier.height(40.dp))

            Button(
                onClick = {
                    val message = "Halo Admin WearWoreWorn, saya ingin konfirmasi pembayaran untuk Order #$orderId sebesar ${Formatter.formatRupiah(totalPrice)}."
                    val intent = Intent(Intent.ACTION_VIEW).apply {
                        data = Uri.parse("https://api.whatsapp.com/send?phone=6281234567890&text=${Uri.encode(message)}")
                    }
                    context.startActivity(intent)
                },
                modifier = Modifier.fillMaxWidth().height(56.dp),
                colors = ButtonDefaults.buttonColors(containerColor = Color(0xFF25D366)),
                shape = RoundedCornerShape(16.dp)
            ) {
                Text("Konfirmasi via WhatsApp", color = Color.White, fontWeight = FontWeight.Bold)
            }

            Spacer(modifier = Modifier.height(12.dp))

            OutlinedButton(
                onClick  = onBack,
                modifier = Modifier.fillMaxWidth().height(56.dp),
                shape    = RoundedCornerShape(16.dp),
                border   = BorderStroke(1.dp, Color.Black)
            ) {
                if (isFromMyOrders) {
                    Icon(Icons.AutoMirrored.Filled.ArrowBack, contentDescription = null, tint = Color.Black)
                    Spacer(modifier = Modifier.width(8.dp))
                    Text("Kembali ke Pesanan", color = Color.Black, fontWeight = FontWeight.Bold)
                } else {
                    Icon(Icons.Default.Home, contentDescription = null, tint = Color.Black)
                    Spacer(modifier = Modifier.width(8.dp))
                    Text("Kembali ke Beranda", color = Color.Black, fontWeight = FontWeight.Bold)
                }
            }
        }
    }
}
