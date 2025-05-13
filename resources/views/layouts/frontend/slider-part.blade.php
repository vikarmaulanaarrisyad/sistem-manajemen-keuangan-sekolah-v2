    <section id="slider-part" class="slider-active">

        @if ($artikelSlider->isNotEmpty())
            @foreach ($artikelSlider as $slider)
                <div class="single-slider bg_cover pt-150"
                    style="background-image: url({{ $slider->image ? Storage::url($slider->image) : asset('images/default-slider.jpg') }})"
                    data-overlay="4">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-7 col-lg-9">
                                <div class="slider-cont">
                                    <h3 data-animation="bounceInLeft" data-delay="1s" style="color: white;">
                                        {{ $slider->judul ?? 'Judul Default' }}
                                    </h3>
                                    <p data-animation="fadeInUp" data-delay="1.3s">
                                        {{--  {{ $slider->content ?? 'Deskripsi tidak tersedia.' }}  --}}
                                        {{ Str::limit(strip_tags($slider->content ?? 'Tidak ada konten berita'), 150, '...') }}
                                    </p>
                                    <ul>
                                        <li>
                                            <a data-animation="fadeInUp" data-delay="1.6s" class="main-btn"
                                                href="{{ route('front.artikel_detail', $slider->slug) }}">
                                                Read More
                                            </a>
                                        </li>
                                        {{--  <li>
                                            <a data-animation="fadeInUp" data-delay="1.9s" class="main-btn main-btn-2"
                                                href="#">
                                                Get Started
                                            </a>
                                        </li>  --}}
                                    </ul>
                                </div>
                            </div>
                        </div> <!-- row -->
                    </div> <!-- container -->
                </div>
            @endforeach
        @endif


        {{--  <div class="single-slider bg_cover pt-150"
            style="background-image: url({{ asset('education') }}/images/slider/s-2.jpg)" data-overlay="4">
            <div class="container">
                <div class="row">
                    <div class="col-xl-7 col-lg-9">
                        <div class="slider-cont">
                            <h1 data-animation="bounceInLeft" data-delay="1s">Choose the right theme for education
                            </h1>
                            <p data-animation="fadeInUp" data-delay="1.3s">Donec vitae sapien ut libearo venenatis
                                faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt Sed
                                fringilla mauri amet nibh.</p>
                            <ul>
                                <li><a data-animation="fadeInUp" data-delay="1.6s" class="main-btn" href="#">Read
                                        More</a></li>
                                <li><a data-animation="fadeInUp" data-delay="1.9s" class="main-btn main-btn-2"
                                        href="#">Get Started</a></li>
                            </ul>
                        </div>
                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
        </div>

        <div class="single-slider bg_cover pt-150"
            style="background-image: url({{ asset('education') }}/images/slider/s-3.jpg)" data-overlay="4">
            <div class="container">
                <div class="row">
                    <div class="col-xl-7 col-lg-9">
                        <div class="slider-cont">
                            <h1 data-animation="bounceInLeft" data-delay="1s">Choose the right theme for education
                            </h1>
                            <p data-animation="fadeInUp" data-delay="1.3s">Donec vitae sapien ut libearo venenatis
                                faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt Sed
                                fringilla mauri amet nibh.</p>
                            <ul>
                                <li><a data-animation="fadeInUp" data-delay="1.6s" class="main-btn" href="#">Read
                                        More</a></li>
                                <li><a data-animation="fadeInUp" data-delay="1.9s" class="main-btn main-btn-2"
                                        href="#">Get Started</a></li>
                            </ul>
                        </div>
                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
        </div>   --}}
    </section>
