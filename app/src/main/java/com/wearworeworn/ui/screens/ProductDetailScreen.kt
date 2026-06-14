package com.wearworeworn.ui.screens

import androidx.compose.foundation.Image
import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.horizontalScroll
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
import androidx.compose.foundation.BorderStroke
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.graphicsLayer
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import coil.compose.AsyncImage
import com.wearworeworn.R
import com.wearworeworn.util.Formatter
import com.wearworeworn.viewmodel.AuthViewModel
import com.wearworeworn.viewmodel.CartViewModel
import com.wearworeworn.viewmodel.ProductViewModel
import kotlinx.coroutines.launch

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

    LaunchedEffect(selectedVariantId) {
        if (selectedVariantId != -1) {
            val stock = product?.variants?.find { it.id == selectedVariantId }?.stock ?: 0
            if (quantity > stock && stock > 0) {
                quantity = stock
            } else if (stock == 0) {
                quantity = 1
            }
        }
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
            title = { Text("LOGIN DIPERLUKAN", fontWeight = FontWeight.Black, letterSpacing = 1.sp) },
            text  = { Text("Kamu harus login untuk melanjutkan transaksi.", color = MaterialTheme.colorScheme.onSurfaceVariant) },
            confirmButton = {
                Button(
                    onClick = { showLoginDialog = false; onNavigateToLogin() },
                    colors  = ButtonDefaults.buttonColors(containerColor = MaterialTheme.colorScheme.primary, contentColor = MaterialTheme.colorScheme.onPrimary),
                    shape   = RoundedCornerShape(12.dp)
                ) {
                    Text("LOGIN / DAFTAR", fontWeight = FontWeight.Bold)
                }
            },
            dismissButton = {
                TextButton(onClick = { showLoginDialog = false }) {
                    Text("NANTI SAJA", color = MaterialTheme.colorScheme.onSurfaceVariant, fontWeight = FontWeight.Bold)
                }
            },
            containerColor = MaterialTheme.colorScheme.surface,
            shape          = RoundedCornerShape(12.dp)
        )
    }

    Scaffold(
        modifier         = Modifier.fillMaxSize(),
        snackbarHost     = { SnackbarHost(snackbarHostState) },
        topBar = {
            Surface(modifier = Modifier.statusBarsPadding(), color = MaterialTheme.colorScheme.background, shadowElevation = 0.dp) {
                Row(
                    modifier          = Modifier.fillMaxWidth().padding(horizontal = 8.dp, vertical = 8.dp),
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    IconButton(onClick = onBack) {
                        Icon(Icons.AutoMirrored.Filled.ArrowBack, contentDescription = "Back", tint = MaterialTheme.colorScheme.onBackground)
                    }
                    Image(
                        painter = painterResource(id = R.drawable.logo_www),
                        contentDescription = "WearWoreWorn Logo",
                        modifier = Modifier
                            .height(32.dp)
                            .widthIn(max = 160.dp)
                            .padding(start = 8.dp),
                        contentScale = ContentScale.Fit
                    )
                    Spacer(modifier = Modifier.weight(1f))
                    BadgedBox(
                        badge = {
                            if (cartViewModel.totalItems > 0) {
                                Badge(containerColor = MaterialTheme.colorScheme.primary) {
                                    Text(
                                        "${cartViewModel.totalItems}",
                                        color    = MaterialTheme.colorScheme.onPrimary,
                                        fontSize = 10.sp
                                    )
                                }
                            }
                        }
                    ) {
                        IconButton(onClick = onNavigateToCart) {
                            Icon(Icons.Default.ShoppingCart, contentDescription = "Cart", tint = MaterialTheme.colorScheme.onBackground)
                        }
                    }
                    IconButton(onClick = onNavigateToProfile) {
                        Icon(Icons.Default.AccountCircle, contentDescription = "Profile", tint = MaterialTheme.colorScheme.onBackground)
                    }
                }
            }
        },
        bottomBar = {
            val isSizeSelected = selectedVariantId != -1
            val isStockAvailable = displayStock > 0

            Surface(
                modifier      = Modifier.navigationBarsPadding().fillMaxWidth(),
                color         = MaterialTheme.colorScheme.background,
                shadowElevation = 0.dp
            ) {
                Row(modifier = Modifier.padding(16.dp), horizontalArrangement = Arrangement.spacedBy(10.dp)) {
                    OutlinedButton(
                        onClick = {
                            if (!authViewModel.isLoggedIn.value) {
                                showLoginDialog = true
                            } else if (isSizeSelected && isStockAvailable) {
                                val currentProduct = product
                                val variant = currentProduct?.variants?.find { it.id == selectedVariantId }
                                if (variant != null && currentProduct != null) {
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
                        modifier = Modifier.weight(1f).height(52.dp),
                        shape    = RoundedCornerShape(12.dp),
                        contentPadding = PaddingValues(horizontal = 4.dp),
                        border   = BorderStroke(
                            1.dp,
                            if (isStockAvailable) MaterialTheme.colorScheme.outline
                            else MaterialTheme.colorScheme.outline.copy(alpha = 0.4f)
                        ),
                        colors   = ButtonDefaults.outlinedButtonColors(
                            contentColor = MaterialTheme.colorScheme.onSurface,
                            disabledContentColor = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.4f)
                        ),
                        enabled = isStockAvailable
                    ) {
                        Text(
                            text  = when {
                                !isStockAvailable && isSizeSelected -> "STOK HABIS"
                                !isSizeSelected -> "PILIH UKURAN"
                                else -> "TAMBAH KERANJANG"
                            },
                            fontWeight = FontWeight.Black,
                            fontSize   = 10.sp,
                            letterSpacing = 0.sp,
                            maxLines   = 1,
                            softWrap   = false,
                            textAlign  = TextAlign.Center
                        )
                    }

                    val isBuyEnabled = isSizeSelected && isStockAvailable
                    Button(
                        onClick = {
                            if (!authViewModel.isLoggedIn.value) {
                                showLoginDialog = true
                            } else if (isBuyEnabled) {
                                val currentProduct = product
                                val variant = currentProduct?.variants?.find { it.id == selectedVariantId }
                                if (variant != null && currentProduct != null) {
                                    cartViewModel.setDirectBuy(
                                        product  = currentProduct,
                                        variant  = variant,
                                        quantity = quantity
                                    )
                                    onNavigateToCheckout()
                                }
                            }
                        },
                        modifier = Modifier
                            .weight(1f)
                            .height(52.dp)
                            .graphicsLayer {
                                alpha = if (isBuyEnabled) 1f else 0.4f
                            },
                        contentPadding = PaddingValues(horizontal = 4.dp),
                        colors   = ButtonDefaults.buttonColors(
                            containerColor = MaterialTheme.colorScheme.primary,
                            contentColor   = MaterialTheme.colorScheme.onPrimary,
                            disabledContainerColor = MaterialTheme.colorScheme.primary,
                            disabledContentColor = MaterialTheme.colorScheme.onPrimary
                        ),
                        shape    = RoundedCornerShape(12.dp),
                        enabled  = isBuyEnabled
                    ) {
                        Text(
                            text  = if (isSizeSelected && !isStockAvailable) "STOK HABIS" else "BELI SEKARANG",
                            fontWeight = FontWeight.Black,
                            fontSize   = 10.sp,
                            letterSpacing = 0.sp,
                            maxLines   = 1,
                            softWrap   = false,
                            textAlign  = TextAlign.Center
                        )
                    }
                }
            }
        }
    ) { innerPadding ->
        if (isLoading) {
            Box(modifier = Modifier.fillMaxSize().padding(innerPadding), contentAlignment = Alignment.Center) {
                CircularProgressIndicator(color = MaterialTheme.colorScheme.primary)
            }
        } else if (product != null) {
            Column(
                modifier = Modifier
                    .padding(innerPadding)
                    .verticalScroll(rememberScrollState())
                    .background(MaterialTheme.colorScheme.background)
            ) {
                val imageUrls = product.images.map { it.imageUrl }.toMutableList()
                if (!product.sizeChartUrl.isNullOrEmpty()) imageUrls.add(product.sizeChartUrl)

                val pagerState = rememberPagerState(pageCount = { imageUrls.size })

                Box(
                    modifier = Modifier
                        .fillMaxWidth()
                        .aspectRatio(1f)
                        .background(MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f))
                ) {
                    HorizontalPager(
                        state = pagerState,
                        modifier = Modifier.fillMaxSize()
                    ) { page ->
                        AsyncImage(
                            model              = imageUrls[page],
                            contentDescription = "Product Image",
                            modifier           = Modifier.fillMaxSize(),
                            contentScale       = ContentScale.Fit
                        )
                    }
                }

                if (imageUrls.size > 1) {
                    Row(
                        modifier = Modifier
                            .fillMaxWidth()
                            .padding(top = 12.dp, bottom = 16.dp),
                        horizontalArrangement = Arrangement.Center
                    ) {
                        repeat(imageUrls.size) { i ->
                            val color = if (pagerState.currentPage == i) MaterialTheme.colorScheme.primary else MaterialTheme.colorScheme.outline
                            Box(
                                modifier = Modifier
                                    .padding(4.dp)
                                    .clip(CircleShape)
                                    .background(color)
                                    .size(6.dp)
                            )
                        }
                    }
                }

                Column(modifier = Modifier.padding(24.dp)) {
                    Text(
                        text = product.name.uppercase(),
                        fontSize = 24.sp,
                        fontWeight = FontWeight.Black,
                        letterSpacing = 1.sp,
                        color = MaterialTheme.colorScheme.onBackground
                    )
                    Spacer(modifier = Modifier.height(4.dp))
                    Text(
                        text = Formatter.formatRupiah(product.price),
                        fontSize = 20.sp,
                        fontWeight = FontWeight.Bold,
                        color = MaterialTheme.colorScheme.onBackground
                    )
                    Spacer(modifier = Modifier.height(32.dp))

                    Text(
                        "PILIH UKURAN",
                        fontWeight = FontWeight.Black,
                        fontSize = 12.sp,
                        letterSpacing = 2.sp,
                        color = MaterialTheme.colorScheme.onBackground
                    )
                    Spacer(modifier = Modifier.height(16.dp))

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
                                    outOfStock -> MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.2f)
                                    isSelected -> MaterialTheme.colorScheme.primary
                                    else       -> MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f)
                                },
                                border = if (!isSelected && !outOfStock) BorderStroke(
                                    1.dp,
                                    MaterialTheme.colorScheme.outline.copy(alpha = 0.5f)
                                ) else null,
                                shape = RoundedCornerShape(12.dp)
                            ) {
                                Text(
                                    text      = variant.size?.name ?: "-",
                                    color     = when {
                                        outOfStock -> MaterialTheme.colorScheme.onSurfaceVariant.copy(alpha = 0.3f)
                                        isSelected -> MaterialTheme.colorScheme.onPrimary
                                        else       -> MaterialTheme.colorScheme.onSurface
                                    },
                                    modifier  = Modifier.padding(horizontal = 24.dp, vertical = 12.dp),
                                    fontWeight = FontWeight.Black,
                                    fontSize  = 13.sp
                                )
                            }
                        }
                    }

                    if (selectedVariantId == -1 && product.variants.isNotEmpty()) {
                        Spacer(modifier = Modifier.height(8.dp))
                        Text(
                            "* Pilih ukuran terlebih dahulu",
                            fontSize = 11.sp,
                            color    = MaterialTheme.colorScheme.onSurfaceVariant.copy(alpha = 0.7f),
                            fontWeight = FontWeight.Medium
                        )
                    }

                    Spacer(modifier = Modifier.height(32.dp))

                    Text(
                        "JUMLAH",
                        fontWeight = FontWeight.Black,
                        fontSize = 12.sp,
                        letterSpacing = 2.sp,
                        color = MaterialTheme.colorScheme.onBackground
                    )
                    Spacer(modifier = Modifier.height(16.dp))
                    Row(verticalAlignment = Alignment.CenterVertically, horizontalArrangement = Arrangement.spacedBy(16.dp)) {
                        Row(
                            verticalAlignment = Alignment.CenterVertically,
                            modifier          = Modifier
                                .background(MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f), RoundedCornerShape(12.dp))
                                .padding(4.dp)
                        ) {
                            IconButton(
                                onClick  = { if (quantity > 1) quantity-- },
                                modifier = Modifier.size(40.dp)
                            ) {
                                Text("-", fontSize = 20.sp, fontWeight = FontWeight.Bold, color = MaterialTheme.colorScheme.onSurface)
                            }
                            Text(
                                text      = quantity.toString(),
                                modifier  = Modifier.width(40.dp),
                                textAlign = TextAlign.Center,
                                fontWeight = FontWeight.Black,
                                fontSize   = 16.sp,
                                color      = MaterialTheme.colorScheme.onSurface
                            )
                            IconButton(
                                onClick  = { if (quantity < displayStock) quantity++ },
                                modifier = Modifier.size(40.dp),
                                enabled  = displayStock > 0
                            ) {
                                Text("+", fontSize = 20.sp, fontWeight = FontWeight.Bold, color = if (displayStock > 0) MaterialTheme.colorScheme.onSurface else MaterialTheme.colorScheme.outline)
                            }
                        }

                        Text(
                            text = if (displayStock > 0) "STOK: $displayStock" else "STOK HABIS",
                            fontSize = 12.sp,
                            fontWeight = FontWeight.Bold,
                            letterSpacing = 1.sp,
                            color = if (displayStock > 0) MaterialTheme.colorScheme.onSurfaceVariant else Color.Red
                        )
                    }

                    Spacer(modifier = Modifier.height(40.dp))
                    HorizontalDivider(color = MaterialTheme.colorScheme.outline.copy(alpha = 0.2f))
                    Spacer(modifier = Modifier.height(32.dp))

                    Text(
                        "DESKRIPSI",
                        fontWeight = FontWeight.Black,
                        fontSize = 12.sp,
                        letterSpacing = 2.sp,
                        color = MaterialTheme.colorScheme.onBackground
                    )
                    Spacer(modifier = Modifier.height(16.dp))
                    if (product.material != null) {
                        Text(
                            "MATERIAL: ${product.material.uppercase()}",
                            fontSize = 13.sp,
                            fontWeight = FontWeight.Black,
                            letterSpacing = 0.5.sp,
                            color = MaterialTheme.colorScheme.onSurface
                        )
                        Spacer(modifier = Modifier.height(8.dp))
                    }
                    Text(
                        product.description ?: "Tidak ada deskripsi.",
                        fontSize = 14.sp,
                        lineHeight = 22.sp,
                        color = MaterialTheme.colorScheme.onSurfaceVariant,
                        fontWeight = FontWeight.Medium
                    )

                    if (!authViewModel.isLoggedIn.value) {
                        Spacer(modifier = Modifier.height(24.dp))
                        Surface(
                            color = MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f),
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
                                    color    = MaterialTheme.colorScheme.onSurfaceVariant
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
