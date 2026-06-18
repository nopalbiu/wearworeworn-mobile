package com.wearworeworn.ui.screens

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.automirrored.filled.ArrowBack
import androidx.compose.material.icons.filled.Add
import androidx.compose.material.icons.filled.Delete
import androidx.compose.material.icons.filled.Remove
import androidx.compose.material.icons.filled.ShoppingCartCheckout
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.text.style.TextOverflow
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import coil.compose.AsyncImage
import com.wearworeworn.model.CartItem
import com.wearworeworn.util.Formatter
import com.wearworeworn.viewmodel.CartViewModel

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun CartScreen(
    viewModel:  CartViewModel,
    onBack:     () -> Unit,
    onCheckout: () -> Unit
) {
    val items     = viewModel.cartItems.value
    val isLoading = viewModel.isLoading.value
    val total     = viewModel.totalPrice
    val errorMsg  = viewModel.errorMessage.value
    val successMsg = viewModel.successMessage.value
    val snackbarHostState = remember { SnackbarHostState() }

    LaunchedEffect(Unit) { viewModel.loadCart() }

    LaunchedEffect(errorMsg) {
        if (errorMsg != null) {
            snackbarHostState.showSnackbar(errorMsg)
            viewModel.clearMessages()
        }
    }

    LaunchedEffect(successMsg) {
        if (successMsg != null) {
            snackbarHostState.showSnackbar(successMsg)
            viewModel.clearMessages()
        }
    }

    Scaffold(
        modifier       = Modifier.fillMaxSize(),
        containerColor = MaterialTheme.colorScheme.background,
        snackbarHost   = { SnackbarHost(snackbarHostState) },
        topBar = {
            Surface(
                modifier        = Modifier.statusBarsPadding(),
                color           = MaterialTheme.colorScheme.background,
                shadowElevation = 0.dp
            ) {
                Row(
                    modifier          = Modifier.fillMaxWidth().padding(horizontal = 8.dp, vertical = 8.dp),
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    IconButton(onClick = onBack) {
                        Icon(Icons.AutoMirrored.Filled.ArrowBack, contentDescription = "Kembali", tint = MaterialTheme.colorScheme.onBackground)
                    }
                    Text(
                        text       = "KERANJANG",
                        fontWeight = FontWeight.Black,
                        fontSize   = 20.sp,
                        letterSpacing = 1.sp,
                        modifier   = Modifier.padding(start = 8.dp),
                        color      = MaterialTheme.colorScheme.onBackground
                    )
                    if (items.isNotEmpty()) {
                        Spacer(modifier = Modifier.width(12.dp))
                        Surface(color = MaterialTheme.colorScheme.primary, shape = RoundedCornerShape(12.dp)) {
                            Text(
                                text     = "${items.size} ITEMS",
                                color    = MaterialTheme.colorScheme.onPrimary,
                                fontSize = 11.sp,
                                fontWeight = FontWeight.Black,
                                modifier = Modifier.padding(horizontal = 10.dp, vertical = 4.dp)
                            )
                        }
                    }
                }
            }
        },
        bottomBar = {
            if (items.isNotEmpty()) {
                Surface(
                    modifier        = Modifier.navigationBarsPadding().fillMaxWidth(),
                    color           = MaterialTheme.colorScheme.background,
                    shadowElevation = 0.dp
                ) {
                    Column(modifier = Modifier.padding(horizontal = 24.dp, vertical = 16.dp)) {
                        Row(
                            modifier              = Modifier.fillMaxWidth(),
                            horizontalArrangement = Arrangement.SpaceBetween,
                            verticalAlignment     = Alignment.CenterVertically
                        ) {
                            Text("TOTAL", color = MaterialTheme.colorScheme.onSurfaceVariant, fontSize = 14.sp, fontWeight = FontWeight.Black, letterSpacing = 1.sp)
                            Text(
                                text       = Formatter.formatRupiah(total),
                                fontSize   = 22.sp,
                                fontWeight = FontWeight.Black,
                                color      = MaterialTheme.colorScheme.onSurface
                            )
                        }
                        Spacer(modifier = Modifier.height(16.dp))
                        Button(
                            onClick  = onCheckout,
                            modifier = Modifier.fillMaxWidth().height(56.dp),
                            colors   = ButtonDefaults.buttonColors(
                                containerColor = MaterialTheme.colorScheme.primary,
                                contentColor = MaterialTheme.colorScheme.onPrimary
                            ),
                            shape    = RoundedCornerShape(12.dp)
                        ) {
                            Icon(Icons.Default.ShoppingCartCheckout, contentDescription = null)
                            Spacer(modifier = Modifier.width(10.dp))
                            Text("PROSES CHECKOUT", fontWeight = FontWeight.Black, letterSpacing = 1.5.sp)
                        }
                    }
                }
            }
        }
    ) { innerPadding ->
        when {
            isLoading -> {
                Box(modifier = Modifier.fillMaxSize().padding(innerPadding), contentAlignment = Alignment.Center) {
                    CircularProgressIndicator(color = MaterialTheme.colorScheme.primary)
                }
            }
            items.isEmpty() -> {
                Box(modifier = Modifier.fillMaxSize().padding(innerPadding), contentAlignment = Alignment.Center) {
                    Column(horizontalAlignment = Alignment.CenterHorizontally, modifier = Modifier.padding(32.dp)) {
                        Text("🛒", fontSize = 64.sp)
                        Spacer(modifier = Modifier.height(24.dp))
                        Text(
                            text = "KERANJANGMU KOSONG",
                            fontSize = 20.sp,
                            fontWeight = FontWeight.Black,
                            letterSpacing = 1.sp,
                            color = MaterialTheme.colorScheme.onBackground
                        )
                        Spacer(modifier = Modifier.height(8.dp))
                        Text(
                            text = "Belum ada produk yang kamu tambahkan.",
                            fontSize = 14.sp,
                            color = MaterialTheme.colorScheme.onSurfaceVariant,
                            textAlign = TextAlign.Center
                        )
                        Spacer(modifier = Modifier.height(32.dp))
                        Button(
                            onClick = onBack,
                            colors  = ButtonDefaults.buttonColors(
                                containerColor = MaterialTheme.colorScheme.primary,
                                contentColor = MaterialTheme.colorScheme.onPrimary
                            ),
                            shape   = RoundedCornerShape(12.dp),
                            modifier = Modifier.fillMaxWidth().height(52.dp)
                        ) {
                            Text("MULAI BELANJA", fontWeight = FontWeight.Black, letterSpacing = 1.sp)
                        }
                    }
                }
            }
            else -> {
                LazyColumn(
                    modifier            = Modifier.padding(innerPadding),
                    contentPadding      = PaddingValues(horizontal = 16.dp, vertical = 12.dp),
                    verticalArrangement = Arrangement.spacedBy(12.dp)
                ) {
                    items(items, key = { it.id }) { cartItem ->
                        CartItemRow(
                            cartItem         = cartItem,
                            onUpdateQuantity = { newQty -> viewModel.updateQuantity(cartItem.id, newQty) },
                            onDelete         = { viewModel.removeFromCart(cartItem.id) }
                        )
                    }
                    item { Spacer(modifier = Modifier.height(8.dp)) }
                }
            }
        }
    }
}

