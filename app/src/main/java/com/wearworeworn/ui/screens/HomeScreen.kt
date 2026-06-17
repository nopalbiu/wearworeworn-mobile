package com.wearworeworn.ui.screens

import androidx.compose.foundation.Image
import androidx.compose.foundation.background
import androidx.compose.foundation.border
import androidx.compose.foundation.clickable
import androidx.compose.foundation.gestures.FlingBehavior
import androidx.compose.foundation.gestures.ScrollableDefaults
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.grid.GridCells
import androidx.compose.foundation.lazy.grid.GridItemSpan
import androidx.compose.foundation.lazy.grid.LazyVerticalGrid
import androidx.compose.foundation.lazy.grid.items
import androidx.compose.foundation.lazy.grid.rememberLazyGridState
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.text.KeyboardActions
import androidx.compose.foundation.text.KeyboardOptions
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.*
import androidx.compose.material.icons.outlined.Search
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.platform.LocalFocusManager
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.input.ImeAction
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.wearworeworn.R
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
    val gridState       = rememberLazyGridState()

    val sortOptions = listOf("Terbaru", "Harga: Termurah", "Harga: Termahal")

    Scaffold(
        modifier = Modifier.fillMaxSize(),
        topBar   = {
            Surface(
                modifier        = Modifier.statusBarsPadding(),
                color           = MaterialTheme.colorScheme.background,
                shadowElevation = 0.dp
            ) {
                Column(modifier = Modifier.padding(bottom = 8.dp)) {
                    Row(
                        modifier              = Modifier.fillMaxWidth().padding(horizontal = 16.dp, vertical = 8.dp),
                        verticalAlignment     = Alignment.CenterVertically,
                        horizontalArrangement = Arrangement.SpaceBetween
                    ) {
                        Image(
                            painter = painterResource(id = R.drawable.logo_www),
                            contentDescription = "WearWoreWorn Logo",
                            modifier = Modifier
                                .height(32.dp)
                                .widthIn(max = 160.dp),
                            contentScale = ContentScale.Fit
                        )
                        Row {
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
                                IconButton(onClick = {
                                    focusManager.clearFocus()
                                    if (isLoggedIn) onNavigateToCart() else onNavigateToLogin()
                                }) {
                                    Icon(Icons.Default.ShoppingCart, contentDescription = "Keranjang")
                                }
                            }

                            IconButton(onClick = {
                                focusManager.clearFocus()
                                if (isLoggedIn) onNavigateToProfile() else onNavigateToLogin()
                            }) {
                                if (isLoggedIn) {
                                    Icon(Icons.Default.AccountCircle, contentDescription = "Profil", tint = MaterialTheme.colorScheme.onBackground)
                                } else {
                                    Icon(Icons.Default.AccountCircle, contentDescription = "Login", tint = MaterialTheme.colorScheme.outline)
                                }
                            }
                        }
                    }

                    Box(modifier = Modifier.padding(horizontal = 16.dp)) {
                        TextField(
                            value         = viewModel.searchQuery.value,
                            onValueChange = { viewModel.searchQuery.value = it },
                            modifier      = Modifier.fillMaxWidth().height(56.dp),
                            placeholder   = { Text("Cari produk...", color = MaterialTheme.colorScheme.onSurfaceVariant.copy(alpha = 0.6f), fontSize = 14.sp) },
                            leadingIcon   = {
                                Icon(
                                    Icons.Outlined.Search,
                                    contentDescription = null,
                                    tint     = MaterialTheme.colorScheme.onSurfaceVariant,
                                    modifier = Modifier.size(20.dp)
                                )
                            },
                            trailingIcon  = {
                                if (viewModel.searchQuery.value.isNotEmpty()) {
                                    IconButton(onClick = {
                                        viewModel.searchQuery.value = ""
                                        focusManager.clearFocus()
                                    }) {
                                        Icon(Icons.Default.Close, contentDescription = "Hapus", modifier = Modifier.size(20.dp), tint = MaterialTheme.colorScheme.onSurfaceVariant)
                                    }
                                }
                            },
                            colors = TextFieldDefaults.colors(
                                focusedContainerColor   = MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f),
                                unfocusedContainerColor = MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f),
                                focusedIndicatorColor   = Color.Transparent,
                                unfocusedIndicatorColor = Color.Transparent,
                                disabledIndicatorColor  = Color.Transparent,
                                cursorColor             = MaterialTheme.colorScheme.primary,
                                focusedTextColor        = MaterialTheme.colorScheme.onSurface,
                                unfocusedTextColor      = MaterialTheme.colorScheme.onSurface
                            ),
                            shape           = RoundedCornerShape(12.dp),
                            singleLine      = true,
                            textStyle       = LocalTextStyle.current.copy(fontSize = 14.sp, fontWeight = FontWeight.Normal),
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
                color         = MaterialTheme.colorScheme.primary,
                shape         = RoundedCornerShape(12.dp),
                shadowElevation = 0.dp
            ) {
                Row(
                    modifier              = Modifier.fillMaxSize(),
                    horizontalArrangement = Arrangement.Center,
                    verticalAlignment     = Alignment.CenterVertically
                ) {
                    Icon(Icons.Default.FilterList, contentDescription = null, tint = MaterialTheme.colorScheme.onPrimary)
                    Spacer(modifier = Modifier.width(8.dp))
                    Text("FILTER", color = MaterialTheme.colorScheme.onPrimary, fontWeight = FontWeight.Black, letterSpacing = 2.sp)
                    
                    val activeFilters = viewModel.selectedCategories.size + viewModel.selectedSizes.size +
                        (if (viewModel.selectedPriceRange.value != null) 1 else 0)
                    if (activeFilters > 0) {
                        Spacer(modifier = Modifier.width(8.dp))
                        Surface(color = MaterialTheme.colorScheme.onPrimary, shape = RoundedCornerShape(8.dp)) {
                            Text(
                                "$activeFilters",
                                color    = MaterialTheme.colorScheme.primary,
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
            columns               = GridCells.Fixed(2),
            state                 = gridState,
            modifier              = Modifier.fillMaxSize().padding(horizontal = 12.dp).clickable { focusManager.clearFocus() },
            contentPadding        = PaddingValues(
                top    = innerPadding.calculateTopPadding() + 8.dp,
                bottom = innerPadding.calculateBottomPadding() + 80.dp
            ),
            horizontalArrangement = Arrangement.spacedBy(12.dp),
            verticalArrangement   = Arrangement.spacedBy(12.dp),
            flingBehavior         = ScrollableDefaults.flingBehavior()
        ) {
            if (viewModel.searchQuery.value.isEmpty()) {
                item(span = { GridItemSpan(2) }) {
                    Card(
                        modifier = Modifier
                            .fillMaxWidth()
                            .padding(horizontal = 8.dp, vertical = 8.dp)
                            .border(
                                width = 1.dp,
                                color = MaterialTheme.colorScheme.outline.copy(alpha = 0.2f),
                                shape = RoundedCornerShape(12.dp)
                            ),
                        shape    = RoundedCornerShape(12.dp),
                        colors   = CardDefaults.cardColors(containerColor = MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f)),
                        elevation = CardDefaults.cardElevation(defaultElevation = 0.dp)
                    ) {
                        Column(
                            modifier = Modifier.padding(if (isLoggedIn) 20.dp else 24.dp),
                            horizontalAlignment = Alignment.CenterHorizontally
                        ) {
                            if (isLoggedIn) {
                                val firstName = authViewModel.currentUser.value?.name?.split(" ")?.firstOrNull() ?: ""
                                Text(
                                    text       = "HALO, ${firstName.uppercase()}!",
                                    color      = MaterialTheme.colorScheme.onSurface,
                                    fontSize   = 18.sp,
                                    fontWeight = FontWeight.Black,
                                    textAlign  = TextAlign.Center,
                                    letterSpacing = 1.5.sp
                                )
                                Spacer(modifier = Modifier.height(4.dp))
                                Text(
                                    text      = "Temukan koleksi pilihan untuk gaya terbaikmu hari ini.",
                                    color     = MaterialTheme.colorScheme.onSurfaceVariant,
                                    fontSize  = 13.sp,
                                    textAlign = TextAlign.Center,
                                    fontWeight = FontWeight.Medium
                                )
                            } else {
                                Text(
                                    text       = "WEARWOREWORN",
                                    color      = MaterialTheme.colorScheme.onSurface,
                                    fontSize   = 22.sp,
                                    fontWeight = FontWeight.Black,
                                    textAlign  = TextAlign.Center,
                                    letterSpacing = 0.5.sp
                                )
                                Spacer(modifier = Modifier.height(8.dp))
                                Text(
                                    text      = "Login untuk mengakses koleksi eksklusif dan mulai belanja produk favoritmu.",
                                    color     = MaterialTheme.colorScheme.onSurfaceVariant,
                                    fontSize  = 14.sp,
                                    textAlign = TextAlign.Center
                                )
                                Spacer(modifier = Modifier.height(20.dp))
                                Button(
                                    onClick = onNavigateToLogin,
                                    modifier = Modifier.height(44.dp),
                                    shape   = RoundedCornerShape(12.dp),
                                    colors  = ButtonDefaults.buttonColors(
                                        containerColor = MaterialTheme.colorScheme.primary,
                                        contentColor = MaterialTheme.colorScheme.onPrimary
                                    )
                                ) {
                                    Text("LOGIN SEKARANG", fontWeight = FontWeight.Black, fontSize = 13.sp, letterSpacing = 1.sp)
                                }
                            }
                        }
                    }
                }
            }

            item(span = { GridItemSpan(2) }) {
                Row(
                    modifier              = Modifier.fillMaxWidth().padding(horizontal = 8.dp, vertical = 16.dp),
                    horizontalArrangement = Arrangement.SpaceBetween,
                    verticalAlignment     = Alignment.CenterVertically
                ) {
                    Column {
                        Text(
                            text       = if (viewModel.searchQuery.value.isEmpty()) "CATALOGUE" else "SEARCH RESULTS",
                            fontWeight = FontWeight.Black,
                            fontSize   = 16.sp,
                            color      = MaterialTheme.colorScheme.onBackground,
                            letterSpacing = 1.sp
                        )
                        if (products.isNotEmpty()) {
                            Text("${products.size} ITEMS FOUND", fontSize = 10.sp, color = MaterialTheme.colorScheme.onSurfaceVariant, fontWeight = FontWeight.Bold)
                        }
                    }

                    Box {
                        Surface(
                            modifier = Modifier.wrapContentWidth().clickable {
                                focusManager.clearFocus()
                                showSortMenu = true
                            },
                            color = MaterialTheme.colorScheme.surfaceVariant.copy(alpha = 0.5f),
                            shape = RoundedCornerShape(12.dp)
                        ) {
                            Row(modifier = Modifier.padding(horizontal = 12.dp, vertical = 8.dp), verticalAlignment = Alignment.CenterVertically) {
                                Text(
                                    viewModel.selectedSortOption.value.uppercase(),
                                    fontSize   = 11.sp,
                                    fontWeight = FontWeight.Black,
                                    color      = MaterialTheme.colorScheme.onSurface,
                                    letterSpacing = 0.5.sp
                                )
                                Spacer(modifier = Modifier.width(4.dp))
                                Icon(Icons.Default.KeyboardArrowDown, null, modifier = Modifier.size(16.dp))
                            }
                        }
                        DropdownMenu(
                            expanded         = showSortMenu,
                            onDismissRequest = { showSortMenu = false },
                            modifier         = Modifier.background(MaterialTheme.colorScheme.surfaceVariant).width(160.dp)
                        ) {
                            sortOptions.forEach { option ->
                                DropdownMenuItem(
                                    text    = { Text(option.uppercase(), fontSize = 12.sp, fontWeight = FontWeight.Bold) },
                                    onClick = { viewModel.selectedSortOption.value = option; showSortMenu = false }
                                )
                            }
                        }
                    }
                }
            }

            if (isLoading) {
                item(span = { GridItemSpan(2) }) {
                    Box(modifier = Modifier.fillMaxWidth().height(200.dp), contentAlignment = Alignment.Center) {
                        CircularProgressIndicator(color = MaterialTheme.colorScheme.primary)
                    }
                }
            } else if (errorMessage != null) {
                item(span = { GridItemSpan(2) }) {
                    Box(modifier = Modifier.fillMaxWidth().padding(32.dp), contentAlignment = Alignment.Center) {
                        Column(horizontalAlignment = Alignment.CenterHorizontally) {
                            Icon(Icons.Default.ErrorOutline, contentDescription = null, tint = Color.Red, modifier = Modifier.size(48.dp))
                            Spacer(modifier = Modifier.height(16.dp))
                            Text(errorMessage, color = MaterialTheme.colorScheme.onSurfaceVariant, fontSize = 14.sp, textAlign = TextAlign.Center)
                            Spacer(modifier = Modifier.height(24.dp))
                            Button(
                                onClick = { viewModel.refreshData() },
                                colors = ButtonDefaults.buttonColors(containerColor = MaterialTheme.colorScheme.primary, contentColor = MaterialTheme.colorScheme.onPrimary),
                                shape = RoundedCornerShape(12.dp)
                            ) {
                                Text("TRY AGAIN", fontWeight = FontWeight.Black)
                            }
                        }
                    }
                }
            } else if (products.isEmpty()) {
                item(span = { GridItemSpan(2) }) {
                    Box(modifier = Modifier.fillMaxWidth().height(300.dp), contentAlignment = Alignment.Center) {
                        Column(horizontalAlignment = Alignment.CenterHorizontally) {
                            Text("🔍", fontSize = 48.sp)
                            Spacer(modifier = Modifier.height(16.dp))
                            Text("NO PRODUCTS FOUND", fontWeight = FontWeight.Black, color = MaterialTheme.colorScheme.onBackground, fontSize = 16.sp, letterSpacing = 1.sp)
                            Spacer(modifier = Modifier.height(8.dp))
                            Text("Try searching with different keywords or resetting filters.", color = MaterialTheme.colorScheme.onSurfaceVariant, fontSize = 14.sp, textAlign = TextAlign.Center)
                            
                            Spacer(modifier = Modifier.height(24.dp))
                            Button(
                                onClick = {
                                    viewModel.searchQuery.value = ""
                                    viewModel.resetFilters()
                                    viewModel.refreshData()
                                    focusManager.clearFocus()
                                },
                                shape = RoundedCornerShape(12.dp),
                                colors = ButtonDefaults.buttonColors(containerColor = MaterialTheme.colorScheme.primary)
                            ) {
                                Text("RESET FILTERS", fontWeight = FontWeight.Black)
                            }
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

        if (showFilterSheet) {
            ModalBottomSheet(
                onDismissRequest = { showFilterSheet = false },
                sheetState       = sheetState,
                containerColor   = MaterialTheme.colorScheme.surface,
                dragHandle = { BottomSheetDefaults.DragHandle(color = MaterialTheme.colorScheme.onSurfaceVariant.copy(alpha = 0.3f)) }
            ) {
                Column(
                    modifier = Modifier.fillMaxWidth().padding(horizontal = 24.dp).verticalScroll(rememberScrollState())
                ) {
                    Row(
                        modifier              = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.SpaceBetween,
                        verticalAlignment     = Alignment.CenterVertically
                    ) {
                        Text("FILTERS", color = MaterialTheme.colorScheme.onSurface, fontSize = 20.sp, fontWeight = FontWeight.Black, letterSpacing = 1.sp)
                        TextButton(onClick = { viewModel.resetFilters() }) {
                            Text("RESET ALL", color = Color(0xFFE57373), fontWeight = FontWeight.Bold, fontSize = 12.sp)
                        }
                    }
                    Spacer(modifier = Modifier.height(8.dp))
                    HorizontalDivider(color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.05f))
                    Spacer(modifier = Modifier.height(20.dp))

                    Text("CATEGORY", color = MaterialTheme.colorScheme.onSurface, fontWeight = FontWeight.Black, fontSize = 12.sp, letterSpacing = 1.sp)
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
                                color    = if (isSelected) MaterialTheme.colorScheme.primary else MaterialTheme.colorScheme.surfaceVariant,
                                shape    = RoundedCornerShape(if (isSelected) 12.dp else 8.dp)
                            ) {
                                Text(
                                    category.name.uppercase(),
                                    color = if (isSelected) MaterialTheme.colorScheme.onPrimary else MaterialTheme.colorScheme.onSurface,
                                    modifier = Modifier.padding(horizontal = 16.dp, vertical = 10.dp),
                                    fontSize = 11.sp,
                                    fontWeight = FontWeight.Bold
                                )
                            }
                        }
                    }

                    Spacer(modifier = Modifier.height(24.dp))
                    Text("SIZE", color = MaterialTheme.colorScheme.onSurface, fontWeight = FontWeight.Black, fontSize = 12.sp, letterSpacing = 1.sp)
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
                                color    = if (isSelected) MaterialTheme.colorScheme.primary else MaterialTheme.colorScheme.surfaceVariant,
                                shape    = RoundedCornerShape(if (isSelected) 12.dp else 8.dp)
                            ) {
                                Text(
                                    size.name.uppercase(),
                                    color = if (isSelected) MaterialTheme.colorScheme.onPrimary else MaterialTheme.colorScheme.onSurface,
                                    modifier = Modifier.padding(horizontal = 16.dp, vertical = 10.dp),
                                    fontSize = 11.sp,
                                    fontWeight = FontWeight.Bold
                                )
                            }
                        }
                    }

                    Spacer(modifier = Modifier.height(24.dp))
                    Text("PRICE RANGE", color = MaterialTheme.colorScheme.onSurface, fontWeight = FontWeight.Black, fontSize = 12.sp, letterSpacing = 1.sp)
                    Column(modifier = Modifier.padding(top = 8.dp)) {
                        listOf("< Rp 100.000", "Rp 100.000 - Rp 500.000", "> Rp 500.000").forEach { range ->
                            Row(
                                verticalAlignment = Alignment.CenterVertically,
                                modifier          = Modifier.fillMaxWidth().clickable { viewModel.setPriceRange(range) }.padding(vertical = 4.dp)
                            ) {
                                RadioButton(
                                    selected = viewModel.selectedPriceRange.value == range,
                                    onClick  = { viewModel.setPriceRange(range) },
                                    modifier = Modifier.size(24.dp),
                                    colors   = RadioButtonDefaults.colors(
                                        selectedColor = MaterialTheme.colorScheme.primary,
                                        unselectedColor = MaterialTheme.colorScheme.outline
                                    )
                                )
                                Text(range, color = MaterialTheme.colorScheme.onSurface, fontSize = 14.sp, fontWeight = FontWeight.Medium)
                            }
                        }
                    }

                    Spacer(modifier = Modifier.height(32.dp))
                    Button(
                        onClick  = { showFilterSheet = false },
                        modifier = Modifier.fillMaxWidth().height(56.dp),
                        colors   = ButtonDefaults.buttonColors(containerColor = MaterialTheme.colorScheme.primary),
                        shape    = RoundedCornerShape(12.dp)
                    ) {
                        Text("APPLY FILTERS", color = MaterialTheme.colorScheme.onPrimary, fontWeight = FontWeight.Black, letterSpacing = 1.sp)
                    }
                    Spacer(modifier = Modifier.height(32.dp))
                }
            }
        }
    }
}
