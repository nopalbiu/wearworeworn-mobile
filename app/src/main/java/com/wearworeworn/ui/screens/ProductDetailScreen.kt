package com.wearworeworn.ui.screens

import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.pager.HorizontalPager
import androidx.compose.foundation.pager.rememberPagerState
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.automirrored.filled.ArrowBack
import androidx.compose.material.icons.filled.AccountCircle
import androidx.compose.material.icons.filled.ShoppingCart
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import coil.compose.AsyncImage
import com.wearworeworn.util.Formatter
import com.wearworeworn.viewmodel.AuthViewModel
import com.wearworeworn.viewmodel.CartViewModel
import com.wearworeworn.viewmodel.ProductViewModel

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun ProductDetailScreen(
    productId:     Int,
    viewModel:     ProductViewModel,
    authViewModel: AuthViewModel,
    cartViewModel: CartViewModel,
    onBack:        () -> Unit,
    onNavigateToCart:    () -> Unit,
    onNavigateToCheckout: () -> Unit,
    onNavigateToProfile: () -> Unit,
    onNavigateToLogin:   () -> Unit
) {
    val product   = viewModel.selectedProduct.value
    val isLoading = viewModel.isLoading.value

    var selectedVariantId by remember { mutableIntStateOf(-1) }
    var quantity          by remember { mutableIntStateOf(1) }

    val snackbarHostState = remember { SnackbarHostState() }
    val cartSuccess       = cartViewModel.successMessage.value
    val cartError         = cartViewModel.errorMessage.value

    var showLoginDialog by remember { mutableStateOf(false) }

    LaunchedEffect(productId) { viewModel.fetchProductById(productId) }

    LaunchedEffect(product) {
        selectedVariantId = -1
        quantity          = 1
    }

    LaunchedEffect(cartSuccess) {
        if (cartSuccess != null) {
            snackbarHostState.showSnackbar(cartSuccess)
            cartViewModel.clearMessages()
        }
    }
    LaunchedEffect(cartError) {
        if (cartError != null) {
            snackbarHostState.showSnackbar(cartError)
            cartViewModel.clearMessages()
        }
    }

    val selectedVariant = product?.variants?.find { it.id == selectedVariantId }
    val displayStock    = selectedVariant?.stock ?: product?.variants?.sumOf { it.stock } ?: 0

    if (showLoginDialog) {
        AlertDialog(
            onDismissRequest = { showLoginDialog = false },
            title = { Text("Login Diperlukan", fontWeight = FontWeight.Bold) },
            text  = { Text("Kamu harus login untuk menambahkan produk ke keranjang.", color = Color.Gray) },
            confirmButton = {
                Button(
                    onClick = { showLoginDialog = false; onNavigateToLogin() },
                    colors  = ButtonDefaults.buttonColors(containerColor = Color.Black)
                ) {
                    Text("Masuk / Daftar", color = Color.White)
                }
            },
            dismissButton = {
                TextButton(onClick = { showLoginDialog = false }) {
                    Text("Nanti saja", color = Color.Gray)
                }
            },
            containerColor = Color.White,
            shape          = RoundedCornerShape(20.dp)
        )
    }

    Scaffold(
        modifier         = Modifier.fillMaxSize(),
        snackbarHost     = { SnackbarHost(snackbarHostState) },
        topBar = {
            Surface(modifier = Modifier.statusBarsPadding(), color = Color.White, shadowElevation = 0.dp) {
                Row(
                    modifier          = Modifier.fillMaxWidth().padding(horizontal = 8.dp, vertical = 8.dp),
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    IconButton(onClick = onBack) {
                        Icon(Icons.AutoMirrored.Filled.ArrowBack, contentDescription = "Back")
                    }
                    Text(
                        text       = "WearWoreWorn",
                        fontWeight = FontWeight.Black,
                        fontSize   = 20.sp,
                        modifier   = Modifier.padding(start = 8.dp)
                    )
                    Spacer(modifier = Modifier.weight(1f))
                    BadgedBox(
                        badge = {
                            if (cartViewModel.totalItems > 0) {
                                Badge(containerColor = Color.Black) {
                                    Text(
                                        "${cartViewModel.totalItems}",
                                        color    = Color.White,
                                        fontSize = 10.sp
                                    )
                                }
                            }
                        }
                    ) {
                        IconButton(onClick = onNavigateToCart) {
                            Icon(Icons.Default.ShoppingCart, contentDescription = "Cart")
                        }
                    }
                    IconButton(onClick = onNavigateToProfile) {
                        Icon(Icons.Default.AccountCircle, contentDescription = "Profile")
                    }
                }
            }
        },
        bottomBar = {
            Surface(
                modifier      = Modifier.navigationBarsPadding().fillMaxWidth(),
                color         = Color.White,
                shadowElevation = 16.dp
            ) {
                Row(modifier = Modifier.padding(16.dp), horizontalArrangement = Arrangement.spacedBy(12.dp)) {
                    Button(
                        onClick = {
                            val currentProduct = product
                            if (!authViewModel.isLoggedIn.value) {
                                showLoginDialog = true
                            } else if (selectedVariantId != -1 && currentProduct != null) {
                                val variant = currentProduct.variants.find { it.id == selectedVariantId }
                                if (variant != null) {
                                    cartViewModel.addToCart(
                                        variantId = selectedVariantId,
                                        quantity  = quantity,
                                        product   = currentProduct,
                                        variant   = variant,
                                        onSuccess = { },
                                        onError   = { }
                                    )
                                }
                            }
                        },
                        modifier = Modifier.weight(1f).height(56.dp),
                        colors   = ButtonDefaults.buttonColors(
                            containerColor = if (selectedVariantId != -1) Color(0xFFEEEEEE) else Color(0xFFF5F5F5)
                        ),
                        shape    = RoundedCornerShape(16.dp)
                    ) {
                        Text(
                            text  = if (selectedVariantId == -1) "Pilih Size" else "Tambah Keranjang",
                            color = if (selectedVariantId != -1) Color.Black else Color.Gray,
                            fontWeight = FontWeight.Bold,
                            fontSize   = 12.sp
                        )
                    }
                    Button(
                        onClick = {
                            val currentProduct = product
                            if (!authViewModel.isLoggedIn.value) {
                                showLoginDialog = true
                            } else if (selectedVariantId != -1 && currentProduct != null) {
                                val variant = currentProduct.variants.find { it.id == selectedVariantId }
                                if (variant != null) {
                                    cartViewModel.prepareDirectPurchase(currentProduct, variant, quantity)
                                    onNavigateToCheckout()
                                }
                            }
                        },
                        modifier = Modifier.weight(1f).height(56.dp),
                        colors   = ButtonDefaults.buttonColors(
                            containerColor = if (selectedVariantId != -1) Color.Black else Color(0xFF444444)
                        ),
                        shape    = RoundedCornerShape(16.dp)
                    ) {
                        Text(
                            text  = "Beli Sekarang",
                            color = Color.White,
                            fontWeight = FontWeight.Bold,
                            fontSize   = 13.sp
                        )
                    }
                }
            }
        }
    ) { innerPadding ->
        if (isLoading) {
            Box(modifier = Modifier.fillMaxSize().padding(innerPadding), contentAlignment = Alignment.Center) {
                CircularProgressIndicator(color = Color.Black)
            }
        } else if (product != null) {
            Column(
                modifier = Modifier
                    .padding(innerPadding)
                    .verticalScroll(rememberScrollState())
                    .background(Color.White)
            ) {
                val imageUrls = product.images.map { it.imageUrl }.toMutableList()
                if (!product.sizeChartUrl.isNullOrEmpty()) imageUrls.add(product.sizeChartUrl)

                val pagerState = rememberPagerState(pageCount = { imageUrls.size })

                Box(modifier = Modifier.fillMaxWidth().height(450.dp)) {
                    HorizontalPager(state = pagerState, modifier = Modifier.fillMaxSize()) { page ->
                        AsyncImage(
                            model              = imageUrls[page],
                            contentDescription = "Product Image",
                            modifier           = Modifier.fillMaxSize(),
                            contentScale       = ContentScale.Crop
                        )
                    }
                    if (imageUrls.size > 1) {
                        Row(
                            modifier              = Modifier.height(50.dp).fillMaxWidth().align(Alignment.BottomCenter),
                            horizontalArrangement = Arrangement.Center
                        ) {
                            repeat(imageUrls.size) { i ->
                                val color = if (pagerState.currentPage == i) Color.Black else Color.LightGray
                                Box(modifier = Modifier.padding(4.dp).clip(CircleShape).background(color).size(8.dp))
                            }
                        }
                    }
                }

                Column(modifier = Modifier.padding(24.dp)) {
                    Text(text = product.name, fontSize = 26.sp, fontWeight = FontWeight.ExtraBold, letterSpacing = (-0.5).sp)
                    Spacer(modifier = Modifier.height(4.dp))
                    Text(text = Formatter.formatRupiah(product.price), fontSize = 22.sp, fontWeight = FontWeight.Bold, color = Color.Black)
                    Spacer(modifier = Modifier.height(8.dp))

                    Surface(color = Color(0xFFF5F5F5), shape = RoundedCornerShape(8.dp)) {
                        Text(
                            text     = "Stok: $displayStock",
                            modifier = Modifier.padding(horizontal = 12.dp, vertical = 6.dp),
                            fontSize = 13.sp,
                            fontWeight = FontWeight.Medium,
                            color    = if (displayStock > 0) Color.DarkGray else Color.Red
                        )
                    }

                    Spacer(modifier = Modifier.height(24.dp))

                    Text("PILIH UKURAN", fontWeight = FontWeight.Black, fontSize = 14.sp, letterSpacing = 1.sp)
                    Spacer(modifier = Modifier.height(12.dp))

                    @OptIn(ExperimentalLayoutApi::class)
                    FlowRow(
                        modifier              = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.spacedBy(12.dp),
                        verticalArrangement   = Arrangement.spacedBy(12.dp)
                    ) {
                        product.variants.forEach { variant ->
                            val isSelected  = selectedVariantId == variant.id
                            val outOfStock  = variant.stock <= 0
                            Surface(
                                modifier = Modifier.clickable(enabled = !outOfStock) {
                                    selectedVariantId = if (isSelected) -1 else variant.id
                                },
                                color  = when {
                                    outOfStock -> Color(0xFFF5F5F5)
                                    isSelected -> Color.Black
                                    else       -> Color.Transparent
                                },
                                border = androidx.compose.foundation.BorderStroke(
                                    1.dp,
                                    when {
                                        outOfStock -> Color.LightGray
                                        isSelected -> Color.Black
                                        else       -> Color.LightGray
                                    }
                                ),
                                shape = RoundedCornerShape(12.dp)
                            ) {
                                Text(
                                    text      = variant.size?.name ?: "-",
                                    color     = when {
                                        outOfStock -> Color.LightGray
                                        isSelected -> Color.White
                                        else       -> Color.Black
                                    },
                                    modifier  = Modifier.padding(horizontal = 20.dp, vertical = 12.dp),
                                    fontWeight = FontWeight.Bold,
                                    fontSize  = 14.sp
                                )
                            }
                        }
                    }

                    if (selectedVariantId == -1 && product.variants.isNotEmpty()) {
                        Spacer(modifier = Modifier.height(8.dp))
                        Text(
                            "* Pilih ukuran terlebih dahulu",
                            fontSize = 12.sp,
                            color    = Color.Gray
                        )
                    }

                    Spacer(modifier = Modifier.height(32.dp))

                    Text("JUMLAH", fontWeight = FontWeight.Black, fontSize = 14.sp, letterSpacing = 1.sp)
                    Spacer(modifier = Modifier.height(12.dp))
                    Row(verticalAlignment = Alignment.CenterVertically, horizontalArrangement = Arrangement.spacedBy(16.dp)) {
                        Row(
                            verticalAlignment = Alignment.CenterVertically,
                            modifier          = Modifier.background(Color(0xFFF5F5F5), RoundedCornerShape(12.dp)).padding(4.dp)
                        ) {
                            IconButton(onClick = { if (quantity > 1) quantity-- }) {
                                Text("-", fontSize = 20.sp, fontWeight = FontWeight.Bold)
                            }
                            Text(
                                text      = quantity.toString(),
                                modifier  = Modifier.width(40.dp),
                                textAlign = TextAlign.Center,
                                fontWeight = FontWeight.Bold
                            )
                            IconButton(onClick = { if (quantity < displayStock) quantity++ }) {
                                Text("+", fontSize = 20.sp, fontWeight = FontWeight.Bold)
                            }
                        }
                    }

                    Spacer(modifier = Modifier.height(32.dp))
                    HorizontalDivider(color = Color(0xFFEEEEEE))
                    Spacer(modifier = Modifier.height(24.dp))

                    Text("DESKRIPSI", fontWeight = FontWeight.Black, fontSize = 14.sp, letterSpacing = 1.sp)
                    Spacer(modifier = Modifier.height(8.dp))
                    Text("Material: ${product.material ?: "-"}", fontSize = 14.sp, fontWeight = FontWeight.Bold, color = Color.DarkGray)
                    Spacer(modifier = Modifier.height(8.dp))
                    Text(product.description ?: "Tidak ada deskripsi.", fontSize = 15.sp, lineHeight = 24.sp, color = Color.Gray)

                    if (!authViewModel.isLoggedIn.value) {
                        Spacer(modifier = Modifier.height(24.dp))
                        Surface(
                            color = Color(0xFFF0F0F0),
                            shape = RoundedCornerShape(12.dp)
                        ) {
                            Row(
                                modifier          = Modifier.padding(horizontal = 16.dp, vertical = 12.dp),
                                verticalAlignment = Alignment.CenterVertically
                            ) {
                                Text("🔒", fontSize = 18.sp)
                                Spacer(modifier = Modifier.width(10.dp))
                                Text(
                                    "Login untuk menambahkan ke keranjang",
                                    fontSize = 13.sp,
                                    color    = Color.DarkGray
                                )
                            }
                        }
                    }

                    Spacer(modifier = Modifier.height(40.dp))
                }
            }
        }
    }
}
