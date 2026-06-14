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
import androidx.compose.runtime.remember
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.toArgb
import androidx.compose.ui.tooling.preview.Preview
import androidx.lifecycle.viewmodel.compose.viewModel
import androidx.navigation.NavType
import androidx.navigation.compose.NavHost
import androidx.navigation.compose.composable
import androidx.navigation.compose.rememberNavController
import androidx.navigation.navArgument
import com.wearworeworn.model.Order
import com.wearworeworn.ui.screens.*
import com.wearworeworn.viewmodel.AuthViewModel
import com.wearworeworn.viewmodel.CartViewModel
import com.wearworeworn.viewmodel.OrderViewModel
import com.wearworeworn.viewmodel.ProductViewModel

import com.wearworeworn.ui.theme.WearWoreWornTheme

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        enableEdgeToEdge(
            statusBarStyle = SystemBarStyle.dark(Color.Black.toArgb())
        )

        setContent {
            WearWoreWornTheme {
                Surface {
                    AppNavigation()
                }
            }
        }
    }
}

@Composable
fun AppNavigation() {
    val navController  = rememberNavController()

    val productViewModel: ProductViewModel = viewModel()
    val authViewModel:    AuthViewModel    = viewModel()
    val cartViewModel:    CartViewModel    = viewModel()
    val orderViewModel:   OrderViewModel   = viewModel()

    // Shared state for passing Order to payment screen
    val selectedOrder = remember { androidx.compose.runtime.mutableStateOf<Order?>(null) }

    LaunchedEffect(authViewModel.isLoggedIn.value) {
        if (authViewModel.isLoggedIn.value) {
            cartViewModel.loadCart()
        }
    }

    NavHost(
        navController    = navController,
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
                productId            = productId,
                viewModel            = productViewModel,
                authViewModel        = authViewModel,
                cartViewModel        = cartViewModel,
                onBack               = { navController.popBackStack() },
                onNavigateToCart     = { navController.navigate("cart") },
                onNavigateToCheckout = { navController.navigate("checkout") },
                onNavigateToProfile  = {
                    if (authViewModel.isLoggedIn.value) navController.navigate("profile")
                    else navController.navigate("login")
                },
                onNavigateToLogin    = { navController.navigate("login") }
            )
        }

        composable("login") {
            LoginScreen(
                viewModel            = authViewModel,
                onLoginSuccess       = { navController.popBackStack() },
                onNavigateToRegister = { navController.navigate("register") }
            )
        }

        composable("register") {
            RegisterScreen(
                viewModel         = authViewModel,
                onRegisterSuccess = {
                    navController.navigate("home") {
                        popUpTo("home") { inclusive = true }
                    }
                },
                onNavigateToLogin = { navController.popBackStack() }
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
                    val encodedCreatedAt = java.net.URLEncoder.encode(order.createdAt ?: "", "UTF-8")
                    navController.navigate("checkoutSuccess/${order.id}/${order.totalPrice}?createdAt=$encodedCreatedAt") {
                        popUpTo("cart") { inclusive = true }
                    }
                }
            )
        }

        composable(
            route     = "checkoutSuccess/{orderId}/{totalPrice}?isFromMyOrders={isFromMyOrders}&createdAt={createdAt}",
            arguments = listOf(
                navArgument("orderId")    { type = NavType.IntType },
                navArgument("totalPrice") { type = NavType.StringType },
                navArgument("isFromMyOrders") { type = NavType.BoolType; defaultValue = false },
                navArgument("createdAt") { type = NavType.StringType; defaultValue = "" }
            )
        ) { backStackEntry ->
            val orderId    = backStackEntry.arguments?.getInt("orderId") ?: 0
            val totalPrice = backStackEntry.arguments?.getString("totalPrice")?.toDoubleOrNull() ?: 0.0
            val isFromMyOrders = backStackEntry.arguments?.getBoolean("isFromMyOrders") ?: false
            val createdAt = try {
                java.net.URLDecoder.decode(backStackEntry.arguments?.getString("createdAt") ?: "", "UTF-8")
            } catch (_: Exception) { "" }
            CheckoutSuccessScreen(
                orderId        = orderId,
                totalPrice     = totalPrice,
                createdAt      = createdAt.ifBlank { null },
                isFromMyOrders = isFromMyOrders,
                onBack         = {
                    if (isFromMyOrders) {
                        navController.popBackStack()
                    } else {
                        navController.navigate("home") {
                            popUpTo("home") { inclusive = true }
                        }
                    }
                }
            )
        }

        composable("profile") {
            ProfileScreen(
                viewModel          = authViewModel,
                onBack             = { navController.popBackStack() },
                onLogout           = {
                    navController.navigate("home") {
                        popUpTo("home") { inclusive = true }
                    }
                },
                onNavigateToOrders = { navController.navigate("myOrders") }
            )
        }

        composable("myOrders") {
            MyOrdersScreen(
                orderViewModel   = orderViewModel,
                authViewModel    = authViewModel,
                onBack           = { navController.popBackStack() },
                onNavigateToHome = {
                    navController.navigate("home") {
                        popUpTo("home") { inclusive = true }
                    }
                },
                onPendingPayment = { order ->
                    val encodedCreatedAt = java.net.URLEncoder.encode(order.createdAt ?: "", "UTF-8")
                    navController.navigate("checkoutSuccess/${order.id}/${order.totalPrice}?isFromMyOrders=true&createdAt=$encodedCreatedAt")
                },
                onPaymentDetail = { order ->
                    selectedOrder.value = order
                    navController.navigate("orderPayment")
                }
            )
        }

        composable("orderPayment") {
            val order = selectedOrder.value
            if (order != null) {
                OrderPaymentScreen(
                    order          = order,
                    orderViewModel = orderViewModel,
                    onBack         = { navController.popBackStack() }
                )
            }
        }
    }
}

@Preview(showBackground = true)
@Composable
fun MainPreview() {
    MaterialTheme { }
}