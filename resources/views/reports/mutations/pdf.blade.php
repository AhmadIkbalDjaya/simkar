<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Laporan Mutasi</title>
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }
      body {
        font-family: sans-serif;
        font-size: 11px;
        color: #1f2937;
        padding: 20px;
      }
      .header {
        text-align: center;
        margin-bottom: 24px;
        border-bottom: 2px solid #1f2937;
        padding-bottom: 12px;
      }
      .header h1 {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 4px;
      }
      .header p {
        font-size: 11px;
        color: #6b7280;
      }
      .meta {
        margin-bottom: 16px;
        font-size: 10px;
        color: #6b7280;
      }
      .meta span {
        display: inline-block;
        margin-right: 16px;
      }
      table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 16px;
      }
      th {
        background-color: #f3f4f6;
        border: 1px solid #d1d5db;
        padding: 6px 8px;
        text-align: left;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        color: #6b7280;
      }
      td {
        border: 1px solid #d1d5db;
        padding: 6px 8px;
        font-size: 10px;
      }
      tr:nth-child(even) {
        background-color: #f9fafb;
      }
      .footer {
        font-size: 10px;
        color: #6b7280;
        text-align: right;
        margin-top: 8px;
      }
    </style>
  </head>
  <body>
    <div class="header">
      <h1>SIMKAR - Laporan Mutasi Kamar</h1>
      <p>Sistem Informasi Mutasi Kamar</p>
    </div>

    <div class="meta">
      <span>
        <strong>Filter:</strong>
        {{ $filterSummary }}
      </span>
      <span>
        <strong>Dicetak:</strong>
        {{ $generatedAt }}
      </span>
      <span>
        <strong>Total:</strong>
        {{ $data->count() }} data
      </span>
    </div>

    <table>
      <thead>
        <tr>
          <th style="width: 30px">No</th>
          <th>Nama WBP</th>
          <th>Kamar Asal</th>
          <th>Kamar Tujuan</th>
          <th>Waktu Mutasi</th>
          <th>Petugas</th>
          <th>Catatan</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($data as $i => $item)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->inmate->name }}</td>
            <td>{{ $item->roomFrom->name }}</td>
            <td>{{ $item->roomTo->name }}</td>
            <td>{{ $item->transferred_at->format("d/m/Y H:i") }}</td>
            <td>{{ $item->officer_name }}</td>
            <td>{{ $item->notes ?? "-" }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="footer">Dicetak pada {{ $generatedAt }}</div>
  </body>
</html>
