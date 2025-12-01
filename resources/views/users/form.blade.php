<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="users.index"></x-navbars.sidebar>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Users"></x-navbars.navs.auth>

        <div class="container-fluid py-4">

            <div class="row">
                <div class="col-12">
                    <div class="card my-4">

                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div
                                class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                                <h6 class="text-white text-capitalize ps-3 mb-0">Form User</h6>
                            </div>
                        </div>

                        <div class="card-body pb-2">

                            <div class="mb-2">
                                <button id="btnBack" class="btn btn-sm btn-outline-primary">
                                    <i class="material-icons fs-5">chevron_left</i> Back
                                </button>
                            </div>

                            <form id="form">
                                <input type="hidden" name="id" id="id" value="{{ $user->id ?? '' }}">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline my-3">
                                            <label class="form-label">Full Name</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $user->name ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline my-3">
                                            <label class="form-label">NIP</label>
                                            <input type="text" name="nip" class="form-control"
                                                value="{{ $user->nip ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline my-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ $user->email ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline my-3">
                                            <label class="form-label">Phone</label>
                                            <input type="text" name="phone" class="form-control"
                                                value="{{ $user->phone ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline my-3">
                                            <label class="form-label">Job Title</label>
                                            <input type="text" name="job_title" class="form-control"
                                                value="{{ $user->job_title ?? '' }}">
                                        </div>
                                    </div>

                                    {{-- Roles dropdown --}}
                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline my-3 is-filled">
                                            <label class="form-label">Role</label>
                                            <select name="role_id" class="form-control">
                                                <option value="">-- Select Role --</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}"
                                                        {{ isset($user) && $user->role_id == $role->id ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Password --}}
                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline my-3">
                                            <label class="form-label">Password
                                                {{ isset($user) ? '(leave blank to keep current)' : '' }}</label>
                                            <input type="password" name="password" class="form-control">
                                        </div>
                                    </div>

                                    {{-- Status Switch --}}
                                    <div class="col-md-6 my-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="status"
                                                name="status"
                                                {{ !isset($user) || (isset($user) && $user->status) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status">Active Status</label>
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
            const BACK_URL = `/users`;
            const RESOURCE_NAME = `User`;
            const STORE_URL = "{{ route('users.store') }}";
            const UPDATE_URL = "{{ isset($user) ? route('users.update', base64_encode($user->id)) : '' }}";
            const CSRF_TOKEN = "{{ csrf_token() }}";
        </script>

        <script src="{{ asset('js/modules/form.js') }}"></script>
    @endpush

</x-layout>
