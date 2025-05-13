@php
    $ppdb = \App\Models\PpdbInfo::first();
@endphp
<div class="navigation">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-9 col-8">
                <nav class="navbar navbar-expand-lg">
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item">
                                <a class="{{ request()->is('/') ? 'active' : '' }}"
                                    href="{{ url('/') }}">Beranda</a>
                            </li>

                            @php
                                $menus = \App\Models\Menu::where('menu_parent_id', 0)
                                    ->with('children')
                                    ->orderBy('menu_position')
                                    ->get();
                            @endphp

                            @foreach ($menus as $menu)
                                <li class="nav-item">
                                    <a class="{{ request()->is($menu->menu_url) ? 'active' : '' }}"
                                        href="{{ $menu->menu_url }}" target="{{ $menu->menu_target }}">
                                        {{ $menu->menu_title }}
                                    </a>

                                    @if ($menu->children->count() > 0)
                                        <ul class="sub-menu">
                                            @foreach ($menu->children as $submenu)
                                                <li>
                                                    <a class="{{ request()->is($submenu->menu_url) ? 'active' : '' }}"
                                                        href="{{ route('front.pages.show', $submenu->menu_url) }}"
                                                        target="{{ $submenu->menu_target }}">
                                                        {{ $submenu->menu_title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach

                            {{--  @if ($ppdb)
                                <li class="nav-item">
                                    <a href="#">PPDB</a>
                                    <ul class="sub-menu">
                                        <li><a href="{{ route('front.ppdb.index', $ppdb->slug) }}">Informasi PPDB</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif  --}}
                            <li class="nav-item">
                                <a href="{{ route('login') }}">Login</a>
                            </li>
                        </ul>
                    </div>
                </nav> <!-- nav -->
            </div>
        </div> <!-- row -->
    </div> <!-- container -->
</div>
