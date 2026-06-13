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

    val productViewModel: ProductViewModel = viewModel()
    val authViewModel:    AuthViewModel    = viewModel()
    val cartViewModel:    CartViewModel    = viewModel()
    val orderViewModel:   OrderViewModel   = viewModel()

    LaunchedEffect(authViewModel.isLoggedIn.value) {
        if (authViewModel.isLoggedIn.value) {
            cartViewModel.loadCart()
        }
    }

    NavHost(
        navController  = navController,
        startDestination = "home"
    ) {

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
                onNavigateToCheckout = { navController.navigate("checkout") },
                onNavigateToProfile = {
                    if (authViewModel.isLoggedIn.value) navController.navigate("profile")
                    else navController.navigate("login")
                },
                onNavigateToLogin   = { navController.navigate("login") }
            )
        }

        composable("login") {
            LoginScreen(
                viewModel            = authViewModel,
                onLoginSuccess       = {
                    navController.popBackStack()
                },
                onNavigateToRegister = { navController.navigate("register") }
            )
        }

        composable("register") {
            RegisterScreen(
                viewModel          = authViewModel,
                onRegisterSuccess  = {
                    navController.navigate("home") {
                        popUpTo("home") { inclusive = true }
                    }
                },
                onNavigateToLogin  = { navController.popBackStack() }
            )
        }

        composable("cart") {
            CartScreen(
                viewModel  = cartViewModel,
                onBack     = { navController.popBackStack() },
                onCheckout = { navController.navigate("checkout") }
            )
        }

        composable("checkout") {
            CheckoutScreen(
                cartViewModel  = cartViewModel,
                orderViewModel = orderViewModel,
                onBack         = { navController.popBackStack() },
                onOrderSuccess = { order ->
                    navController.navigate("checkoutSuccess/${order.id}/${order.totalPrice}") {
                        popUpTo("cart") { inclusive = true }
                    }
                }
            )
        }

        composable(
            route = "checkoutSuccess/{orderId}/{totalPrice}",
            arguments = listOf(
                navArgument("orderId") { type = NavType.IntType },
                navArgument("totalPrice") { type = NavType.StringType }
            )
        ) { backStackEntry ->
            val orderId = backStackEntry.arguments?.getInt("orderId") ?: 0
            val totalPrice = backStackEntry.arguments?.getString("totalPrice")?.toDoubleOrNull() ?: 0.0
            CheckoutSuccessScreen(
                orderId = orderId,
                totalPrice = totalPrice,
                onBackToHome = {
                    navController.navigate("home") {
                        popUpTo("home") { inclusive = true }
                    }
                }
            )
        }

        composable("profile") {
            ProfileScreen(
                viewModel = authViewModel,
                onBack    = { navController.popBackStack() },
                onLogout  = {
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