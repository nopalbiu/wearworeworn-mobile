package com.wearworeworn.ui.screens

import androidx.compose.foundation.layout.*
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.automirrored.filled.ArrowBack
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.wearworeworn.model.Order
import com.wearworeworn.model.OrderDetail
import com.wearworeworn.util.Formatter
import com.wearworeworn.viewmodel.OrderViewModel

/**
 * Layar detail pesanan yang sudah divalidasi admin.
 * Menampilkan info pengiriman, item list, dan rincian pembayaran dari database.
 */
@Composable
fun OrderPaymentScreen(
    order:          Order,
    orderViewModel: OrderViewModel,
    onBack:         () -> Unit
) {
    val detail          = orderViewModel.orderDetail.value
    val isDetailLoading = orderViewModel.isDetailLoading.value

    LaunchedEffect(order.id) { orderViewModel.loadOrderDetail(order.id) }

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
                    Text(
                        "DETAIL PESANAN",
                        fontSize      = 16.sp,
                        fontWeight    = FontWeight.Black,
                        letterSpacing = 1.sp
                    )
                }
            }
        },
        containerColor = Color(0xFFF8F8F8)
    ) { innerPadding ->
        if (isDetailLoading) {
            Box(
                modifier         = Modifier.fillMaxSize().padding(innerPadding),
                contentAlignment = Alignment.Center
            ) {
                CircularProgressIndicator(color = Color.Black)
            }
        } else {
            Column(
                modifier = Modifier
                    .fillMaxSize()
                    .padding(innerPadding)
                    .verticalScroll(rememberScrollState())
                    .padding(horizontal = 20.dp, vertical = 20.dp)
            ) {
                OrderDetailView(order = order, detail = detail)

                Spacer(modifier = Modifier.height(16.dp))

                OutlinedButton(
                    onClick  = onBack,
                    modifier = Modifier.fillMaxWidth().height(52.dp),
                    shape    = RoundedCornerShape(16.dp)
                ) {
                    Text("Kembali ke Pesanan", fontWeight = FontWeight.SemiBold)
                }

                Spacer(modifier = Modifier.height(24.dp))
            }
        }
    }
}

