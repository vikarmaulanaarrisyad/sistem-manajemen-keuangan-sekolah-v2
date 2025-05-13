   <div class="appBottomMenu">
       <a href="{{ route('dashboard') }}" class="item {{ request()->is('dashboard') ? 'active' : '' }}">
           <div class="col">
               <ion-icon name="home-outline" role="img" class="md hydrated"></ion-icon>
               <strong>Home</strong>
           </div>
       </a>
       <a href="" class="item {{ request()->is('presensi/siswa') ? 'active' : '' }}">
           <div class="col">
               <ion-icon name="finger-print-outline" role="img" class="md hydrated"
                   aria-label="finger-print outline"></ion-icon>
               <strong>Absen Siswa</strong>
           </div>
       </a>
       <a href="" class="item">
           <div class="col">
               <div class="action-button large">
                   <ion-icon name="camera" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
               </div>
           </div>
       </a>
       <a href="" class="item {{ request()->is('jurnal') ? 'active' : '' }}">
           <div class="col">
               <ion-icon name="document-text-outline" role="img" class="md hydrated"
                   aria-label="document text outline"></ion-icon>
               <strong>Journal</strong>
           </div>
       </a>
       <a href="javascript:;" class="item">
           <div class="col">
               <ion-icon name="people-outline" role="img" class="md hydrated"
                   aria-label="people outline"></ion-icon>
               <strong>Profile</strong>
           </div>
       </a>
   </div>
