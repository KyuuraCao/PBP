<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Anggota - {{ $anggota->nama }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: #f5f5f5;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .kartu-container {
            width: 600px;
            background: linear-gradient(135deg, #777272 0%, #897c7c 100%);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            color: white;
            display: flex;
            flex-direction: column;
        }
        
        .kartu-header {
            text-align: center;
            border-bottom: 2px solid rgba(255,255,255,0.3);
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        
        .kartu-header h2 {
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .kartu-header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .kartu-body {
            display: flex;
            gap: 30px;
            align-items: center;
        }
        
        .foto-container {
            flex-shrink: 0;
        }
        
        .foto-container img {
            width: 150px;
            height: 150px;
            border-radius: 15px;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .foto-container .no-foto {
            width: 150px;
            height: 150px;
            border-radius: 15px;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            border: 4px solid white;
        }
        
        .info-section {
            flex: 1;
            background: rgba(255,255,255,0.15);
            border-radius: 15px;
            padding: 20px;
            backdrop-filter: blur(10px);
        }
        
        .info-row {
            margin-bottom: 15px;
            display: flex;
            align-items: baseline;
            gap: 10px;
        }
        
        .info-row:last-child {
            margin-bottom: 0;
        }
        
        .info-label {
            font-size: 12px;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            min-width: 140px;
            flex-shrink: 0;
        }
        
        .info-value {
            font-size: 17px;
            font-weight: bold;
            flex: 1;
        }
        
        .kartu-footer {
            margin-top: 20px;
            text-align: center;
            padding-top: 15px;
            border-top: 2px solid rgba(255,255,255,0.3);
        }
        
        .kartu-footer p {
            font-size: 10px;
            opacity: 0.8;
        }
        
        .badge-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            background: rgba(255,255,255,0.3);
            margin-top: 5px;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .kartu-container {
                box-shadow: none;
                page-break-inside: avoid;
            }
            
            .no-print {
                display: none;
            }
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s;
        }
        
        .print-button:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">🖨️ Cetak Kartu</button>
    
    <div class="kartu-container">
        <div class="kartu-header">
            <h2>Kartu Anggota</h2>
            <p>{{ $anggota->id_anggota }}</p>
        </div>
        
        <div class="kartu-body">
            <div class="foto-container">
                @if($anggota->foto)
                    <img src="{{ asset('uploads/foto/'.$anggota->foto) }}" alt="Foto {{ $anggota->nama }}">
                @else
                    <div class="no-foto">👤</div>
                @endif
            </div>
            
            <div class="info-section">
                <div class="info-row">
                    <span class="info-label">Nama Lengkap</span>
                    <span class="info-value">{{ $anggota->nama }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Jenis Kelamin</span>
                    <span class="info-value">{{ $anggota->jenis_kelamin }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Tanggal Daftar</span>
                    <span class="info-value">
                        {{ $anggota->tanggal_daftar ? \Carbon\Carbon::parse($anggota->tanggal_daftar)->format('d/m/Y') : '-' }}
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Berlaku Hingga</span>
                    <span class="info-value">
                        {{ $anggota->berlaku_hingga ? \Carbon\Carbon::parse($anggota->berlaku_hingga)->format('d/m/Y') : '-' }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="kartu-footer">
            <p>Kartu ini adalah bukti keanggotaan resmi</p>
            <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
        </div>
    </div>
    
    <script>
        // Auto print ketika halaman dibuka (opsional, bisa dihapus jika tidak diinginkan)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>