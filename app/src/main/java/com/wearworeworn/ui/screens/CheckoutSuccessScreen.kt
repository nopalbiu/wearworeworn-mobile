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
import kotlinx.coroutines.launch
import java.text.SimpleDateFormat
import java.util.Locale
import java.util.TimeZone
import java.util.concurrent.TimeUnit

/**
 * Menghitung sisa detik dari deadline pembayaran (24 jam setelah created_at).
 * Jika createdAt kosongnull atau gagal di-parse, fallback ke 24 jam penuh.
 */
private fun calculateRemainingSeconds(createdAt: String?): Long {
    if (createdAt.isNullOrBlank()) return 24 * 3600L

    return try {
        val format = if (createdAt.contains("T")) {
            SimpleDateFormat("yyyy-MM-dd'T'HH:mm:ss", Locale.getDefault())
        } else {
            SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault())
        }
        format.timeZone = TimeZone.getTimeZone("UTC")

        val orderTime = format.parse(createdAt)
        if (orderTime != null) {
            val deadlineMs = orderTime.time + (24 * 3600 * 1000L)
            val nowMs = System.currentTimeMillis()
            val remainingMs = deadlineMs - nowMs
            if (remainingMs > 0) (remainingMs / 1000).coerceAtMost(24 * 3600L) else 0L
        } else {
            24 * 3600L
        }
    } catch (e: Exception) {
        Log.e("CheckoutSuccess", "Failed to parse createdAt: $createdAt", e)
        24 * 3600L
    }
}

