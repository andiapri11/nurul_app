<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Barcode Inventaris</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: flex-start;
        }
        .label {
            width: 50mm;
            border: 1px solid #ccc;
            padding: 5px;
            text-align: center;
            box-sizing: border-box;
            background: #fff;
        }
        .label-title {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 2px;
            text-transform: uppercase;
            border-bottom: 1px solid #eee;
            padding-bottom: 2px;
        }
        .label-name {
            font-size: 11px;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        canvas {
            max-width: 100%;
            height: auto;
        }
        .label-code {
            font-size: 10px;
            margin-top: 2px;
            font-family: 'Courier New', Courier, monospace;
        }
        @media print {
            .no-print {
                display: none;
            }
            .container {
                gap: 5mm;
            }
        }
        .no-print {
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }
        button {
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">
            <i class="bi bi-printer"></i> Cetak Label Barcode
        </button>
        <p style="font-size: 12px; color: #666; margin-top: 5px;">
            Gunakan ukuran label 50mm x 25mm atau kertas A4 untuk hasil terbaik.
        </p>
    </div>

    <div class="container">
        @foreach($items as $item)
            <!-- Label Title/Unit -->
            <div class="label-title">{{ $item->room->unit->name ?? 'NURUL ILMI' }}</div>
            
            <!-- Item Name & Category -->
            <div class="label-name" style="font-weight: bold;">{{ $item->name }}</div>
            <div style="font-size: 9px; margin-bottom: 5px; color: #666;">{{ $item->category->name ?? '-' }}</div>
            
            <!-- QR Code Container -->
            <div id="qrcode-{{ $item->id }}" style="display: flex; justify-content: center; margin: 5px 0;"></div>
            
            <!-- Item Code Footer -->
            <div class="label-code" style="border-top: 1px dashed #ddd; padding-top: 3px; margin-top: 5px;">{{ $item->code }}</div>
            <div style="font-size: 8px; color: #888;">Tgl Beli: {{ $item->purchase_date ? $item->purchase_date->format('d/m/Y') : '-' }}</div>
        </div>
        @endforeach
    </div>

    <!-- Includes QRCode.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        document.querySelectorAll('[id^="qrcode-"]').forEach(function(el) {
            const containerId = el.id;
            const code = document.querySelector('#' + containerId).closest('.label').querySelector('.label-code').innerText.trim();
            
            new QRCode(el, {
                text: code,
                width: 75,
                height: 75,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        });
    </script>
</body>
</html>
