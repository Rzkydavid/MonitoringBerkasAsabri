<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Tanda Terima</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-box {
            border: 1px solid #000;
            border-radius: 12px;
            padding: 8px 12px;
            font-size: 10px;
            line-height: 1.4;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 18px 0;
        }

        .label-table td {
            padding: 3px 0;
        }

        .pengajuan-box {
            border: 1px solid #000;
            height: 60px;
            margin-top: 5px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 16px;
        }

        .checklist td {
            vertical-align: top;
            line-height: 1.6;
        }

        .signature {
            margin-top: 45px;
            text-align: center;
        }
    </style>
</head>

<body>

    @php
        function cb($checked)
        {
            $symbol = $checked ? '☑' : '☐';
            return '<span style="font-size:14px; font-weight:bold;">' . $symbol . '</span>';
        }
    @endphp

    <!-- HEADER -->
    <table>
        <tr>
            <td style="width:50%;">
                <img src="{{ public_path('assets/img/asabri_transparent.png') }}" height="60">
            </td>
            <td style="width:50%; text-align:right;">
                <div class="header-box">
                    <strong>PT ASABRI (PERSERO)</strong><br>
                    Jl. Mayjen Sutoyo, No. 11, Jakarta 13630<br>
                    Telepon : 0812 82371518-19<br>
                    Call Center 021-1500043
                </div>
            </td>
        </tr>
    </table>

    <div class="title">TANDA TERIMA</div>

    <!-- BIODATA -->
    <table class="label-table">
        <tr>
            <td style="width:30%;">NAMA DAN PANGKAT</td>
            <td style="width:2%;">:</td>
            <td>{{ $data->nama_peserta }}</td>
        </tr>
        <tr>
            <td>NRP / NIP</td>
            <td>:</td>
            <td>{{ $data->nomor_identitas }}</td>
        </tr>
        <tr>
            <td>TANGGAL SURAT</td>
            <td>:</td>
            <td>{{ $data->tgl_berkas_diterima_cso
                ? \Carbon\Carbon::parse($data->tgl_berkas_diterima_cso)->format('d / m / Y')
                : '' }}
            </td>
        </tr>
    </table>

    <!-- PENGAJUAN -->
    <div class="section-title">PENGAJUAN:</div>

    <table style="width:100%; margin-top:5px;">
        <tr>
            <td style="width:50%;">
                <div
                    style="
                    border:1px solid #000;
                    height:60px;
                    padding:6px;
                    border-radius:6px;
                    vertical-align:top;
                    text-align:left;
                ">
                    {{ optional($data->jenisKlaim)->name }}
                </div>
            </td>
            <td style="width:50%;"></td>
        </tr>
    </table>

    <!-- PERSYARATAN -->
    <div class="section-title">PERSYARATAN:</div>

    <table class="checklist" style="margin-top:6px;">
        <tr>
            <td style="width:50%;">
                1. {!! cb(false) !!} Formulir Pengajuan<br>
                2. {!! cb(false) !!} FC / Asli SKEP Pensiun / Salinan<br>
                3. {!! cb(false) !!} FC / Asli Pengangkatan Pertama / Kep. PDH<br>
                4. {!! cb(false) !!} FC / Asli SKPP<br>
                5. {!! cb(false) !!} FC KTPA / Kartu ASABRI<br>
                6. {!! cb(false) !!} FC KTP yang bersangkutan / Ahli Waris<br>
                7. {!! cb(false) !!} Surat Ket. Ahli / Kuasa Waris (ASLI)<br>
                8. {!! cb(false) !!} FC Akte Kematian (Dukcapil)<br>
                9. {!! cb(false) !!} FC NPWP
            </td>

            <td style="width:50%;">
                10. {!! cb(false) !!} FC Buku Pensiun (Struk Gaji)<br>
                11. {!! cb(false) !!} Daftar Keluarga (KU-1) / FC KK<br>
                12. {!! cb(false) !!} Pas Foto Suami / Istri (4x6) 4 Lembar<br>
                13. {!! cb(false) !!} FC Buku Tabungan<br>
                14. {!! cb(false) !!} Surat Perwalian<br>
                15. {!! cb(false) !!} Surat Keterangan Kuliah Asli<br>
                16. {!! cb(false) !!} Surat Keterangan Janda / Duda<br>
                17. {!! cb(false) !!} FC Buku Nikah / Karis / SPPI<br>
                18. {!! cb(false) !!} Daftar Riwayat Hidup Singkat<br>
                19. {!! cb(false) !!} DPP Komputer (KU-107) Legalisir
            </td>
        </tr>
    </table>

    <!-- SIGNATURE -->
    <div style="margin-top:45px; text-align:right;">
        Petugas Penerima,
        <br><br><br><br>

        <span
            style="
            display:inline-block;
            border-bottom:1px solid #000;
            padding-bottom:4px;
            font-weight:bold;
        ">
            {{ optional($data->creator)->name }}
        </span>
    </div>


</body>

</html>
