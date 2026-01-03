<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="roles-privileges.index"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Roles Privileges"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div
                                class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">

                                <h6 class="text-white text-capitalize ps-3 mb-0">Role Privilege table</h6>

                                <div class="d-flex align-items-center me-3" style="gap: 8px;">
                                    <button class="btn btn-light text-primary" id="openAddPrivilegeModal">Tambah
                                        Privileges</button>

                                    <button id="deleteSelected" class="btn btn-sm text-white"
                                        style="background-color:#e53935;">
                                        <i class="material-icons" style="font-size:5px; vertical-align:middle;"></i>
                                        <span style="vertical-align:middle;">Hapus</span>
                                    </button>
                                </div>

                            </div>
                        </div>


                        <div class="card-body pb-2">
                            <div class="mb-4 col-md-4">
                                <label for="role_id" class="form-label">Role</label>
                                <select name="role_id" id="roleId" class="form-control border-radius-lg">
                                    <option value="">-- None --</option>

                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="table-responsive">
                                <table id="table" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="checkAll" class="table-checkbox" />
                                            </th>
                                            <th>No</th>
                                            <th>Privilege</th>
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
            const FETCH_URL = "{{ route('roles-privileges.data') }}";
            const DELETE_URL = "{{ route('roles-privileges.bulk-delete') }}";
            const CSRF_TOKEN = "{{ csrf_token() }}";
            const RESOURCE_NAME = "roles-privileges";
            const TABLE_COLUMNS = [{
                    data: 'checkbox',
                    name: 'checkbox',
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
                    data: 'name',
                    name: 'name'
                },
            ];
            const AVAILABLE_PRIVILEGES_URL = "{{ route('privileges.available') }}";
            const ADD_ROLE_PRIVILEGES_URL = "{{ route('roles-privileges.assign') }}";
        </script>
        <script src="{{ asset('js/modules/table.js') }}"></script>
        <script src="{{ asset('js/modules/roles-privileges.js') }}"></script>
    @endpush

</x-layout>

<!-- Modal -->
<div class="modal fade" id="addPrivilegeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Available Privileges</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-primary" id="applyPrivilegesBtn">
                        Add Selected Privileges
                    </button>
                </div>

                <table id="availablePrivilegesTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAllAvail" class="table-checkbox"></th>
                            <th>No</th>
                            <th>Privilege</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>
