package com.wearworeworn.ui.screens

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.automirrored.filled.ArrowBack
import androidx.compose.material.icons.filled.Delete
import androidx.compose.material.icons.filled.ShoppingCartCheckout
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.text.font.FontWeight
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
    viewModel: CartViewModel,
    onBack: () -> Unit,
    onCheckout: () -> Unit
) {
    val items     = viewModel.cartItems.value
    val isLoading = viewModel.isLoading.value
    val total     = viewModel.totalPrice

    // Refresh cart setiap kali screen muncul
    LaunchedEffect(Unit) { viewModel.loadCart() }

    Scaffold(
        modifier = Modifier.fillMaxSize(),
        containerColor = Color(0xFFF8F8F8),
        topBar = {
            Surface(
                modifier      = Modifier.statusBarsPadding(),
                color         = Color.White,
                shadowElevation = 2.dp
            ) {
                Row(
                    modifier          = Modifier.fillMaxWidth().padding(horizontal = 8.dp, vertical = 8.dp),
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    IconButton(onClick = onBack) {
                        Icon(Icons.AutoMirrored.Filled.ArrowBack, contentDescription = "Kembali")
                    }
                    Text(
                        text       = "Keranjang",
                        fontWeight = FontWeight.Black,
                        fontSize   = 20.sp,
                        modifier   = Modifier.padding(start = 8.dp)
                    )
                    if (items.isNotEmpty()) {
                        Spacer(modifier = Modifier.width(8.dp))
                        Surface(
                            color = Color.Black,
                            shape = RoundedCornerShape(12.dp)
                        ) {
                            Text(
                                text     = "${items.size} item",
                                color    = Color.White,
                                fontSize = 12.sp,
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
                    modifier      = Modifier.navigationBarsPadding().fillMaxWidth(),
                    color         = Color.White,
                    shadowElevation = 16.dp
                ) {
                    Column(modifier = Modifier.padding(horizontal = 24.dp, vertical = 16.dp)) {
                        Row(
                            modifier              = Modifier.fillMaxWidth(),
                            horizontalArrangement = Arrangement.SpaceBetween,
                            verticalAlignment     = Alignment.CenterVertically
                        ) {
                            Text("Total", color = Color.Gray, fontSize = 14.sp)
                            Text(
                                text       = Formatter.formatRupiah(total),
                                fontSize   = 22.sp,
                                fontWeight = FontWeight.Black,
                                color      = Color.Black
                            )
                        }
                        Spacer(modifier = Modifier.height(12.dp))
                        Button(
                            onClick  = onCheckout,
                            modifier = Modifier.fillMaxWidth().height(56.dp),
                            colors   = ButtonDefaults.buttonColors(containerColor = Color.Black),
                            shape    = RoundedCornerShape(16.dp)
                        ) {
                            Icon(Icons.Default.ShoppingCartCheckout, contentDescription = null, tint = Color.White)
                            Spacer(modifier = Modifier.width(10.dp))
                            Text(
                                "CHECKOUT",
                                color         = Color.White,
                                fontWeight    = FontWeight.Bold,
                                letterSpacing = 1.5.sp
                            )
                        }
                    }
                }
            }
        }
    ) { innerPadding ->

        when {
            isLoading -> {
                Box(
                    modifier       = Modifier.fillMaxSize().padding(innerPadding),
                    contentAlignment = Alignment.Center
                ) {
                    CircularProgressIndicator(color = Color.Black)
                }
            }

            items.isEmpty() -> {
                // ── Empty State ───────────────────────────────────────────────
                Box(
                    modifier       = Modifier.fillMaxSize().padding(innerPadding),
                    contentAlignment = Alignment.Center
                ) {
                    Column(horizontalAlignment = Alignment.CenterHorizontally) {
                        Text("🛒", fontSize = 64.sp)
                        Spacer(modifier = Modifier.height(16.dp))
                        Text(
                            text       = "Keranjangmu masih kosong",
                            fontSize   = 18.sp,
                            fontWeight = FontWeight.Bold,
                            color      = Color.Black
                        )
                        Spacer(modifier = Modifier.height(8.dp))
                        Text(
                            text     = "Yuk, tambahkan produk favoritmu!",
                            fontSize = 14.sp,
                            color    = Color.Gray
                        )
                        Spacer(modifier = Modifier.height(24.dp))
                        Button(
                            onClick = onBack,
                            colors  = ButtonDefaults.buttonColors(containerColor = Color.Black),
                            shape   = RoundedCornerShape(14.dp)
                        ) {
                            Text("Belanja Sekarang", color = Color.White, fontWeight = FontWeight.Bold)
                        }
                    }
                }
            }

            else -> {
                // ── Cart Item List ────────────────────────────────────────────
                LazyColumn(
                    modifier        = Modifier.padding(innerPadding),
                    contentPadding  = PaddingValues(horizontal = 16.dp, vertical = 12.dp),
                    verticalArrangement = Arrangement.spacedBy(12.dp)
                ) {
                    items(items, key = { it.id }) { cartItem ->
                        CartItemRow(
                            cartItem  = cartItem,
                            onDelete  = { viewModel.removeFromCart(cartItem.id) }
                        )
                    }
                    // padding bawah untuk bottom bar
                    item { Spacer(modifier = Modifier.height(8.dp)) }
                }
            }
        }
    }
}

// ─── Cart Item Row Component ──────────────────────────────────────────────────

@Composable
private fun CartItemRow(
    cartItem: CartItem,
    onDelete: () -> Unit
) {
    val imageUrl = cartItem.product.images.find { it.isPrimary }?.imageUrl
        ?: cartItem.product.images.firstOrNull()?.imageUrl ?: ""

    Card(
        modifier = Modifier.fillMaxWidth(),
        colors   = CardDefaults.cardColors(containerColor = Color.White),
        shape    = RoundedCornerShape(16.dp),
        elevation = CardDefaults.cardElevation(defaultElevation = 1.dp)
    ) {
        Row(
            modifier          = Modifier.padding(12.dp),
            verticalAlignment = Alignment.CenterVertically
        ) {
            // Thumbnail
            AsyncImage(
                model              = imageUrl,
                contentDescription = cartItem.product.name,
                modifier           = Modifier.size(80.dp).clip(RoundedCornerShape(12.dp)),
                contentScale       = ContentScale.Crop
            )

            Spacer(modifier = Modifier.width(14.dp))

            // Info
            Column(modifier = Modifier.weight(1f)) {
                Text(
                    text      = cartItem.product.name,
                    fontWeight = FontWeight.Bold,
                    fontSize  = 14.sp,
                    maxLines  = 2,
                    overflow  = TextOverflow.Ellipsis
                )
                Spacer(modifier = Modifier.height(4.dp))
                Row(horizontalArrangement = Arrangement.spacedBy(8.dp)) {
                    // Size badge
                    Surface(
                        color = Color(0xFFF0F0F0),
                        shape = RoundedCornerShape(6.dp)
                    ) {
                        Text(
                            text     = cartItem.variant.size?.name ?: "-",
                            fontSize = 11.sp,
                            color    = Color.DarkGray,
                            modifier = Modifier.padding(horizontal = 8.dp, vertical = 3.dp)
                        )
                    }
                    // Qty badge
                    Surface(
                        color = Color(0xFF1A1A1A),
                        shape = RoundedCornerShape(6.dp)
                    ) {
                        Text(
                            text     = "×${cartItem.quantity}",
                            fontSize = 11.sp,
                            color    = Color.White,
                            modifier = Modifier.padding(horizontal = 8.dp, vertical = 3.dp)
                        )
                    }
                }
                Spacer(modifier = Modifier.height(6.dp))
                Text(
                    text       = Formatter.formatRupiah(cartItem.subtotal),
                    fontWeight = FontWeight.Black,
                    fontSize   = 15.sp,
                    color      = Color.Black
                )
            }

            // Delete
            IconButton(
                onClick  = onDelete,
                modifier = Modifier.size(36.dp)
            ) {
                Icon(
                    Icons.Default.Delete,
                    contentDescription = "Hapus",
                    tint               = Color(0xFFCC3333),
                    modifier           = Modifier.size(20.dp)
                )
            }
        }
    }
}
