<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class JurnalMengajarNotification extends Notification
{
    use Queueable;

    protected $jadwal;

    public function __construct($jadwal)
    {
        $this->jadwal = $jadwal;
    }

    public function via($notifiable)
    {
        return ['database']; // Simpan ke database
    }

    public function toDatabase($notifiable)
    {
        return [
            'jadwal_pelajaran_id' => $this->jadwal->id,
            'message' => "Anda belum membuat jurnal mengajar untuk mata pelajaran {$this->jadwal->mataPelajaran->nama} di kelas {$this->jadwal->rombel->kelas->nama} {$this->jadwal->rombel->nama} pada jam {$this->jadwal->jamPelajaran->jam_ke}.",
        ];
    }
}
