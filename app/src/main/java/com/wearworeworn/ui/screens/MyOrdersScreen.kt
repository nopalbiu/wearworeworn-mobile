package com.wearworeworn.ui.screens

import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.automirrored.filled.ArrowBack
import androidx.compose.material.icons.filled.ShoppingBag
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.wearworeworn.model.Order
import com.wearworeworn.util.Formatter
import com.wearworeworn.viewmodel.OrderViewModel
import java.text.SimpleDateFormat
import java.util.Locale

@Composable
fun MyOrdersScreen(
    orderViewModel:    OrderViewModel,
    onBack:            () -> Unit,
    // Belum dibayar/pending → ke halaman instruksi pembayaran (CheckoutSuccess-like)
    onPendingPayment:  (Order) -> Unit,
    // Sudah divalidasi admin → ke halaman detail order dari database
    onPaymentDetail:   (Order) -> Unit
) {
    val orders    = orderViewModel.orders.value
    val isLoading = orderViewModel.isOrdersLoading.value

    LaunchedEffect(Unit) { orderViewModel.loadOrders() }

    Scaffold(
        topBar = {
            Surface(color = Color.White, shadowElevation = 2.dp) {
                Row(
                    modifier          = Modifier
                        .fillMaxWidth()
                        .statusBarsPadding()
                        .padding(horizontal = 8.dp, vertical = 12.dp),
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    IconButton(onClick = onBack) {
                        Icon(Icons.AutoMirrored.Filled.ArrowBack, contentDescription = "Kembali")
                    }
                    Spacer(modifier = Modifier.width(4.dp))
                    Text("MY ORDERS", fontSize = 18.sp, fontWeight = FontWeight.Black, letterSpacing = 1.sp)
                }
            }
        },
        containerColor = Color(0xFFF8F8F8)
    ) { innerPadding ->
        if (isLoading) {
            Box(modifier = Modifier.fillMaxSize().padding(innerPadding), contentAlignment = Alignment.Center) {
                CircularProgressIndicator(color = Color.Black)
            }
        } else if (orders.isEmpty()) {
            Box(modifier = Modifier.fillMaxSize().padding(innerPadding), contentAlignment = Alignment.Center) {
                Column(horizontalAlignment = Alignment.CenterHorizontally) {
                    Icon(Icons.Default.ShoppingBag, contentDescription = null, tint = Color.LightGray, modifier = Modifier.size(72.dp))
                    Spacer(modifier = Modifier.height(16.dp))
                    Text("Belum ada pesanan", fontWeight = FontWeight.Bold, fontSize = 18.sp)
                    Spacer(modifier = Modifier.height(8.dp))
                    Text("Pesanan kamu akan muncul di sini", color = Color.Gray, fontSize = 14.sp, textAlign = TextAlign.Center)
                    
                    val errMsg = orderViewModel.errorMessage.value
                    if (errMsg != null) {
                        Spacer(modifier = Modifier.height(16.dp))
                        Text("DEBUG ERROR:", color = Color.Red, fontSize = 12.sp, fontWeight = FontWeight.Bold)
                        Text(errMsg, color = Color.Red, fontSize = 12.sp, textAlign = TextAlign.Center, modifier = Modifier.padding(horizontal = 16.dp))
                    }
                }
            }
        } else {
            LazyColumn(
                modifier            = Modifier.fillMaxSize().padding(innerPadding),
                contentPadding      = PaddingValues(horizontal = 16.dp, vertical = 12.dp),
                verticalArrangement = Arrangement.spacedBy(12.dp)
            ) {
                items(orders) { order ->
                    OrderCard(
                        order            = order,
                        onPendingPayment = { onPendingPayment(order) },
                        onDetailClick    = { onPaymentDetail(order) }
                    )
                }
                item { Spacer(modifier = Modifier.height(16.dp)) }
            }
        }
    }
}

