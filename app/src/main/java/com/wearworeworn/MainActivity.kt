package com.wearworeworn

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.SystemBarStyle
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Surface
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.toArgb
import androidx.compose.ui.tooling.preview.Preview
import androidx.lifecycle.viewmodel.compose.viewModel
import androidx.navigation.NavType
import androidx.navigation.compose.NavHost
import androidx.navigation.compose.composable
import androidx.navigation.compose.rememberNavController
import androidx.navigation.navArgument
import com.wearworeworn.ui.screens.*
import com.wearworeworn.viewmodel.AuthViewModel
import com.wearworeworn.viewmodel.CartViewModel
import com.wearworeworn.viewmodel.OrderViewModel
import com.wearworeworn.viewmodel.ProductViewModel

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        enableEdgeToEdge(
            statusBarStyle = SystemBarStyle.dark(Color.Black.toArgb())
        )

        setContent {
            MaterialTheme {
                Surface {
                    AppNavigation()
                }
            }
        }
    }
}

@Composable
fun AppNavigation() {
    val navController    = rememberNavController()

    // ── ViewModels (hoisted at app level, shared across screens) ──────────────
    val productViewModel: ProductViewModel = viewModel()
    val authViewModel:    AuthViewModel    = viewModel()
    val cartViewModel:    CartViewModel    = viewModel()
    val orderViewModel:   OrderViewModel   = viewModel()

    // Load cart when user logs in
    LaunchedEffect(authViewModel.isLoggedIn.value) {
        if (authViewModel.isLoggedIn.value) {
            cartViewModel.loadCart()
        }
    }

    NavHost(
        navController  = navController,
        startDestination = "home"   // Selalu mulai dari Home (guest dibolehkan lihat katalog)
    ) {

        // ── Home ──────────────────────────────────────────────────────────────
        composable("home") {
            HomeScreen(
                viewModel           = productViewModel,
                authViewModel       = authViewModel,
                cartViewModel       = cartViewModel,
                onProductClick      = { id -> navController.navigate("productDetail/$id") },
                onNavigateToCart    = { navController.navigate("cart") },
                onNavigateToProfile = { navController.navigate("profile") },
                onNavigateToLogin   = { navController.navigate("login") }
            )
        }

        // ── Product Detail ────────────────────────────────────────────────────
        composable(
            route     = "productDetail/{productId}",
            arguments = listOf(navArgument("productId") { type = NavType.IntType })
        ) { backStackEntry ->
            val productId = backStackEntry.arguments?.getInt("productId") ?: 0
            ProductDetailScreen(
                productId           = productId,
                viewModel           = productViewModel,
                authViewModel       = authViewModel,
                cartViewModel       = cartViewModel,
                onBack              = { navController.popBackStack() },
                onNavigateToCart    = { navController.navigate("cart") },
                onNavigateToProfile = {
                    if (authViewModel.isLoggedIn.value) navController.navigate("profile")
                    else navController.navigate("login")
                },
                onNavigateToLogin   = { navController.navigate("login") }
            )
        }

        // ── Login ─────────────────────────────────────────────────────────────
        composable("login") {
            LoginScreen(
                viewModel            = authViewModel,
                onLoginSuccess       = {
                    // Setelah login / lanjut sebagai guest, kembali ke sebelumnya
                    navController.popBackStack()
                },
                onNavigateToRegister = { navController.navigate("register") }
            )
        }

        // ── Register ──────────────────────────────────────────────────────────
        composable("register") {
            RegisterScreen(
                viewModel          = authViewModel,
                onRegisterSuccess  = {
                    // Setelah daftar, kembali ke Home
                    navController.navigate("home") {
                        popUpTo("home") { inclusive = true }
                    }
                },
                onNavigateToLogin  = { navController.popBackStack() }
            )
        }

        // ── Cart ──────────────────────────────────────────────────────────────
        composable("cart") {
            CartScreen(
                viewModel  = cartViewModel,
                onBack     = { navController.popBackStack() },
                onCheckout = { navController.navigate("checkout") }
            )
        }

        // ── Checkout ──────────────────────────────────────────────────────────
        composable("checkout") {
            CheckoutScreen(
                cartViewModel  = cartViewModel,
                orderViewModel = orderViewModel,
                onBack         = { navController.popBackStack() },
                onOrderSuccess = {
                    // Setelah order berhasil, kembali ke Home dan bersihkan back stack
                    navController.navigate("home") {
                        popUpTo("home") { inclusive = true }
                    }
                }
            )
        }

        // ── Profile ───────────────────────────────────────────────────────────
        composable("profile") {
            ProfileScreen(
                viewModel = authViewModel,
                onBack    = { navController.popBackStack() },
                onLogout  = {
                    // Setelah logout, ke Home
                    navController.navigate("home") {
                        popUpTo("home") { inclusive = true }
                    }
                }
            )
        }
    }
}

@Preview(showBackground = true)
@Composable
fun MainPreview() {
    MaterialTheme { }
}
