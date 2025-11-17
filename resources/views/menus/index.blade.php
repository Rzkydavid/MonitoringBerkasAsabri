<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="menus.index"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Menus"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div
                                class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">

                                <h6 class="text-white text-capitalize ps-3 mb-0">Menu table</h6>

                                <div class="d-flex align-items-center me-3" style="gap: 8px;">
                                    <a href="{{ route('menus.create') }}" class="btn btn-sm btn-light text-primary">
                                        + Add Menu
                                    </a>

                                    <button id="deleteSelected" class="btn btn-sm text-white"
                                        style="background-color:#e53935;">
                                        <i class="material-icons"
                                            style="font-size:16px; vertical-align:middle;">delete</i>
                                        <span style="vertical-align:middle;">Delete Selected</span>
                                    </button>
                                </div>

                            </div>
                        </div>


                        <div class="card-body pb-2">
                            <div class="table-responsive">
                                <table id="menuTable" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="checkAll" class="table-checkbox" />
                                            </th>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Route</th>
                                            <th>Icon</th>
                                            <th>Action</th>
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
            const MENU_URL = "{{ route('menus.data') }}";
            const DELETE_URL = "{{ route('menus.bulk-delete') }}";
            const CSRF_TOKEN = "{{ csrf_token() }}";
        </script>
        <script src="{{ asset('js/menu.js') }}"></script>
    @endpush

</x-layout>
