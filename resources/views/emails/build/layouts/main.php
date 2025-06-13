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

    <yield></yield>

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