<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Magic Card QR Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 1cm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            width: 5.5cm;
            height: 7.5cm;
            padding: 0.25cm;
            vertical-align: top;
        }

        .card-wrapper {
            position: relative;
            width: 5cm;
            height: 7cm;
        }

        .card-bg {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .qr-code {
            position: absolute;
            bottom: 1.8cm;
            left: 1.5cm;
            width: 2cm;
            height: 2cm;
        }

        .page {
            page-break-after: always;
        }

        .qr-text {
            position: absolute;
            bottom: 4.5cm;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 40pt;
            font-weight: bold;
            color: #ff4081;
            font-family: 'Comic Sans MS', cursive, sans-serif;
            text-shadow: 1px 1px 2px #00000055;
        }
    </style>
</head>

<body>
    @php
        $backgrounds = ['background1.png', 'background2.png', 'background3.png', 'background4.png'];
        $cardIndex = 0;
    @endphp

    @foreach ($soals->chunk(9) as $soalChunk)
        <div class="page">
            <table>
                @foreach ($soalChunk->chunk(3) as $row)
                    <tr>
                        @foreach ($row as $soal)
                            @php
                                $bgIndex = $cardIndex % count($backgrounds);
                                $bgImage = $backgrounds[$bgIndex];
                                $cardIndex++;
                            @endphp
                            <td>
                                <div class="card-wrapper">
                                    <img src="{{ public_path('admin_assets/images/' . $bgImage) }}" alt="Background"
                                        class="card-bg">
                                    <div class="qr-text">{{ $soal->getQrText() }}</div> 
                                    <img src="{{ $soal->qrcode_base64 }}" alt="QR Code" class="qr-code">
                                </div>
                            </td>
                        @endforeach
                        @for ($i = $row->count(); $i < 3; $i++)
                            <td></td>
                        @endfor
                    </tr>
                @endforeach
            </table>
        </div>
    @endforeach

</body>

</html>
