@props(['activePage'])

<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0 d-flex text-wrap align-items-center" href=" {{ route('dashboard') }} ">
            <img src="{{ asset('assets') }}/img/logo-ct.png" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-2 font-weight-bold text-white">KCU Jakarta</span>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div id="sidenav-collapse-main">
        <!-- collapse navbar-collapse  w-auto  max-height-vh-100 -->
        <ul class="navbar-nav">
            @php
                $role = auth()->user()->role;
                $menus = $role->menus->sortBy('pivot_order');
                $rootMenus = $menus->where('pivot_parent_menu_id', null);
                $children = fn($parentId) => $menus->where('pivot_parent_menu_id', $parentId);
            @endphp

            @foreach ($rootMenus as $menu)
                {{-- Header --}}
                @if (empty($menu->route) || $menu->route === '#')
                    <li class="nav-item mt-3">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">
                            {{ $menu->name }}
                        </h6>
                    </li>

                    @foreach ($children($menu->id) as $child)
                        @php
                            $link = Route::has($child->route) ? route($child->route) : '#';
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link text-white {{ $activePage == $child->route ? 'active bg-gradient-primary' : '' }}"
                                href="{{ $link }}">
                                <div
                                    class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="{{ $child->icon ?? 'fas fa-circle' }} ps-2 pe-2"></i>
                                </div>
                                <span class="nav-link-text ms-1">{{ $child->name }}</span>
                            </a>
                        </li>
                    @endforeach
                @else
                    {{-- Single Menu --}}
                    @php
                        $link = Route::has($menu->route) ? route($menu->route) : '#';
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link text-white {{ $activePage == $menu->route ? 'active bg-gradient-primary' : '' }}"
                            href="{{ $link }}">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="{{ $menu->icon ?? 'fas fa-circle' }} ps-2 pe-2"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ $menu->name }}</span>
                        </a>
                    </li>
                @endif
            @endforeach

        </ul>
    </div>
</aside>
