import { config as dotenvConfig } from 'dotenv';
import { resolve } from 'path';

// Load .env file from project root
dotenvConfig({ path: resolve(__dirname, '.env') });

const capacitorConfig = {
  appId: process.env.CAPACITOR_APP_ID || 'com.laravertex.app',
  appName: process.env.CAPACITOR_APP_NAME || 'Laravertex',
  webDir: process.env.CAPACITOR_WEB_DIR || 'dist',
  server: {
    url: process.env.CAPACITOR_SERVER_URL || 'https://app.laravertex.com',
    cleartext: process.env.CAPACITOR_SERVER_CLEARTEXT === 'true',
    androidScheme: (process.env.CAPACITOR_ANDROID_SCHEME as 'https' | 'http') || 'https',
  },
  plugins: {
    SplashScreen: {
      launchAutoHide: false,
      androidScaleType: 'CENTER_CROP',
      splashFullScreen: true,
      splashImmersive: true,
    },
  },
};

export default capacitorConfig;
