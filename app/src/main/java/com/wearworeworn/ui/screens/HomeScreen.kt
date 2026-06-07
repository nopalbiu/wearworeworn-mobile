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
import com.wearworeworn.viewmodel.ProductViewModel

@OptIn(ExperimentalLayoutApi::class, ExperimentalMaterial3Api::class)
@Composable
fun HomeScreen(viewModel: ProductViewModel, onProductClick: (Int) -> Unit) {
    val products = viewModel.filteredProducts
    val isLoading = viewModel.isLoading.value
    val categories = viewModel.categories.value
    val sizes = viewModel.sizes.value
    val focusManager = LocalFocusManager.current

    var showSortMenu by remember { mutableStateOf(false) }
    var showFilterSheet by remember { mutableStateOf(false) }
    val sheetState = rememberModalBottomSheetState(skipPartiallyExpanded = true)

    val sortOptions = listOf("Terbaru", "Harga: Termurah", "Harga: Termahal")

    Scaffold(
        modifier = Modifier.fillMaxSize(),
        topBar = {
            Surface(
                modifier = Modifier.statusBarsPadding(),
                color = Color.White,
                shadowElevation = 0.dp
            ) {
                Column(modifier = Modifier.padding(bottom = 8.dp)) {
                    Row(
                        modifier = Modifier
                            .fillMaxWidth()
                            .padding(horizontal = 16.dp, vertical = 8.dp),
                        verticalAlignment = Alignment.CenterVertically,
                        horizontalArrangement = Arrangement.SpaceBetween
                    ) {
                        Text(
                            text = "WearWoreWorn",
                            fontWeight = FontWeight.Black,
                            fontSize = 22.sp,
                            letterSpacing = (-1).sp
                        )
                        Row {
                            IconButton(onClick = {}) {
                                Icon(Icons.Default.ShoppingCart, contentDescription = "Cart")
                            }
                            IconButton(onClick = {}) {
                                Icon(Icons.Default.AccountCircle, contentDescription = "Profile")
                            }
                        }
                    }
                    
                    Box(modifier = Modifier.padding(horizontal = 16.dp)) {
                        TextField(
                            value = viewModel.searchQuery.value,
                            onValueChange = { viewModel.searchQuery.value = it },
                            modifier = Modifier
                                .fillMaxWidth()
                                .height(56.dp),
                            placeholder = { Text("What are you looking for?", color = Color.Gray, fontSize = 14.sp) },
                            leadingIcon = { 
                                Icon(
                                    Icons.Default.Search, 
                                    contentDescription = null, 
                                    tint = if (viewModel.searchQuery.value.isEmpty()) Color.Gray else Color.Black,
                                    modifier = Modifier.size(22.dp)
                                ) 
                            },
                            trailingIcon = {
                                if (viewModel.searchQuery.value.isNotEmpty()) {
                                    IconButton(onClick = { 
                                        viewModel.searchQuery.value = "" 
                                        focusManager.clearFocus()
                                    }) {
                                        Icon(Icons.Default.Close, contentDescription = "Clear", modifier = Modifier.size(18.dp))
                                    }
                                }
                            },
                            colors = TextFieldDefaults.colors(
                                focusedContainerColor = Color(0xFFF1F1F1),
                                unfocusedContainerColor = Color(0xFFF1F1F1),
                                disabledContainerColor = Color(0xFFF1F1F1),
                                focusedIndicatorColor = Color.Transparent,
                                unfocusedIndicatorColor = Color.Transparent,
                                cursorColor = Color.Black
                            ),
                            shape = RoundedCornerShape(16.dp),
                            singleLine = true,
                            textStyle = LocalTextStyle.current.copy(fontSize = 15.sp, fontWeight = FontWeight.Medium),
                            keyboardOptions = KeyboardOptions(imeAction = ImeAction.Search),
                            keyboardActions = KeyboardActions(
                                onSearch = {
                                    focusManager.clearFocus()
                                }
                            )
                        )
                    }
                }
            }
        },
        bottomBar = {
            Surface(
                modifier = Modifier
                    .fillMaxWidth()
                    .navigationBarsPadding()
                    .padding(16.dp)
                    .height(56.dp)
                    .clickable { 
                        focusManager.clearFocus()
                        showFilterSheet = true 
                    },
                color = Color.Black,
                shape = RoundedCornerShape(16.dp),
                shadowElevation = 8.dp
            ) {
                Row(
                    modifier = Modifier.fillMaxSize(),
                    horizontalArrangement = Arrangement.Center,
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    Icon(Icons.Default.FilterList, contentDescription = null, tint = Color.White)
                    Spacer(modifier = Modifier.width(8.dp))
                    Text(
                        text = "FILTERS",
                        color = Color.White,
                        fontWeight = FontWeight.Bold,
                        letterSpacing = 2.sp
                    )
                }
            }
        }
    ) { innerPadding ->
        LazyVerticalGrid(
            columns = GridCells.Fixed(2),
            modifier = Modifier
                .fillMaxSize()
                .padding(horizontal = 8.dp)
                .clickable { focusManager.clearFocus() },
            contentPadding = PaddingValues(
                top = innerPadding.calculateTopPadding() + 8.dp,
                bottom = innerPadding.calculateBottomPadding() + 80.dp
            )
        ) {
            item(span = { GridItemSpan(2) }) {
                Card(
                    modifier = Modifier
                        .fillMaxWidth()
                        .padding(horizontal = 8.dp, vertical = 8.dp),
                    shape = RoundedCornerShape(24.dp),
                    colors = CardDefaults.cardColors(containerColor = Color(0xFF212121))
                ) {
                    Column(
                        modifier = Modifier.padding(24.dp),
                        horizontalAlignment = Alignment.CenterHorizontally
                    ) {
                        Text(
                            text = "Find Your Best Style",
                            color = Color.White,
                            fontSize = 24.sp,
                            fontWeight = FontWeight.ExtraBold,
                            textAlign = TextAlign.Center
                        )
                        Spacer(modifier = Modifier.height(8.dp))
                        Text(
                            text = "Discover the latest trends in teen & adult outfits.",
                            color = Color.LightGray,
                            fontSize = 14.sp,
                            textAlign = TextAlign.Center
                        )
                    }
                }
            }

            item(span = { GridItemSpan(2) }) {
                Row(
                    modifier = Modifier
                        .fillMaxWidth()
                        .padding(horizontal = 8.dp, vertical = 16.dp),
                    horizontalArrangement = Arrangement.SpaceBetween,
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    Text(
                        text = "Product Catalog",
                        fontWeight = FontWeight.Bold,
                        fontSize = 18.sp
                    )
                    
                    Box {
                        Surface(
                            modifier = Modifier
                                .wrapContentWidth()
                                .clickable { 
                                    focusManager.clearFocus()
                                    showSortMenu = true 
                                },
                            color = Color(0xFFF5F5F5),
                            shape = RoundedCornerShape(8.dp)
                        ) {
                            Row(
                                modifier = Modifier.padding(horizontal = 12.dp, vertical = 8.dp),
                                verticalAlignment = Alignment.CenterVertically
                            ) {
                                Text(
                                    text = viewModel.selectedSortOption.value,
                                    fontSize = 12.sp,
                                    fontWeight = FontWeight.Bold,
                                    color = if (viewModel.selectedSortOption.value == "SORT BY") Color.Gray else Color.Black
                                )
                                Icon(Icons.Default.KeyboardArrowDown, contentDescription = null, modifier = Modifier.size(18.dp))
                            }
                        }

                        DropdownMenu(
                            expanded = showSortMenu,
                            onDismissRequest = { showSortMenu = false },
                            modifier = Modifier.background(Color.White).width(160.dp)
                        ) {
                            sortOptions.forEach { option ->
                                DropdownMenuItem(
                                    text = { Text(option, fontSize = 14.sp) },
                                    onClick = {
                                        viewModel.selectedSortOption.value = option
                                        showSortMenu = false
                                    }
                                )
                            }
                        }
                    }
                }
            }

            if (isLoading) {
                item(span = { GridItemSpan(2) }) {
                    Box(modifier = Modifier.fillMaxWidth().height(200.dp), contentAlignment = Alignment.Center) {
                        CircularProgressIndicator(color = Color.Black)
                    }
                }
            } else if (products.isEmpty()) {
                item(span = { GridItemSpan(2) }) {
                    Box(modifier = Modifier.fillMaxWidth().height(200.dp), contentAlignment = Alignment.Center) {
                        Text("Belum ada produk yang cocok.", color = Color.Gray)
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
                sheetState = sheetState,
                containerColor = Color(0xFF1A232E)
            ) {
                Column(
                    modifier = Modifier
                        .fillMaxWidth()
                        .padding(horizontal = 24.dp)
                        .verticalScroll(rememberScrollState())
                ) {
                    Row(
                        modifier = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.SpaceBetween,
                        verticalAlignment = Alignment.CenterVertically
                    ) {
                        Text("FILTERS", color = Color.White, fontSize = 18.sp, fontWeight = FontWeight.Black)
                        TextButton(onClick = { viewModel.resetFilters() }) {
                            Text("Reset All", color = Color.Red)
                        }
                    }
                    HorizontalDivider(modifier = Modifier.padding(vertical = 12.dp), color = Color.Gray)
                    
                    Text("CATEGORIES", color = Color.White, fontWeight = FontWeight.Bold, fontSize = 14.sp)
                    Spacer(modifier = Modifier.height(12.dp))
                    FlowRow(
                        modifier = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.spacedBy(8.dp),
                        verticalArrangement = Arrangement.spacedBy(8.dp)
                    ) {
                        categories.forEach { category ->
                            val isSelected = viewModel.selectedCategories.contains(category.id)
                            Surface(
                                modifier = Modifier.clickable { viewModel.toggleCategory(category.id) },
                                color = if (isSelected) Color(0xFF2D3848) else Color.Transparent,
                                border = androidx.compose.foundation.BorderStroke(1.dp, Color.Gray),
                                shape = RoundedCornerShape(4.dp)
                            ) {
                                Text(
                                    category.name,
                                    color = Color.White,
                                    modifier = Modifier.padding(horizontal = 12.dp, vertical = 8.dp),
                                    fontSize = 12.sp
                                )
                            }
                        }
                    }

                    Spacer(modifier = Modifier.height(24.dp))

                    Text("SIZE", color = Color.White, fontWeight = FontWeight.Bold, fontSize = 14.sp)
                    Spacer(modifier = Modifier.height(12.dp))
                    FlowRow(
                        modifier = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.spacedBy(8.dp),
                        verticalArrangement = Arrangement.spacedBy(8.dp)
                    ) {
                        sizes.forEach { size ->
                            val isSelected = viewModel.selectedSizes.contains(size.id)
                            Surface(
                                modifier = Modifier.clickable { viewModel.toggleSize(size.id) },
                                color = if (isSelected) Color(0xFF2D3848) else Color.Transparent,
                                border = androidx.compose.foundation.BorderStroke(1.dp, Color.Gray),
                                shape = RoundedCornerShape(4.dp)
                            ) {
                                Text(
                                    size.name,
                                    color = Color.White,
                                    modifier = Modifier.padding(horizontal = 12.dp, vertical = 8.dp),
                                    fontSize = 12.sp
                                )
                            }
                        }
                    }

                    Spacer(modifier = Modifier.height(24.dp))

                    Text("PRICE RANGE", color = Color.White, fontWeight = FontWeight.Bold, fontSize = 14.sp)
                    Column(modifier = Modifier.padding(top = 8.dp)) {
                        listOf("< Rp 100.000", "Rp 100.000 - Rp 500.000", "> Rp 500.000").forEach { range ->
                            Row(
                                verticalAlignment = Alignment.CenterVertically, 
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .clickable { viewModel.setPriceRange(range) }
                                    .padding(vertical = 4.dp)
                            ) {
                                RadioButton(
                                    selected = viewModel.selectedPriceRange.value == range, 
                                    onClick = { viewModel.setPriceRange(range) },
                                    colors = RadioButtonDefaults.colors(
                                        selectedColor = Color.White,
                                        unselectedColor = Color.White
                                    )
                                )
                                Text(range, color = Color.White, fontSize = 14.sp)
                            }
                        }
                    }
                    
                    Spacer(modifier = Modifier.height(32.dp))
                    Button(
                        onClick = { showFilterSheet = false },
                        modifier = Modifier
                            .fillMaxWidth()
                            .height(56.dp),
                        colors = ButtonDefaults.buttonColors(containerColor = Color.White),
                        shape = RoundedCornerShape(16.dp)
                    ) {
                        Text("APPLY FILTERS", color = Color.Black, fontWeight = FontWeight.Bold)
                    }
                    Spacer(modifier = Modifier.height(32.dp))
                }
            }
        }
    }
}
