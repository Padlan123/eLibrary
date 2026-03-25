<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #1f2937;
        }

        .header {
            background-color: #10b981;
            color: white;
            padding: 24px;
            border-radius: 8px;
            margin-bottom: 24px;
        }

        .label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .value {
            font-size: 13px;
            font-weight: 600;
            margin-top: 2px;
        }

        .section {
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
        }

        .section-title {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #9ca3af;
            margin-bottom: 12px;
        }

        .grid {
            display: table;
            width: 100%;
        }

        .col {
            display: table-cell;
            width: 50%;
            padding-right: 12px;
        }

        .total-box {
            background-color: #ecfdf5;
            border: 1px solid #6ee7b7;
            border-radius: 8px;
            padding: 16px;
            margin-top: 20px;
        }

        .total-label {
            font-size: 11px;
            color: #059669;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .total-value {
            font-size: 22px;
            font-weight: 700;
            color: #065f46;
            margin-top: 4px;
        }

        .footer {
            margin-top: 32px;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 16px;
        }
    </style>
</head>

<body>
    <div class="header">
        <p style="font-size:10px; margin:0 0 4px; letter-spacing:2px;">INVOICE PEMBAYARAN</p>
        <h1 style="margin:0 0 12px; font-size:22px;">Detail Transaksi</h1>
        <span
            style="background:rgba(255,255,255,0.2);color:white; padding:6px 12px; border-radius:6px; font-family:monospace;">
            {{ $invoice->invoice_number }}
        </span>
    </div>

    <div class="section">
        <p class="section-title">Informasi Pengguna</p>
        <div class="grid">
            <div class="col">
                <p class="label">Nama Pengguna</p>
                <p class="value">{{ $invoice->member->username }}</p>
            </div>
            <div class="col">
                <p class="label">Tanggal Transaksi</p>
                <p class="value">{{ $invoice->paid_date->format('d M Y') }}</p>
            </div>
        </div>
        <div class="grid" style="margin-top:12px;">
            <div class="col">
                <p class="label">Transfer Atas Nama</p>
                <p class="value">{{ $invoice->name }}</p>
            </div>
            <div class="col">
                <p class="label">Nomor Pengirim</p>
                <p class="value">{{ $invoice->number }}</p>
            </div>
        </div>
    </div>

    <div class="section">
        <p class="section-title">Rincian Paket</p>
        <div class="grid">
            <div class="col">
                <p class="label">Nama Paket</p>
                <p class="value">{{ $invoice->package->name }}</p>
            </div>
            <div class="col">
                <p class="label">Harga Paket</p>
                <p class="value">Rp {{ number_format($invoice->package->price, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="footer">
        Dokumen ini digenerate otomatis pada {{ now()->format('d M Y, H:i') }} WIB
    </div>
</body>

</html>
