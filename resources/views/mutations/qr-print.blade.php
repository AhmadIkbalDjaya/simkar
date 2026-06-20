<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title }} - SIMKAR</title>
    <style>
      * {
        box-sizing: border-box;
      }
      body {
        margin: 0;
        color: #111827;
        font-family: Arial, sans-serif;
      }
      .page {
        width: 100%;
        max-width: 720px;
        margin: 0 auto;
        padding: 32px;
        text-align: center;
      }
      h1 {
        margin: 0;
        font-size: 28px;
      }
      .subtitle {
        margin: 8px 0 24px;
        color: #4b5563;
      }
      .qr {
        display: block;
        width: min(100%, 480px);
        height: auto;
        margin: 0 auto;
      }
      .url {
        margin: 24px auto 0;
        max-width: 560px;
        overflow-wrap: anywhere;
        color: #4b5563;
        font-size: 14px;
      }
      .hint {
        margin-top: 12px;
        font-size: 14px;
      }
      .actions {
        margin-top: 24px;
      }
      button {
        border: 0;
        border-radius: 8px;
        background: #111827;
        padding: 10px 18px;
        color: white;
        cursor: pointer;
        font-weight: 600;
      }
      @media print {
        @page {
          margin: 12mm;
        }
        .page {
          padding: 0;
        }
        .actions {
          display: none;
        }
      }
    </style>
  </head>
  <body>
    <main class="page">
      <h1>{{ $title }}</h1>
      <p class="subtitle">Sistem Informasi Mutasi Kamar</p>
      <img
        class="qr"
        src="{{ $imageUrl }}"
        alt="{{ $title }}"
        width="512"
        height="512"
      />
      <p class="hint">
        Pindai QR Code untuk membuka form
        mutasi{{ $room ? " dengan tujuan {$room->name}" : "" }}.
      </p>
      <p class="url">{{ $targetUrl }}</p>
      <div class="actions">
        <button type="button" onclick="window.print()">Print QR Code</button>
      </div>
    </main>
  </body>
</html>
