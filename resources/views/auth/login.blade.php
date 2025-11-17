<x-layout bodyClass="">

    <div>
        <div class="container position-sticky z-index-sticky top-0">
            <div class="row">
                <div class="col-12">
                    <!-- Navbar -->
                    <!-- <x-navbars.navs.guest signin='login' signup='register'></x-navbars.navs.guest> -->
                    <!-- End Navbar -->
                </div>
            </div>
        </div>
        <main class="main-content  mt-0">
            <section>
                <div class="page-header min-vh-100">
                    <div class="container">
                        <div class="row">
                            <div
                                class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
                                <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center"
                                    style="background-image: url('../assets/img/illustrations/illustration-signup.jpg'); background-size: cover;">
                                </div>
                            </div>
                            <div
                                class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
                                <div class="card card-plain">
                                    <div class="card-header">
                                        <h4 class="font-weight-bolder">Login</h4>
                                        <p class="mb-0">Masukkan NIP dan password untuk login</p>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('login.post') }}">
                                            @csrf
                                            <div class="input-group input-group-outline mt-3">
                                                <label class="form-label">NIP</label>
                                                <input type="text" class="form-control" name="nip"
                                                    value="{{ old('nip') }}">
                                            </div>
                                            @error('nip')
                                                <p class='text-danger inputerror'>{{ $message }} </p>
                                            @enderror
                                            <!-- <div class="input-group input-group-outline mt-3">
                                                <label class="form-label">Password</label>
                                                <input type="password" class="form-control" name="password">
                                            </div> -->
                                            <div class="input-group input-group-outline mt-3 position-relative">
                                                <label class="form-label">Password</label>
                                                <input type="password" class="form-control pe-5" name="password"
                                                    id="password">

                                                <!-- Eye icon -->
                                                <span id="togglePassword"
                                                    class="position-absolute end-0 top-50 translate-middle-y me-3"
                                                    style="cursor: pointer; z-index: 10;">
                                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                                </span>
                                            </div>


                                            @error('password')
                                                <p class='text-danger inputerror'>{{ $message }} </p>
                                            @enderror
                                            <!-- <div class="form-check form-check-info text-start ps-0 mt-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="flexCheckDefault" checked>
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    I agree the <a href="javascript:;"
                                                        class="text-dark font-weight-bolder">Terms and Conditions</a>
                                                </label>
                                            </div> -->
                                            <div class="text-center">
                                                <button type="submit"
                                                    class="btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    @push('js')
        <script src="{{ asset('assets') }}/js/jquery.min.js"></script>
        <script src="{{ asset('js/modules/login.js') }}"></script>
        <script>
            $(function() {

                var text_val = $(".input-group input").val();
                if (text_val === "") {
                    $(".input-group").removeClass('is-filled');
                } else {
                    $(".input-group").addClass('is-filled');
                }
            });
        </script>
    @endpush
</x-layout>
