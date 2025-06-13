<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="x-apple-disable-message-reformatting">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="light dark">
  <meta name="supported-color-schemes" content="light dark">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings xmlns:o="urn:schemas-microsoft-com:office:office">
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <style>
        td, th, div, p, a, h1, h2, h3, h4, h5, h6 {
            font-family: "Segoe UI", sans-serif;
            mso-line-height-rule: exactly;
        }

        .mso-break-all {
            word-break: break-all;
        }
    </style>
    <![endif]-->

  <title>New Login Detected</title>
  <style>
    .hover-bg-blue-700:hover {
      background-color: #1d4ed8 !important
    }
    .hover-bg-neutral-50:hover {
      background-color: #fafafa !important
    }
    .hover-text-blue-800:hover {
      color: #1e40af !important
    }
    .hover-underline:hover {
      text-decoration: underline !important
    }
    @media (max-width: 600px) {
      .sm-grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important
      }
    }
    @media (prefers-color-scheme: dark) {
      .dark-border-neutral-600 {
        border-color: #525252 !important
      }
      .dark-border-neutral-700 {
        border-color: #404040 !important
      }
      .dark-bg-blue-900-30 {
        background-color: rgb(30 58 138 / 0.3) !important
      }
      .dark-bg-green-900-20 {
        background-color: rgb(20 83 45 / 0.2) !important
      }
      .dark-bg-neutral-700 {
        background-color: #404040 !important
      }
      .dark-bg-neutral-700-30 {
        background-color: rgb(64 64 64 / 0.3) !important
      }
      .dark-bg-neutral-800 {
        background-color: #262626 !important
      }
      .dark-bg-neutral-900 {
        background-color: #171717 !important
      }
      .dark-text-blue-300 {
        color: #93c5fd !important
      }
      .dark-text-blue-400 {
        color: #60a5fa !important
      }
      .dark-text-green-300 {
        color: #86efac !important
      }
      .dark-text-green-400 {
        color: #4ade80 !important
      }
      .dark-text-neutral-100 {
        color: #f5f5f5 !important
      }
      .dark-text-neutral-200 {
        color: #e5e5e5 !important
      }
      .dark-text-neutral-400 {
        color: #a3a3a3 !important
      }
      .dark-hover-bg-neutral-700:hover {
        background-color: #404040 !important
      }
      .dark-hover-text-blue-300:hover {
        color: #93c5fd !important
      }
    }
  </style>



  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">

