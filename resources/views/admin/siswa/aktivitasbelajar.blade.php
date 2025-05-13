<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>Tahun Pelajaran</th>
            <th>Tanggal Mulai Masuk</th>
            <th>Tingkat / Kelompok</th>
            <th>Rombel</th>
            <th>Status Keaktifan</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($siswa->siswa_rombel->sortByDesc('id') as $rombel)
            <tr>
                <td>
                    {{ optional($rombel->tahun_pelajaran)->nama ?? '-' }}
                    {{ optional($rombel->tahun_pelajaran->semester)->nama ?? '-' }}
                </td>
                <td>{{ $rombel->pivot->tanggal_masuk ? \Carbon\Carbon::parse($rombel->pivot->tanggal_masuk)->format('d M Y') : '-' }}
                </td>
                <td>{{ optional($rombel->kelas)->nama ?? '-' }}</td>
                <td>{{ $rombel->nama ?? '-' }}</td>
                <td>
                    @if (in_array($rombel->pivot->keterangan ?? '', ['Siswa Baru', 'Naik dari Kelas Sebelumnya']))
                        <span class="badge bg-success">Aktif</span>
                    @elseif ($rombel->pivot->keterangan == 'Mutasi')
                        <span class="badge bg-danger">Mutasi</span>
                    @else
                        <span class="badge bg-warning">Lulus</span>
                    @endif
                </td>
                <td>{{ $rombel->pivot->keterangan ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">Belum ada riwayat aktivitas belajar.</td>
            </tr>
        @endforelse
    </tbody>
</table>
