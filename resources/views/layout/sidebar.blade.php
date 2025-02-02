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
            {{-- <li class="menu-title"> <span>Others</span></li> --}}
            <li class="no-sub">
                <a class="" href="{{ route('index') }}">
                    <i class="ti ti-home"></i> Home
                </a>
            </li>
            <li>
                <a class="" data-bs-toggle="collapse" href="#level" aria-expanded="false">
                    <i class="ph-duotone  ph-number-circle-two"></i> 2 level
                </a>
                <ul class="collapse" id="level">
                    <li><a href="#">Blank</a></li>
                    <li class="another-level">
                        <a class="" data-bs-toggle="collapse" href="#level2" aria-expanded="false">
                            Another level
                        </a>
                        <ul class="collapse" id="level2">
                            <li><a href="#">Blank</a></li>
                        </ul>
                    </li>

                </ul>
            </li>




        </ul>
    </div>

    <div class="menu-navs">
        <span class="menu-previous"><i class="ti ti-chevron-left"></i></span>
        <span class="menu-next"><i class="ti ti-chevron-right"></i></span>
    </div>

</nav>
<!-- Menu Navigation ends -->
