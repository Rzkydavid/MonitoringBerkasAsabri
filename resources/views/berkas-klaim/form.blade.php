<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="berkas-klaim.index"></x-navbars.sidebar>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        <x-navbars.navs.auth titlePage="{{ isset($berkasKlaim) ? 'Edit Berkas Klaim' : 'Tambah Berkas Klaim' }}">
        </x-navbars.navs.auth>

        <div class="container-fluid py-4">

            <div class="row">
                <div class="col-12">
                    <div class="card my-4">

                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3 mb-0">
                                    Form Berkas Klaim
                                </h6>
                            </div>
                        </div>

                        <div class="card-body">

                            <button id="btnBack" class="btn btn-sm btn-outline-primary mb-3">
                                <i class="material-icons fs-5">chevron_left</i> Back
                            </button>

                            <form id="form">

                                {{-- Hidden ID --}}
                                <input type="hidden" name="id" id="id"
                                    value="{{ $berkasKlaim->id ?? '' }}">

                                <div class="row">

                                    {{-- Nama Peserta --}}
                                    <div class="col-md-4">
                                        <div class="input-group input-group-outline my-3">
                                            <label class="form-label">Nama Peserta</label>
                                            <input type="text" class="form-control" name="nama_peserta"
                                                value="{{ $berkasKlaim->nama_peserta ?? '' }}">
                                        </div>
                                    </div>

                                    {{-- Nomor Identitas --}}
                                    <div class="col-md-4">
                                        <div class="input-group input-group-outline my-3">
                                            <label class="form-label">Nomor Identitas</label>
                                            <input type="text" class="form-control" name="nomor_identitas"
                                                value="{{ $berkasKlaim->nomor_identitas ?? '' }}">
                                        </div>
                                    </div>

                                    {{-- Tanggal Diterima --}}
                                    {{-- <div class="col-md-6">
                                        <div class="input-group input-group-static my-3">
                                            <label>Tanggal Berkas Diterima CSO</label>
                                            <input type="date" class="form-control" name="tgl_berkas_diterima_cso"
                                                value="{{ $berkasKlaim->tgl_berkas_diterima_cso ?? '' }}">
                                        </div>
                                    </div> --}}

                                    {{-- Tanggal Diterima --}}
                                    <div class="col-md-4">
                                        <div class="input-group input-group-outline my-3">
                                            <label class="form-label">Tanggal Berkas Diterima CSO</label>
                                            <input type="text" class="form-control datepicker"
                                                name="tgl_berkas_diterima_cso"
                                                value="{{ $berkasKlaim->tgl_berkas_diterima_cso ?? '' }}">
                                        </div>
                                    </div>

                                    {{-- Jenis Klaim (Choices.js) --}}
                                    <div class="col-md-6">
                                        <div class="my-3">
                                            <label class="form-label">Jenis Klaim</label>
                                            <select class="form-control" id="jenis_klaim_id" name="jenis_klaim_id">
                                                <option value="">-- Pilih --</option>
                                                @foreach ($jenisKlaim as $jk)
                                                    <option value="{{ $jk->id }}"
                                                        {{ isset($berkasKlaim) && $berkasKlaim->jenis_klaim_id == $jk->id ? 'selected' : '' }}>
                                                        {{ $jk->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Status Terima Klaim Masuk (Choices.js) --}}
                                    <div class="col-md-6">
                                        <div class="col-md-12">
                                            <div class="my-3">
                                                <label class="form-label">Status Terima Klaim Masuk</label>
                                                <select class="form-control" id="status_terima_klaim_masuk"
                                                    name="status_terima_klaim_masuk">
                                                    @php
                                                        $opts = [
                                                            'Datang Langsung',
                                                            'ASABRI Link',
                                                            'Klaim Online',
                                                            'Email',
                                                            'Lainnya',
                                                        ];
                                                    @endphp
                                                    <option value="">-- Pilih --</option>
                                                    @foreach ($opts as $opt)
                                                        <option value="{{ $opt }}"
                                                            {{ isset($berkasKlaim) && $berkasKlaim->status_terima_klaim_masuk == $opt ? 'selected' : '' }}>
                                                            {{ $opt }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Lainnya Input --}}
                                        <div class="col-md-12" id="lainnya_input_container" style="display:none;">
                                            <div class="input-group input-group-outline my-3">
                                                <label class="form-label">Sebutkan Lainnya</label>
                                                <input type="text" class="form-control"
                                                    id="status_terima_klaim_masuk_lainnya"
                                                    name="status_terima_klaim_masuk_lainnya"
                                                    value="{{ $berkasKlaim->status_terima_klaim_masuk_lainnya ?? '' }}">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- --- RADIO COMPONENT GENERATOR --- --}}
                                    @php
                                        function radioMD($name, $value, $current)
                                        {
                                            return '
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio"
                                                       name="' .
                                                $name .
                                                '" value="' .
                                                $value .
                                                '"
                                                       ' .
                                                ($current == $value ? 'checked' : '') .
                                                '>
                                                <label class="custom-control-label">' .
                                                $value .
                                                '</label>
                                            </div>';
                                        }
                                    @endphp

                                    {{-- Kelengkapan Persyaratan --}}
                                    <div class="col-md-4 mt-3">
                                        <label class="form-label">Kelengkapan Persyaratan</label><br>
                                        {!! radioMD('kelengkapan_persyaratan', 'Lengkap', $berkasKlaim->kelengkapan_persyaratan ?? '') !!}
                                        {!! radioMD('kelengkapan_persyaratan', 'Tidak Lengkap', $berkasKlaim->kelengkapan_persyaratan ?? '') !!}
                                    </div>

                                    {{-- Copy Dokumen --}}
                                    <div class="col-md-4 mt-3">
                                        <label class="form-label">Copy Dokumen Terbaca Jelas</label><br>
                                        {!! radioMD('copy_seluruh_dokumen_terbaca_jelas', 'Ya', $berkasKlaim->copy_seluruh_dokumen_terbaca_jelas ?? '') !!}
                                        {!! radioMD(
                                            'copy_seluruh_dokumen_terbaca_jelas',
                                            'Tidak',
                                            $berkasKlaim->copy_seluruh_dokumen_terbaca_jelas ?? '',
                                        ) !!}
                                    </div>

                                    {{-- Keaslian Akta --}}
                                    <div class="col-md-4 mt-3">
                                        <label class="form-label">Keaslian Akta Kematian</label><br>
                                        {!! radioMD('keaslian_akta_kematian', 'Valid', $berkasKlaim->keaslian_akta_kematian ?? '') !!}
                                        {!! radioMD('keaslian_akta_kematian', 'Tidak Valid', $berkasKlaim->keaslian_akta_kematian ?? '') !!}
                                    </div>

                                    {{-- Flagging Kredit --}}
                                    <div class="col-md-4 mt-3">
                                        <label class="form-label">Flagging Kredit</label><br>
                                        {!! radioMD('flagging_kredit', 'Ya', $berkasKlaim->flagging_kredit ?? '') !!}
                                        {!! radioMD('flagging_kredit', 'Tidak', $berkasKlaim->flagging_kredit ?? '') !!}
                                    </div>

                                    {{-- WA Pengaju --}}
                                    <div class="col-md-4 mt-3">
                                        <label class="form-label">Nomor WA Pengaju</label><br>
                                        {!! radioMD('nomor_wa_pengaju', 'Tercantum', $berkasKlaim->nomor_wa_pengaju ?? '') !!}
                                        {!! radioMD('nomor_wa_pengaju', 'Tidak Tercantum', $berkasKlaim->nomor_wa_pengaju ?? '') !!}
                                    </div>

                                    {{-- Rekening Pengaju --}}
                                    <div class="col-md-4 mt-3">
                                        <label class="form-label">Nomor Rekening Pengaju</label><br>
                                        {!! radioMD('nomor_rekening_pengaju', 'Aktif', $berkasKlaim->nomor_rekening_pengaju ?? '') !!}
                                        {!! radioMD('nomor_rekening_pengaju', 'Tidak Aktif', $berkasKlaim->nomor_rekening_pengaju ?? '') !!}
                                    </div>

                                    {{-- Perlu Konfirmasi --}}
                                    <div class="col-md-12 mt-3">
                                        <label class="form-label">Perlu Konfirmasi Ulang</label><br>
                                        {!! radioMD('perlu_konfirmasi_ulang', 'Ya', $berkasKlaim->perlu_konfirmasi_ulang ?? '') !!}
                                        {!! radioMD('perlu_konfirmasi_ulang', 'Tidak', $berkasKlaim->perlu_konfirmasi_ulang ?? '') !!}
                                    </div>

                                    {{-- Catatan --}}
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static my-3">
                                            <label>Catatan Konfirmasi</label>
                                            <textarea class="form-control" name="catatan_konfirmasi" rows="3">{{ $berkasKlaim->catatan_konfirmasi ?? '' }}</textarea>
                                        </div>
                                    </div>

                                    {{-- Selesai Konfirmasi --}}
                                    {{-- <div class="col-md-6">
                                        <div class="input-group input-group-static my-3">
                                            <label>Tanggal Selesai Konfirmasi</label>
                                            <input type="date" class="form-control" name="selesai_konfirmasi"
                                                value="{{ $berkasKlaim->selesai_konfirmasi ?? '' }}">
                                        </div>
                                    </div> --}}

                                    {{-- Selesai Konfirmasi --}}
                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline my-3">
                                            <label class="form-label">Tanggal Selesai Konfirmasi</label>
                                            <input type="text" class="form-control datepicker"
                                                name="selesai_konfirmasi"
                                                value="{{ $berkasKlaim->selesai_konfirmasi ?? '' }}">
                                        </div>
                                    </div>

                                </div>

                                <button type="submit" class="btn bg-gradient-primary mt-4">Save</button>
                                <button type="button" id="btnReset" class="btn btn-secondary mt-4">Reset</button>

                            </form>

                        </div>

                    </div>
                </div>
            </div>

            <x-footers.auth></x-footers.auth>

        </div>
    </main>

    <x-plugins></x-plugins>

    @push('js')
        <script>
            const BACK_URL = "/berkas-klaim";
            const RESOURCE_NAME = "Berkas Klaim";
            const STORE_URL = "{{ route('berkas-klaim.store') }}";
            const UPDATE_URL =
                "{{ isset($berkasKlaim) ? route('berkas-klaim.update', base64_encode($berkasKlaim->id)) : '' }}";
            const CSRF_TOKEN = "{{ csrf_token() }}";

            function toggleLainnya() {
                const select = document.getElementById("status_terima_klaim_masuk");
                const container = document.getElementById("lainnya_input_container");

                if (select.value === "Lainnya") {
                    container.style.display = "block";
                } else {
                    container.style.display = "none";
                }
            }

            document.getElementById("status_terima_klaim_masuk").addEventListener("change", toggleLainnya);
            toggleLainnya();

            // Choices.js init
            new Choices("#jenis_klaim_id", {
                searchEnabled: true
            });
            new Choices("#status_terima_klaim_masuk", {
                searchEnabled: true
            });
        </script>
        <script>
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                allowInput: true
            });
        </script>
        <script src="{{ asset('js/modules/form.js') }}"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                const btnReset = document.getElementById("btnReset");
                const lainnyaContainer = document.getElementById("lainnya_input_container");
                const lainnyaField = document.getElementById("status_terima_klaim_masuk_lainnya");

                btnReset.addEventListener("click", function() {

                    // Hide the "Lainnya" input field container
                    if (lainnyaContainer) {
                        lainnyaContainer.style.display = "none";
                    }

                    // Clear the input value
                    if (lainnyaField) {
                        lainnyaField.value = "";
                    }
                });

            });
        </script>
    @endpush

</x-layout>
