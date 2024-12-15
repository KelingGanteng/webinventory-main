<br>

<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <div id="jam-digital">

      </div>
    </div>

    <!-- Script JavaScript tetap sama seperti sebelumnya -->
    <script>
      function updateJam() {
        var now = new Date();
        var hours = now.getHours().toString().padStart(2, '11');
        var minutes = now.getMinutes().toString().padStart(2, '43');
        var seconds = now.getSeconds().toString().padStart(2, '0');

        var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        var day = days[now.getDay()];
        var date = now.getDate().toString().padStart(2, '0');
        var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        var month = months[now.getMonth()];
        var year = now.getFullYear();

        document.getElementById('jam-digital').innerHTML =
          day + ', ' + date + ' ' + month + ' ' + year + '<br>' +
          hours + ':' + minutes + ':' + seconds;
      }

      setInterval(updateJam, 1000);
      updateJam();

    </script>

  </div>
  <div class="wrapper">
    <svg>
      <text x="50%" y="50%" dy=".35em" text-anchor="middle">
        Selamat Datang Di Inventory IT Samco Farma
      </text>
    </svg>

  </div>
  <br></br>
  <!-- Content Row -->
  <div class="row">







    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <a href="?page=jenisbarang">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                  <h4>Jenis Barang</h4>
                </div>
                <div class="row no-gutters align-items-center">
                  <div class="col-auto">

                  </div>
                  <div class="col">

                  </div>
                </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-clipboard-list fa-2x text-black-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <a href="?page=gudang">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                  <h4>Data Gudang</h4>
                </div>
                <div class="row no-gutters align-items-center">
                  <div class="col-auto">

                  </div>
                  <div class="col">

                  </div>
                </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-clipboard-list fa-2x text-black-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <a href="?page=barangmasuk">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                  <h4>Barang Masuk</h4>
                </div>

            </div>
            <div class="col-auto">
              <i class="fas fa-dollar-sign fa-2x text-black-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <a href="?page=barangkeluar">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                  <h4>Barang Keluar</h4>
                </div>

            </div>
            <div class="col-auto">
              <i class="fas fa-comments fa-2x text-black-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!DOCTYPE html>
    <html>

    <head>
      <meta charset="UTF-8" />
      <meta content="width=device-width, initial-scale=1, user-scalable=1, minimum scale=1, maximum-scale=5"
        name="viewport" />
      <link rel="stylesheet" href="sb-admin-2.min.v2.css">

      <title> Jam Digital</title>
    </head>

    <body>





  </div>