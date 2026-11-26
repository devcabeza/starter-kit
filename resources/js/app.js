import { App } from '@capacitor/app';
import { SplashScreen } from '@capacitor/splash-screen';
import { Capacitor } from '@capacitor/core';

// Native Capacitor Ergonomics
if (Capacitor.isNativePlatform()) {
    // Handle Android hardware back button & gestures
    App.addListener('backButton', ({ canGoBack }) => {
        if (canGoBack && window.history.length > 1) {
            window.history.back();
        } else {
            App.exitApp();
        }
    });

    // Hide splash screen once page is ready
    const hideSplash = () => {
        SplashScreen.hide({
            fadeOutDuration: 250,
        }).catch(() => {});
    };

    if (document.readyState === 'complete') {
        hideSplash();
    } else {
        window.addEventListener('load', hideSplash, { once: true });
    }
}

// Global Online/Offline Event Dispatcher for Livewire
window.addEventListener('offline', () => {
    window.dispatchEvent(new CustomEvent('app:network-offline', { detail: { isOnline: false } }));
});

window.addEventListener('online', () => {
    window.dispatchEvent(new CustomEvent('app:network-online', { detail: { isOnline: true } }));
});
