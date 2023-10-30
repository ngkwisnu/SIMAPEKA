<?php if ($user->role == 'admin') { ?>
  <li class="nav-header">MENU ADMIN</li>
  <li class="nav-item nav-sidebar nav-child-indent">
    <a href="#" class="nav-link">
      <i class="nav-icon far fa-address-book"></i>
      <p>
        Master Data
        <i class="fas fa-angle-left right"></i>
      </p>
    </a>
    <ul class="nav nav-treeview">
      <li class="nav-item nav-child-indent">
        <a href="#data_pengguna" class="nav-link" onclick="loadPage(this)">
          <i class="nav-icon fas fa-users"></i>
          <p>
            Data Pengguna
          </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="#data_mahasiswa" class="nav-link" onclick="loadPage(this)">
          <i class="nav-icon fas fa-list-alt"></i>
          <p>
            Data Mahasiswa
          </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="#data_pembimbing_industri" class="nav-link" onclick="loadPage(this)">
          <i class="nav-icon fas fa-user-tie"></i>
          <div class="d-inline text-truncate">
            Data Pem. Industri
          </div>
        </a>
      </li>
      <li class="nav-item">
        <a href="#data_pembimbing_kampus" class="nav-link" onclick="loadPage(this)">
          <i class="nav-icon fas fa-user-graduate"></i>
          <div class="d-inline text-truncate">
            Data Pem. Kampus
          </div>
        </a>
      </li>
      <li class="nav-item">
        <a href="#data_industri" class="nav-link" onclick="loadPage(this)">
          <i class="nav-icon fas fa-building"></i>
          <p>
            Data Industri
          </p>
        </a>
      </li>
    </ul>
  </li>
  <li class="nav-item nav-sidebar nav-child-indent">
    <a href="#" class="nav-link">
      <i class="nav-icon fas fa-tasks"></i>
      <p>
        PKL
        <i class="fas fa-angle-left right"></i>
      </p>
    </a>
    <ul class="nav nav-treeview">
      <li class="nav-item">
        <a href="#pengajuan_pkl" class="nav-link" onclick="loadPage(this)">
          <i class="nav-icon far fa-id-card"></i>
          <p>
            Pengajuan PKL
          </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="#pilih_pembimbing" class="nav-link" onclick="loadPage(this)">
          <i class="nav-icon fas fa-user-plus"></i>
          <p>
            Pilih Pembimbing
          </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="#periode_pkl" class="nav-link" onclick="loadPage(this)">
          <i class="nav-icon far fa-calendar-alt"></i>
          <p>
            Periode PKL
          </p>
        </a>
      </li>
    </ul>
  </li>
<?php } else if ($user->role == 'mahasiswa') { ?>
  <li class="nav-header">MENU MAHASISWA</li>
  <li class="nav-item nav-sidebar nav-child-indent">
    <a href="#" class="nav-link">
      <i class="nav-icon fas fa-code-branch"></i>
      <p>
        PKL
        <i class="fas fa-angle-left right"></i>
      </p>
    </a>
    <ul class="nav nav-treeview">
      <?php
      $pkl = $this->m_pkl->get_by_nim($this->mhs->nim);
      if (!$pkl) {
      ?>
        <li class="nav-item nav-child-indent">
          <a href="#daftar_pkl" class="nav-link" onclick="loadPage(this)">
            <i class="nav-icon fas fa-envelope-open-text"></i>
            <p>
              Daftar PKL
            </p>
          </a>
        </li>
      <?php } else { ?>
        <?php
        $tahap = $pkl->tahap;
        ?>
        <li class="nav-item nav-child-indent">
          <a href="#proses_pkl" class="nav-link" onclick="loadPage(this)">
            <i class="nav-icon fas fa-envelope"></i>
            <p>
              Proses PKL
            </p>
          </a>
        </li>
        <?php
        if ($tahap >= 1) {  // nanti ganti jadi $tahap >= 1
        ?>
          <li class="nav-item nav-child-indent">
            <a href="#aktivitas_pkl" class="nav-link" onclick="loadPage(this)">
              <i class="nav-icon far fa-clipboard"></i>
              <p>
                Aktivitas PKL
              </p>
            </a>
          </li>
          <li class="nav-item nav-child-indent">
            <a href="#bimbingan" class="nav-link" onclick="loadPage(this)">
              <i class="nav-icon far fa-file"></i>
              <p>
                Bimbingan PKL
              </p>
            </a>
          </li>
          <li class="nav-item nav-child-indent">
            <a href="#nilai_pkl" class="nav-link" onclick="loadPage(this)">
              <i class="nav-icon far fa-star"></i>
              <p>
                Nilai PKL
              </p>
            </a>
          </li>
        <?php } ?>
      <?php } ?>
    </ul>
  </li>
<?php } else if ($user->role == 'pembimbing_kampus') { ?>
  <li class="nav-header">MENU PKL</li>
  <li class="nav-item nav-sidebar nav-child-indent">
    <a href="#" class="nav-link">
      <i class="nav-icon far fa-address-book"></i>
      <p>
        PKL
        <i class="fas fa-angle-left right"></i>
      </p>
    </a>
    <ul class="nav nav-treeview">
      <li class="nav-item nav-child-indent">
        <a href="#mahasiswa_bimbingan" class="nav-link" onclick="loadPage(this)">
          <i class="fas fa-users"></i>
          <p>
            Mahasiswa Bimbingan
          </p>
        </a>
      </li>
      <li class="nav-item nav-child-indent">
        <a href="#bimbingan_pkl" class="nav-link" onclick="loadPage(this)">
          <i class="nav-icon far fa-file"></i>
          <p>
            Bimbingan PKL
          </p>
        </a>
      </li>
      <li class="nav-item nav-child-indent">
        <a href="#nilai_bimbingan" class="nav-link" onclick="loadPage(this)">
          <i class="nav-icon far fa-star"></i>
          <p>
            Nilai Mahasiswa
          </p>
        </a>
      </li>
    </ul>
  </li>
<?php } else if ($user->role == 'pembimbing_industri') { ?>
  <li class="nav-header">MENU PKL</li>
  <li class="nav-item nav-sidebar nav-child-indent">
    <a href="#" class="nav-link">
      <i class="nav-icon far fa-address-book"></i>
      <p>
        PKL
        <i class="fas fa-angle-left right"></i>
      </p>
    </a>
    <ul class="nav nav-treeview">
      <li class="nav-item nav-child-indent">
        <a href="#mahasiswa_pkl" class="nav-link" onclick="loadPage(this)">
          <i class="fas fa-users"></i>
          <p>
            Mahasiswa PKL
          </p>
        </a>
      </li>
      <li class="nav-item nav-child-indent">
        <a href="#aktivitas_mahasiswa" class="nav-link" onclick="loadPage(this)">
          <i class="nav-icon far fa-clipboard"></i>
          <p>
            Aktivitas Mahasiswa
          </p>
        </a>
      </li>
      <li class="nav-item nav-child-indent">
        <a href="#daftar_nilai" class="nav-link" onclick="loadPage(this)">
          <i class="nav-icon far fa-star"></i>
          <p>
            Nilai Mahasiswa
          </p>
        </a>
      </li>
    </ul>
  </li>
<?php } ?>