@Composable
private fun CartItemRow(
    cartItem:         CartItem,
    onUpdateQuantity: (Int) -> Unit,
    onDelete:         () -> Unit
) {
    val imageUrl = cartItem.product.images.find { it.isPrimary }?.imageUrl
        ?: cartItem.product.images.firstOrNull()?.imageUrl ?: ""

    Card(
        modifier  = Modifier.fillMaxWidth(),
        colors    = CardDefaults.cardColors(
            containerColor = MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f)
        ),
        shape     = RoundedCornerShape(12.dp),
        elevation = CardDefaults.cardElevation(0.dp)
    ) {
        Row(modifier = Modifier.padding(12.dp), verticalAlignment = Alignment.CenterVertically) {
            AsyncImage(
                model              = imageUrl,
                contentDescription = cartItem.product.name,
                modifier           = Modifier.size(90.dp).clip(RoundedCornerShape(12.dp)),
                contentScale       = ContentScale.Crop
            )
            Spacer(modifier = Modifier.width(14.dp))
            Column(modifier = Modifier.weight(1f)) {
                Text(
                    text       = cartItem.product.name.uppercase(),
                    fontWeight = FontWeight.Black,
                    fontSize   = 13.sp,
                    maxLines   = 2,
                    overflow   = TextOverflow.Ellipsis,
                    letterSpacing = 0.5.sp,
                    color      = MaterialTheme.colorScheme.onSurface
                )
                Text(
                    text = "SIZE: ${cartItem.variant.size?.name?.uppercase() ?: "-"}",
                    fontSize = 11.sp,
                    fontWeight = FontWeight.Bold,
                    color = MaterialTheme.colorScheme.onSurfaceVariant
                )
                Spacer(modifier = Modifier.height(8.dp))
                Text(
                    text = Formatter.formatRupiah(cartItem.subtotal),
                    fontWeight = FontWeight.Black,
                    fontSize   = 16.sp,
                    color      = MaterialTheme.colorScheme.onSurface
                )
                Spacer(modifier = Modifier.height(12.dp))
                Row(
                    verticalAlignment = Alignment.CenterVertically,
                    modifier          = Modifier
                        .background(
                            MaterialTheme.colorScheme.surfaceVariant,
                            RoundedCornerShape(12.dp)
                        )
                        .padding(2.dp)
                ) {
                    IconButton(
                        onClick  = { if (cartItem.quantity > 1) onUpdateQuantity(cartItem.quantity - 1) },
                        modifier = Modifier.size(36.dp)
                    ) {
                        Icon(Icons.Default.Remove, contentDescription = "Kurangi", modifier = Modifier.size(18.dp), tint = MaterialTheme.colorScheme.onSurfaceVariant)
                    }
                    Text(
                        text       = cartItem.quantity.toString(),
                        modifier   = Modifier.width(36.dp),
                        textAlign  = TextAlign.Center,
                        fontWeight = FontWeight.Bold,
                        fontSize   = 14.sp,
                        color      = MaterialTheme.colorScheme.onSurfaceVariant
                    )
                    IconButton(
                        onClick  = { 
                            if (cartItem.quantity < cartItem.variant.stock) {
                                onUpdateQuantity(cartItem.quantity + 1)
                            }
                        },
                        enabled  = cartItem.quantity < cartItem.variant.stock,
                        modifier = Modifier.size(36.dp)
                    ) {
                        Icon(
                            Icons.Default.Add, 
                            contentDescription = "Tambah", 
                            modifier = Modifier.size(18.dp), 
                            tint = if (cartItem.quantity < cartItem.variant.stock) MaterialTheme.colorScheme.onSurfaceVariant else MaterialTheme.colorScheme.outline.copy(alpha = 0.3f)
                        )
                    }
                }
            }
            IconButton(onClick = onDelete) {
                Icon(Icons.Default.Delete, contentDescription = "Hapus", tint = Color(0xFFCC3333), modifier = Modifier.size(22.dp))
            }
        }
        if (cartItem.quantity >= cartItem.variant.stock) {
            Text(
                text = "Mencapai batas stok tersedia",
                fontSize = 10.sp,
                color = MaterialTheme.colorScheme.error,
                modifier = Modifier.padding(start = 116.dp, bottom = 12.dp),
                fontWeight = FontWeight.Medium
            )
        }
    }
}
