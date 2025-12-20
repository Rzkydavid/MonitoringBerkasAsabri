<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Lembar Kontrol Klaim</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        .header {
            background: #0b2a6f;
            color: #fff;
            font-weight: bold;
            text-align: center;
        }

        .center {
            text-align: center;
        }

        .tall {
            height: 120px;
        }
    </style>
</head>

<body>

    @php
        function check($value, $expected)
        {
            $symbol = $value === $expected ? '☑' : '☐';
            return '<span style="font-size:14px; font-weight:bold;">' . $symbol . '</span>';
        }
    @endphp


    <!-- HEADER -->
    <table style="width:100%; border:none; margin-bottom:10px;">
        <tr>
            <td style="border:none;">
                <img src="{{ public_path('assets/img/asabri_transparent.png') }}" style="height:65px;">
            </td>
        </tr>
        <tr>
            <td style="border:none; text-align:center; padding-top:5px;">
                <div style="font-size:16px; font-weight:bold;">
                    LEMBAR KONTROL KLAIM KCU JAKARTA
                </div>
            </td>
        </tr>
    </table>


    <table>
        <tr>
            <th class="header">KETERANGAN</th>
            <th class="header">PILIHAN</th>
        </tr>

        <tr>
            <td>Tanggal Berkas Diterima CSO</td>
            <td>
                {{ $data->tgl_berkas_diterima_cso
                    ? \Carbon\Carbon::parse($data->tgl_berkas_diterima_cso)->format('d / m / Y')
                    : '' }}
            </td>
        </tr>

        <tr>
            <td>Status Terima Klaim Masuk</td>
            <td>
                @if ($data->status_terima_klaim_masuk === 'Lainnya' && !empty($data->status_terima_klaim_masuk_lainnya))
                    Lainnya: {{ $data->status_terima_klaim_masuk_lainnya }}
                @else
                    {{ $data->status_terima_klaim_masuk }}
                @endif

            </td>
        </tr>

        <tr>
            <td>Kelengkapan Persyaratan Klaim</td>
            <td>
                <table style=" width:100%; border-collapse:collapse; margin:0;">
                    <tr>
                        <td style="padding:0; border:0; width:50%;">
                            {!! check($data->kelengkapan_persyaratan, 'Lengkap') !!} Lengkap
                        </td>
                        <td style="padding:0; border:0;">
                            {!! check($data->kelengkapan_persyaratan, 'Tidak Lengkap') !!} Tidak Lengkap
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td><strong>Validasi Berkas Klaim:</strong></td>
            <td></td>
        </tr>

        <tr>
            <td>Copy Seluruh Dokumen Terbaca Jelas</td>
            <td>
                <table style=" width:100%; border-collapse:collapse; margin:0;">
                    <tr>
                        <td style="padding:0; border:0; width:50%;">
                            {!! check($data->copy_seluruh_dokumen_terbaca_jelas, 'Ya') !!} Ya
                        </td>
                        <td style="padding:0; border:0;">
                            {!! check($data->copy_seluruh_dokumen_terbaca_jelas, 'Tidak') !!} Tidak
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>Keaslian Akta Kematian</td>
            <td>
                <table style=" width:100%; border-collapse:collapse; margin:0;">
                    <tr>
                        <td style="padding:0; border:0; width:50%;">
                            {!! check($data->keaslian_akta_kematian, 'Valid') !!} Valid
                        </td>
                        <td style="padding:0; border:0;">
                            {!! check($data->keaslian_akta_kematian, 'Tidak Valid') !!} Tidak Valid
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>Flagging Kredit</td>
            <td>
                <table style=" width:100%; border-collapse:collapse; margin:0;">
                    <tr>
                        <td style="padding:0; border:0; width:50%;">
                            {!! check($data->flagging_kredit, 'Ya') !!} Ya
                        </td>
                        <td style="padding:0; border:0;">
                            {!! check($data->flagging_kredit, 'Tidak') !!} Tidak
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>Nomor WA Pengaju</td>
            <td>
                <table style=" width:100%; border-collapse:collapse; margin:0;">
                    <tr>
                        <td style="padding:0; border:0; width:50%;">
                            {!! check($data->nomor_wa_pengaju, 'Tercantum') !!} Tercantum
                        </td>
                        <td style="padding:0; border:0;">
                            {!! check($data->nomor_wa_pengaju, 'Tidak Tercantum') !!} Tidak Tercantum
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>Nomor Rekening Pengaju (Mitra Bayar)</td>
            <td>
                <table style=" width:100%; border-collapse:collapse; margin:0;">
                    <tr>
                        <td style="padding:0; border:0; width:50%;">
                            {!! check($data->nomor_rekening_pengaju, 'Aktif') !!} Aktif
                        </td>
                        <td style="padding:0; border:0;">
                            {!! check($data->nomor_rekening_pengaju, 'Tidak Aktif') !!} Tidak Aktif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>Perlu Konfirmasi Ulang*</td>
            <td>
                <table style=" width:100%; border-collapse:collapse; margin:0;">
                    <tr>
                        <td style="padding:0; border:0; width:50%;">
                            {!! check($data->perlu_konfirmasi_ulang, 'Ya') !!} Ya
                        </td>
                        <td style="padding:0; border:0;">
                            {!! check($data->perlu_konfirmasi_ulang, 'Tidak') !!} Tidak
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td style="border-top:0; border-bottom:0;">
                Catatan Konfirmasi
            </td>

            <td style="padding:0; border:0;">

                <table style="width:100%; border-collapse:collapse; margin:0;">
                    <tr>
                        <th
                            style="border-left:0; border-top:0; border-right: 1px solid #000; border-bottom: 1px solid #000; text-align:center; width:50%;">
                            CSO</th>
                        <th
                            style="border-left:0; border-top:0; border-right: 1px solid #000; border-bottom: 1px solid #000; ; text-align:center;">
                            Tim Proses</th>
                    </tr>
                    <tr>
                        <td
                            style="border-left:0; border-top:0; border-right: 1px solid #000; border-bottom:0; vertical-align:top; width:50%;">
                            <div style="height:180px;">
                                {{ $data->catatan_konfirmasi }}
                            </div>
                        </td>
                        <td style="border-left:0; border-top:0; border-right: 1px solid #000; border-bottom:0;">
                            <div style="height:180px;"></div>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>


        <tr>
            <td>Selesai Konfirmasi</td>
            <td>
                {{ $data->selesai_konfirmasi ? \Carbon\Carbon::parse($data->selesai_konfirmasi)->format('d / m / Y') : '' }}
            </td>
        </tr>

        <tr>
            <td>Nama CSO</td>
            <td>{{ optional($data->creator)->name }}</td>
        </tr>
    </table>

</body>

</html>
