package com.wearworeworn.ui.theme

import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.darkColorScheme
import androidx.compose.runtime.Composable

private val StreetwearDarkColorScheme = darkColorScheme(
    primary = PureWhite,
    onPrimary = PureBlack,
    background = RichBlack,
    onBackground = PureWhite,
    surface = DarkGrey,
    onSurface = PureWhite,
    surfaceVariant = MediumGrey,
    onSurfaceVariant = LightGrey,
    outline = OutlineGrey
)

@Composable
fun WearWoreWornTheme(
    content: @Composable () -> Unit
) {
    MaterialTheme(
        colorScheme = StreetwearDarkColorScheme,
        typography = Typography,
        content = content
    )
}
