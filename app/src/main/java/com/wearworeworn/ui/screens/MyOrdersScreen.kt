package com.wearworeworn.ui.screens

import androidx.compose.foundation.Image
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
import androidx.compose.ui.graphics.graphicsLayer
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.wearworeworn.R
import com.wearworeworn.model.Order
import com.wearworeworn.util.Formatter
import com.wearworeworn.viewmodel.AuthViewModel
import com.wearworeworn.viewmodel.OrderViewModel
import java.text.SimpleDateFormat
import java.util.Locale

@Composable
fun MyOrdersScreen(
    orderViewModel:    OrderViewModel,
    authViewModel:     AuthViewModel,
    onBack:            () -> Unit,
    onNavigateToHome:  () -> Unit,
    onPendingPayment:  (Order) -> Unit,
    onPaymentDetail:   (Order) -> Unit
) {
    val orders    = orderViewModel.orders.value
    val isLoading = orderViewModel.isOrdersLoading.value

    LaunchedEffect(Unit) { orderViewModel.loadOrders() }

    Scaffold(
        topBar = {
            Surface(
                modifier = Modifier.statusBarsPadding(),
                color = MaterialTheme.colorScheme.background,
                shadowElevation = 0.dp
            ) {
                Row(
                    modifier          = Modifier
                        .fillMaxWidth()
                        .padding(horizontal = 8.dp, vertical = 8.dp),
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    IconButton(onClick = onBack) {
                        Icon(Icons.AutoMirrored.Filled.ArrowBack, contentDescription = "Kembali", tint = MaterialTheme.colorScheme.onSurface)
                    }
                    Image(
                        painter = painterResource(id = R.drawable.logo_www),
                        contentDescription = "WearWoreWorn Logo",
                        modifier = Modifier
                            .height(28.dp)
                            .widthIn(max = 120.dp)
                            .padding(start = 8.dp),
                        contentScale = ContentScale.Fit
                    )
                    Spacer(modifier = Modifier.width(12.dp))
                    Text("MY ORDERS", fontSize = 14.sp, fontWeight = FontWeight.Black, letterSpacing = 1.sp, color = MaterialTheme.colorScheme.onSurface)
                }
            }
        },
        containerColor = MaterialTheme.colorScheme.background
    ) { innerPadding ->
        if (isLoading) {
            Box(modifier = Modifier.fillMaxSize().padding(innerPadding), contentAlignment = Alignment.Center) {
                CircularProgressIndicator(color = MaterialTheme.colorScheme.primary)
            }
        } else if (orders.isEmpty()) {
            Box(modifier = Modifier.fillMaxSize().padding(innerPadding), contentAlignment = Alignment.Center) {
                Column(horizontalAlignment = Alignment.CenterHorizontally, modifier = Modifier.padding(32.dp)) {
                    Surface(
                        color = MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.3f),
                        shape = CircleShape,
                        modifier = Modifier.size(120.dp)
                    ) {
                        Box(contentAlignment = Alignment.Center) {
                            Icon(Icons.Default.ShoppingBag, contentDescription = null, tint = MaterialTheme.colorScheme.onSurfaceVariant.copy(alpha = 0.4f), modifier = Modifier.size(48.dp))
                        }
                    }
                    Spacer(modifier = Modifier.height(24.dp))
                    Text("NO ORDERS YET", fontWeight = FontWeight.Black, fontSize = 20.sp, color = MaterialTheme.colorScheme.onBackground, letterSpacing = 1.sp)
                    Spacer(modifier = Modifier.height(8.dp))
                    Text("Your purchase history will appear here once you've made an order.", color = MaterialTheme.colorScheme.onSurfaceVariant, fontSize = 14.sp, textAlign = TextAlign.Center, lineHeight = 20.sp)
                    
                    Spacer(modifier = Modifier.height(32.dp))
                    Button(
                        onClick = onNavigateToHome,
                        modifier = Modifier.fillMaxWidth().height(52.dp),
                        shape = RoundedCornerShape(12.dp),
                        colors = ButtonDefaults.buttonColors(containerColor = MaterialTheme.colorScheme.primary)
                    ) {
                        Text("START SHOPPING", fontWeight = FontWeight.Black, letterSpacing = 1.sp)
                    }

                    val errMsg = orderViewModel.errorMessage.value
                    if (errMsg != null) {
                        Spacer(modifier = Modifier.height(24.dp))
                        Text("ERROR LOG", color = MaterialTheme.colorScheme.error, fontSize = 10.sp, fontWeight = FontWeight.Black, letterSpacing = 1.sp)
                        Text(errMsg, color = MaterialTheme.colorScheme.error, fontSize = 12.sp, textAlign = TextAlign.Center, modifier = Modifier.padding(top = 4.dp))
                    }
                }
            }
        } else {
            LazyColumn(
                modifier            = Modifier.fillMaxSize().padding(innerPadding),
                contentPadding      = PaddingValues(horizontal = 16.dp, vertical = 12.dp),
                verticalArrangement = Arrangement.spacedBy(16.dp)
            ) {
                item {
                    val firstName = authViewModel.currentUser.value?.name?.split(" ")?.firstOrNull() ?: ""
                    Column(modifier = Modifier.padding(vertical = 8.dp)) {
                        Text(
                            text       = "HALO, ${firstName.uppercase()}!",
                            fontSize   = 18.sp,
                            fontWeight = FontWeight.Black,
                            letterSpacing = 1.sp,
                            color      = MaterialTheme.colorScheme.onBackground
                        )
                        Text(
                            text     = "Berikut adalah riwayat pesananmu.",
                            fontSize = 13.sp,
                            color    = MaterialTheme.colorScheme.onSurfaceVariant,
                            fontWeight = FontWeight.Medium
                        )
                    }
                }
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
        "pending"             -> "PENDING"
        "menunggu pembayaran" -> "WAITING PAYMENT"
        "paid", "lunas"       -> "PAID"
        "processing"          -> "PROCESSING"
        "shipped"             -> "SHIPPED"
        "delivered"           -> "DELIVERED"
        "cancelled"           -> "CANCELLED"
        else                  -> order.status.uppercase()
    }

    val isPendingPayment = order.status.lowercase() in listOf("pending", "menunggu pembayaran")

    val statusBg = when {
        isPendingPayment                                         -> Color(0xFF2E1A00)
        order.status.lowercase() in listOf("paid", "lunas", "delivered") -> Color(0xFF0D2411)
        order.status.lowercase() == "cancelled"                 -> Color(0xFF2D1012)
        else                                                     -> MaterialTheme.colorScheme.surfaceVariant
    }
    val statusTextColor = when {
        isPendingPayment                                         -> Color(0xFFFFB74D)
        order.status.lowercase() in listOf("paid", "lunas", "delivered") -> Color(0xFF81C784)
        order.status.lowercase() == "cancelled"                 -> Color(0xFFE57373)
        else                                                     -> MaterialTheme.colorScheme.onSurfaceVariant
    }

    val dateFormatted = try {
        if (order.createdAt.isNullOrEmpty()) {
            "DATE UNKNOWN"
        } else {
            val patterns = listOf(
                "yyyy-MM-dd'T'HH:mm:ss.SSSSSS'Z'",
                "yyyy-MM-dd'T'HH:mm:ss.SSS'Z'",
                "yyyy-MM-dd'T'HH:mm:ss'Z'",
                "yyyy-MM-dd HH:mm:ss"
            )
            
            var parsedDate: java.util.Date? = null
            for (pattern in patterns) {
                try {
                    val sdf = SimpleDateFormat(pattern, Locale.getDefault())
                    sdf.timeZone = java.util.TimeZone.getTimeZone("UTC")
                    parsedDate = sdf.parse(order.createdAt)
                    if (parsedDate != null) break
                } catch (_: Exception) {}
            }

            val out = SimpleDateFormat("dd MMM yyyy", Locale.US)
            if (parsedDate != null) out.format(parsedDate).uppercase() else order.createdAt.take(10)
        }
    } catch (_: Exception) {
        order.createdAt?.take(10) ?: "DATE UNKNOWN"
    }

    Card(
        modifier  = Modifier.fillMaxWidth().clickable { 
            if (isPendingPayment) onPendingPayment() else onDetailClick()
        },
        colors    = CardDefaults.cardColors(
            containerColor = MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f)
        ),
        shape     = RoundedCornerShape(12.dp),
        elevation = CardDefaults.cardElevation(0.dp)
    ) {
        Column(modifier = Modifier.padding(18.dp)) {
            Row(
                modifier              = Modifier.fillMaxWidth(),
                verticalAlignment     = Alignment.CenterVertically,
                horizontalArrangement = Arrangement.SpaceBetween
            ) {
                Column {
                    Text(
                        "#TRX-${order.id.toString().padStart(4, '0')}",
                        color      = MaterialTheme.colorScheme.primary,
                        fontWeight = FontWeight.Black,
                        fontSize   = 14.sp,
                        letterSpacing = 0.5.sp
                    )
                    Text(
                        dateFormatted, 
                        color = MaterialTheme.colorScheme.onSurfaceVariant, 
                        fontSize = 11.sp, 
                        fontWeight = FontWeight.Black,
                        letterSpacing = 0.5.sp
                    )
                }
                Surface(color = statusBg.copy(alpha = 0.8f), shape = RoundedCornerShape(12.dp)) {
                    Text(
                        statusLabel, 
                        color = statusTextColor, 
                        fontSize = 10.sp, 
                        fontWeight = FontWeight.Black,
                        modifier = Modifier.padding(horizontal = 10.dp, vertical = 6.dp),
                        letterSpacing = 1.sp
                    )
                }
            }

            Spacer(modifier = Modifier.height(16.dp))
            HorizontalDivider(color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.05f))
            Spacer(modifier = Modifier.height(16.dp))

            Row(
                modifier              = Modifier.fillMaxWidth(),
                verticalAlignment     = Alignment.CenterVertically,
                horizontalArrangement = Arrangement.SpaceBetween
            ) {
                Column {
                    Text(
                        "TOTAL AMOUNT", 
                        fontSize = 10.sp, 
                        fontWeight = FontWeight.Black, 
                        color = MaterialTheme.colorScheme.onSurfaceVariant,
                        letterSpacing = 1.sp
                    )
                    Text(
                        Formatter.formatRupiah(order.totalPrice),
                        fontWeight = FontWeight.Black,
                        fontSize   = 16.sp,
                        color      = MaterialTheme.colorScheme.onSurface
                    )
                }
                
                Icon(
                    imageVector = Icons.AutoMirrored.Filled.ArrowBack,
                    contentDescription = null,
                    tint = MaterialTheme.colorScheme.onSurfaceVariant.copy(alpha = 0.5f),
                    modifier = Modifier
                        .size(16.dp)
                        .graphicsLayer(rotationZ = 180f)
                )
            }
        }
    }
}
