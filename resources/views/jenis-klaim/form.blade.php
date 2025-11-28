<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="jenis-klaim.index"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Jenis Klaim"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">

            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div
                                class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">

                                <h6 class="text-white text-capitalize ps-3 mb-0">Form Jenis Klaim</h6>

                            </div>
                        </div>


                        <div class="card-body pb-2">
                            <div class="mb-2">
                                <button id="btnBack" class="btn btn-sm btn-outline-primary">
                                    <i class="material-icons fs-5">chevron_left</i> Back
                                </button>
                            </div>
                            <form id="form">

                                {{-- hidden id field for edit mode --}}
                                <input type="hidden" name="id" id="id"
                                    value="{{ isset($jenis_klaim) ? $jenis_klaim->id : '' }}">

                                <div class="row">

                                    {{-- Name --}}
                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline my-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="name" id="name" class="form-control"
                                                value="{{ isset($jenis_klaim) ? $jenis_klaim->name : '' }}">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn bg-gradient-primary mt-4">
                                    Save
                                </button>

                                <button type="button" id="btnReset" class="btn btn-secondary mt-4">
                                    Reset
                                </button>

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
            const BACK_URL = `/jenis-klaim`;
            const RESOURCE_NAME = `Jenis Klaim`;
            const STORE_URL = "{{ route('jenis-klaim.store') }}";
            const UPDATE_URL = "{{ isset($jenis_klaim) ? route('jenis-klaim.update', base64_encode($jenis_klaim->id)) : '' }}";
            const CSRF_TOKEN = "{{ csrf_token() }}";
        </script>

        <script src="{{ asset('js/modules/form.js') }}"></script>
    @endpush

</x-layout>
