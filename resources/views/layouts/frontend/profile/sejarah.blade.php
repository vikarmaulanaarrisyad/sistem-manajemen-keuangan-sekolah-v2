@extends('layouts.front')

@section('content')
    <section id="teachers-singel" class="pt-20 pb-120 gray-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="teachers-right mt-20">
                        <ul class="nav nav-justified" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="active" id="sejarah-tab" data-toggle="tab" href="#sejarah" role="tab"
                                    aria-controls="sejarah" aria-selected="true">Sejarah Madrasah</a>
                            </li>
                            <li class="nav-item">
                                <a id="visi-tab" data-toggle="tab" href="#visi" role="tab" aria-controls="visi"
                                    aria-selected="false">Visi Madrasah</a>
                            </li>
                            <li class="nav-item">
                                <a id="misi-tab" data-toggle="tab" href="#misi" role="tab" aria-controls="misi"
                                    aria-selected="false">Misi</a>
                            </li>
                        </ul> <!-- nav -->
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="sejarah" role="tabpanel"
                                aria-labelledby="sejarah-tab">
                                <div class="sejarah-cont">
                                    <div class="singel-sejarah pt-40">
                                        {{--  <h5>Sejarah</h5>  --}}
                                        <p class="text-justify">
                                            {!! $sekolah->sejarah !!}
                                        </p>
                                    </div> <!-- singel sejarah -->
                                </div> <!-- sejarah cont -->
                            </div>
                            <div class="tab-pane fade" id="visi" role="tabpanel" aria-labelledby="visi-tab">
                                <div class="visi-cont pt-20">
                                    {!! $sekolah->visi !!}
                                </div>
                            </div>
                            <div class="tab-pane fade" id="misi" role="tabpanel" aria-labelledby="misi-tab">
                                <div class="misi-cont pt-20">
                                    {!! $sekolah->misi !!}

                                </div> <!-- misi cont -->
                            </div>
                        </div> <!-- tab content -->
                    </div> <!-- teachers right -->
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </section>
@endsection


@push('css')
    <style>
        #teachers-singel {
            min-height: 50vh !important;
            /* Minimal setinggi layar */
            padding-top: 20px;
            padding-bottom: 10px;
        }
    </style>
@endpush
