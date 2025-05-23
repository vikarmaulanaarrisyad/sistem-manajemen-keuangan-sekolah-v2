@extends('layouts.app')

@section('title', 'Profile')

@section('subtitle', 'Profile Madrasah')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid" src="{{ Storage::url($sekolah->logo) }}"
                            alt="User profile picture">
                    </div>

                    <h3 class="profile-username text-center">{{ $sekolah->npsn }}</h3>

                    <p class="text-muted text-center">{{ $sekolah->nsm }}</p>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#identitas" data-toggle="tab">Profile
                                Sekolah</a>
                        </li>
                        {{--  <li class="nav-item"><a class="nav-link" href="#sambutan" data-toggle="tab">Sambutan Madrasah</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#sejarah" data-toggle="tab">Sejarah Madrasah</a></li>
                        <li class="nav-item"><a class="nav-link" href="#visi" data-toggle="tab">Visi Madrasah</a></li>
                        <li class="nav-item"><a class="nav-link" href="#misi" data-toggle="tab">Misi Madrasah</a></li>  --}}
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="identitas">
                            @include('pengaturan.sekolah.profile')
                        </div>
                        <div class="tab-pane" id="sambutan">
                            @include('pengaturan.sekolah.sambutan')
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="sejarah">
                            @include('pengaturan.sekolah.sejarah')
                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="visi">
                            @include('pengaturan.sekolah.visi')
                        </div>

                        <div class="tab-pane" id="misi">
                            @include('pengaturan.sekolah.misi')
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
@endsection
