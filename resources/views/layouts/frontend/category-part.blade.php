 <section id="category-part">
     <div class="container">
         <div class="category pt-40 pb-80">
             <div class="row">
                 <div class="col-lg-4">
                     <div class="category-text pt-40">
                         <h2>Best platform</h2>
                     </div>
                 </div>
                 <div class="col-lg-6 offset-lg-1 col-md-8 offset-md-2 col-sm-8 offset-sm-2 col-8 offset-2">
                     <div class="row category-slied mt-40">
                         @php
                             $platform = \App\Models\Platform::all();
                             $platform = $platform->shuffle(); // Mengacak urutan data
                             $colors = ['color-1', 'color-2', 'color-3']; // Warna yang digunakan
                         @endphp
                         @if ($platform->isEmpty())
                             <div class="col-lg-4">
                                 <a href="#">
                                     <span class="singel-category text-center color-2">
                                         <span class="icon">
                                             <img src="{{ asset('education') }}/images/all-icon/ctg-1.png"
                                                 alt="Icon">
                                         </span>
                                         <span class="cont">
                                             <span>Erkam</span>
                                         </span>
                                     </span> <!-- singel category -->
                                 </a>
                             </div>
                             <div class="col-lg-4">
                                 <a href="#">
                                     <span class="singel-category text-center color-1">
                                         <span class="icon">
                                             <img src="{{ asset('education') }}/images/all-icon/ctg-1.png"
                                                 alt="Icon">
                                         </span>
                                         <span class="cont">
                                             <span>Language</span>
                                         </span>
                                     </span> <!-- singel category -->
                                 </a>
                             </div>
                             <div class="col-lg-4">
                                 <a href="#">
                                     <span class="singel-category text-center color-2">
                                         <span class="icon">
                                             <img src="{{ asset('education') }}/images/all-icon/ctg-2.png"
                                                 alt="Icon">
                                         </span>
                                         <span class="cont">
                                             <span>Business</span>
                                         </span>
                                     </span> <!-- singel category -->
                                 </a>
                             </div>
                             <div class="col-lg-4">
                                 <a href="#">
                                     <span class="singel-category text-center color-3">
                                         <span class="icon">
                                             <img src="{{ asset('education') }}/images/all-icon/ctg-3.png"
                                                 alt="Icon">
                                         </span>
                                         <span class="cont">
                                             <span>Literature</span>
                                         </span>
                                     </span> <!-- singel category -->
                                 </a>
                             </div>
                         @else
                             @foreach ($platform as $key => $item)
                                 <div class="col-lg-4">
                                     <a href="{{ $item->url }}">
                                         <span class="singel-category text-center {{ $colors[$key % 3] }}">
                                             <span class="icon">
                                                 <img src="{{ Storage::url($item->gambar ?? '') }}" alt="Icon"
                                                     style="width: 82px; height: 82px;">
                                             </span>
                                             <span class="cont">
                                                 <span>{{ $item->nama }}</span>
                                             </span>
                                         </span> <!-- singel category -->
                                     </a>
                                 </div>
                             @endforeach
                         @endif
                     </div>
                 </div>
             </div> <!-- row -->
         </div> <!-- category -->
     </div> <!-- container -->
 </section>
