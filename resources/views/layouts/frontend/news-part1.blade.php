<section id="news-part" class="pt-115 pb-110">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="section-title">
                    <h5>Berita Terbaru</h5>
                </div> <!-- section title -->
            </div>
        </div> <!-- row -->

        <div class="row">
            @if ($artikelTerbaru)
                <!-- Jika Ada Artikel Terbaru -->
                <div class="col-lg-6">
                    <div class="singel-news mt-30">
                        <div class="news-thum pb-25">
                            <img src="{{ $artikelTerbaru->image ? Storage::url($artikelTerbaru->image) : asset('images/no-image.jpg') }}"
                                alt="News">
                        </div>
                        <div class="news-cont">
                            <ul>
                                <li><a href="#"><i class="fa fa-calendar"></i>
                                        {{ tanggal_indonesia($artikelTerbaru->tgl_publish) }}</a></li>
                                <li><a href="#"> <span>By</span> Admin</a></li>
                            </ul>
                            <a href="{{ route('front.artikel_detail', $artikelTerbaru->slug) }}">
                                <h3>{{ $artikelTerbaru->judul ?? 'Tidak ada berita terbaru' }}</h3>
                            </a>
                            <p>
                                {{--  {!! $artikelTerbaru->content ?? 'Belum ada konten berita yang tersedia.' !!}  --}}
                                {{ Str::limit(strip_tags($artikelTerbaru->content ?? 'Tidak ada konten berita'), 150, '...') }}

                            </p>
                        </div>
                    </div> <!-- singel news -->
                </div>
            @else
                <!-- Jika Tidak Ada Artikel Terbaru -->
                <div class="col-lg-6">
                    <div class="singel-news mt-30 text-center">
                        <h4>Belum ada berita terbaru</h4>
                        <img src="{{ asset('images/no-news.jpg') }}" alt="No News"
                            style="width: 100%; max-width: 400px;">
                    </div>
                </div>
            @endif

            <div class="col-lg-6">
                @if ($artikel->isNotEmpty())
                    @foreach ($artikel as $item)
                        <div class="singel-news news-list">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="news-thum mt-30">
                                        <img src="{{ $item->image ? Storage::url($item->image) : asset('images/no-image.jpg') }}"
                                            alt="News">
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="news-cont mt-30">
                                        <ul>
                                            <li><a href="#"><i class="fa fa-calendar"></i>
                                                    {{ tanggal_indonesia($item->tgl_publish) }}</a></li>
                                            <li><a href="#"> <span>By</span> Admin</a></li>
                                        </ul>
                                        <a href="{{ route('front.artikel_detail', $item->slug) }}">
                                            <h3>{{ $item->judul ?? 'Judul Tidak Tersedia' }}</h3>
                                        </a>
                                        <p>{{ Str::limit(strip_tags($item->content ?? 'Tidak ada konten berita'), 100, '...') }}
                                        </p>
                                    </div>
                                </div>
                            </div> <!-- row -->
                        </div>
                    @endforeach
                @else
                    <!-- Jika Tidak Ada Artikel Lain -->
                    <div class="singel-news mt-30 text-center">
                        <h4>Belum ada berita lainnya</h4>
                        <img src="{{ asset('images/no-news.jpg') }}" alt="No News"
                            style="width: 100%; max-width: 400px;">
                    </div>
                @endif
            </div>
        </div>
    </div> <!-- container -->
</section>
