@push('css')
    <style>
        .teachers-slider {
            max-width: 100%;
            overflow: hidden;
        }

        .singel-teachers {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s ease-in-out;
        }

        .singel-teachers:hover {
            transform: scale(1.05);
        }

        .singel-teachers .image img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
    </style>
@endpush

<section id="teachers-part" class="pt-70 pb-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="teachers mt-20">
                    <div class="teachers-slider">
                        <div class="singel-teachers text-center">
                            <div class="image">
                                <img src="{{ asset('education') }}/images/teachers/t-1.jpg" alt="Teachers">
                            </div>
                            <div class="cont">
                                <a href="teachers-singel.html">
                                    <h6>Mark Alen</h6>
                                </a>
                                <span>Vice Chancellor</span>
                            </div>
                        </div>
                        <div class="singel-teachers text-center">
                            <div class="image">
                                <img src="{{ asset('education') }}/images/teachers/t-2.jpg" alt="Teachers">
                            </div>
                            <div class="cont">
                                <a href="teachers-singel.html">
                                    <h6>David Card</h6>
                                </a>
                                <span>Pro Chancellor</span>
                            </div>
                        </div>
                        <div class="singel-teachers text-center">
                            <div class="image">
                                <img src="{{ asset('education') }}/images/teachers/t-3.jpg" alt="Teachers">
                            </div>
                            <div class="cont">
                                <a href="teachers-singel.html">
                                    <h6>Rebeka Alig</h6>
                                </a>
                                <span>Pro Chancellor</span>
                            </div>
                        </div>
                        <div class="singel-teachers text-center">
                            <div class="image">
                                <img src="{{ asset('education') }}/images/teachers/t-4.jpg" alt="Teachers">
                            </div>
                            <div class="cont">
                                <a href="teachers-singel.html">
                                    <h6>Hanna Bein</h6>
                                </a>
                                <span>Aerobics Head</span>
                            </div>
                        </div>
                    </div> <!-- teachers-slider -->
                </div> <!-- teachers -->
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.teachers-slider').slick({
                slidesToShow: 3, // Tampilkan 3 item dalam satu baris
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2500,
                dots: true,
                arrows: false,
                centerMode: true, // Agar tampilan lebih modern
                responsive: [{
                        breakpoint: 992, // Tablet
                        settings: {
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 768, // Layar lebih kecil
                        settings: {
                            slidesToShow: 1
                        }
                    }
                ]
            });
        });
    </script>
@endpush