// ─── Order Detail View (Validated Orders) ────────────────────────────────────
@Composable
private fun OrderDetailView(order: Order, detail: OrderDetail?) {

    @Composable
    fun SectionTitle(text: String) {
        Text(text = text, fontSize = 11.sp, fontWeight = FontWeight.Black, color = Color.Gray, letterSpacing = 2.sp)
        Spacer(modifier = Modifier.height(10.dp))
    }

    // ─── Title ───────────────────────────────────────────────────────────
    Text(
        "ORDER DETAILS",
        fontSize   = 20.sp,
        fontWeight = FontWeight.Black
    )
    Text(
        "#TRX-${order.id.toString().padStart(4, '0')}",
        fontSize   = 15.sp,
        fontWeight = FontWeight.Bold,
        color      = Color(0xFF1565C0)
    )

    // Status badge
    val statusLabel = when (order.status.lowercase()) {
        "paid", "lunas"  -> "✓  Sudah Dibayar"
        "processing"     -> "⚙  Sedang Diproses"
        "shipped"        -> "🚚  Dalam Pengiriman"
        "delivered"      -> "✅  Selesai"
        "cancelled"      -> "✕  Dibatalkan"
        else             -> order.status
    }
    val statusColor = when (order.status.lowercase()) {
        "paid", "lunas"  -> Color(0xFF4CAF50)
        "shipped"        -> Color(0xFF2196F3)
        "delivered"      -> Color(0xFF4CAF50)
        "cancelled"      -> Color(0xFFEF5350)
        else             -> Color.Gray
    }
    Spacer(modifier = Modifier.height(8.dp))
    Surface(color = statusColor.copy(alpha = 0.12f), shape = RoundedCornerShape(20.dp)) {
        Text(
            statusLabel,
            color      = statusColor,
            fontSize   = 12.sp,
            fontWeight = FontWeight.Bold,
            modifier   = Modifier.padding(horizontal = 14.dp, vertical = 6.dp)
        )
    }

    Spacer(modifier = Modifier.height(20.dp))

    // ─── Shipping Information ─────────────────────────────────────────────
    Card(
        modifier  = Modifier.fillMaxWidth(),
        colors    = CardDefaults.cardColors(containerColor = Color.White),
        shape     = RoundedCornerShape(16.dp),
        elevation = CardDefaults.cardElevation(2.dp)
    ) {
        Column(modifier = Modifier.padding(18.dp)) {
            SectionTitle("SHIPPING INFORMATION")

            Row(modifier = Modifier.fillMaxWidth(), horizontalArrangement = Arrangement.spacedBy(12.dp)) {
                Column(modifier = Modifier.weight(1f)) {
                    Text("RECEIVER NAME", fontSize = 10.sp, color = Color.Gray, letterSpacing = 1.sp)
                    Spacer(modifier = Modifier.height(4.dp))
                    Text(detail?.recipientName ?: "-", fontSize = 14.sp, fontWeight = FontWeight.SemiBold)
                }
                Column(modifier = Modifier.weight(1f)) {
                    Text("PHONE NUMBER", fontSize = 10.sp, color = Color.Gray, letterSpacing = 1.sp)
                    Spacer(modifier = Modifier.height(4.dp))
                    Text(detail?.phone ?: "-", fontSize = 14.sp, fontWeight = FontWeight.SemiBold)
                }
            }

            Spacer(modifier = Modifier.height(14.dp))
            Text("DELIVERY ADDRESS", fontSize = 10.sp, color = Color.Gray, letterSpacing = 1.sp)
            Spacer(modifier = Modifier.height(4.dp))
            Text(detail?.deliveryAddress ?: "-", fontSize = 14.sp, fontWeight = FontWeight.SemiBold)

            Spacer(modifier = Modifier.height(14.dp))
            Text("SHIPPING COURIER", fontSize = 10.sp, color = Color.Gray, letterSpacing = 1.sp)
            Spacer(modifier = Modifier.height(6.dp))
            if (!detail?.courier.isNullOrBlank() && detail?.courier != "-") {
                Surface(color = Color(0xFF1A3A6B), shape = RoundedCornerShape(8.dp)) {
                    Text(
                        detail!!.courier.uppercase(),
                        fontSize   = 12.sp,
                        fontWeight = FontWeight.Bold,
                        color      = Color(0xFF4A90D9),
                        modifier   = Modifier.padding(horizontal = 12.dp, vertical = 6.dp)
                    )
                }
            } else {
                Text("-", fontSize = 14.sp, color = Color.DarkGray)
            }
        }
    }

    Spacer(modifier = Modifier.height(12.dp))

    // ─── Item List ────────────────────────────────────────────────────────
    Card(
        modifier  = Modifier.fillMaxWidth(),
        colors    = CardDefaults.cardColors(containerColor = Color.White),
        shape     = RoundedCornerShape(16.dp),
        elevation = CardDefaults.cardElevation(2.dp)
    ) {
        Column(modifier = Modifier.padding(18.dp)) {
            SectionTitle("ITEM LIST")

            if (detail != null && detail.items.isNotEmpty()) {
                detail.items.forEach { item ->
                    Row(
                        modifier              = Modifier.fillMaxWidth().padding(vertical = 6.dp),
                        horizontalArrangement = Arrangement.SpaceBetween,
                        verticalAlignment     = Alignment.CenterVertically
                    ) {
                        Row(modifier = Modifier.weight(1f), verticalAlignment = Alignment.CenterVertically) {
                            Text(item.productName, fontSize = 14.sp, fontWeight = FontWeight.Medium)
                            if (item.size != null) {
                                Text(" (${item.size})", fontSize = 12.sp, color = Color.Gray)
                            }
                            Text(
                                " x${item.quantity}",
                                fontSize   = 12.sp,
                                color      = Color(0xFF1565C0),
                                fontWeight = FontWeight.Bold
                            )
                        }
                        Spacer(modifier = Modifier.width(8.dp))
                        Text(
                            Formatter.formatRupiah(
                                item.subtotal.takeIf { it > 0 } ?: (item.price * item.quantity)
                            ),
                            fontSize   = 14.sp,
                            fontWeight = FontWeight.SemiBold
                        )
                    }
                    if (detail.items.last() != item) {
                        HorizontalDivider(color = Color(0xFFF5F5F5))
                    }
                }
            } else {
                // Fallback jika API tidak mengembalikan items
                Row(
                    modifier              = Modifier.fillMaxWidth().padding(vertical = 6.dp),
                    horizontalArrangement = Arrangement.SpaceBetween
                ) {
                    Text("Produk", fontSize = 14.sp, color = Color.DarkGray)
                    Text(
                        Formatter.formatRupiah(detail?.productSubtotal ?: order.totalPrice),
                        fontSize   = 14.sp,
                        fontWeight = FontWeight.SemiBold
                    )
                }
            }
        }
    }

    Spacer(modifier = Modifier.height(12.dp))

    // ─── Payment Details ──────────────────────────────────────────────────
    Card(
        modifier  = Modifier.fillMaxWidth(),
        colors    = CardDefaults.cardColors(containerColor = Color.White),
        shape     = RoundedCornerShape(16.dp),
        elevation = CardDefaults.cardElevation(2.dp)
    ) {
        Column(modifier = Modifier.padding(18.dp)) {
            SectionTitle("PAYMENT DETAILS")

            val subtotal    = detail?.productSubtotal?.takeIf { it > 0 }
                ?: (order.totalPrice - (detail?.shippingFee ?: 0.0) - (detail?.shippingInsurance ?: 0.0))
            val shippingFee = detail?.shippingFee ?: 0.0
            val insurance   = detail?.shippingInsurance ?: 0.0

            PaymentDetailRow("Product Subtotal",  subtotal)
            PaymentDetailRow("Shipping Fee",       shippingFee)
            PaymentDetailRow("Shipping Insurance", insurance)

            HorizontalDivider(color = Color(0xFFF0F0F0), modifier = Modifier.padding(vertical = 10.dp))

            Row(
                modifier              = Modifier.fillMaxWidth(),
                horizontalArrangement = Arrangement.SpaceBetween,
                verticalAlignment     = Alignment.CenterVertically
            ) {
                Text("GRAND TOTAL", fontSize = 14.sp, fontWeight = FontWeight.Black)
                Text(
                    Formatter.formatRupiah(order.totalPrice),
                    fontSize   = 16.sp,
                    fontWeight = FontWeight.Black,
                    color      = Color(0xFF1565C0)
                )
            }
        }
    }
}

@Composable
private fun PaymentDetailRow(label: String, amount: Double) {
    Row(
        modifier              = Modifier.fillMaxWidth().padding(vertical = 4.dp),
        horizontalArrangement = Arrangement.SpaceBetween
    ) {
        Text(label, fontSize = 13.sp, color = Color.Gray)
        Text(Formatter.formatRupiah(amount), fontSize = 13.sp)
    }
}
