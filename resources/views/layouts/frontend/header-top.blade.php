        <div class="header-top d-none d-lg-block">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="header-contact text-lg-left text-center">
                            <ul>
                                <li><img src="{{ asset('education') }}/images/all-icon/map.png"
                                        alt="icon"><span>{{ $sekolah->alamat }}</span></li>
                                <li><img src="{{ asset('education') }}/images/all-icon/email.png"
                                        alt="icon"><span>{{ $sekolah->email }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="header-opening-time text-lg-right text-center">
                            <p>Opening Hours : {{ $sekolah->opening ?? '' }}</p>
                        </div>
                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
        </div>
