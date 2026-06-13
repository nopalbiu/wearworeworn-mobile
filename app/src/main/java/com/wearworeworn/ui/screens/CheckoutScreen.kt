package com.wearworeworn.ui.screens

import androidx.compose.animation.*
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.text.KeyboardActions
import androidx.compose.foundation.text.KeyboardOptions
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.automirrored.filled.ArrowBack
import androidx.compose.material.icons.filled.CheckCircle
import androidx.compose.material.icons.filled.Home
import androidx.compose.material.icons.filled.LocationOn
import androidx.compose.material.icons.filled.Person
import androidx.compose.material.icons.filled.Phone
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.focus.FocusDirection
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.platform.LocalFocusManager
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.input.ImeAction
import androidx.compose.ui.text.input.KeyboardType
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.wearworeworn.util.Formatter
import com.wearworeworn.viewmodel.CartViewModel
import com.wearworeworn.viewmodel.OrderViewModel

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun CheckoutScreen(
    cartViewModel:  CartViewModel,
    orderViewModel: OrderViewModel,
    onBack:         () -> Unit,
    onOrderSuccess: () -> Unit
) {
    val items      = cartViewModel.cartItems.value
    val total      = cartViewModel.totalPrice
    val isLoading  = orderViewModel.isLoading.value
    val errorMsg   = orderViewModel.errorMessage.value
    val focusMgr   = LocalFocusManager.current

    var recipientName   by remember { mutableStateOf("") }
    var shippingAddress by remember { mutableStateOf("") }
    var phone           by remember { mutableStateOf("") }
    var note            by remember { mutableStateOf("") }

    var orderPlaced by remember { mutableStateOf(false) }

    LaunchedEffect(Unit) { orderViewModel.resetState() }

    // ─── Success State ────────────────────────────────────────────────────────
    AnimatedVisibility(
        visible = orderPlaced,
        enter   = fadeIn() + scaleIn(),
        exit    = fadeOut()
    ) {
        Box(
            modifier          = Modifier.fillMaxSize().background(Color.White),
            contentAlignment  = Alignment.Center
        ) {
            Column(
                horizontalAlignment = Alignment.CenterHorizontally,
                modifier            = Modifier.padding(32.dp)
            ) {
                Box(
                    modifier          = Modifier.size(100.dp).background(Color(0xFF1A1A1A), CircleShape),
                    contentAlignment  = Alignment.Center
                ) {
                    Icon(
                        Icons.Default.CheckCircle,
                        contentDescription = null,
                        tint               = Color.White,
                        modifier           = Modifier.size(56.dp)
                    )
                }
                Spacer(modifier = Modifier.height(24.dp))
                Text("Pesanan Dibuat!", fontSize = 26.sp, fontWeight = FontWeight.Black)
                Spacer(modifier = Modifier.height(12.dp))
                Text(
                    "Pesananmu sedang diproses oleh admin.\nKamu akan dihubungi untuk konfirmasi pembayaran.",
                    fontSize  = 14.sp,
                    color     = Color.Gray,
                    textAlign = TextAlign.Center,
                    lineHeight = 22.sp
                )
                Spacer(modifier = Modifier.height(40.dp))
                Button(
                    onClick  = onOrderSuccess,
                    modifier = Modifier.fillMaxWidth().height(56.dp),
                    colors   = ButtonDefaults.buttonColors(containerColor = Color.Black),
                    shape    = RoundedCornerShape(16.dp)
                ) {
                    Icon(Icons.Default.Home, contentDescription = null, tint = Color.White)
                    Spacer(modifier = Modifier.width(8.dp))
                    Text("Kembali ke Beranda", color = Color.White, fontWeight = FontWeight.Bold)
                }
            }
        }
    }

    // ─── Checkout Form ────────────────────────────────────────────────────────
    AnimatedVisibility(visible = !orderPlaced, enter = fadeIn(), exit = fadeOut()) {
        Scaffold(
            modifier       = Modifier.fillMaxSize(),
            containerColor = Color(0xFFF8F8F8),
            topBar = {
                Surface(modifier = Modifier.statusBarsPadding(), color = Color.White, shadowElevation = 2.dp) {
                    Row(
                        modifier          = Modifier.fillMaxWidth().padding(horizontal = 8.dp, vertical = 8.dp),
                        verticalAlignment = Alignment.CenterVertically
                    ) {
                        IconButton(onClick = onBack) {
                            Icon(Icons.AutoMirrored.Filled.ArrowBack, contentDescription = "Kembali")
                        }
                        Text("Checkout", fontWeight = FontWeight.Black, fontSize = 20.sp, modifier = Modifier.padding(start = 8.dp))
                    }
                }
            },
            bottomBar = {
                Surface(modifier = Modifier.navigationBarsPadding().fillMaxWidth(), color = Color.White, shadowElevation = 16.dp) {
                    Column(modifier = Modifier.padding(horizontal = 24.dp, vertical = 16.dp)) {
                        Row(
                            modifier              = Modifier.fillMaxWidth(),
                            horizontalArrangement = Arrangement.SpaceBetween,
                            verticalAlignment     = Alignment.CenterVertically
                        ) {
                            Text("Total Pembayaran", color = Color.Gray, fontSize = 13.sp)
                            Text(Formatter.formatRupiah(total), fontSize = 20.sp, fontWeight = FontWeight.Black)
                        }
                        Spacer(modifier = Modifier.height(12.dp))
                        Button(
                            onClick  = {
                                focusMgr.clearFocus()
                                orderViewModel.createOrder(
                                    recipientName   = recipientName,
                                    shippingAddress = shippingAddress,
                                    phone           = phone,
                                    note            = note,
                                    onSuccess       = { _ ->
                                        cartViewModel.clearLocalCart()
                                        orderPlaced = true
                                    },
                                    onError         = { /* errorMsg state handles display */ }
                                )
                            },
                            enabled  = !isLoading,
                            modifier = Modifier.fillMaxWidth().height(56.dp),
                            colors   = ButtonDefaults.buttonColors(
                                containerColor         = Color.Black,
                                disabledContainerColor = Color(0xFF444444)
                            ),
                            shape = RoundedCornerShape(16.dp)
                        ) {
                            if (isLoading) {
                                CircularProgressIndicator(modifier = Modifier.size(22.dp), color = Color.White, strokeWidth = 2.5.dp)
                            } else {
                                Text("BUAT PESANAN", color = Color.White, fontWeight = FontWeight.Bold, letterSpacing = 1.sp)
                            }
                        }
                    }
                }
            }
        ) { innerPadding ->
            Column(
                modifier = Modifier
                    .padding(innerPadding)
                    .verticalScroll(rememberScrollState())
                    .padding(horizontal = 16.dp, vertical = 12.dp),
                verticalArrangement = Arrangement.spacedBy(12.dp)
            ) {

                // ── Order Summary Card ─────────────────────────────────────────
                Card(
                    colors = CardDefaults.cardColors(containerColor = Color.White),
                    shape  = RoundedCornerShape(16.dp),
                    elevation = CardDefaults.cardElevation(1.dp)
                ) {
                    Column(modifier = Modifier.padding(16.dp)) {
                        Text("Ringkasan Pesanan", fontWeight = FontWeight.Bold, fontSize = 14.sp, letterSpacing = 0.5.sp)
                        Spacer(modifier = Modifier.height(12.dp))
                        items.forEach { item ->
                            Row(
                                modifier              = Modifier.fillMaxWidth().padding(vertical = 4.dp),
                                horizontalArrangement = Arrangement.SpaceBetween
                            ) {
                                Text(
                                    text     = "${item.product.name} (${item.variant.size?.name ?: "-"}) ×${item.quantity}",
                                    fontSize = 13.sp,
                                    color    = Color.DarkGray,
                                    modifier = Modifier.weight(1f)
                                )
                                Spacer(modifier = Modifier.width(8.dp))
                                Text(
                                    text     = Formatter.formatRupiah(item.subtotal),
                                    fontSize = 13.sp,
                                    fontWeight = FontWeight.SemiBold
                                )
                            }
                        }
                        HorizontalDivider(modifier = Modifier.padding(vertical = 8.dp), color = Color(0xFFEEEEEE))
                        Row(
                            modifier              = Modifier.fillMaxWidth(),
                            horizontalArrangement = Arrangement.SpaceBetween
                        ) {
                            Text("Total", fontWeight = FontWeight.Bold, fontSize = 14.sp)
                            Text(Formatter.formatRupiah(total), fontWeight = FontWeight.Black, fontSize = 14.sp)
                        }
                    }
                }

                Spacer(modifier = Modifier.height(4.dp))
                Text(
                    "Data Pengiriman",
                    fontWeight = FontWeight.Black,
                    fontSize   = 16.sp,
                    letterSpacing = 0.5.sp
                )

                val fieldColors = TextFieldDefaults.colors(
                    focusedContainerColor   = Color.White,
                    unfocusedContainerColor = Color.White,
                    focusedIndicatorColor   = Color.Black,
                    unfocusedIndicatorColor = Color(0xFFDDDDDD),
                    cursorColor             = Color.Black,
                    focusedTextColor        = Color.Black,
                    unfocusedTextColor      = Color.Black,
                    focusedPlaceholderColor   = Color.LightGray,
                    unfocusedPlaceholderColor = Color.LightGray
                )

                // Nama Penerima
                TextField(
                    value         = recipientName,
                    onValueChange = { recipientName = it },
                    modifier      = Modifier.fillMaxWidth(),
                    label         = { Text("Nama Penerima*") },
                    leadingIcon   = { Icon(Icons.Default.Person, null, modifier = Modifier.size(20.dp)) },
                    colors        = fieldColors,
                    singleLine    = true,
                    shape         = RoundedCornerShape(topStart = 12.dp, topEnd = 12.dp),
                    keyboardOptions = KeyboardOptions(imeAction = ImeAction.Next),
                    keyboardActions = KeyboardActions(onNext = { focusMgr.moveFocus(FocusDirection.Down) })
                )

                // Nomor HP
                TextField(
                    value         = phone,
                    onValueChange = { phone = it },
                    modifier      = Modifier.fillMaxWidth(),
                    label         = { Text("Nomor HP*") },
                    leadingIcon   = { Icon(Icons.Default.Phone, null, modifier = Modifier.size(20.dp)) },
                    colors        = fieldColors,
                    singleLine    = true,
                    shape         = RoundedCornerShape(12.dp),
                    keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Phone, imeAction = ImeAction.Next),
                    keyboardActions = KeyboardActions(onNext = { focusMgr.moveFocus(FocusDirection.Down) })
                )

                // Alamat Pengiriman
                TextField(
                    value         = shippingAddress,
                    onValueChange = { shippingAddress = it },
                    modifier      = Modifier.fillMaxWidth().heightIn(min = 100.dp),
                    label         = { Text("Alamat Lengkap*") },
                    leadingIcon   = { Icon(Icons.Default.LocationOn, null, modifier = Modifier.size(20.dp)) },
                    colors        = fieldColors,
                    maxLines      = 4,
                    shape         = RoundedCornerShape(12.dp),
                    keyboardOptions = KeyboardOptions(imeAction = ImeAction.Next),
                    keyboardActions = KeyboardActions(onNext = { focusMgr.moveFocus(FocusDirection.Down) })
                )

                // Catatan
                TextField(
                    value         = note,
                    onValueChange = { note = it },
                    modifier      = Modifier.fillMaxWidth(),
                    label         = { Text("Catatan (opsional)") },
                    placeholder   = { Text("Misal: titip ke tetangga, dll.", fontSize = 13.sp) },
                    colors        = fieldColors,
                    maxLines      = 3,
                    shape         = RoundedCornerShape(12.dp),
                    keyboardOptions = KeyboardOptions(imeAction = ImeAction.Done),
                    keyboardActions = KeyboardActions(onDone = { focusMgr.clearFocus() })
                )

                // Error Message
                if (errorMsg != null) {
                    Surface(
                        color = Color(0xFFFFEEEE),
                        shape = RoundedCornerShape(12.dp)
                    ) {
                        Text(
                            text     = errorMsg,
                            color    = Color(0xFFCC0000),
                            fontSize = 13.sp,
                            modifier = Modifier.padding(horizontal = 16.dp, vertical = 10.dp)
                        )
                    }
                }

                // Transfer Info
                Card(
                    colors = CardDefaults.cardColors(containerColor = Color(0xFFFFF8E1)),
                    shape  = RoundedCornerShape(12.dp)
                ) {
                    Column(modifier = Modifier.padding(16.dp)) {
                        Text("📋 Informasi Pembayaran", fontWeight = FontWeight.Bold, fontSize = 13.sp, color = Color(0xFF8B6914))
                        Spacer(modifier = Modifier.height(6.dp))
                        Text(
                            "Pembayaran dilakukan via transfer manual. Admin akan menghubungi kamu setelah pesanan diterima untuk konfirmasi.",
                            fontSize  = 12.sp,
                            color     = Color(0xFF8B6914),
                            lineHeight = 18.sp
                        )
                    }
                }

                Spacer(modifier = Modifier.height(80.dp))
            }
        }
    }
}
