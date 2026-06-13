package com.wearworeworn.ui.screens

import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.grid.GridCells
import androidx.compose.foundation.lazy.grid.GridItemSpan
import androidx.compose.foundation.lazy.grid.LazyVerticalGrid
import androidx.compose.foundation.lazy.grid.items
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.text.KeyboardActions
import androidx.compose.foundation.text.KeyboardOptions
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.*
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.platform.LocalFocusManager
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.input.ImeAction
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.wearworeworn.ui.components.ProductCard
import com.wearworeworn.viewmodel.AuthViewModel
import com.wearworeworn.viewmodel.CartViewModel
import com.wearworeworn.viewmodel.ProductViewModel

@OptIn(ExperimentalLayoutApi::class, ExperimentalMaterial3Api::class)
@Composable
fun HomeScreen(
    viewModel:           ProductViewModel,
    authViewModel:       AuthViewModel,
    cartViewModel:       CartViewModel,
    onProductClick:      (Int) -> Unit,
    onNavigateToCart:    () -> Unit,
    onNavigateToProfile: () -> Unit,
    onNavigateToLogin:   () -> Unit
) {
    val products    = viewModel.filteredProducts
    val isLoading   = viewModel.isLoading.value
    val errorMessage = viewModel.errorMessage.value
    val categories  = viewModel.categories.value
    val sizes       = viewModel.sizes.value
    val isLoggedIn  = authViewModel.isLoggedIn.value
    val focusManager = LocalFocusManager.current

    var showSortMenu    by remember { mutableStateOf(false) }
    var showFilterSheet by remember { mutableStateOf(false) }
    val sheetState      = rememberModalBottomSheetState(skipPartiallyExpanded = true)

    val sortOptions = listOf("Terbaru", "Harga: Termurah", "Harga: Termahal")

    Scaffold(
        modifier = Modifier.fillMaxSize(),
        topBar   = {
            Surface(
                modifier      = Modifier.statusBarsPadding(),
                color         = Color.White,
                shadowElevation = 0.dp
            ) {
                Column(modifier = Modifier.padding(bottom = 8.dp)) {
                    Row(
                        modifier              = Modifier.fillMaxWidth().padding(horizontal = 16.dp, vertical = 8.dp),
                        verticalAlignment     = Alignment.CenterVertically,
                        horizontalArrangement = Arrangement.SpaceBetween
                    ) {
                        Text(
                            text          = "WearWoreWorn",
                            fontWeight    = FontWeight.Black,
                            fontSize      = 22.sp,
                            letterSpacing = (-1).sp
                        )
                        Row {
                            // ─ Cart icon with badge ───────────────────────────
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
                                IconButton(onClick = {
                                    focusManager.clearFocus()
                                    if (isLoggedIn) onNavigateToCart() else onNavigateToLogin()
                                }) {
                                    Icon(Icons.Default.ShoppingCart, contentDescription = "Keranjang")
                                }
                            }

                            // ─ Profile / Login icon ───────────────────────────
                            IconButton(onClick = {
                                focusManager.clearFocus()
                                if (isLoggedIn) onNavigateToProfile() else onNavigateToLogin()
                            }) {
                                if (isLoggedIn) {
                                    Icon(Icons.Default.AccountCircle, contentDescription = "Profil", tint = Color.Black)
                                } else {
                                    Icon(Icons.Default.AccountCircle, contentDescription = "Masuk", tint = Color.Gray)
                                }
                            }
                        }
                    }

                    // ─ Search Bar ─────────────────────────────────────────────
                    Box(modifier = Modifier.padding(horizontal = 16.dp)) {
                        TextField(
                            value         = viewModel.searchQuery.value,
                            onValueChange = { viewModel.searchQuery.value = it },
                            modifier      = Modifier.fillMaxWidth().height(56.dp),
                            placeholder   = { Text("Cari produk...", color = Color.Gray, fontSize = 14.sp) },
                            leadingIcon   = {
                                Icon(
                                    Icons.Default.Search,
                                    contentDescription = null,
                                    tint     = if (viewModel.searchQuery.value.isEmpty()) Color.Gray else Color.Black,
                                    modifier = Modifier.size(22.dp)
                                )
                            },
                            trailingIcon  = {
                                if (viewModel.searchQuery.value.isNotEmpty()) {
                                    IconButton(onClick = {
                                        viewModel.searchQuery.value = ""
                                        focusManager.clearFocus()
                                    }) {
                                        Icon(Icons.Default.Close, contentDescription = "Hapus", modifier = Modifier.size(18.dp))
                                    }
                                }
                            },
                            colors = TextFieldDefaults.colors(
                                focusedContainerColor   = Color(0xFFF1F1F1),
                                unfocusedContainerColor = Color(0xFFF1F1F1),
                                focusedIndicatorColor   = Color.Transparent,
                                unfocusedIndicatorColor = Color.Transparent,
                                cursorColor             = Color.Black
                            ),
                            shape           = RoundedCornerShape(16.dp),
                            singleLine      = true,
                            textStyle       = LocalTextStyle.current.copy(fontSize = 15.sp, fontWeight = FontWeight.Medium),
                            keyboardOptions = KeyboardOptions(imeAction = ImeAction.Search),
                            keyboardActions = KeyboardActions(onSearch = { focusManager.clearFocus() })
                        )
                    }
                }
            }
        },
        bottomBar = {
            Surface(
                modifier = Modifier.fillMaxWidth().navigationBarsPadding().padding(16.dp).height(56.dp).clickable {
                    focusManager.clearFocus()
                    showFilterSheet = true
                },
                color         = Color.Black,
                shape         = RoundedCornerShape(16.dp),
                shadowElevation = 8.dp
            ) {
                Row(
                    modifier              = Modifier.fillMaxSize(),
                    horizontalArrangement = Arrangement.Center,
                    verticalAlignment     = Alignment.CenterVertically
                ) {
                    Icon(Icons.Default.FilterList, contentDescription = null, tint = Color.White)
                    Spacer(modifier = Modifier.width(8.dp))
                    Text("FILTER", color = Color.White, fontWeight = FontWeight.Bold, letterSpacing = 2.sp)
                    // Show active filter count
                    val activeFilters = viewModel.selectedCategories.size + viewModel.selectedSizes.size +
                        (if (viewModel.selectedPriceRange.value != null) 1 else 0)
                    if (activeFilters > 0) {
                        Spacer(modifier = Modifier.width(8.dp))
                        Surface(color = Color.White, shape = RoundedCornerShape(8.dp)) {
                            Text(
                                "$activeFilters",
                                color    = Color.Black,
                                fontSize = 11.sp,
                                fontWeight = FontWeight.Black,
                                modifier = Modifier.padding(horizontal = 7.dp, vertical = 2.dp)
                            )
                        }
                    }
                }
            }
        }
    ) { innerPadding ->
        LazyVerticalGrid(
            columns      = GridCells.Fixed(2),
            modifier     = Modifier.fillMaxSize().padding(horizontal = 8.dp).clickable { focusManager.clearFocus() },
            contentPadding = PaddingValues(
                top    = innerPadding.calculateTopPadding() + 8.dp,
                bottom = innerPadding.calculateBottomPadding() + 80.dp
            )
        ) {
            // ── Hero Banner ──────────────────────────────────────────────────
            item(span = { GridItemSpan(2) }) {
                Card(
                    modifier = Modifier.fillMaxWidth().padding(horizontal = 8.dp, vertical = 8.dp),
                    shape    = RoundedCornerShape(24.dp),
                    colors   = CardDefaults.cardColors(containerColor = Color(0xFF212121))
                ) {
                    Column(modifier = Modifier.padding(24.dp), horizontalAlignment = Alignment.CenterHorizontally) {
                        Text(
                            text       = "Find Your Best Style",
                            color      = Color.White,
                            fontSize   = 24.sp,
                            fontWeight = FontWeight.ExtraBold,
                            textAlign  = TextAlign.Center
                        )
                        Spacer(modifier = Modifier.height(8.dp))
                        Text(
                            text      = "Explore the latest trends in teen & adult outfits.",
                            color     = Color.LightGray,
                            fontSize  = 14.sp,
                            textAlign = TextAlign.Center
                        )
                        // Guest prompt
                        if (!isLoggedIn) {
                            Spacer(modifier = Modifier.height(16.dp))
                            OutlinedButton(
                                onClick = onNavigateToLogin,
                                border  = androidx.compose.foundation.BorderStroke(1.dp, Color.White),
                                shape   = RoundedCornerShape(12.dp)
                            ) {
                                Text("Masuk untuk Belanja", color = Color.White, fontSize = 13.sp)
                            }
                        }
                    }
                }
            }

            // ── Catalog Header ───────────────────────────────────────────────
            item(span = { GridItemSpan(2) }) {
                Row(
                    modifier              = Modifier.fillMaxWidth().padding(horizontal = 8.dp, vertical = 16.dp),
                    horizontalArrangement = Arrangement.SpaceBetween,
                    verticalAlignment     = Alignment.CenterVertically
                ) {
                    Column {
                        Text("Katalog Produk", fontWeight = FontWeight.Bold, fontSize = 18.sp)
                        if (products.isNotEmpty()) {
                            Text("${products.size} produk", fontSize = 12.sp, color = Color.Gray)
                        }
                    }

                    Box {
                        Surface(
                            modifier = Modifier.wrapContentWidth().clickable {
                                focusManager.clearFocus()
                                showSortMenu = true
                            },
                            color = Color(0xFFF5F5F5),
                            shape = RoundedCornerShape(8.dp)
                        ) {
                            Row(modifier = Modifier.padding(horizontal = 12.dp, vertical = 8.dp), verticalAlignment = Alignment.CenterVertically) {
                                Text(
                                    viewModel.selectedSortOption.value,
                                    fontSize   = 12.sp,
                                    fontWeight = FontWeight.Bold,
                                    color      = if (viewModel.selectedSortOption.value == "SORT BY") Color.Gray else Color.Black
                                )
                                Icon(Icons.Default.KeyboardArrowDown, null, modifier = Modifier.size(18.dp))
                            }
                        }
                        DropdownMenu(
                            expanded          = showSortMenu,
                            onDismissRequest  = { showSortMenu = false },
                            modifier          = Modifier.background(Color.White).width(160.dp)
                        ) {
                            sortOptions.forEach { option ->
                                DropdownMenuItem(
                                    text    = { Text(option, fontSize = 14.sp) },
                                    onClick = { viewModel.selectedSortOption.value = option; showSortMenu = false }
                                )
                            }
                        }
                    }
                }
            }

            // ── Product Grid ─────────────────────────────────────────────────
            if (isLoading) {
                item(span = { GridItemSpan(2) }) {
                    Box(modifier = Modifier.fillMaxWidth().height(200.dp), contentAlignment = Alignment.Center) {
                        CircularProgressIndicator(color = Color.Black)
                    }
                }
            } else if (errorMessage != null) {
                item(span = { GridItemSpan(2) }) {
                    Box(modifier = Modifier.fillMaxWidth().padding(32.dp), contentAlignment = Alignment.Center) {
                        Column(horizontalAlignment = Alignment.CenterHorizontally) {
                            Icon(Icons.Default.ErrorOutline, contentDescription = null, tint = Color.Red, modifier = Modifier.size(48.dp))
                            Spacer(modifier = Modifier.height(16.dp))
                            Text(errorMessage, color = Color.Gray, fontSize = 14.sp, textAlign = TextAlign.Center)
                            Spacer(modifier = Modifier.height(24.dp))
                            Button(
                                onClick = { viewModel.refreshData() },
                                colors = ButtonDefaults.buttonColors(containerColor = Color.Black)
                            ) {
                                Text("Coba Lagi", color = Color.White)
                            }
                        }
                    }
                }
            } else if (products.isEmpty()) {
                item(span = { GridItemSpan(2) }) {
                    Box(modifier = Modifier.fillMaxWidth().height(200.dp), contentAlignment = Alignment.Center) {
                        Column(horizontalAlignment = Alignment.CenterHorizontally) {
                            Text("🔍", fontSize = 40.sp)
                            Spacer(modifier = Modifier.height(12.dp))
                            Text("Produk tidak ditemukan", color = Color.Gray, fontSize = 14.sp)
                        }
                    }
                }
            } else {
                items(products) { product ->
                    ProductCard(product = product, onClick = {
                        focusManager.clearFocus()
                        onProductClick(product.id)
                    })
                }
            }
        }

        // ── Filter Bottom Sheet ───────────────────────────────────────────────
        if (showFilterSheet) {
            ModalBottomSheet(
                onDismissRequest = { showFilterSheet = false },
                sheetState       = sheetState,
                containerColor   = Color(0xFF1A232E)
            ) {
                Column(
                    modifier = Modifier.fillMaxWidth().padding(horizontal = 24.dp).verticalScroll(rememberScrollState())
                ) {
                    Row(
                        modifier              = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.SpaceBetween,
                        verticalAlignment     = Alignment.CenterVertically
                    ) {
                        Text("FILTER", color = Color.White, fontSize = 18.sp, fontWeight = FontWeight.Black)
                        TextButton(onClick = { viewModel.resetFilters() }) {
                            Text("Reset", color = Color.Red)
                        }
                    }
                    HorizontalDivider(modifier = Modifier.padding(vertical = 12.dp), color = Color.Gray)

                    Text("KATEGORI", color = Color.White, fontWeight = FontWeight.Bold, fontSize = 14.sp)
                    Spacer(modifier = Modifier.height(12.dp))
                    FlowRow(
                        modifier              = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.spacedBy(8.dp),
                        verticalArrangement   = Arrangement.spacedBy(8.dp)
                    ) {
                        categories.forEach { category ->
                            val isSelected = viewModel.selectedCategories.contains(category.id)
                            Surface(
                                modifier = Modifier.clickable { viewModel.toggleCategory(category.id) },
                                color    = if (isSelected) Color(0xFF2D3848) else Color.Transparent,
                                border   = androidx.compose.foundation.BorderStroke(1.dp, if (isSelected) Color.White else Color.Gray),
                                shape    = RoundedCornerShape(4.dp)
                            ) {
                                Text(category.name, color = Color.White, modifier = Modifier.padding(horizontal = 12.dp, vertical = 8.dp), fontSize = 12.sp)
                            }
                        }
                    }

                    Spacer(modifier = Modifier.height(24.dp))
                    Text("UKURAN", color = Color.White, fontWeight = FontWeight.Bold, fontSize = 14.sp)
                    Spacer(modifier = Modifier.height(12.dp))
                    FlowRow(
                        modifier              = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.spacedBy(8.dp),
                        verticalArrangement   = Arrangement.spacedBy(8.dp)
                    ) {
                        sizes.forEach { size ->
                            val isSelected = viewModel.selectedSizes.contains(size.id)
                            Surface(
                                modifier = Modifier.clickable { viewModel.toggleSize(size.id) },
                                color    = if (isSelected) Color(0xFF2D3848) else Color.Transparent,
                                border   = androidx.compose.foundation.BorderStroke(1.dp, if (isSelected) Color.White else Color.Gray),
                                shape    = RoundedCornerShape(4.dp)
                            ) {
                                Text(size.name, color = Color.White, modifier = Modifier.padding(horizontal = 12.dp, vertical = 8.dp), fontSize = 12.sp)
                            }
                        }
                    }

                    Spacer(modifier = Modifier.height(24.dp))
                    Text("RANGE HARGA", color = Color.White, fontWeight = FontWeight.Bold, fontSize = 14.sp)
                    Column(modifier = Modifier.padding(top = 8.dp)) {
                        listOf("< Rp 100.000", "Rp 100.000 - Rp 500.000", "> Rp 500.000").forEach { range ->
                            Row(
                                verticalAlignment = Alignment.CenterVertically,
                                modifier          = Modifier.fillMaxWidth().clickable { viewModel.setPriceRange(range) }.padding(vertical = 4.dp)
                            ) {
                                RadioButton(
                                    selected = viewModel.selectedPriceRange.value == range,
                                    onClick  = { viewModel.setPriceRange(range) },
                                    colors   = RadioButtonDefaults.colors(selectedColor = Color.White, unselectedColor = Color.White)
                                )
                                Text(range, color = Color.White, fontSize = 14.sp)
                            }
                        }
                    }

                    Spacer(modifier = Modifier.height(32.dp))
                    Button(
                        onClick  = { showFilterSheet = false },
                        modifier = Modifier.fillMaxWidth().height(56.dp),
                        colors   = ButtonDefaults.buttonColors(containerColor = Color.White),
                        shape    = RoundedCornerShape(16.dp)
                    ) {
                        Text("TERAPKAN FILTER", color = Color.Black, fontWeight = FontWeight.Bold)
                    }
                    Spacer(modifier = Modifier.height(32.dp))
                }
            }
        }
    }
}