@Composable
private fun OrderCard(
    order:           Order,
    onPendingPayment: () -> Unit,
    onDetailClick:   () -> Unit
) {
    val statusLabel = when (order.status.lowercase()) {
        "pending"             -> "Pending"
        "menunggu pembayaran" -> "Menunggu Pembayaran"
        "paid", "lunas"       -> "Lunas"
        "processing"          -> "Diproses"
        "shipped"             -> "Dikirim"
        "delivered"           -> "Selesai"
        "cancelled"           -> "Dibatalkan"
        else                  -> order.status
    }

    val isPendingPayment = order.status.lowercase() in listOf("pending", "menunggu pembayaran")

    val statusBg = when {
        isPendingPayment                                         -> Color(0xFFFFF3E0)
        order.status.lowercase() in listOf("paid", "lunas")     -> Color(0xFFE8F5E9)
        order.status.lowercase() == "cancelled"                 -> Color(0xFFFFEBEE)
        else                                                     -> Color(0xFFF5F5F5)
    }
    val statusTextColor = when {
        isPendingPayment                                         -> Color(0xFFE65100)
        order.status.lowercase() in listOf("paid", "lunas")     -> Color(0xFF4CAF50)
        order.status.lowercase() == "cancelled"                 -> Color(0xFFEF5350)
        else                                                     -> Color.Gray
    }

    val dateFormatted = try {
        if (order.createdAt.isNullOrEmpty()) {
            "Tanggal tidak tersedia"
        } else {
            val sdf  = SimpleDateFormat("yyyy-MM-dd'T'HH:mm:ss.SSSSSS'Z'", Locale.getDefault())
            val date = sdf.parse(order.createdAt)
            val out  = SimpleDateFormat("dd MMM yyyy", Locale("id"))
            if (date != null) out.format(date) else order.createdAt.take(10)
        }
    } catch (_: Exception) {
        order.createdAt?.take(10) ?: "Tanggal tidak tersedia"
    }

    Card(
        modifier  = Modifier.fillMaxWidth(),
        colors    = CardDefaults.cardColors(containerColor = Color.White),
        shape     = RoundedCornerShape(16.dp),
        elevation = CardDefaults.cardElevation(2.dp)
    ) {
        Column(modifier = Modifier.padding(16.dp)) {
            // ─── Header ──────────────────────────────────────────────────
            Row(
                modifier              = Modifier.fillMaxWidth(),
                verticalAlignment     = Alignment.CenterVertically,
                horizontalArrangement = Arrangement.SpaceBetween
            ) {
                Row(verticalAlignment = Alignment.CenterVertically) {
                    Text(
                        "#TRX-${order.id.toString().padStart(4, '0')}",
                        color      = Color(0xFF1565C0),
                        fontWeight = FontWeight.Bold,
                        fontSize   = 14.sp
                    )
                    Spacer(modifier = Modifier.width(8.dp))
                    Text(dateFormatted, color = Color.Gray, fontSize = 12.sp)
                }
                Surface(color = statusBg, shape = RoundedCornerShape(20.dp)) {
                    Row(
                        modifier          = Modifier.padding(horizontal = 10.dp, vertical = 5.dp),
                        verticalAlignment = Alignment.CenterVertically
                    ) {
                        if (isPendingPayment) {
                            Box(modifier = Modifier.size(6.dp).background(statusTextColor, CircleShape))
                            Spacer(modifier = Modifier.width(5.dp))
                        }
                        Text(statusLabel, color = statusTextColor, fontSize = 11.sp, fontWeight = FontWeight.Bold)
                    }
                }
            }

            Spacer(modifier = Modifier.height(12.dp))
            HorizontalDivider(color = Color(0xFFF0F0F0))
            Spacer(modifier = Modifier.height(12.dp))

            // ─── Footer ──────────────────────────────────────────────────
            Row(
                modifier              = Modifier.fillMaxWidth(),
                verticalAlignment     = Alignment.Bottom,
                horizontalArrangement = Arrangement.SpaceBetween
            ) {
                Column {
                    Text("1 Product(s)", fontWeight = FontWeight.SemiBold, fontSize = 14.sp)
                    Spacer(modifier = Modifier.height(6.dp))

                    if (isPendingPayment) {
                        // Belum bayar → arahkan ke instruksi pembayaran
                        Surface(
                            color    = Color(0xFFFFF3E0),
                            shape    = RoundedCornerShape(8.dp),
                            modifier = Modifier.clickable { onPendingPayment() }
                        ) {
                            Text(
                                "💳  Lihat instruksi pembayaran",
                                color      = Color(0xFFE65100),
                                fontSize   = 12.sp,
                                fontWeight = FontWeight.SemiBold,
                                modifier   = Modifier.padding(horizontal = 10.dp, vertical = 5.dp)
                            )
                        }
                    } else {
                        // Sudah divalidasi → lihat detail dari database
                        Surface(
                            color    = Color(0xFFE3F2FD),
                            shape    = RoundedCornerShape(8.dp),
                            modifier = Modifier.clickable { onDetailClick() }
                        ) {
                            Text(
                                "📋  Lihat detail pesanan",
                                color      = Color(0xFF1565C0),
                                fontSize   = 12.sp,
                                fontWeight = FontWeight.SemiBold,
                                modifier   = Modifier.padding(horizontal = 10.dp, vertical = 5.dp)
                            )
                        }
                    }
                }
                Text(
                    Formatter.formatRupiah(order.totalPrice),
                    fontWeight = FontWeight.Bold,
                    fontSize   = 16.sp
                )
            }
        }
    }
}
