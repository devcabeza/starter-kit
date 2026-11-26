<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mobile App Distribution
    |--------------------------------------------------------------------------
    |
    | Public download URL for the Android APK. The mobile workflow publishes
    | the binary to Cloudflare R2 so the link stays reachable even when the
    | repository is private. Set APK_DOWNLOAD_URL to your bucket URL.
    |
    */

    'apk_url' => env('APK_DOWNLOAD_URL', 'https://github.com/devcabeza/starter-kit/releases/latest/download/app-debug.apk'),

];