@Composable
fun CheckoutSuccessScreen(
    orderId:        Int,
    totalPrice:     Double,
    createdAt:      String? = null,
    paymentMethod:  String? = "",
    isFromMyOrders: Boolean = false,
    onBack:         () -> Unit
) {
    val context          = LocalContext.current
    val clipboardManager = LocalClipboardManager.current
    val snackbarHostState = remember { SnackbarHostState() }
    val scope            = rememberCoroutineScope()

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

    val bankName = when {
        paymentMethod?.contains("BCA", true) == true -> "BCA"
        paymentMethod?.contains("BRI", true) == true -> "BRI"
        paymentMethod?.contains("Mandiri", true) == true -> "MANDIRI"
        else -> "BCA"
    }

    val accountNumber = when (bankName) {
        "BCA"     -> "1234567890"
        "BRI"     -> "0987654321"
        "MANDIRI" -> "1122334455"
        else      -> "1234567890"
    }
    
    val accountHolder = "WearWoreWorn Store"

    Scaffold(
        containerColor = MaterialTheme.colorScheme.background,
        snackbarHost = { SnackbarHost(hostState = snackbarHostState) }
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
                tint = Color(0xFF81C784),
                modifier = Modifier.size(80.dp)
            )
            
            Spacer(modifier = Modifier.height(16.dp))
            
            Text(
                "PESANAN BERHASIL!",
                fontSize = 24.sp,
                fontWeight = FontWeight.Black,
                letterSpacing = 1.sp,
                color = MaterialTheme.colorScheme.onBackground
            )
            
            Text(
                "Segera lakukan pembayaran sebelum waktu habis",
                fontSize = 14.sp,
                color = MaterialTheme.colorScheme.onSurfaceVariant,
                textAlign = TextAlign.Center
            )

            Spacer(modifier = Modifier.height(24.dp))

            Card(
                colors = CardDefaults.cardColors(containerColor = Color(0xFF1A1A1A)),
                shape = RoundedCornerShape(12.dp)
            ) {
                Column(
                    modifier = Modifier.padding(horizontal = 32.dp, vertical = 12.dp),
                    horizontalAlignment = Alignment.CenterHorizontally
                ) {
                    Text("BATAS WAKTU PEMBAYARAN", fontSize = 11.sp, fontWeight = FontWeight.Bold, color = Color.White.copy(alpha = 0.6f))
                    Text(
                        timerText,
                        fontSize = 28.sp,
                        fontWeight = FontWeight.Black,
                        color = Color.White
                    )
                }
            }

            Spacer(modifier = Modifier.height(32.dp))

            Card(
                modifier = Modifier.fillMaxWidth(),
                colors = CardDefaults.cardColors(containerColor = MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f)),
                shape = RoundedCornerShape(12.dp),
                elevation = CardDefaults.cardElevation(0.dp)
            ) {
                Column(modifier = Modifier.padding(16.dp)) {
                    Text(
                        "DETAIL PEMBAYARAN",
                        fontWeight = FontWeight.Black,
                        fontSize = 14.sp,
                        letterSpacing = 1.sp,
                        color = MaterialTheme.colorScheme.onSurface
                    )
                    Spacer(modifier = Modifier.height(16.dp))
                    
                    Row(Modifier.fillMaxWidth(), horizontalArrangement = Arrangement.SpaceBetween) {
                        Text("TOTAL TAGIHAN", fontSize = 12.sp, fontWeight = FontWeight.Bold, color = MaterialTheme.colorScheme.onSurfaceVariant)
                        Text(
                            Formatter.formatRupiah(totalPrice),
                            fontWeight = FontWeight.Black,
                            fontSize = 15.sp,
                            color = MaterialTheme.colorScheme.onSurface
                        )
                    }
                    
                    HorizontalDivider(
                        Modifier.padding(vertical = 12.dp),
                        color = MaterialTheme.colorScheme.outline.copy(alpha = 0.5f)
                    )
                    
                    Text("TRANSFER KE REKENING:", color = MaterialTheme.colorScheme.onSurfaceVariant, fontWeight = FontWeight.Bold, fontSize = 11.sp, letterSpacing = 0.5.sp)
                    Spacer(modifier = Modifier.height(4.dp))
                    Text(
                        "$bankName - $accountHolder".uppercase(),
                        fontWeight = FontWeight.Black,
                        fontSize = 13.sp,
                        color = MaterialTheme.colorScheme.onSurface
                    )
                    
                    Row(
                        modifier = Modifier.fillMaxWidth(),
                        verticalAlignment = Alignment.CenterVertically,
                        horizontalArrangement = Arrangement.SpaceBetween
                    ) {
                        Text(
                            accountNumber,
                            fontSize = 24.sp,
                            fontWeight = FontWeight.Black,
                            letterSpacing = 2.sp,
                            color = MaterialTheme.colorScheme.onSurface
                        )
                        IconButton(onClick = {
                            clipboardManager.setText(AnnotatedString(accountNumber))
                            scope.launch {
                                snackbarHostState.showSnackbar(
                                    message = "Nomor rekening berhasil disalin",
                                    duration = SnackbarDuration.Short
                                )
                            }
                        }) {
                            Icon(
                                Icons.Default.ContentCopy,
                                contentDescription = "Salin",
                                modifier = Modifier.size(20.dp),
                                tint = MaterialTheme.colorScheme.onSurface
                            )
                        }
                    }
                }
            }

            Spacer(modifier = Modifier.height(40.dp))

            Button(
                onClick = {
                    val message = "Halo Admin WearWoreWorn, saya ingin konfirmasi pembayaran untuk Order #$orderId sebesar ${Formatter.formatRupiah(totalPrice)}."
                    val intent = Intent(Intent.ACTION_VIEW).apply {
                        data = Uri.parse("https://wa.me/6285877639320?text=${Uri.encode(message)}")
                    }
                    context.startActivity(intent)
                },
                modifier = Modifier.fillMaxWidth().height(56.dp),
                colors = ButtonDefaults.buttonColors(containerColor = Color(0xFF25D366)),
                shape = RoundedCornerShape(12.dp)
            ) {
                Text("KONFIRMASI VIA WHATSAPP", color = Color.White, fontWeight = FontWeight.Black, letterSpacing = 1.sp)
            }

            Spacer(modifier = Modifier.height(12.dp))

            OutlinedButton(
                onClick  = onBack,
                modifier = Modifier.fillMaxWidth().height(56.dp),
                shape    = RoundedCornerShape(12.dp),
                border   = BorderStroke(1.5.dp, MaterialTheme.colorScheme.onSurface)
            ) {
                if (isFromMyOrders) {
                    Icon(
                        Icons.AutoMirrored.Filled.ArrowBack,
                        contentDescription = null,
                        tint = MaterialTheme.colorScheme.onSurface
                    )
                    Spacer(modifier = Modifier.width(8.dp))
                    Text(
                        "KEMBALI KE PESANAN",
                        color = MaterialTheme.colorScheme.onSurface,
                        fontWeight = FontWeight.Black,
                        letterSpacing = 1.sp
                    )
                } else {
                    Icon(
                        Icons.Default.Home,
                        contentDescription = null,
                        tint = MaterialTheme.colorScheme.onSurface
                    )
                    Spacer(modifier = Modifier.width(8.dp))
                    Text(
                        "KEMBALI KE BERANDA",
                        color = MaterialTheme.colorScheme.onSurface,
                        fontWeight = FontWeight.Black,
                        letterSpacing = 1.sp
                    )
                }
            }
        }
    }
}
