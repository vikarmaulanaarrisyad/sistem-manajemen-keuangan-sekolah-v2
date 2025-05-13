@php
    $ppdb = \App\Models\PpdbInfo::first();
@endphp
<div class="navigation">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-9 col-8">
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

                            <li class="nav-item">
                                <a class="" href="#">Profile</a>
                                <ul class="sub-menu">
                                    <li><a class="" href="{{ route('front.sejarah_index') }}">Sejarah</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item">
                                <a class="{{ request()->routeIs('front.event_index') ? 'active' : '' }}"
                                    href="{{ route('front.event_index') }}">Agenda</a>
                            </li>

                            <li class="nav-item">
                                <a class="{{ request()->routeIs('front.artikel_index') ? 'active' : '' }}"
                                    href="{{ route('front.artikel_index') }}">Artikel</a>
                            </li>
                            @if ($ppdb)
                                <li class="nav-item">
                                    <a href="#">PPDB</a>
                                    <ul class="sub-menu">
                                        <li><a href="{{ route('front.ppdb.index', $ppdb->slug) }}">Informasi PPDB</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
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
