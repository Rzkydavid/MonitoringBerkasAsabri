<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="privileges.index"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Privileges"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div
                                class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">

                                <h6 class="text-white text-capitalize ps-3 mb-0">Privilege table</h6>

                                <div class="d-flex align-items-center me-3" style="gap: 8px;">
                                    {{-- <a href="{{ route('privileges.populate') }}"
                                        class="btn btn-sm btn-light text-primary">
                                        + Populate
                                    </a> --}}
                                    <button id="btnPopulate" class="btn btn-sm text-white"
                                        style="background-color:#e53935;">
                                        <span style="vertical-align:middle;">+ Populate</span>
                                    </button>
                                </div>

                            </div>
                        </div>


                        <div class="card-body pb-2">
                            <div class="table-responsive">
                                <table id="table" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
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
            const FETCH_URL = "{{ route('privileges.data') }}";
            const DELETE_URL = "";
            const CSRF_TOKEN = "{{ csrf_token() }}";
            const RESOURCE_NAME = "privileges";
            const TABLE_COLUMNS = [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
            ];
        </script>
        <script src="{{ asset('js/modules/table.js') }}"></script>
        <script src="{{ asset('js/modules/privilege.js') }}"></script>
    @endpush

</x-layout>
