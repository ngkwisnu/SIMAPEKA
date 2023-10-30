<?php
function nilaiTersebut($nilai)
{
	if ($nilai >= 8.1 && $nilai <= 10) {
		return 'A';
	} elseif ($nilai >= 7.6 && $nilai <= 8.0) {
		return 'AB';
	} elseif ($nilai >= 6.6 && $nilai <= 7.5) {
		return 'B';
	} elseif ($nilai >= 6.1 && $nilai <= 6.5) {
		return 'BC';
	} elseif ($nilai >= 5.6 && $nilai <= 6.0) {
		return 'C';
	} elseif ($nilai >= 4.1 && $nilai <= 5.5) {
		return 'D';
	} elseif ($nilai < 4.1) {
		return 'E';
	}
}
?>
<div class="callout callout-info">
    <h5><b>Informasi</b></h5>
    <p>Lembar penilaian dapat dicetak melalui halaman Proses PKL!</p>
</div>
<div class="row">
	<div class="col-6">
		<div class="card card-primary">
			<div class="card-header">
				<h3 class="card-title">Nilai Kampus</h3>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
            <i class="fas fa-minus"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <table class="table table-sm">
          <thead>
            <tr>
              <th class="p-1" style="width: 210px;"><b>UNSUR PENILAIAN</b></th>
              <th class="p-1" style="width: px;">
                <center><b>BOBOT (%)</b></center>
              </th>
              <th class="p-1" style="width: 56px;">
                <b>
                  <center>NILAI</center>
                </b>
              </th>
              <th class="p-1" style="width: 125px;">
                <b>
                <center><b>BOBOT x NILAI</b></center>
                </b>
              </th>
            </tr>
          </thead>
          <tbody>
          <?php
            // Menginputkan bobot secara manual
            $bobot_motivasi = 20;
            $bobot_kreativitas = 20;
            $bobot_disiplin = 20;
            $bobot_metode = 40;
            
            if (isset($nilai1)) {
              echo '<tr>';
              echo '<td class="p-1">Motivasi</td>';
              echo '<td class="p-1" align="center">' . $bobot_motivasi . '</td>';
              echo '<td class="p-1" align="center">' . $nilai1->motivasi . '</td>';
              echo '<td class="p-1 text-center">' . ($bobot_motivasi / 100) * $nilai1->motivasi . '</td>';
              echo '</tr>';
              echo '<tr>';
              echo '<td class="p-1">Kreativitas</td>';
              echo '<td class="p-1" align="center">' . $bobot_kreativitas . '</td>';
              echo '<td class="p-1" align="center">' . $nilai1->kreativitas . '</td>';
              echo '<td class="p-1 text-center">' . ($bobot_kreativitas / 100) * $nilai1->kreativitas . '</td>';
              echo '</tr>';
              echo '<tr>';
              echo '<td class="p-1">Disiplin</td>';
              echo '<td class="p-1" align="center">' . $bobot_disiplin . '</td>';
              echo '<td class="p-1" align="center">' . $nilai1->disiplin . '</td>';
              echo '<td class="p-1 text-center">' . ($bobot_disiplin / 100) * $nilai1->disiplin . '</td>';
              echo '</tr>';
              echo '<tr>';
              echo '<td class="p-1">Metode Pembahasan Laporan</td>';
              echo '<td class="p-1" align="center">' . $bobot_metode . '</td>';
              echo '<td class="p-1" align="center">' . $nilai1->metode . '</td>';
              echo '<td class="p-1 text-center">' . ($bobot_metode / 100) * $nilai1->metode . '</td>';
              echo '</tr>';
              echo '<tr>';
              echo '<td class="p-1" colspan="3"><b>TOTAL NILAI</b></td>';
              $total_nilai = ($bobot_motivasi / 100) * $nilai1->motivasi + ($bobot_kreativitas / 100) * $nilai1->kreativitas + ($bobot_disiplin / 100) * $nilai1->disiplin + ($bobot_metode / 100) * $nilai1->metode;
              echo '<td class="p-1 text-center">' . sprintf("%.2f", $total_nilai) . '</td>';
              echo '</tr>';
            } else {
              echo '<td colspan="4"><center><span class="text-muted">Nilai belum tersedia...</span></center></td>';
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-6">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Nilai Industri</h3>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
            <i class="fas fa-minus"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th class="p-1" style="width: 210px;"><b>KOMPONEN PENILAIAN</b></th>
              <th class="p-1" style="width: px;">
                <center><b>BOBOT (%)</b></center>
              </th>
              <th class="p-1" style="width: 56px;">
                <b>
                  <center>NILAI</center>
                </b>
              </th>
              <th class="p-1" style="width: 125px;">
                <b>
                <center><b>BOBOT x NILAI</b></center>
                </b>
              </th>
            </tr>
          </thead>
          <tbody>
          <?php
          $bobot_kemampuan_kerja = 30;
          $bobot_disiplin = 20;
          $bobot_komunikasi = 15;
          $bobot_inisiatif = 15;
          $bobot_kreativitas = 10;
          $bobot_kerjasama = 10;
          
          if (isset($nilai2)) {
              echo '<tr>';
              echo '<td class="p-1">Kemampuan Kerja</td>';
              echo '<td class="p-1" align="center">' . ($bobot_kemampuan_kerja) . '</td>';
              echo '<td class="p-1" align="center">' . $nilai2->kemampuan_kerja . '</td>';
              echo '<td class="p-1 text-center">' . ($bobot_kemampuan_kerja / 100) * $nilai2->kemampuan_kerja . '</td>';
              echo '</tr>';
              echo '<tr>';
              echo '<td class="p-1">Disiplin</td>';
              echo '<td class="p-1" align="center">' . ($bobot_disiplin) . '</td>';
              echo '<td class="p-1" align="center">' . $nilai2->disiplin . '</td>';
              echo '<td class="p-1 text-center">' . ($bobot_disiplin / 100) * $nilai2->disiplin . '</td>';
              echo '</tr>';
              echo '<tr>';
              echo '<td class="p-1">Komunikasi</td>';
              echo '<td class="p-1" align="center">' . ($bobot_komunikasi) . '</td>';
              echo '<td class="p-1" align="center">' . $nilai2->komunikasi . '</td>';
              echo '<td class="p-1 text-center">' . ($bobot_komunikasi / 100) * $nilai2->komunikasi . '</td>';
              echo '</tr>';
              echo '<tr>';
              echo '<td class="p-1">Inisiatif</td>';
              echo '<td class="p-1" align="center">' . ($bobot_inisiatif) . '</td>';
              echo '<td class="p-1" align="center">' . $nilai2->inisiatif . '</td>';
              echo '<td class="p-1 text-center">' . ($bobot_inisiatif / 100) * $nilai2->inisiatif . '</td>';
              echo '</tr>';
              echo '<tr>';
              echo '<td class="p-1">Kreativitas</td>';
              echo '<td class="p-1" align="center">' . ($bobot_kreativitas) . '</td>';
              echo '<td class="p-1" align="center">' . $nilai2->kreativitas . '</td>';
              echo '<td class="p-1 text-center">' . ($bobot_kreativitas / 100) * $nilai2->kreativitas . '</td>';
              echo '</tr>';
              echo '<tr>';
              echo '<td class="p-1">Kerjasama</td>';
              echo '<td class="p-1" align="center">' . ($bobot_kerjasama) . '</td>';
              echo '<td class="p-1" align="center">' . $nilai2->kerjasama . '</td>';
              echo '<td class="p-1 text-center">' . ($bobot_kerjasama / 100) * $nilai2->kerjasama . '</td>';
              echo '</tr>';
              echo '<tr>';
              echo '<td class="p-1" colspan="3"><b>TOTAL NILAI</b></td>';
              $total_nilai = ($bobot_kemampuan_kerja / 100) * $nilai2->kemampuan_kerja + ($bobot_disiplin / 100) * $nilai2->disiplin + ($bobot_komunikasi / 100) * $nilai2->komunikasi + ($bobot_inisiatif / 100) * $nilai2->inisiatif + ($bobot_kreativitas / 100) * $nilai2->kreativitas + ($bobot_kerjasama / 100) * $nilai2->kerjasama;
              echo '<td class="p-1 text-center">' . sprintf("%.2f", $total_nilai) . '</td>';
              echo '</tr>';
            } else {
              echo '<td colspan="4"><center><span class="text-muted">Nilai belum tersedia...</span></center></td>';
            }
          ?>
          </tbody>
        </table>
      </div>
      <!-- /.card-body -->
    </div>
  </div>
</div>
<style>
	table {
		table-layout: fixed;
	}

	td {
		width: 33%;
	}
</style>