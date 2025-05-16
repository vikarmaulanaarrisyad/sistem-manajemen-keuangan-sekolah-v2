@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Total Setor</h5>
                    <h3>Rp{{ number_format($totalSetorSiswa, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5>Total Tarik</h5>
                    <h3>Rp{{ number_format($totalTarikSiswa, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Saldo Akhir</h5>
                    <h3>Rp{{ number_format($saldoSiswa, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>
@endsection
