<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Game Play</title>
    <style>
        @font-face {
            font-family: 'NotoSansDevanagari';
            src: url("{{ storage_path('fonts/NotoSansDevanagari-Regular.ttf') }}") format('truetype');
        }
        body {
            /* font-family: 'Segoe UI', Arial, sans-serif; */
            font-family: 'NotoSansDevanagari', sans-serif;
            margin: 0;
            padding: 40px;
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            animation: fadeIn 0.5s ease-in;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        .logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            position: absolute;
            top: -90px;
            left: 50%;
            transform: translateX(-50%);
            transition: transform 0.3s ease;
        }
        .logo:hover {
            transform: translateX(-50%) scale(1.1);
        }
        h2 {
            font-size: 2.2em;
            margin: 0;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        p.date {
            color: #7f8c8d;
            font-size: 1em;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
        }
        thead th {
            background: #3498db;
            color: #fff;
            padding: 12px;
            font-size: 1.1em;
            text-align: left;
            border-bottom: 2px solid #2980b9;
        }
        tbody tr {
            background: #f9f9f9;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        tbody tr:hover {
            background: #e8f4f8;
            transform: translateY(-2px);
            cursor: pointer;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            font-size: 1em;
            color: #34495e;
        }
        td:last-child {
            font-weight: bold;
            color: #e74c3c;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }
            h2 {
                font-size: 1.8em;
            }
            table, th, td {
                font-size: 0.9em;
            }
            .logo {
                width: 60px;
                height: 60px;
                top: -70px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            {{-- <img src="{{ asset('logo.png') }}" alt="Logo" class="logo"> --}}
            <h2>{{ $title }}</h2>
            <p class="date">Date: {{ $date }}</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Type</th>
                    <th>Market</th>
                    <th>Bid Number</th>
                    <th>Amount (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bids as $index => $bid)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ App\Models\GameType::findOrFail($bid['game_type_id'])->name }}</td>
                        <td>{{ $market }}</td>
                        <td>{{ $bid['number'] }}</td>
                        <td>Rs. {{ $bid['amount'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>