<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="pending-task.index"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Pending Task"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div
                                class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">

                                <h6 class="text-white text-capitalize ps-3 mb-0">Berkas Klaim</h6>

                                {{-- <div class="d-flex align-items-center me-3" style="gap: 8px;">
                                    <button id="acceptSelected" class="btn btn-sm text-white"
                                        style="background-color:#3be535;">
                                        <i class="material-icons"
                                            style="font-size:16px; vertical-align:middle;">save</i>
                                        <span style="vertical-align:middle;">Terima Berkas</span>
                                    </button>

                                    @php
                                        $role = Auth::user()->role; // adjust if different
                                    @endphp

                                    @if (in_array($role->name, ['Kepala Bidang', 'Proses']))
                                        <button id="rejectSelected" class="btn btn-sm text-white"
                                            style="background-color:#e53935;">
                                            <i class="material-icons"
                                                style="font-size:16px; vertical-align:middle;">cancel</i>
                                            <span style="vertical-align:middle;">Tolak Berkas</span>
                                        </button>
                                    @endif

                                </div> --}}

                            </div>
                        </div>


                        <div class="card-body pb-2">
                            <div class="table-responsive" style="margin-left:-8px; margin-right:-8px;">
                                <table id="table" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="checkAll" class="table-checkbox" /></th>
                                            <th>No</th>
                                            <th>Nama Peserta</th>
                                            <th>NRP/KTPA</th>
                                            <th>Jenis Klaim</th>
                                            <th>Tgl Terima CSO</th>
                                            <th>Status Terima</th>
                                            <th>Status Terima (Lainnya)</th>

                                            <!-- enum / flags -->
                                            <th>Kelengkapan Persyaratan</th>
                                            <th>Copy Dokumen Terbaca Jelas</th>
                                            <th>Keaslian Akta Kematian</th>
                                            <th>Flagging Kredit</th>
                                            <th>Nomor WA Pengaju</th>
                                            <th>Nomor Rekening Pengaju</th>
                                            <th>Perlu Konfirmasi Ulang</th>

                                            <th>Catatan Konfirmasi</th>
                                            <th>Selesai Konfirmasi</th>

                                            <!-- status_berkas relation -->
                                            <th>Status Terkini</th>
                                            <th>Langkah Selanjutnya</th>

                                            <th>Dibuat Oleh</th>
                                            <th>Tindakan</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
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
            const FETCH_URL = "{{ route('pending-task.data') }}";
            const DELETE_URL = "{{ route('berkas-klaim.bulk-delete') }}";
            const ACCEPT_URL = "{{ route('berkas-klaim.accept') }}";
            const REJECT_URL = "{{ route('berkas-klaim.reject') }}";
            const CSRF_TOKEN = "{{ csrf_token() }}";
            const RESOURCE_NAME = "berkas-klaim";

            const TABLE_COLUMNS = [{
                    data: 'checkbox',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },

                {
                    data: 'nama_peserta',
                    name: 'nama_peserta'
                },
                {
                    data: 'nomor_identitas',
                    name: 'nomor_identitas'
                },
                {
                    data: 'jenis_klaim',
                    name: 'jenisKlaim.name'
                },
                {
                    data: 'tgl_berkas_diterima_cso',
                    name: 'tgl_berkas_diterima_cso'
                },
                {
                    data: 'status_terima_klaim_masuk',
                    name: 'status_terima_klaim_masuk'
                },

                {
                    data: 'status_terima_lainnya',
                    name: 'status_terima_lainnya'
                },

                // enum/flag columns (stored as enum strings)
                {
                    data: 'kelengkapan_persyaratan',
                    name: 'kelengkapan_persyaratan'
                },
                {
                    data: 'copy_seluruh_dokumen_terbaca_jelas',
                    name: 'copy_seluruh_dokumen_terbaca_jelas'
                },
                {
                    data: 'keaslian_akta_kematian',
                    name: 'keaslian_akta_kematian'
                },
                {
                    data: 'flagging_kredit',
                    name: 'flagging_kredit'
                },
                {
                    data: 'nomor_wa_pengaju',
                    name: 'nomor_wa_pengaju'
                },
                {
                    data: 'nomor_rekening_pengaju',
                    name: 'nomor_rekening_pengaju'
                },
                {
                    data: 'perlu_konfirmasi_ulang',
                    name: 'perlu_konfirmasi_ulang'
                },

                {
                    data: 'catatan_konfirmasi',
                    name: 'catatan_konfirmasi'
                },
                {
                    data: 'selesai_konfirmasi',
                    name: 'selesai_konfirmasi'
                },

                // status_berkas relation fields
                {
                    data: 'status_terkini',
                    name: 'statusBerkas.status_terkini'
                },
                {
                    data: 'next_step',
                    name: 'statusBerkas.next_step'
                },

                {
                    data: 'created_by_name',
                    name: 'created_by'
                },

                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                },
            ];
        </script>
        <script src="{{ asset('js/modules/table.js') }}"></script>
        <script src="{{ asset('js/modules/pending-task.js') }}"></script>
    @endpush

</x-layout>
