<div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ url('/') }}/image/polinema-bw.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="{{ url('/profile')}}" class="d-block">Sikompen</a>
        </div>
      </div>
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link {{ $activeMenu == 'dashboard' ? 'active' : '' }} ">
                    <i class="nav-icon fa-solid fa-house-chimney"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link {{ ($activeMenu == 'mahasiswa' || $activeMenu == 'personil_akademik' || $activeMenu == 'level') ? 'active' : '' }} ">
                    <i class="nav-icon fa-solid fa-user"></i>
                    <p>
                      Manajemen User
                      <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/mahasiswa') }}" class="nav-link {{ $activeMenu == 'mahasiswa' ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Data Mahasiswa</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/personil_akademik') }}" class="nav-link {{ $activeMenu == 'personil_akademik' ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Data Personil Akademik</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/level') }}" class="nav-link {{ $activeMenu == 'level' ? 'active' : '' }} ">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Level User</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="{{ url('/kompetensi') }}" class="nav-link {{ $activeMenu == 'kompetensi' ? 'active' : '' }} ">
                    <i class="nav-icon fa-solid fa-list-check"></i>
                    <p>Daftar Kompetensi</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/mahasiswa_alpha') }}" class="nav-link {{ $activeMenu == 'mahasiswa_alpha' ? 'active' : '' }} ">
                    <i class="nav-icon fa-solid fa-users"></i>
                    <p>Data Mahasiswa Alpha</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/mahasiswa_kompen') }}" class="nav-link {{ $activeMenu == 'mahasiswa_kompen' ? 'active' : '' }} ">
                    <i class="nav-icon fa-regular fa-rectangle-list"></i>
                    <p>Data Mahasiswa Kompen</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/tugas_kompen') }}" class="nav-link {{ $activeMenu == 'tugas_kompen' ? 'active' : '' }} ">
                    <i class="nav-icon fa-solid fa-file-pen"></i>
                    <p>Data Tugas Kompen</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/kompen_selesai') }}" class="nav-link {{ $activeMenu == 'kompen_selesai' ? 'active' : '' }} ">
                    <i class="nav-icon fa-solid fa-file-lines"></i>
                    <p>Status Kompen</p>
                </a>
            </li>
        </ul>
    </nav>
</div>
