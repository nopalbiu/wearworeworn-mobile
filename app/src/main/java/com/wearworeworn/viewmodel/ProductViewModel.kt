package com.wearworeworn.viewmodel

import android.util.Log
import androidx.compose.runtime.State
import androidx.compose.runtime.mutableStateListOf
import androidx.compose.runtime.mutableStateOf
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.wearworeworn.model.Category
import com.wearworeworn.model.Product
import com.wearworeworn.model.Size
import com.wearworeworn.network.RetrofitClient
import kotlinx.coroutines.launch

class ProductViewModel : ViewModel() {
    private val _products = mutableStateOf<List<Product>>(emptyList())
    val products: State<List<Product>> = _products

    private val _categories = mutableStateOf<List<Category>>(emptyList())
    val categories: State<List<Category>> = _categories

    private val _sizes = mutableStateOf<List<Size>>(emptyList())
    val sizes: State<List<Size>> = _sizes

    private val _selectedProduct = mutableStateOf<Product?>(null)
    val selectedProduct: State<Product?> = _selectedProduct

    private val _isLoading = mutableStateOf(false)
    val isLoading: State<Boolean> = _isLoading

    private val _errorMessage = mutableStateOf<String?>(null)
    val errorMessage: State<String?> = _errorMessage

    // UI States for Filtering/Sorting
    var searchQuery = mutableStateOf("")
    var selectedSortOption = mutableStateOf("SORT BY")
    
    // Multi-select for categories and sizes
    val selectedCategories = mutableStateListOf<Int>()
    val selectedSizes = mutableStateListOf<Int>()
    
    var selectedPriceRange = mutableStateOf<String?>(null)

    init {
        refreshData()
    }

    fun refreshData() {
        fetchInitialData()
    }

    private fun fetchInitialData() {
        viewModelScope.launch {
            _isLoading.value = true
            _errorMessage.value = null
            try {
                _products.value = RetrofitClient.instance.getProducts()
                _categories.value = RetrofitClient.instance.getCategories()
                _sizes.value = RetrofitClient.instance.getSizes()
            } catch (e: Exception) {
                Log.e("API_ERROR", "Error fetching data: ${e.message}")
                _errorMessage.value = "Gagal memuat data: ${e.localizedMessage ?: "Cek koneksi internet atau server"}"
            } finally {
                _isLoading.value = false
            }
        }
    }

    fun fetchProductById(id: Int) {
        viewModelScope.launch {
            _isLoading.value = true
            try {
                _selectedProduct.value = RetrofitClient.instance.getProductById(id)
            } catch (e: Exception) {
                Log.e("API_ERROR", "Error fetching detail: ${e.message}")
            } finally {
                _isLoading.value = false
            }
        }
    }

    fun toggleCategory(categoryId: Int) {
        if (selectedCategories.contains(categoryId)) {
            selectedCategories.remove(categoryId)
        } else {
            selectedCategories.add(categoryId)
        }
    }

    fun toggleSize(sizeId: Int) {
        if (selectedSizes.contains(sizeId)) {
            selectedSizes.remove(sizeId)
        } else {
            selectedSizes.add(sizeId)
        }
    }

    fun setPriceRange(range: String) {
        if (selectedPriceRange.value == range) {
            selectedPriceRange.value = null 
        } else {
            selectedPriceRange.value = range
        }
    }

    fun resetFilters() {
        selectedCategories.clear()
        selectedSizes.clear()
        selectedPriceRange.value = null
        searchQuery.value = ""
    }

    // Logic for filtered and sorted products
    val filteredProducts: List<Product>
        get() {
            var list = _products.value

            // 1. Search Filter
            if (searchQuery.value.isNotEmpty()) {
                list = list.filter { it.name.contains(searchQuery.value, ignoreCase = true) }
            }

            // 2. Category Filter (Multi-select)
            if (selectedCategories.isNotEmpty()) {
                list = list.filter { product ->
                    product.categories.any { cat -> selectedCategories.contains(cat.id) }
                }
            }

            // 3. Size Filter (Multi-select)
            if (selectedSizes.isNotEmpty()) {
                list = list.filter { product ->
                    product.variants.any { variant -> 
                        variant.size?.id != null && selectedSizes.contains(variant.size.id) 
                    }
                }
            }

            // 4. Price Range Filter
            when (selectedPriceRange.value) {
                "< Rp 100.000" -> list = list.filter { it.price < 100000 }
                "Rp 100.000 - Rp 500.000" -> list = list.filter { it.price in 100000.0..500000.0 }
                "> Rp 500.000" -> list = list.filter { it.price > 500000 }
            }

            // 5. Sorting
            return when (selectedSortOption.value) {
                "Terbaru" -> list.sortedByDescending { it.id }
                "Harga: Termurah" -> list.sortedBy { it.price }
                "Harga: Termahal" -> list.sortedByDescending { it.price }
                else -> list
            }
        }
}
