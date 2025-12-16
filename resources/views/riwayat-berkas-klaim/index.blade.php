<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="riwayat-berkas-klaim.index"></x-navbars.sidebar>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-navbars.navs.auth titlePage="Riwayat Berkas Klaim"></x-navbars.navs.auth>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">

                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div
                                class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3
                                        d-flex justify-content-between align-items-center">

                                <h6 class="text-white text-capitalize ps-3 mb-0">
                                    Riwayat Berkas Klaim
                                </h6>

                                {{-- <button id="deleteSelected" class="btn btn-sm text-white me-3"
                                    style="background-color:#e53935;">
                                    <i class="material-icons" style="font-size:16px; vertical-align:middle;">delete</i>
                                    <span style="vertical-align:middle;">Delete Selected</span>
                                </button> --}}

                            </div>
                        </div>

                        <div class="card-body pb-2">
                            <div class="table-responsive">
                                <table id="table" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Peserta</th>
                                            <th>Status Terkini</th>
                                            <th>Langkah Selanjutnya</th>
                                            <th>Dibuat oleh</th>
                                            <th>Dibuat pada</th>
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
            const FETCH_URL = "{{ route('riwayat-berkas-klaim.data') }}";
            // const DELETE_URL = "{{ route('riwayat-berkas-klaim.bulk-delete') }}";
            const CSRF_TOKEN = "{{ csrf_token() }}";
            const RESOURCE_NAME = "riwayat-berkas-klaim";

            const TABLE_COLUMNS = [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nama_peserta',
                    name: 'berkasKlaim.nama_peserta'
                },
                {
                    data: 'status_terkini',
                    name: 'statusBerkas.status_terkini'
                },
                {
                    data: 'next_step',
                    name: 'statusBerkas.next_step'
                },
                {
                    data: 'user_name',
                    name: 'user.name'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
            ];
        </script>

        <script src="{{ asset('js/modules/table.js') }}"></script>
    @endpush

</x-layout>