</head>
<body class="dark-bg-neutral-900" style="background-color: #f5f5f5; font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif">
  <div class="dark-bg-neutral-800" style="margin: 32px auto; max-width: 576px; overflow: hidden; border-radius: 12px; background-color: #fffffe; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05)">


    <!-- Header with Logo -->
    <div class="from-blue-600 to-purple-600" style="position: relative; background-image: undefined; padding: 24px; text-align: center; color: #fffffe">
      <div style="margin-bottom: 16px; display: flex; justify-content: center">
        <img src="{{ $logoUrl }}" alt="{{ $appName }} Logo" style="height: 40px" height="40">
      </div>
      <h1 style="font-size: 24px; font-weight: 700">New Login Detected</h1>
      <p style="margin-top: 4px; opacity: 0.9">We noticed a recent login to your {{ $appName }} account</p>
    </div>

    <div style="padding: 24px">
      <!-- User Greeting -->
      <div style="margin-bottom: 24px; display: flex; align-items: center">
        <div class="from-blue-500 to-purple-500" style="margin-right: 16px; height: 64px; width: 64px; overflow: hidden; border-radius: 9999px; background-image: undefined; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1)">
          <img src="{{ asset('storage/users/' . $userPhoto) }}" alt="User Photo" style="height: 100%; width: 100%; object-fit: cover">
        </div>
        <div>
          <h2 class="dark-text-neutral-100" style="font-size: 20px; font-weight: 700; color: #262626">Hello, {{ $userName }}</h2>
          <p class="dark-text-neutral-400" style="color: #525252">A new login was detected on your account</p>
        </div>
      </div>

      <!-- Security Alert -->
      <div class="dark-bg-blue-900-30" style="margin-bottom: 24px; border-top-right-radius: 8px; border-bottom-right-radius: 8px; border-left-width: 4px; border-color: #3b82f6; background-color: #eff6ff; padding: 16px">
        <div style="display: flex">
          <div class="dark-text-blue-400" style="flex-shrink: 0; color: #3b82f6">
            <span class="material-symbols-outlined">security</span>
          </div>
          <div style="margin-left: 12px">
            <p class="dark-text-blue-300" style="font-size: 14px; color: #1d4ed8">
              If you don't recognize this activity, please secure your account immediately.
            </p>
          </div>
        </div>
      </div>

      <!-- Login Details Card -->
      <div class="dark-bg-neutral-700-30" style="margin-bottom: 24px; border-radius: 8px; background-color: #fafafa; padding: 20px">
        <h3 class="dark-text-neutral-100" style="margin-bottom: 16px; font-size: 18px; font-weight: 600; color: #262626">
          <span class="material-symbols-outlined" style="margin-right: 8px; color: #3b82f6">info</span> Login Details
        </h3>

        <div class="space-y-4">
          <!-- Date & Time -->
          <div style="display: flex; align-items: flex-start">
            <div class="dark-text-neutral-400" style="margin-top: 4px; flex-shrink: 0; color: #737373">
              <span class="material-symbols-outlined">calendar_today</span>
            </div>
            <div style="margin-left: 12px">
              <p class="dark-text-neutral-400" style="font-size: 14px; font-weight: 500; color: #525252">Date & Time</p>
              <p class="dark-text-neutral-200" style="color: #262626">{{ $loginTime }}</p>
            </div>
          </div>

          <!-- Device Information -->
          <div style="display: flex; align-items: flex-start; margin-top: 16px; margin-bottom: 0">
            <div class="dark-text-neutral-400" style="margin-top: 4px; flex-shrink: 0; color: #737373">
              @if($deviceInfo['is_mobile'])
              <span class="material-symbols-outlined">smartphone</span>
              @else
              <span class="material-symbols-outlined">laptop</span>
              @endif
            </div>
            <div style="margin-left: 12px">
              <p class="dark-text-neutral-400" style="font-size: 14px; font-weight: 500; color: #525252">Device</p>
              <div class="dark-text-neutral-200" style="color: #262626">
                <p>{{ $deviceInfo['platform'] }} • {{ $deviceInfo['browser'] }}</p>
                <p class="dark-text-neutral-400" style="margin-top: 4px; font-size: 14px; color: #737373">
                  {{ $deviceInfo['device'] }}
                </p>
              </div>
            </div>
          </div>

          <!-- Location -->
          <div style="display: flex; align-items: flex-start; margin-top: 16px; margin-bottom: 0">
            <div class="dark-text-neutral-400" style="margin-top: 4px; flex-shrink: 0; color: #737373">
              <span class="material-symbols-outlined">location_on</span>
            </div>
            <div style="margin-left: 12px">
              <p class="dark-text-neutral-400" style="font-size: 14px; font-weight: 500; color: #525252">Approximate Location</p>
              <p class="dark-text-neutral-200" style="color: #262626">
                {{ $loginLocation ?: 'Could not determine location' }}
              </p>
              @if($loginLocation)
              <p class="dark-text-neutral-400" style="margin-top: 4px; font-size: 12px; color: #737373">
                (Based on IP: {{ $ipAddress }})
              </p>
              @endif
            </div>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="sm-grid-cols-2" style="margin-bottom: 24px; display: grid; grid-template-columns: repeat(1, minmax(0, 1fr)); gap: 16px">
        <a href="#" class="hover-bg-blue-700" style="border-radius: 8px; background-color: #2563eb; padding: 12px 16px; text-align: center; font-weight: 500; color: #fffffe; transition-property: color, background-color, border-color, text-decoration-color, fill, stroke; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms">
          <span class="material-symbols-outlined" style="margin-right: 8px">lock</span> Change Password
        </a>
        <a href="#" class="dark-border-neutral-600 hover-bg-neutral-50 dark-hover-bg-neutral-700 dark-text-neutral-200" style="border-radius: 8px; border-width: 1px; border-color: #d4d4d4; padding: 12px 16px; text-align: center; font-weight: 500; color: #404040; transition-property: color, background-color, border-color, text-decoration-color, fill, stroke; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms">
          <span class="material-symbols-outlined" style="margin-right: 8px">history</span> View All Activity
        </a>
      </div>

      <!-- Additional Security Tips -->
      <div class="dark-bg-neutral-700-30 dark-border-neutral-700" style="border-radius: 8px; border-width: 1px; border-color: #e5e5e5; background-color: #fafafa; padding: 16px">
        <h4 class="dark-text-neutral-200" style="margin-bottom: 8px; font-weight: 500; color: #262626">
          <span class="material-symbols-outlined" style="margin-right: 8px; color: #eab308">lightbulb</span> Security Tips
        </h4>
        <ul class="dark-text-neutral-400 space-y-2" style="font-size: 14px; color: #525252">
          <li style="display: flex; align-items: flex-start">
            <span class="material-symbols-outlined" style="margin-right: 8px; margin-top: 4px; font-size: 12px; color: #22c55e">check_circle</span>
            <span>Use a unique password for your {{ $appName }} account</span>
          </li>
          <li style="display: flex; align-items: flex-start; margin-top: 8px; margin-bottom: 0">
            <span class="material-symbols-outlined" style="margin-right: 8px; margin-top: 4px; font-size: 12px; color: #22c55e">check_circle</span>
            <span>Review your account's authorized devices regularly</span>
          </li>
        </ul>
      </div>
    </div>


    <footer class="dark-text-neutral-400" style="margin-bottom: 24px; text-align: center; font-size: 14px; color: #737373">
      <p style="margin-bottom: 8px">This is an automated message. Please do not reply.</p>
      <p>© {{ now()->year }} {{ $appName }}. All rights reserved.</p>
      <div style="margin-top: 16px">
        <a href="#" class="dark-text-blue-400 hover-text-blue-800 dark-hover-text-blue-300" style="margin-right: 16px; color: #2563eb">
          Help Center
        </a>
        <a href="#" class="dark-text-blue-400 hover-text-blue-800 dark-hover-text-blue-300" style="margin-right: 16px; color: #2563eb">
          Privacy Policy
        </a>
      </div>
    </footer>
  </div>
</body>
</html>