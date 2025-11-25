<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="roles-menus.index"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Roles Menus"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div
                                class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">

                                <h6 class="text-white text-capitalize ps-3 mb-0">Role Menu table</h6>

                                <div class="d-flex align-items-center me-3" style="gap: 8px;">
                                    <a href="{{ route('roles-menus.create') }}"
                                        class="btn btn-sm btn-light text-primary">
                                        + Add Role Menu
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
                                            <th>Menu</th>
                                            <th>Parent Menu</th>
                                            <th>Order</th>
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
            const FETCH_URL = "{{ route('roles-menus.data') }}";
            const DELETE_URL = "{{ route('roles-menus.bulk-delete') }}";
            const CSRF_TOKEN = "{{ csrf_token() }}";
            const RESOURCE_NAME = "roles-menus";
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
                    data: 'menu_name',
                    name: 'menu_name'
                },
                {
                    data: 'parent_menu_name',
                    name: 'parent_menu_name'
                },
                {
                    data: 'order',
                    name: 'order'
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                },
            ];
        </script>
        <script src="{{ asset('js/modules/table.js') }}"></script>
        <script src="{{ asset('js/modules/roles-menus.js') }}"></script>
    @endpush

</x-layout>
