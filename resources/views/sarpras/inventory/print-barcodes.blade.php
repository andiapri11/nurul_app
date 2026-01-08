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
        <div class="label">
            <div class="label-title">{{ $item->room->unit->name ?? (\App\Models\Setting::where('key', 'app_name')->value('value') ?? 'NURUL ILMI') }}</div>
            <div class="label-name">{{ $item->name }} / {{ $item->category->name ?? '-' }}</div>
            <div style="font-size: 9px; margin-bottom: 3px;">Tgl Beli: {{ $item->purchase_date ? $item->purchase_date->format('d/m/Y') : '-' }}</div>
            <canvas id="barcode-{{ $item->id }}" data-code="{{ $item->code }}"></canvas>
            <div class="label-code">{{ $item->code }}</div>
        </div>
        @endforeach
    </div>

    <!-- Includes JsBarcode -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        document.querySelectorAll('canvas').forEach(function(el) {
            JsBarcode(el, el.getAttribute('data-code'), {
                format: "CODE128",
                width: 2,
                height: 55, // Increased height for easier scanning
                displayValue: false,
                margin: 10 // Added margin (quiet zone) for better scanner focus
            });
        });
    </script>
</body>
</html>
