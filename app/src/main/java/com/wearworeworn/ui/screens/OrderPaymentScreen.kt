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
            Surface(color = MaterialTheme.colorScheme.background) {
                Row(
                    modifier          = Modifier
                        .fillMaxWidth()
                        .statusBarsPadding()
                        .padding(horizontal = 8.dp, vertical = 12.dp),
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    IconButton(onClick = onBack) {
                        Icon(
                            Icons.AutoMirrored.Filled.ArrowBack,
                            contentDescription = "Kembali",
                            tint = MaterialTheme.colorScheme.onSurface
                        )
                    }
                    Spacer(modifier = Modifier.width(4.dp))
                    Text(
                        "DETAIL PESANAN",
                        fontSize      = 16.sp,
                        fontWeight    = FontWeight.Black,
                        letterSpacing = 1.sp,
                        color         = MaterialTheme.colorScheme.onSurface
                    )
                }
            }
        },
        containerColor = MaterialTheme.colorScheme.background
    ) { innerPadding ->
        if (isDetailLoading) {
            Box(
                modifier         = Modifier.fillMaxSize().padding(innerPadding),
                contentAlignment = Alignment.Center
            ) {
                CircularProgressIndicator(color = MaterialTheme.colorScheme.primary)
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

                Spacer(modifier = Modifier.height(24.dp))

                Button(
                    onClick  = onBack,
                    modifier = Modifier.fillMaxWidth().height(52.dp),
                    shape    = RoundedCornerShape(12.dp),
                    colors   = ButtonDefaults.buttonColors(
                        containerColor = MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f),
                        contentColor   = MaterialTheme.colorScheme.onSurface
                    )
                ) {
                    Text("Kembali ke Pesanan", fontWeight = FontWeight.Bold, letterSpacing = 0.5.sp)
                }

                Spacer(modifier = Modifier.height(24.dp))
            }
        }
    }
}

