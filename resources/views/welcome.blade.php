@extends('layouts.front')


@section('content')
    <!--====== SLIDER PART START ======-->

    @include('layouts.frontend.slider-part')

    <!--====== SLIDER PART ENDS ======-->

    <!--====== CATEGORY PART START ======-->

    @include('layouts.frontend.category-part')

    <!--====== CATEGORY PART ENDS ======-->

    <!--====== ABOUT PART START ======-->

    @include('layouts.frontend.about-part')

    <!--====== ABOUT PART ENDS ======-->

    <!--====== APPLY PART START ======-->

    {{--  @include('layouts.frontend.apply-aprt')  --}}

    <!--====== APPLY PART ENDS ======-->

    <!--====== COURSE PART START ======-->

    {{--  @include('layouts.frontend.course-part')  --}}

    <!--====== COURSE PART ENDS ======-->

    <!--====== VIDEO FEATURE PART START ======-->
    @include('layouts.frontend.video-feature')
    <!--====== VIDEO FEATURE PART ENDS ======-->

    <!--====== NEWS PART START ======-->

    @include('layouts.frontend.news-part')

    <!--====== NEWS PART ENDS ======-->

    @include('layouts.frontend.course-part')

    @include('layouts.frontend.testimonial')
@endsection
