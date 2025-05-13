<section id="apply-aprt" class="pb-120">
    <div class="container">
        <div class="apply">

            <div class="row no-gutters">
                @if ($upcomingEvents->isNotEmpty())
                    @foreach ($upcomingEvents as $item)
                        <div class="col-lg-6">
                            <div class="apply-cont apply-color-1">
                                <h3>{{ $item->judul }}</h3>
                                <p>
                                    {!! Str::limit($item->deskripsi, 200, '...') !!}
                                </p>
                                <a href="{{ route('front.event_detail', $item->slug) }}" class="main-btn">Lihat
                                    Detail</a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="apply-cont apply-color-1 text-center">
                            <h3>Tidak ada event dalam waktu dekat</h3>
                        </div>
                    </div>
                @endif
            </div>
        </div> <!-- row -->
    </div> <!-- container -->
</section>
