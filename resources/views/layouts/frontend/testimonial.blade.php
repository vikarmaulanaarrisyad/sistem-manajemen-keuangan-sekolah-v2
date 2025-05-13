@push('css')
    <style>
        .singel-testimonial .testimonial-cont p {
            color: #fff;
            padding-bottom: 10px !important;
        }

        .singel-testimonial .testimonial-cont {
            padding-left: 10px !important;
        }
    </style>
@endpush

@php
    $prestasis = \App\Models\Prestasi::orderBy('id', 'desc')->get();
@endphp

<section id="testimonial" class="bg_cover pt-40 pb-50" data-overlay="8">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="section-title">
                    <h5>Prestasi Madrasah</h5>
                </div> <!-- section title -->
            </div>
        </div> <!-- row -->
        <div class="row testimonial-slied mt-40">

            @foreach ($prestasis as $prestasi)
                <div class="col-lg-6">
                    <div class="singel-testimonial">
                        <div class="testimonial-cont">
                            <p class="text-justify">
                                {{ $prestasi->title }}
                            </p>
                        </div>
                    </div> <!-- singel testimonial -->
                </div>
            @endforeach

        </div> <!-- testimonial slied -->
    </div> <!-- container -->
</section>
