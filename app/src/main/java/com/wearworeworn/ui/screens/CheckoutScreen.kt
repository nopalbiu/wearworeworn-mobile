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
import androidx.compose.ui.focus.onFocusChanged
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
    var nameError by remember { mutableStateOf<String?>(null) }
    var phoneError by remember { mutableStateOf<String?>(null) }
    var addressError by remember { mutableStateOf<String?>(null) }

    var nameTouched by remember { mutableStateOf(false) }
    var phoneTouched by remember { mutableStateOf(false) }
    var addressTouched by remember { mutableStateOf(false) }

    fun validateName(value: String) {
        nameError = if (value.isBlank()) "Nama Penerima wajib diisi" else null
    }

    fun validatePhone(value: String) {
        phoneError = when {
            value.isBlank() -> "Nomor HP wajib diisi"
            !value.all { it.isDigit() } -> "Nomor HP hanya boleh berisi angka"
            value.length < 9 -> "Nomor HP minimal 9 digit"
            else -> null
        }
    }

    fun validateAddress(value: String) {
        addressError = when {
            value.isBlank() -> "Alamat Lengkap wajib diisi"
            value.length < 10 -> "Alamat Lengkap minimal 10 karakter"
            else -> null
        }
    }
    
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

    val paymentMethods = listOf(
        "Transfer Bank BCA",
        "Transfer Bank BRI",
        "Transfer Bank Mandiri"
    )
    var selectedPayment by remember { mutableStateOf(paymentMethods[0]) }

    LaunchedEffect(Unit) { orderViewModel.resetState() }

    Scaffold(
        modifier       = Modifier.fillMaxSize(),
        containerColor = MaterialTheme.colorScheme.background,
        topBar = {
            Surface(
                modifier = Modifier.statusBarsPadding(),
                color = MaterialTheme.colorScheme.background,
                shadowElevation = 0.dp
            ) {
                Row(
                    modifier          = Modifier.fillMaxWidth().padding(horizontal = 8.dp, vertical = 8.dp),
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    IconButton(onClick = {
                        cartViewModel.clearDirectBuy()
                        onBack()
                    }) {
                        Icon(Icons.AutoMirrored.Filled.ArrowBack, contentDescription = "Kembali", tint = MaterialTheme.colorScheme.onSurface)
                    }
                    Text("CHECKOUT", fontWeight = FontWeight.Black, fontSize = 20.sp, letterSpacing = 1.sp, modifier = Modifier.padding(start = 8.dp), color = MaterialTheme.colorScheme.onSurface)
                }
            }
        }
    ) { innerPadding ->
        Column(
            modifier = Modifier
                .padding(innerPadding)
                .fillMaxSize()
                .imePadding()
                .verticalScroll(rememberScrollState())
                .padding(horizontal = 16.dp, vertical = 12.dp),
            verticalArrangement = Arrangement.spacedBy(16.dp)
        ) {

            Card(
                colors    = CardDefaults.cardColors(
                    containerColor = MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f)
                ),
                shape     = RoundedCornerShape(12.dp),
                elevation = CardDefaults.cardElevation(0.dp)
            ) {
                Column(modifier = Modifier.padding(16.dp)) {
                    Text("RINGKASAN PESANAN", fontWeight = FontWeight.Black, fontSize = 14.sp, letterSpacing = 1.sp, color = MaterialTheme.colorScheme.onSurface)
                    Spacer(modifier = Modifier.height(16.dp))
                    items.forEach { item ->
                        Row(
                            modifier              = Modifier.fillMaxWidth().padding(vertical = 4.dp),
                            horizontalArrangement = Arrangement.SpaceBetween
                        ) {
                            Text(
                                text     = "${item.product.name.uppercase()} (${item.variant.size?.name?.uppercase() ?: "-"}) ×${item.quantity}",
                                fontSize = 12.sp,
                                fontWeight = FontWeight.Bold,
                                color    = MaterialTheme.colorScheme.onSurfaceVariant,
                                modifier = Modifier.weight(1f)
                            )
                            Spacer(modifier = Modifier.width(8.dp))
                            Text(
                                text       = Formatter.formatRupiah(item.subtotal),
                                fontSize   = 13.sp,
                                fontWeight = FontWeight.Black,
                                color      = MaterialTheme.colorScheme.onSurface
                            )
                        }
                    }
                    HorizontalDivider(modifier = Modifier.padding(vertical = 12.dp), color = MaterialTheme.colorScheme.outline.copy(alpha = 0.5f))
                    
                    Row(
                        modifier              = Modifier.fillMaxWidth().padding(vertical = 2.dp),
                        horizontalArrangement = Arrangement.SpaceBetween
                    ) {
                        Text("ONGKOS KIRIM ($selectedCourier)", fontSize = 12.sp, fontWeight = FontWeight.Bold, color = MaterialTheme.colorScheme.onSurfaceVariant)
                        Text(Formatter.formatRupiah(shippingFee), fontSize = 13.sp, color = MaterialTheme.colorScheme.onSurface)
                    }
                    Row(
                        modifier              = Modifier.fillMaxWidth().padding(vertical = 2.dp),
                        horizontalArrangement = Arrangement.SpaceBetween
                    ) {
                        Text("BIAYA ADMIN", fontSize = 12.sp, fontWeight = FontWeight.Bold, color = MaterialTheme.colorScheme.onSurfaceVariant)
                        Text(Formatter.formatRupiah(adminFee), fontSize = 13.sp, color = MaterialTheme.colorScheme.onSurface)
                    }

                    HorizontalDivider(modifier = Modifier.padding(vertical = 12.dp), color = MaterialTheme.colorScheme.outline.copy(alpha = 0.5f))
                    
                    Row(
                        modifier              = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.SpaceBetween
                    ) {
                        Text("TOTAL PESANAN", fontWeight = FontWeight.Black, fontSize = 14.sp, letterSpacing = 0.5.sp, color = MaterialTheme.colorScheme.onSurface)
                        Text(Formatter.formatRupiah(grandTotal), fontWeight = FontWeight.Black, fontSize = 15.sp, color = MaterialTheme.colorScheme.onSurface)
                    }
                }
            }

            Text("DATA PENGIRIMAN", fontWeight = FontWeight.Black, fontSize = 16.sp, letterSpacing = 1.sp, color = MaterialTheme.colorScheme.onBackground)

            val fieldColors = TextFieldDefaults.colors(
                focusedContainerColor     = MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f),
                unfocusedContainerColor   = MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f),
                focusedIndicatorColor     = Color.Transparent,
                unfocusedIndicatorColor   = Color.Transparent,
                cursorColor               = MaterialTheme.colorScheme.primary,
                focusedTextColor          = MaterialTheme.colorScheme.onSurface,
                unfocusedTextColor        = MaterialTheme.colorScheme.onSurface,
                focusedLabelColor         = MaterialTheme.colorScheme.primary,
                unfocusedLabelColor       = MaterialTheme.colorScheme.onSurfaceVariant,
                errorContainerColor       = MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f)
            )

            Column {
                TextField(
                    value         = recipientName,
                    onValueChange = { 
                        recipientName = it
                        if (nameTouched) validateName(it)
                    },
                    modifier      = Modifier
                        .fillMaxWidth()
                        .onFocusChanged { 
                            if (!it.isFocused && (nameTouched || recipientName.isNotEmpty())) {
                                nameTouched = true
                                validateName(recipientName)
                            }
                        },
                    label         = { Text("Nama Penerima*") },
                    leadingIcon   = { Icon(Icons.Default.Person, null, modifier = Modifier.size(20.dp), tint = MaterialTheme.colorScheme.onSurfaceVariant) },
                    colors        = fieldColors,
                    singleLine    = true,
                    keyboardOptions = KeyboardOptions(imeAction = ImeAction.Next),
                    keyboardActions = KeyboardActions(onNext = { focusMgr.moveFocus(FocusDirection.Down) }),
                    isError = nameTouched && nameError != null,
                    shape = RoundedCornerShape(12.dp)
                )
                if (nameTouched && nameError != null) {
                    Text(
                        text = nameError!!,
                        color = MaterialTheme.colorScheme.error,
                        fontSize = 12.sp,
                        modifier = Modifier.padding(start = 4.dp, top = 4.dp)
                    )
                }
            }

            Column {
                TextField(
                    value         = phone,
                    onValueChange = { 
                        if (it.all { char -> char.isDigit() }) {
                            phone = it
                            if (phoneTouched) validatePhone(it)
                        }
                    },
                    modifier      = Modifier
                        .fillMaxWidth()
                        .onFocusChanged { 
                            if (!it.isFocused && (phoneTouched || phone.isNotEmpty())) {
                                phoneTouched = true
                                validatePhone(phone)
                            }
                        },
                    label         = { Text("Nomor HP*") },
                    leadingIcon   = { Icon(Icons.Default.Phone, null, modifier = Modifier.size(20.dp), tint = MaterialTheme.colorScheme.onSurfaceVariant) },
                    colors        = fieldColors,
                    singleLine    = true,
                    keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Phone, imeAction = ImeAction.Next),
                    keyboardActions = KeyboardActions(onNext = { focusMgr.moveFocus(FocusDirection.Down) }),
                    isError = phoneTouched && phoneError != null,
                    shape = RoundedCornerShape(12.dp)
                )
                if (phoneTouched && phoneError != null) {
                    Text(
                        text = phoneError!!,
                        color = MaterialTheme.colorScheme.error,
                        fontSize = 12.sp,
                        modifier = Modifier.padding(start = 4.dp, top = 4.dp)
                    )
                }
            }

            Column {
                TextField(
                    value         = shippingAddress,
                    onValueChange = { 
                        shippingAddress = it
                        if (addressTouched) validateAddress(it)
                    },
                    modifier      = Modifier
                        .fillMaxWidth()
                        .heightIn(min = 80.dp)
                        .onFocusChanged { 
                            if (!it.isFocused && (addressTouched || shippingAddress.isNotEmpty())) {
                                addressTouched = true
                                validateAddress(shippingAddress)
                            }
                        },
                    label         = { Text("Alamat Lengkap*") },
                    leadingIcon   = { Icon(Icons.Default.LocationOn, null, modifier = Modifier.size(20.dp), tint = MaterialTheme.colorScheme.onSurfaceVariant) },
                    colors        = fieldColors,
                    maxLines      = 3,
                    keyboardOptions = KeyboardOptions(imeAction = ImeAction.Done),
                    keyboardActions = KeyboardActions(onDone = { focusMgr.clearFocus() }),
                    isError = addressTouched && addressError != null,
                    shape = RoundedCornerShape(12.dp)
                )
                if (addressTouched && addressError != null) {
                    Text(
                        text = addressError!!,
                        color = MaterialTheme.colorScheme.error,
                        fontSize = 12.sp,
                        modifier = Modifier.padding(start = 4.dp, top = 4.dp)
                    )
                }
            }

            Text("PILIH KURIR", fontWeight = FontWeight.Black, fontSize = 16.sp, letterSpacing = 1.sp, color = MaterialTheme.colorScheme.onBackground)
            Row(modifier = Modifier.fillMaxWidth(), horizontalArrangement = Arrangement.spacedBy(8.dp)) {
                couriers.forEach { courier ->
                    val selected = selectedCourier == courier
                    Surface(
                        modifier = Modifier.weight(1f).clickable { selectedCourier = courier },
                        color    = if (selected) MaterialTheme.colorScheme.primary else MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f),
                        shape    = RoundedCornerShape(12.dp),
                        border   = if (selected) null else BorderStroke(1.dp, MaterialTheme.colorScheme.outline.copy(alpha = 0.2f))
                    ) {
                        Box(modifier = Modifier.padding(vertical = 12.dp), contentAlignment = Alignment.Center) {
                            Text(courier.uppercase(), color = if (selected) MaterialTheme.colorScheme.onPrimary else MaterialTheme.colorScheme.onSurface, fontWeight = FontWeight.Black, fontSize = 13.sp, letterSpacing = 0.5.sp)
                        }
                    }
                }
            }

            Text("METODE PEMBAYARAN", fontWeight = FontWeight.Black, fontSize = 16.sp, letterSpacing = 1.sp, color = MaterialTheme.colorScheme.onBackground)
            paymentMethods.forEach { method ->
                val selected = selectedPayment == method
                Surface(
                    modifier = Modifier.fillMaxWidth().clickable { selectedPayment = method },
                    color    = if (selected) MaterialTheme.colorScheme.primary else MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f),
                    shape    = RoundedCornerShape(12.dp),
                    border   = if (selected) null else BorderStroke(1.dp, MaterialTheme.colorScheme.outline.copy(alpha = 0.2f))
                ) {
                    Row(modifier = Modifier.padding(16.dp), verticalAlignment = Alignment.CenterVertically) {
                        RadioButton(selected = selected, onClick = null, colors = RadioButtonDefaults.colors(selectedColor = MaterialTheme.colorScheme.onPrimary, unselectedColor = MaterialTheme.colorScheme.onSurfaceVariant))
                        Spacer(modifier = Modifier.width(8.dp))
                        Text(method.uppercase(), color = if (selected) MaterialTheme.colorScheme.onPrimary else MaterialTheme.colorScheme.onSurface, fontWeight = FontWeight.Black, fontSize = 13.sp, letterSpacing = 0.5.sp)
                    }
                }
            }

            if (errorMsg != null) {
                Text(errorMsg, color = MaterialTheme.colorScheme.error, fontSize = 12.sp, modifier = Modifier.padding(horizontal = 4.dp))
            }

            Spacer(modifier = Modifier.height(16.dp))

            Card(
                colors = CardDefaults.cardColors(
                    containerColor = MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f)
                ),
                shape = RoundedCornerShape(12.dp),
                elevation = CardDefaults.cardElevation(0.dp),
                modifier = Modifier.fillMaxWidth()
            ) {
                Column(modifier = Modifier.padding(16.dp)) {
                    Row(
                        modifier              = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.SpaceBetween,
                        verticalAlignment     = Alignment.CenterVertically
                    ) {
                        Text("TOTAL PEMBAYARAN", color = MaterialTheme.colorScheme.onSurfaceVariant, fontSize = 13.sp, fontWeight = FontWeight.Black, letterSpacing = 0.5.sp)
                        Text(Formatter.formatRupiah(grandTotal), fontSize = 20.sp, fontWeight = FontWeight.Black, color = MaterialTheme.colorScheme.onSurface)
                    }
                    Spacer(modifier = Modifier.height(16.dp))
                    Button(
                        onClick  = {
                            focusMgr.clearFocus()

                            validateName(recipientName)
                            validatePhone(phone)
                            validateAddress(shippingAddress)

                            nameTouched = true
                            phoneTouched = true
                            addressTouched = true

                            if (nameError != null || phoneError != null || addressError != null) {
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
                        enabled  = !isLoading,
                        modifier = Modifier.fillMaxWidth().height(56.dp),
                        colors   = ButtonDefaults.buttonColors(
                            containerColor         = MaterialTheme.colorScheme.primary,
                            disabledContainerColor = MaterialTheme.colorScheme.surfaceVariant
                        ),
                        shape = RoundedCornerShape(12.dp)
                    ) {
                        if (isLoading) {
                            CircularProgressIndicator(modifier = Modifier.size(22.dp), color = MaterialTheme.colorScheme.onPrimary, strokeWidth = 2.5.dp)
                        } else {
                            Text("BUAT PESANAN", color = MaterialTheme.colorScheme.onPrimary, fontWeight = FontWeight.Black, letterSpacing = 1.5.sp)
                        }
                    }
                }
            }

            Spacer(modifier = Modifier.height(32.dp))
        }
    }
}
