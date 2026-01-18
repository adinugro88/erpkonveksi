   <div class="page__header mb-0">
       <div class="container page__container">
           <div class="navbar navbar-secondary navbar-light navbar-expand-sm p-0">
               <button class="navbar-toggler navbar-toggler-right" data-toggle="collapse"
                   data-target="#navbarsExample03" type="button">
                   <span class="navbar-toggler-icon"></span>
               </button>

               <div class="navbar-collapse collapse" id="navbarsExample03">
                   <ul class="nav navbar-nav">
                        @if(auth()->user()->isAdmin() || auth()->user()->isOperasional())
                       <li class="nav-item dropdown">
                           <a href="/dashboard" class="nav-link">Dashboard</a>
                       </li>
                       @endif
                       @if(auth()->user()->isAdmin() || auth()->user()->isOperasional())
                       <li class="nav-item dropdown">
                           <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Operasional</a>
                           <div class="dropdown-menu">
                               <div class="dropdown-header">Karyawan</div>
                               <a class="dropdown-item" href="/karyawan"> <i class="fa fa-user-friends"></i> Karyawan</a>
                               <a class="dropdown-item" href="/pekerjaan"> <i class="fa fa-clock"></i> Pekerjaan</a>
                               <a class="dropdown-item" href="/setortarik"><i class="fa fa-shopping-bag"></i> Setor Tarik</a>
                               <div class="dropdown-divider"></div>
                               <div class="dropdown-header">Logistik</div>
                               <a class="dropdown-item" href="/surat-jalan"><i class="fa fa-file-invoice"></i> Surat Jalan</a>
                           </div>
                       </li>

                       @endif
                    @if(auth()->user()->isAdmin())
                       <li class="nav-item dropdown">
                           <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Managerial</a>
                           <div class="dropdown-menu">
                               <div class="dropdown-header">Operasional</div>
                               <a class="dropdown-item" href="#"> <i class="fa fa-globe-americas"></i> Asset</a>
                               <a class="dropdown-item" href="#"> <i class="fa fa-book"></i> Buku Besar</a>
                               <a class="dropdown-item" href="/customers"> <i class="fa fa-user-alt"></i> Customers</a>
                               <div class="dropdown-divider"></div>
                               <div class="dropdown-header">Faktur</div>
                               <a class="dropdown-item" href="/banks"><i class="fa fa-settings"></i>Master Bank</a>
                               <a class="dropdown-item" href="/invoices"><i class="fa fa-file-invoice"></i>Invoice</a>
                               <a class="dropdown-item" href="/gajian"><i class="fa fa-money-bill"></i>Gaji Karyawan</a>
                           </div>
                       </li>
                       @endif

                       @if(auth()->user()->isKaryawan())
                       <li class="nav-item">
                           <a class="nav-link" href="/setortarik">Setor Tarik</a>
                       </li>
                       <li class="nav-item">
                           <a class="nav-link" href="/gajian">Gaji Karyawan</a>
                       </li>
                       @endif

                       @if(auth()->user()->isAdmin())
                       <li class="nav-item dropdown">
                           <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">User Management</a>
                           <div class="dropdown-menu">
                               <div class="dropdown-header">Master</div>
                               <a class="dropdown-item" href="/user-management">Daftar User</a>
                           </div>
                       </li>
                       @endif

                   </ul>
               </div>
           </div>
       </div>
   </div>
