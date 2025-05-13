 <section id="about-part" class="pt-65">
     <div class="container">
         <div class="row">
             <div class="col-lg-5">
                 <div class="section-title mt-20">
                     <h5>Sambutan Kepala {{ $sekolah->nama }}</h5>
                     <h3 class="mt-2">Selamat Datang </h3>
                 </div> <!-- section title -->
                 <div class="about-cont">
                     {!! Str::limit($sekolah->sambutan, 400) !!}

                 </div>
                 <a href="#" class="main-btn mt-55" data-toggle="modal" data-target="#sambutanModal">
                     Baca Selengkapnya
                 </a>

             </div>
             <div class="col-lg-6 offset-lg-1">
                 <div class="about-event mt-20">
                     <div class="event-title">
                         <h3>Upcoming events</h3>
                     </div> <!-- event title -->
                     <ul>
                         @if ($events->isNotEmpty())
                             @foreach ($events as $event)
                                 <li>
                                     <div class="singel-event">
                                         <span><i class="fa fa-calendar"></i>
                                             {{ \Carbon\Carbon::parse($event->tanggal)->format('d F Y') }}</span>
                                         <a href="#">
                                             <h4>{{ $event->judul }}</h4>
                                         </a>
                                         <span><i class="fa fa-clock-o"></i>
                                             {{ \Carbon\Carbon::parse($event->waktu_mulai)->format('H:i A') }} -
                                             {{ \Carbon\Carbon::parse($event->waktu_selesai)->format('H:i A') }}
                                         </span>
                                         <span><i class="fa fa-map-marker"></i> {{ $event->lokasi }}</span>
                                     </div>
                                 </li>
                             @endforeach
                         @else
                             <li>Tidak ada event yang tersedia.</li>
                         @endif

                     </ul>
                 </div> <!-- about event -->
             </div>
         </div>
     </div>
     <div class="about-bg">
         <img src="{{ asset('images/Bg-front.png') }}" alt="About">
     </div>

     <!-- Modal -->
     <div class="modal fade" id="sambutanModal" tabindex="-1" aria-labelledby="sambutanModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="sambutanModalLabel">
                         Sambutan Kepala {{ $sekolah->nama }}
                     </h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>
                 <div class="modal-body">
                     <!-- Loading Spinner -->
                     <div id="loadingSpinner" class="text-center">
                         <div class="spinner-border text-primary" role="status">
                             <span class="sr-only">Loading...</span>
                         </div>
                         <p class="mt-2 text-muted">Memuat sambutan...</p>
                     </div>

                     <!-- Sambutan Content -->
                     <div id="sambutanContent" style="display: none;">
                         {!! $sekolah->sambutan !!}
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </section>


 @push('css')
     <style>
         /* Animasi loading */
         .spinner-border {
             width: 3rem;
             height: 3rem;
         }

         /* Efek hover tombol */
         .main-btn {
             transition: all 0.3s ease-in-out;
             padding: 12px 20px;
             border-radius: 8px;
         }

         .main-btn:hover {
             background-color: #0056b3;
             transform: scale(1.05);
         }
     </style>
 @endpush

 @push('scripts')
     <script>
         $('#sambutanModal').on('show.bs.modal', function() {
             // Tampilkan spinner, sembunyikan konten
             $('#loadingSpinner').show();
             $('#sambutanContent').hide();

             // Simulasi loading selama 1.5 detik
             setTimeout(function() {
                 $('#loadingSpinner').fadeOut('fast', function() {
                     $('#sambutanContent').fadeIn('slow');
                 });
             }, 1500);
         });
     </script>
 @endpush
