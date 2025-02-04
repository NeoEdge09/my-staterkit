<!-- Menu Navigation starts -->
<nav>
    <div class="app-logo">
        <a class="logo d-inline-block" href="{{ route('index') }}">
            <img src="{{ asset('../assets/images/logo/1.png') }}" alt="#">
        </a>

        <span class="bg-light-primary toggle-semi-nav">
            <i class="ti ti-chevrons-right f-s-20"></i>
        </span>
    </div>
    <div class="app-nav" id="app-simple-bar">
        <ul class="main-nav p-0 mt-2">
            @foreach (getMenu() as $menu)
                @if (!$menu->permission || auth()->user()->can($menu->permission))
                    <li class="{{ $menu->children->count() ? '' : 'no-sub' }}">
                        @if ($menu->route)
                            <a class="" href="{{ route($menu->route) }}">
                                <i class="{{ $menu->icon }}"></i> {{ $menu->name }}
                            </a>
                        @else
                            <a class="" data-bs-toggle="collapse" href="#menu-{{ $menu->id }}"
                                aria-expanded="false">
                                <i class="{{ $menu->icon }}"></i> {{ $menu->name }}
                            </a>
                        @endif
                        @if ($menu->id == 2)
                    </li>
                    <li class="menu-title">
                        <span>Apps</span>
                    </li>
                    <li>
                @endif
                @if ($menu->children->count())
                    <ul class="collapse" id="menu-{{ $menu->id }}">
                        @foreach ($menu->children as $child)
                            @if (!$child->permission || auth()->user()->can($child->permission))
                                <li class="{{ $child->children->count() ? 'another-level' : '' }}">
                                    @if ($child->route)
                                        <a href="{{ route($child->route) }}">{{ $child->name }}</a>
                                    @else
                                        <a class="" data-bs-toggle="collapse" href="#submenu-{{ $child->id }}"
                                            aria-expanded="false">
                                            {{ $child->name }}
                                        </a>
                                    @endif

                                    @if ($child->children->count())
                                        <ul class="collapse" id="submenu-{{ $child->id }}">
                                            @foreach ($child->children as $subChild)
                                                @if (!$subChild->permission || auth()->user()->can($subChild->permission))
                                                    <li>
                                                        @if ($subChild->route)
                                                            <a
                                                                href="{{ route($subChild->route) }}">{{ $subChild->name }}</a>
                                                        @else
                                                            <a href="#">{{ $subChild->name }}</a>
                                                        @endif
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
                </li>
            @endif
            @endforeach
        </ul>
    </div>

    <div class="menu-navs">
        <span class="menu-previous"><i class="ti ti-chevron-left"></i></span>
        <span class="menu-next"><i class="ti ti-chevron-right"></i></span>
    </div>

</nav>
<!-- Menu Navigation ends -->
