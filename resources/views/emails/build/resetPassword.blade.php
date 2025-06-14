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

  <title>Password Reset Request</title>
  <style>
    .last-border-0:last-child {
      border-width: 0px !important
    }
    .last-pb-0:last-child {
      padding-bottom: 0 !important
    }
    .hover-bg-amber-700:hover {
      background-color: #b45309 !important
    }
    .hover-bg-blue-700:hover {
      background-color: #1d4ed8 !important
    }
    .hover-bg-green-700:hover {
      background-color: #15803d !important
    }
    .hover-bg-neutral-50:hover {
      background-color: #fafafa !important
    }
    .hover-bg-red-700:hover {
      background-color: #b91c1c !important
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
      .dark-border-blue-800 {
        border-color: #1e40af !important
      }
      .dark-border-green-800 {
        border-color: #166534 !important
      }
      .dark-border-neutral-600 {
        border-color: #525252 !important
      }
      .dark-border-neutral-700 {
        border-color: #404040 !important
      }
      .dark-bg-amber-900-30 {
        background-color: rgb(120 53 15 / 0.3) !important
      }
      .dark-bg-blue-900-20 {
        background-color: rgb(30 58 138 / 0.2) !important
      }
      .dark-bg-blue-900-30 {
        background-color: rgb(30 58 138 / 0.3) !important
      }
      .dark-bg-green-900-20 {
        background-color: rgb(20 83 45 / 0.2) !important
      }
      .dark-bg-green-900-30 {
        background-color: rgb(20 83 45 / 0.3) !important
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
      .dark-bg-red-900-30 {
        background-color: rgb(127 29 29 / 0.3) !important
      }
      .dark-text-amber-300 {
        color: #fcd34d !important
      }
      .dark-text-amber-400 {
        color: #fbbf24 !important
      }
      .dark-text-blue-200 {
        color: #bfdbfe !important
      }
      .dark-text-blue-300 {
        color: #93c5fd !important
      }
      .dark-text-blue-400 {
        color: #60a5fa !important
      }
      .dark-text-green-200 {
        color: #bbf7d0 !important
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
      .dark-text-red-300 {
        color: #fca5a5 !important
      }
      .dark-text-red-400 {
        color: #f87171 !important
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
      <h1 style="font-size: 24px; font-weight: 700">Password Reset Request</h1>
      <p style="margin-top: 4px; opacity: 0.9">We received a request to reset your {{ $appName }} password</p>
    </div>

    <div style="padding: 24px">
      <!-- User Greeting -->
      <div style="margin-bottom: 24px; display: flex; align-items: center">
        <div class="from-blue-500 to-purple-500" style="margin-right: 16px; height: 64px; width: 64px; overflow: hidden; border-radius: 9999px; background-image: undefined; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1)">
          <img src="{{ asset(&#039;storage/users/&#039; . $userPhoto) }}" alt="User Photo" style="height: 100%; width: 100%; object-fit: cover">
        </div>
        <div>
          <h2 class="dark-text-neutral-100" style="font-size: 20px; font-weight: 700; color: #262626">Hello, {{ $userName }}</h2>
          <p class="dark-text-neutral-400" style="color: #525252">You requested to reset your password</p>
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
              If you didn't request this, please ignore this email or contact support immediately.
            </p>
          </div>
        </div>
      </div>

      <!-- Reset Details Card -->
      <div class="dark-bg-neutral-700-30" style="margin-bottom: 24px; border-radius: 8px; background-color: #fafafa; padding: 20px">
        <h3 class="dark-text-neutral-100" style="margin-bottom: 16px; font-size: 18px; font-weight: 600; color: #262626">
          <span class="material-symbols-outlined" style="margin-right: 8px; color: #3b82f6">vpn_key</span> Reset Request Information
        </h3>

        <div class="space-y-4">
          <!-- Request Time -->
          <div style="display: flex; align-items: flex-start">
            <div class="dark-text-neutral-400" style="margin-top: 4px; flex-shrink: 0; color: #737373">
              <span class="material-symbols-outlined">schedule</span>
            </div>
            <div style="margin-left: 12px">
              <p class="dark-text-neutral-400" style="font-size: 14px; font-weight: 500; color: #525252">Request Time</p>
              <p class="dark-text-neutral-200" style="color: #262626">{{ now()-&gt;format(&#039;F j, Y \a\t g:i A T&#039;) }}</p>
            </div>
          </div>

          <!-- Request Device -->
          <div style="display: flex; align-items: flex-start; margin-top: 16px; margin-bottom: 0">
            <div class="dark-text-neutral-400" style="margin-top: 4px; flex-shrink: 0; color: #737373">
              @if($deviceInfo['is_mobile'])
              <span class="material-symbols-outlined">smartphone</span>
              @else
              <span class="material-symbols-outlined">laptop</span>
              @endif
            </div>
            <div style="margin-left: 12px">
              <p class="dark-text-neutral-400" style="font-size: 14px; font-weight: 500; color: #525252">Request Device</p>
              <div class="dark-text-neutral-200" style="color: #262626">
                <p>{{ $deviceInfo[&#039;platform&#039;] }} • {{ $deviceInfo[&#039;browser&#039;] }}</p>
                <p class="dark-text-neutral-400" style="margin-top: 4px; font-size: 14px; color: #737373">
                  {{ $deviceInfo[&#039;device&#039;] }}
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
                {{ $loginLocation ?: &#039;Could not determine location&#039; }}
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

      <!-- Reset Button -->
      <div style="margin-bottom: 24px; text-align: center">
        <a href="{{ $url }}" class="hover-bg-blue-700" style="display: inline-block; border-radius: 8px; background-color: #2563eb; padding: 12px 24px; font-weight: 500; color: #fffffe; transition-property: color, background-color, border-color, text-decoration-color, fill, stroke; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1)">
          <span class="material-symbols-outlined" style="margin-right: 8px">refresh</span> Reset Password
        </a>
        <p class="dark-text-neutral-400" style="margin-top: 12px; font-size: 14px; color: #737373">
          This link will expire in {{ $expirationTime }} hour{{ $expirationTime &gt; 1 ? &#039;s&#039; : &#039;&#039; }}.
        </p>
      </div>

      <!-- Security Tips -->
      <div class="dark-bg-neutral-700-30 dark-border-neutral-700" style="border-radius: 8px; border-width: 1px; border-color: #e5e5e5; background-color: #fafafa; padding: 16px">
        <h4 class="dark-text-neutral-200" style="margin-bottom: 8px; font-weight: 500; color: #262626">
          <span class="material-symbols-outlined" style="margin-right: 8px; color: #eab308">lightbulb</span> Creating a Strong Password
        </h4>
        <ul class="dark-text-neutral-400 space-y-2" style="font-size: 14px; color: #525252">
          <li style="display: flex; align-items: flex-start">
            <span class="material-symbols-outlined" style="margin-right: 8px; margin-top: 4px; font-size: 12px; color: #22c55e">check_circle</span>
            <span>Use at least 12 characters with a mix of letters, numbers and symbols</span>
          </li>
          <li style="display: flex; align-items: flex-start; margin-top: 8px; margin-bottom: 0">
            <span class="material-symbols-outlined" style="margin-right: 8px; margin-top: 4px; font-size: 12px; color: #22c55e">check_circle</span>
            <span>Avoid personal information or common words</span>
          </li>
          <li style="display: flex; align-items: flex-start; margin-top: 8px; margin-bottom: 0">
            <span class="material-symbols-outlined" style="margin-right: 8px; margin-top: 4px; font-size: 12px; color: #22c55e">check_circle</span>
            <span>Consider using a password manager to generate and store passwords</span>
          </li>
        </ul>
      </div>

      <!-- Support Info -->
      <div class="dark-text-neutral-400" style="margin-top: 24px; text-align: center; font-size: 14px; color: #737373">
        <p>If you're having trouble with the button above, copy and paste this link into your browser:</p>
        <p class="dark-text-blue-400" style="margin-top: 8px; word-break: break-all; color: #2563eb">{{ $url }}</p>
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