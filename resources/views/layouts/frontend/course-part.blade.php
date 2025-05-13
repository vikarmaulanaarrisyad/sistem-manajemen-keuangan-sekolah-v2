@php
    $albums = App\Models\Album::all();
@endphp

@push('css')
    <style>
        /* Styling for the gallery section */
        #course-part {
            background-color: #f5f5f5;
            padding: 80px 0;
        }

        .section-title h5 {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        /* Layout for gallery items */
        .course-slied {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .singel-course {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
            max-width: 320px;
            height: 100%;
            /* Menyesuaikan tinggi card agar seragam */
            display: flex;
            flex-direction: column;
        }

        .singel-course:hover {
            transform: scale(1.05);
        }

        /* Styling untuk gambar agar sesuai ukuran card */
        .singel-course .thum {
            width: 100%;
            height: 220px;
            /* Menentukan tinggi tetap untuk gambar */
            overflow: hidden;
        }

        .singel-course .image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Memastikan gambar memenuhi frame tanpa distorsi */
            border-radius: 10px 10px 0 0;
            transition: transform 0.3s ease-in-out;
        }

        .singel-course:hover .image img {
            transform: scale(1.1);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .course-slied {
                flex-direction: column;
                align-items: center;
            }

            .singel-course {
                max-width: 90%;
            }
        }
    </style>
@endpush
@if ($albums->isNotEmpty())
    <section id="course-part" class="pt-80 pb-80 gray-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="section-title pb-20">
                        <h5>Galeri Foto</h5>
                    </div> <!-- section title -->
                </div>
            </div> <!-- row -->
            <div class="row course-slied mt-30">
                @foreach ($albums as $album)
                    <div class="col-lg-4">
                        <div class="singel-course">
                            {{--  <div class="thum">  --}}
                            <div class="image">
                                <img src="{{ Storage::url($album->foto) }}" alt="Course"
                                    style="width: 100%; height: 100%;">
                            </div>
                            {{--  </div>  --}}
                        </div>
                    </div>
                @endforeach
            </div> <!-- course slied -->
        </div> <!-- container -->
    </section>
@endif
