<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="roles-menus.index"></x-navbars.sidebar>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-navbars.navs.auth titlePage="Role Menu"></x-navbars.navs.auth>

        <div class="container-fluid py-4">

            <div class="row">
                <div class="col-12">
                    <div class="card my-4">

                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div
                                class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                                <h6 class="text-white text-capitalize ps-3 mb-0">Form Role Menu</h6>
                            </div>
                        </div>

                        <div class="card-body pb-2">
                            <div class="mb-2">
                                <button id="btnBack" class="btn btn-sm btn-outline-primary">
                                    <i class="material-icons fs-5">chevron_left</i> Back
                                </button>
                            </div>

                            <form id="form">

                                <input type="hidden" name="id" id="id"
                                    value="{{ isset($roleMenu) ? $roleMenu->id : '' }}">

                                <div class="row">

                                    {{-- ROLE --}}
                                    <div class="col-md-4 my-3">
                                        <label class="form-label">Role</label>
                                        <select name="role_id" id="role_id" class="form-control border-radius-lg">
                                            <option value="">-- Select Role --</option>
                                            @foreach ($roles as $r)
                                                <option value="{{ $r->id }}"
                                                    {{ isset($roleMenu) && $roleMenu->role_id == $r->id ? 'selected' : '' }}>
                                                    {{ $r->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- MENU --}}
                                    <div class="col-md-4 my-3">
                                        <label class="form-label">Menu</label>
                                        <select name="menu_id" id="menu_id" class="form-control border-radius-lg">
                                            <option value="">-- Select Menu --</option>
                                            @foreach ($menus as $m)
                                                <option value="{{ $m->id }}"
                                                    {{ isset($roleMenu) && $roleMenu->menu_id == $m->id ? 'selected' : '' }}>
                                                    {{ $m->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- PARENT MENU --}}
                                    <div class="col-md-4 my-3">
                                        <label class="form-label">Parent Menu</label>
                                        <select name="parent_id" id="parent_id" class="form-control border-radius-lg">
                                            <option value="">-- None --</option>
                                            @foreach ($parents as $p)
                                                <option value="{{ $p->id }}"
                                                    {{ isset($roleMenu) && $roleMenu->parent_id == $p->id ? 'selected' : '' }}>
                                                    {{ $p->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- ORDER --}}
                                    <div class="col-md-4 my-3">
                                        <div class="input-group input-group-outline my-3">
                                            <label class="form-label">Order</label>
                                            <input type="number" name="order" id="order" min="0"
                                                class="form-control"
                                                value="{{ isset($roleMenu) ? $roleMenu->order : '' }}">
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
            const BACK_URL = `/roles-menus`;
            const RESOURCE_NAME = `Role Menu`;
            const STORE_URL = "{{ route('roles-menus.store') }}";
            const UPDATE_URL = "{{ isset($roleMenu) ? route('roles-menus.update', base64_encode($roleMenu->id)) : '' }}";
            const CSRF_TOKEN = "{{ csrf_token() }}";
        </script>

        <script>
            new Choices("#role_id", {
                searchEnabled: true
            });
            new Choices("#menu_id", {
                searchEnabled: true
            });
            new Choices("#parent_id", {
                searchEnabled: true
            });
        </script>

        <script src="{{ asset('js/modules/form.js') }}"></script>
    @endpush

</x-layout>
