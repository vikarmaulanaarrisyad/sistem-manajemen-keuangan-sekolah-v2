@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('subtitle', 'Detail Siswa')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Detail Siswa</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#siswa" data-toggle="tab">Data Siswa</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#orangtua" data-toggle="tab">Data Orang Tua</a></li>
                        <li class="nav-item"><a class="nav-link" href="#aktivitasbelajar" data-toggle="tab">Aktivitas
                                Belajar</a>
                        </li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="siswa">
                            @include('admin.siswa.siswa_detail')
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="orangtua">
                            @include('admin.siswa.orangtua')
                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="aktivitasbelajar">
                            @include('admin.siswa.aktivitasbelajar')
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.card-body -->
                <!-- /.card -->
            </div>
        </div>
    </div>
@endsection
