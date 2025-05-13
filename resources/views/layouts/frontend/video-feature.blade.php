<section id="video-feature" class="bg_cover pt-60 pb-110" style="background-image: url({{ asset('images/bg.jpeg') }})">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 order-last order-lg-first">
                <div class="video text-lg-left text-center pt-50">
                    <a class="Video-popup" href="https://www.youtube.com/watch?v=IavaGDq0FQg"><i
                            class="fa fa-play"></i></a>
                </div> <!-- row -->
            </div>
            <div class="col-lg-5 offset-lg-1 order-first order-lg-last">
                <div class="feature pt-50">
                    <div class="feature-title">
                        <h3>Fasilitas Madrasah</h3>
                    </div>
                    <ul>
                        @php
                            $fasilitas = \App\Models\Fasilitas::all();
                        @endphp
                        <li>
                            @if ($fasilitas->isNotEmpty())
                                @foreach ($fasilitas as $item)
                                    <div class="singel-feature">
                                        <div class="icon">
                                            <img src="{{ Storage::url($item->gambar ?? '') }}" alt="icon"
                                                style="width: 71px; height: 89px;">
                                        </div>
                                        <div class="cont">
                                            <h4>{{ $item->nama }}</h4>
                                            <p class="text-justify">{{ $item->short }}</p>
                                        </div>
                                    </div> <!-- singel feature -->
                                @endforeach
                            @else
                        </li>
                        <li>
                            <div class="singel-feature">
                                <div class="icon">
                                    <img src="{{ asset('education') }}/images/all-icon/f-1.png" alt="icon">
                                </div>
                                <div class="cont">
                                    <h4>Global Certificate</h4>
                                    <p>Gravida nibh vel velit auctor aliquetn auci elit cons solliazcitudirem sem
                                        quibibendum sem nibhutis.</p>
                                </div>
                            </div> <!-- singel feature -->
                        </li>
                        <li>
                            <div class="singel-feature">
                                <div class="icon">
                                    <img src="{{ asset('education') }}/images/all-icon/f-1.png" alt="icon">
                                </div>
                                <div class="cont">
                                    <h4>Global Certificate</h4>
                                    <p>Gravida nibh vel velit auctor aliquetn auci elit cons solliazcitudirem sem
                                        quibibendum sem nibhutis.</p>
                                </div>
                            </div> <!-- singel feature -->
                        </li>
                        @endif

                    </ul>
                </div> <!-- feature -->
            </div>
        </div> <!-- row -->
    </div> <!-- container -->
    <div class="feature-bg"></div> <!-- feature bg -->
</section>