@Composable
private fun OrderDetailView(order: Order, detail: OrderDetail?) {

    @Composable
    fun SectionTitle(text: String) {
        Text(
            text = text,
            fontSize = 11.sp,
            fontWeight = FontWeight.Black,
            color = MaterialTheme.colorScheme.onSurfaceVariant,
            letterSpacing = 1.5.sp
        )
        Spacer(modifier = Modifier.height(10.dp))
    }

    Text(
        "ORDER SUMMARY",
        fontSize   = 22.sp,
        fontWeight = FontWeight.Black,
        color      = MaterialTheme.colorScheme.onSurface,
        letterSpacing = (-0.5).sp
    )
    Text(
        "#TRX-${order.id.toString().padStart(4, '0')}",
        fontSize   = 14.sp,
        fontWeight = FontWeight.Bold,
        color      = MaterialTheme.colorScheme.onSurfaceVariant
    )

    val statusLabel = when (order.status.lowercase()) {
        "paid", "lunas"  -> "PAID"
        "processing"     -> "PROCESSING"
        "shipped"        -> "SHIPPED"
        "delivered"      -> "DELIVERED"
        "cancelled"      -> "CANCELLED"
        else             -> order.status.uppercase()
    }
    val (statusBg, statusText) = when (order.status.lowercase()) {
        "paid", "lunas", "delivered" -> Color(0xFF0A2E14) to Color(0xFF81C784)
        "shipped"                    -> Color(0xFF0D2137) to Color(0xFF64B5F6)
        "cancelled"                  -> Color(0xFF2E0D0D) to Color(0xFFE57373)
        "processing"                 -> Color(0xFF2E1A00) to Color(0xFFFFB74D)
        else                         -> MaterialTheme.colorScheme.surfaceVariant to MaterialTheme.colorScheme.onSurfaceVariant
    }
    Spacer(modifier = Modifier.height(12.dp))
    Surface(color = statusBg.copy(alpha = 0.8f), shape = RoundedCornerShape(12.dp)) {
        Text(
            statusLabel,
            color      = statusText,
            fontSize   = 11.sp,
            fontWeight = FontWeight.Black,
            modifier   = Modifier.padding(horizontal = 12.dp, vertical = 6.dp),
            letterSpacing = 1.sp
        )
    }

    Spacer(modifier = Modifier.height(24.dp))

    Card(
        modifier  = Modifier.fillMaxWidth(),
        colors    = CardDefaults.cardColors(
            containerColor = MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f),
            contentColor   = MaterialTheme.colorScheme.onSurface
        ),
        shape     = RoundedCornerShape(12.dp),
        elevation = CardDefaults.cardElevation(defaultElevation = 0.dp)
    ) {
        Column(modifier = Modifier.padding(18.dp)) {
            SectionTitle("SHIPPING INFORMATION")

            Row(modifier = Modifier.fillMaxWidth(), horizontalArrangement = Arrangement.spacedBy(12.dp)) {
                Column(modifier = Modifier.weight(1f)) {
                    Text("RECEIVER", fontSize = 10.sp, color = MaterialTheme.colorScheme.onSurfaceVariant, letterSpacing = 1.sp, fontWeight = FontWeight.Bold)
                    Spacer(modifier = Modifier.height(4.dp))
                    Text(detail?.recipientName ?: "-", fontSize = 14.sp, fontWeight = FontWeight.SemiBold)
                }
                Column(modifier = Modifier.weight(1f)) {
                    Text("PHONE", fontSize = 10.sp, color = MaterialTheme.colorScheme.onSurfaceVariant, letterSpacing = 1.sp, fontWeight = FontWeight.Bold)
                    Spacer(modifier = Modifier.height(4.dp))
                    Text(detail?.phone ?: "-", fontSize = 14.sp, fontWeight = FontWeight.SemiBold)
                }
            }

            Spacer(modifier = Modifier.height(14.dp))
            Text("ADDRESS", fontSize = 10.sp, color = MaterialTheme.colorScheme.onSurfaceVariant, letterSpacing = 1.sp, fontWeight = FontWeight.Bold)
            Spacer(modifier = Modifier.height(4.dp))
            Text(detail?.deliveryAddress ?: "-", fontSize = 14.sp, fontWeight = FontWeight.Medium, lineHeight = 20.sp)

            Spacer(modifier = Modifier.height(14.dp))
            Text("COURIER", fontSize = 10.sp, color = MaterialTheme.colorScheme.onSurfaceVariant, letterSpacing = 1.sp, fontWeight = FontWeight.Bold)
            Spacer(modifier = Modifier.height(6.dp))
            if (!detail?.courier.isNullOrBlank() && detail?.courier != "-") {
                Surface(color = MaterialTheme.colorScheme.surfaceVariant, shape = RoundedCornerShape(12.dp)) {
                    Text(
                        detail!!.courier.uppercase(),
                        fontSize   = 11.sp,
                        fontWeight = FontWeight.Black,
                        color      = MaterialTheme.colorScheme.onSurface,
                        modifier   = Modifier.padding(horizontal = 12.dp, vertical = 6.dp)
                    )
                }
            } else {
                Text("-", fontSize = 14.sp, color = MaterialTheme.colorScheme.onSurfaceVariant)
            }
        }
    }

    Spacer(modifier = Modifier.height(12.dp))

    Card(
        modifier  = Modifier.fillMaxWidth(),
        colors    = CardDefaults.cardColors(
            containerColor = MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f),
            contentColor   = MaterialTheme.colorScheme.onSurface
        ),
        shape     = RoundedCornerShape(12.dp),
        elevation = CardDefaults.cardElevation(defaultElevation = 0.dp)
    ) {
        Column(modifier = Modifier.padding(18.dp)) {
            SectionTitle("ITEM LIST")

            if (detail != null && detail.items.isNotEmpty()) {
                detail.items.forEach { item ->
                    Row(
                        modifier              = Modifier.fillMaxWidth().padding(vertical = 8.dp),
                        horizontalArrangement = Arrangement.SpaceBetween,
                        verticalAlignment     = Alignment.CenterVertically
                    ) {
                        Column(modifier = Modifier.weight(1f)) {
                            Text(item.productName, fontSize = 14.sp, fontWeight = FontWeight.Bold)
                            Row(verticalAlignment = Alignment.CenterVertically) {
                                if (item.size != null) {
                                    Text("SIZE: ${item.size}", fontSize = 11.sp, color = MaterialTheme.colorScheme.onSurfaceVariant, fontWeight = FontWeight.Bold)
                                    Text("  •  ", fontSize = 11.sp, color = MaterialTheme.colorScheme.onSurfaceVariant)
                                }
                                Text(
                                    "QTY: ${item.quantity}",
                                    fontSize   = 11.sp,
                                    color      = MaterialTheme.colorScheme.onSurfaceVariant,
                                    fontWeight = FontWeight.Bold
                                )
                            }
                        }
                        Text(
                            Formatter.formatRupiah(
                                item.subtotal.takeIf { it > 0 } ?: (item.price * item.quantity)
                            ),
                            fontSize   = 14.sp,
                            fontWeight = FontWeight.Black
                        )
                    }
                    if (detail.items.last() != item) {
                        HorizontalDivider(color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.05f), modifier = Modifier.padding(vertical = 4.dp))
                    }
                }
            } else {
                Row(
                    modifier              = Modifier.fillMaxWidth().padding(vertical = 6.dp),
                    horizontalArrangement = Arrangement.SpaceBetween
                ) {
                    Text("Produk", fontSize = 14.sp, color = MaterialTheme.colorScheme.onSurfaceVariant)
                    Text(
                        Formatter.formatRupiah(detail?.productSubtotal ?: order.totalPrice),
                        fontSize   = 14.sp,
                        fontWeight = FontWeight.Black
                    )
                }
            }
        }
    }

    Spacer(modifier = Modifier.height(12.dp))

    Card(
        modifier  = Modifier.fillMaxWidth(),
        colors    = CardDefaults.cardColors(
            containerColor = MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f),
            contentColor   = MaterialTheme.colorScheme.onSurface
        ),
        shape     = RoundedCornerShape(12.dp),
        elevation = CardDefaults.cardElevation(defaultElevation = 0.dp)
    ) {
        Column(modifier = Modifier.padding(18.dp)) {
            SectionTitle("PAYMENT DETAILS")

            val subtotal    = detail?.productSubtotal?.takeIf { it > 0 }
                ?: (order.totalPrice - (detail?.shippingFee ?: 0.0) - (detail?.shippingInsurance ?: 0.0))
            val shippingFee = detail?.shippingFee ?: 0.0
            val insurance   = detail?.shippingInsurance ?: 0.0

            PaymentDetailRow("Subtotal",  subtotal)
            PaymentDetailRow("Shipping",       shippingFee)
            PaymentDetailRow("Insurance", insurance)

            HorizontalDivider(color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.1f), modifier = Modifier.padding(vertical = 12.dp))

            Row(
                modifier              = Modifier.fillMaxWidth(),
                horizontalArrangement = Arrangement.SpaceBetween,
                verticalAlignment     = Alignment.CenterVertically
            ) {
                Text("GRAND TOTAL", fontSize = 14.sp, fontWeight = FontWeight.Black, letterSpacing = 1.sp)
                Text(
                    Formatter.formatRupiah(order.totalPrice),
                    fontSize   = 18.sp,
                    fontWeight = FontWeight.Black,
                    color      = MaterialTheme.colorScheme.primary
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
        Text(label, fontSize = 13.sp, color = MaterialTheme.colorScheme.onSurfaceVariant)
        Text(Formatter.formatRupiah(amount), fontSize = 13.sp, color = MaterialTheme.colorScheme.onSurface)
    }
}
