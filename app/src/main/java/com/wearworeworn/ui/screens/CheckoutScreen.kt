package com.wearworeworn.ui.screens

import androidx.compose.foundation.background
import androidx.compose.foundation.BorderStroke
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.text.KeyboardActions
import androidx.compose.foundation.text.KeyboardOptions
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.automirrored.filled.ArrowBack
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
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.wearworeworn.model.Order
import com.wearworeworn.util.Formatter
import com.wearworeworn.viewmodel.CartViewModel
import com.wearworeworn.viewmodel.OrderViewModel

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun CheckoutScreen(
    cartViewModel:  CartViewModel,
    orderViewModel: OrderViewModel,
    onBack:         () -> Unit,
    onOrderSuccess: (Order) -> Unit
) {
    val directBuyItem = cartViewModel.directBuyItem.value
    val isDirectBuy   = directBuyItem != null
    val items      = if (isDirectBuy) listOf(directBuyItem!!) else cartViewModel.cartItems.value
    val total      = if (isDirectBuy) directBuyItem!!.subtotal else cartViewModel.totalPrice
    val isLoading  = orderViewModel.isLoading.value
    val errorMsg   = orderViewModel.errorMessage.value
    val focusMgr   = LocalFocusManager.current

    var recipientName   by remember { mutableStateOf("") }
    var shippingAddress by remember { mutableStateOf("") }
    var phone           by remember { mutableStateOf("") }
    var note            by remember { mutableStateOf("") }
    
    val couriers = listOf("JNE", "J&T", "SiCepat")
    var selectedCourier by remember { mutableStateOf(couriers[0]) }

    val shippingFee = when (selectedCourier) {
        "JNE" -> 15000.0
        "J&T" -> 13000.0
        "SiCepat" -> 12000.0
        else -> 0.0
    }

    val adminFee = when (selectedCourier) {
        "JNE" -> 3000.0
        "J&T" -> 2500.0
        "SiCepat" -> 2000.0
        else -> 0.0
    }

    val grandTotal = total + shippingFee + adminFee

    val paymentMethods = listOf("Transfer Bank (Manual)")
    var selectedPayment by remember { mutableStateOf(paymentMethods[0]) }

    LaunchedEffect(Unit) { orderViewModel.resetState() }

    Scaffold(
        modifier       = Modifier.fillMaxSize(),
        containerColor = Color(0xFFF8F8F8),
        topBar = {
            Surface(modifier = Modifier.statusBarsPadding(), color = Color.White, shadowElevation = 2.dp) {
                Row(
                    modifier          = Modifier.fillMaxWidth().padding(horizontal = 8.dp, vertical = 8.dp),
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    IconButton(onClick = {
                        cartViewModel.clearDirectBuy()
                        onBack()
                    }) {
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
                        Text(Formatter.formatRupiah(grandTotal), fontSize = 20.sp, fontWeight = FontWeight.Black)
                    }
                    Spacer(modifier = Modifier.height(12.dp))
                    Button(
                        onClick  = {
                            focusMgr.clearFocus()

                            if (recipientName.isBlank() || shippingAddress.isBlank() || phone.isBlank()) {
                                return@Button
                            }
                            
                            if (isDirectBuy) {
                                orderViewModel.directBuy(
                                    recipientName   = recipientName,
                                    shippingAddress = shippingAddress,
                                    phone           = phone,
                                    note            = note,
                                    courier         = selectedCourier,
                                    paymentMethod   = selectedPayment,
                                    variantId       = directBuyItem!!.variant.id,
                                    qty             = directBuyItem!!.quantity,
                                    onSuccess       = { order ->
                                        cartViewModel.clearDirectBuy()
                                        onOrderSuccess(order.copy(totalPrice = grandTotal))
                                    },
                                    onError         = { }
                                )
                            } else {
                                orderViewModel.createOrder(
                                    recipientName   = recipientName,
                                    shippingAddress = shippingAddress,
                                    phone           = phone,
                                    note            = note,
                                    courier         = selectedCourier,
                                    paymentMethod   = selectedPayment,
                                    onSuccess       = { order ->
                                        cartViewModel.clearLocalCart()
                                        onOrderSuccess(order.copy(totalPrice = grandTotal))
                                    },
                                    onError         = { }
                                )
                            }
                        },
                        enabled  = !isLoading && recipientName.isNotBlank() && shippingAddress.isNotBlank() && phone.isNotBlank() && phone.length >= 11,
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
            verticalArrangement = Arrangement.spacedBy(16.dp)
        ) {

            Card(
                colors    = CardDefaults.cardColors(containerColor = Color.White),
                shape     = RoundedCornerShape(16.dp),
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
                                text       = Formatter.formatRupiah(item.subtotal),
                                fontSize   = 13.sp,
                                fontWeight = FontWeight.SemiBold
                            )
                        }
                    }
                    HorizontalDivider(modifier = Modifier.padding(vertical = 8.dp), color = Color(0xFFEEEEEE))
                    
                    Row(
                        modifier              = Modifier.fillMaxWidth().padding(vertical = 2.dp),
                        horizontalArrangement = Arrangement.SpaceBetween
                    ) {
                        Text("Ongkos Kirim ($selectedCourier)", fontSize = 13.sp, color = Color.Gray)
                        Text(Formatter.formatRupiah(shippingFee), fontSize = 13.sp)
                    }
                    Row(
                        modifier              = Modifier.fillMaxWidth().padding(vertical = 2.dp),
                        horizontalArrangement = Arrangement.SpaceBetween
                    ) {
                        Text("Biaya Admin", fontSize = 13.sp, color = Color.Gray)
                        Text(Formatter.formatRupiah(adminFee), fontSize = 13.sp)
                    }

                    HorizontalDivider(modifier = Modifier.padding(vertical = 8.dp), color = Color(0xFFEEEEEE))
                    
                    Row(
                        modifier              = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.SpaceBetween
                    ) {
                        Text("Total Pesanan", fontWeight = FontWeight.Bold, fontSize = 14.sp)
                        Text(Formatter.formatRupiah(grandTotal), fontWeight = FontWeight.Black, fontSize = 14.sp)
                    }
                }
            }

            Text("Data Pengiriman", fontWeight = FontWeight.Black, fontSize = 16.sp)

            val fieldColors = TextFieldDefaults.colors(
                focusedContainerColor     = Color.White,
                unfocusedContainerColor   = Color.White,
                focusedIndicatorColor     = Color.Black,
                unfocusedIndicatorColor   = Color(0xFFDDDDDD),
                cursorColor               = Color.Black,
                focusedTextColor          = Color.Black,
                unfocusedTextColor        = Color.Black
            )

            TextField(
                value         = recipientName,
                onValueChange = { recipientName = it },
                modifier      = Modifier.fillMaxWidth(),
                label         = { Text("Nama Penerima*") },
                leadingIcon   = { Icon(Icons.Default.Person, null, modifier = Modifier.size(20.dp)) },
                colors        = fieldColors,
                singleLine    = true,
                keyboardOptions = KeyboardOptions(imeAction = ImeAction.Next),
                keyboardActions = KeyboardActions(onNext = { focusMgr.moveFocus(FocusDirection.Down) })
            )

            Column {
                TextField(
                    value         = phone,
                    onValueChange = { phone = it },
                    modifier      = Modifier.fillMaxWidth(),
                    label         = { Text("Nomor HP*") },
                    leadingIcon   = { Icon(Icons.Default.Phone, null, modifier = Modifier.size(20.dp)) },
                    colors        = fieldColors,
                    singleLine    = true,
                    keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Phone, imeAction = ImeAction.Next),
                    keyboardActions = KeyboardActions(onNext = { focusMgr.moveFocus(FocusDirection.Down) })
                )
                if (phone.isNotEmpty() && phone.length < 11) {
                    Text(
                        text = "Nomor HP minimal 11 angka",
                        color = Color.Red,
                        fontSize = 12.sp,
                        modifier = Modifier.padding(start = 4.dp, top = 4.dp)
                    )
                }
            }

            TextField(
                value         = shippingAddress,
                onValueChange = { shippingAddress = it },
                modifier      = Modifier.fillMaxWidth().heightIn(min = 80.dp),
                label         = { Text("Alamat Lengkap*") },
                leadingIcon   = { Icon(Icons.Default.LocationOn, null, modifier = Modifier.size(20.dp)) },
                colors        = fieldColors,
                maxLines      = 3,
                keyboardOptions = KeyboardOptions(imeAction = ImeAction.Next),
                keyboardActions = KeyboardActions(onNext = { focusMgr.moveFocus(FocusDirection.Down) })
            )

            Text("Pilih Kurir", fontWeight = FontWeight.Black, fontSize = 16.sp)
            Row(modifier = Modifier.fillMaxWidth(), horizontalArrangement = Arrangement.spacedBy(8.dp)) {
                couriers.forEach { courier ->
                    val selected = selectedCourier == courier
                    Surface(
                        modifier = Modifier.weight(1f).clickable { selectedCourier = courier },
                        color    = if (selected) Color.Black else Color.White,
                        shape    = RoundedCornerShape(12.dp),
                        border   = if (selected) null else BorderStroke(1.dp, Color(0xFFDDDDDD))
                    ) {
                        Box(modifier = Modifier.padding(vertical = 12.dp), contentAlignment = Alignment.Center) {
                            Text(courier, color = if (selected) Color.White else Color.Black, fontWeight = FontWeight.Bold, fontSize = 14.sp)
                        }
                    }
                }
            }

            Text("Metode Pembayaran", fontWeight = FontWeight.Black, fontSize = 16.sp)
            paymentMethods.forEach { method ->
                val selected = selectedPayment == method
                Surface(
                    modifier = Modifier.fillMaxWidth().clickable { selectedPayment = method },
                    color    = if (selected) Color.Black else Color.White,
                    shape    = RoundedCornerShape(12.dp),
                    border   = if (selected) null else BorderStroke(1.dp, Color(0xFFDDDDDD))
                ) {
                    Row(modifier = Modifier.padding(16.dp), verticalAlignment = Alignment.CenterVertically) {
                        RadioButton(selected = selected, onClick = null, colors = RadioButtonDefaults.colors(selectedColor = Color.White, unselectedColor = Color.Gray))
                        Spacer(modifier = Modifier.width(8.dp))
                        Text(method, color = if (selected) Color.White else Color.Black, fontWeight = FontWeight.SemiBold)
                    }
                }
            }

            if (errorMsg != null) {
                Text(errorMsg, color = Color.Red, fontSize = 12.sp, modifier = Modifier.padding(horizontal = 4.dp))
            }

            Spacer(modifier = Modifier.height(100.dp))
        }
    }
}
