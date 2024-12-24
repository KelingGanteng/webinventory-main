<?php
require_once 'middleware.php';
Middleware::handleAuth(); // Cek login sudah termasuk session_start()


// Untuk halaman yang memerlukan role tertentu
// Middleware::handleRole(['admin', 'superadmin']);

$koneksi = new mysqli("localhost", "root", "", "webinventory");
?>

<!-- HTML content tetap sama -->
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Inventory Barang</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.v2.css" rel="stylesheet">


  <!-- Custom styles for this page -->
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <head- Existing meta tags and title -->
    <!- <!-- Favicon -->
      <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
      <link rel="apple-touch-icon" sizes="180x180" href="assets/img/apple-touch-icon.png">
      <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon-32x32.png">
      <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon-16x16.png">


      <!-- Di file header.php atau bagian head -->

      <head>
        <!-- 1. jQuery (Wajib dimuat pertama) -->
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

        <!-- 2. Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- 3. DataTables CSS -->
        <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css" rel="stylesheet">

        <!-- 4. Font Awesome untuk icon -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
      </head>




<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon">
          <img src="Samco.png" alt="Logo" class="logo-image">
          <style>
            /* Styling untuk logo di sidebar */
            .sidebar-brand-icon .logo-image {
              width: 100px;
              /* Ukuran lebar gambar diperbesar */
              height: 50px;
              /* Ukuran tinggi gambar diperbesar */
              object-fit: contain;
              margin-top: 30px;
              /* Memberikan jarak agar gambar sedikit lebih turun */
            }
          </style>
        </div>

      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <?php
      // Menyediakan session untuk superadmin
      if (isset($_SESSION['superadmin'])) {
        $user = $_SESSION['superadmin'];
        $sql = $koneksi->prepare("SELECT * FROM users WHERE id = ?");
        $sql->bind_param("i", $user);
        $sql->execute();
        $data = $sql->get_result()->fetch_assoc();
      }
      ?>

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="?page=home3">
          <i class="fas fa-fw fa-home"></i>
          <span>Home</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Pilih Menu
      </div>
      <!-- Transaksi -->
      <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
          aria-controls="collapsePages">
          <i class="fas fa-fw fa-folder"></i>
          <span>Transaksi</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Menu:</h6>
            <a class="collapse-item" href="?page=barangmasuk">Barang Masuk</a>
            <a class="collapse-item" href="?page=barangkeluar">Barang Keluar</a>
            <a class="collapse-item" href="?page=barangretur">Barang Retur</a>
          </div>
        </div>
      </li>
      
      <!-- Data Master -->
      <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseData" aria-expanded="true"
          aria-controls="collapseData">
          <i class="fas fa-fw fa-folder"></i>
          <span>Data Master</span>
        </a>
        <div id="collapseData" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Menu:</h6>
            <a class="collapse-item" href="?page=gudang">Gudang</a>
            <a class="collapse-item" href="?page=jenisbarang">Jenis Barang</a>
            <a class="collapse-item" href="?page=aset">Asset Management</a>
            <a class="collapse-item" href="?page=satuanbarang">Satuan Barang</a>
            <a class="collapse-item" href="?page=departemen">Departement</a>
            <a class="collapse-item" href="?page=daftarkaryawan">Daftar Karyawan</a>

          </div>
        </div>
      </li>


      <!-- Laporan -->
      <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporan"
          aria-expanded="true" aria-controls="collapseLaporan">
          <i class="fas fa-fw fa-folder"></i>
          <span>Laporan</span>
        </a>
        <div id="collapseLaporan" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Menu Laporan:</h6>
            <a class="collapse-item" href="?page=laporan_barangmasuk">Laporan Barang Masuk</a>
            <a class="collapse-item" href="?page=laporan_gudang">Laporan Stok Gudang</a>
            <a class="collapse-item" href="?page=laporan_barangkeluar">Laporan Barang Keluar</a>
            <a class="collapse-item" href="?page=laporan_barangretur">Laporan Barang Retur</a>
          </div>
        </div>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">


      <style>
        /* Styling untuk Sidebar */
        .sidebar {
          background: linear-gradient(135deg, #ff4d4d, #ff1a1a);
          /* Gradiasi merah dari gelap ke cerah */
          font-size: 16px;
          /* Ukuran font default */
          position: fixed;
          /* Agar sidebar tetap di posisi tetap */
          top: 0;
          left: 0;
          /* Pindahkan sidebar ke kiri */
          height: 100vh;
          /* Menutupi seluruh tinggi layar */
          width: 250px;
          /* Lebar sidebar */
          z-index: 1000;
          /* Menjamin sidebar berada di atas konten */
          transition: transform 0.3s ease-in-out;
          box-shadow: 4px 0 6px rgba(0, 0, 0, 0.1);
          /* Tambahkan bayangan pada sisi kanan sidebar untuk efek kedalaman */
        }

        /* Tombol Toggle Sidebar */
        #sidebarToggle {
          background-color: #ff1a1a;
          /* Warna merah terang untuk tombol toggle */
          color: #fff;
          padding: 10px 15px;
          border-radius: 50%;
          transition: transform 0.3s ease;
          position: absolute;
          /* Menempatkan tombol toggle di posisi yang tetap */
          top: 20px;
          left: 20px;
          /* Tombol berada di kiri atas */
          z-index: 1100;
          /* Pastikan tombol berada di atas sidebar */
        }

        #sidebarToggle:hover {
          transform: rotate(180deg);
          /* Efek rotasi saat tombol sidebar toggle di-hover */
        }

        /* Styling untuk navbar item */
        .sidebar .nav-item {
          margin-bottom: 1rem;
          /* Memberikan jarak antar menu */
        }

        .sidebar .nav-link {
          color: #fff !important;
          /* Warna teks default */
          font-weight: 600;
          /* Mengatur font agar lebih tebal */
          font-size: 16px;
          padding: 10px 20px;
          /* Memberikan padding untuk menu */
          transition: transform 0.3s ease-in-out, background-color 0.3s ease;
          /* Animasi saat hover */
        }

        .sidebar .nav-link:hover {
          background-color: #e60000;
          /* Warna merah lebih gelap saat hover */
          color: #fff;
          transform: scale(1.05);
          /* Efek memperbesar sedikit */
        }

        .sidebar .nav-link i {
          margin-right: 10px;
          /* Jarak antara ikon dan teks */
          font-size: 18px;
          /* Ukuran ikon */
        }

        /* Styling untuk Sub-Menu */
        .collapse-inner .collapse-item {
          padding: 8px 20px;
          font-size: 14px;
          color: #6c757d;
          transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .collapse-inner .collapse-item:hover {
          background-color: #ff4d4d;
          /* Warna merah terang saat hover pada sub-menu */
          color: white;
          /* Warna teks putih saat hover */
          transform: scale(1.05);
          /* Efek membesar saat hover pada sub-menu */
        }

        /* Sidebar aktif */
        .nav-item.active .nav-link {
          background-color: #ff3333 !important;
          /* Warna merah aktif */
          color: white;
        }

        .nav-item.active .nav-link:hover {
          background-color: #e60000 !important;
          /* Warna saat hover pada item aktif */
        }

        /* Animasi pada menu collapse */
        .collapse {
          transition: max-height 0.5s ease-out;
          /* Efek smooth saat membuka menu collapse */
        }

        .collapse-inner {
          background-color: #f8f9fa;
          border-radius: 5px;
        }

        /* Styling untuk Heading Sidebar */
        .sidebar-heading {
          font-size: 16px;
          color: #ccc;
          text-transform: uppercase;
          padding: 10px 20px;
          font-weight: bold;
        }

        /* Divider untuk memisahkan menu */
        .sidebar-divider {
          border-top: 1px solid #e5e5e5;
        }

        /* Agar konten utama tidak tertutup sidebar */
        body {
          margin-left: 250px;
          /* Memberikan ruang di sebelah kiri konten untuk sidebar */
          transition: margin-left 0.3s ease;
          /* Efek transisi saat membuka/menutup sidebar */
        }

        /* Untuk tampilan ketika sidebar tersembunyi (hanya jika dibutuhkan) */
        .sidebar-hidden {
          transform: translateX(-250px);
          /* Menyembunyikan sidebar */
        }

        /* Efek ketika menu di-expand */
        .collapse {
          transition: max-height 0.5s ease-out;
          /* Efek smooth saat membuka menu collapse */
        }


        .collapse-inner {
          background-color: #f8f9fa;
          border-radius: 5px;
        }

        .sidebar-heading {
          font-size: 16px;
          color: #ccc;
          text-transform: uppercase;
          padding: 10px 20px;
          font-weight: bold;
        }

        .sidebar-divider {
          border-top: 1px solid #e5e5e5;
        }

        /* Tombol Toggle Sidebar */
        #sidebarToggle {
          background-color: #ff1a1a;
          /* Warna merah terang untuk tombol toggle */
          color: #fff;
          padding: 10px 15px;
          border-radius: 50%;
          transition: transform 0.3s ease;
        }

        #sidebarToggle:hover {
          transform: rotate(180deg);
          /* Efek rotasi saat tombol sidebar toggle di-hover */
        }
      </style>
    </ul>

    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>



          <ul class="navbar-nav" style="width: 100%; display: flex; justify-content: space-between;">

            <!-- Left Section: Data Master Transaksi Laporan -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <span class="data-master-text">All Item</span>
              </a>
              <!-- Dropdown Menu -->
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="?page=laporan_gudang">Laporan Gudang </a>
                <a class="dropdown-item" href="?page=departemen">Departement</a>
                <a class="dropdown-item" href="?page=gudang">Stok Gudang</a>
              </div>
            </li>

            <!-- Right Section: Welcome & Logout -->
            <li class="nav-item dropdown no-arrow">
              <div class="top-menu">
                <ul class="nav pull-right top-menu">

                  <!-- Personalized Greeting -->
                  <li class="nav-item">
                    <a class="nav-link" href="#">
                      <span class="greeting-text">Welcome, <strong><?php echo $_SESSION['username']; ?></strong>!</span>
                    </a>
                  </li>

                  <!-- Logout Button -->
                  <li>
                    <a onclick="return confirm('Apakah anda yakin akan logout?')" class="btn btn-danger logout-btn"
                      href="logout.php">
                      <i class="fas fa-sign-out-alt"></i> <span class="logout-text">Logout</span>
                    </a>
                  </li>

                  <style>
                    /* Styling untuk Navbar */
                    .navbar {
                      background: linear-gradient(135deg, #ff4d4d, #ff1a1a);
                      /* Gradiasi merah yang konsisten dengan sidebar */
                      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
                      /* Tambahkan sedikit bayangan agar lebih elegan */
                    }

                    .navbar-nav {
                      display: flex;
                      justify-content: space-between;
                      width: 100%;
                    }

                    /* Left Section: Data Master Text Styling */
                    .data-master-text {
                      font-family: 'Nunito', sans-serif;
                      font-size: 22px;
                      color: #fff;
                      /* Warna teks putih agar kontras dengan latar belakang merah */
                      font-weight: 700;
                      margin-right: 25px;
                      cursor: pointer;
                      padding: 5px 15px;
                      border: 2px solid #fff;
                      /* Border putih untuk kontras */
                      border-radius: 5px;
                      transition: border-color 0.3s ease, color 0.3s ease;
                    }

                    .data-master-text:hover {
                      color: #fff;
                      /* Tetap putih saat hover */
                      border-color: #fff;
                      /* Border tetap putih */
                      background-color: #e60000;
                      /* Warna latar belakang saat hover */
                    }

                    /* Dropdown Menu Styling */
                    .dropdown-menu {
                      background-color: #f8f9fa;
                      /* Warna background terang agar menu tetap terbaca */
                      border: 1px solid #ddd;
                      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
                      border-radius: 5px;
                      position: absolute;
                      top: 100%;
                      left: 0;
                      z-index: 1000;
                    }

                    .dropdown-item {
                      padding: 10px 20px;
                      font-size: 16px;
                      color: #333;
                      text-decoration: none;
                      transition: background-color 0.3s ease;
                    }

                    .dropdown-item:hover {
                      background-color: #ff4d4d;
                      /* Sama dengan warna gradiasi pada navbar */
                      color: white;
                    }

                    /* Right Section: Greeting Text */
                    .greeting-text {
                      font-family: 'Nunito', sans-serif;
                      font-size: 18px;
                      color: #fff;
                      /* Warna putih untuk teks sapaan */
                      font-weight: 700;
                      transition: color 0.3s ease;
                      margin-right: 25px;
                    }

                    .greeting-text:hover {
                      color: #34bfa3;
                      /* Warna saat hover */
                      cursor: pointer;
                    }

                    /* Customize the logout button */
                    .logout-btn {
                      display: flex;
                      align-items: center;
                      padding: 10px 20px;
                      font-size: 16px;
                      font-weight: 600;
                      background-color: #ff4d4d;
                      /* Sesuaikan dengan gradiasi navbar */
                      color: white;
                      border-radius: 5px;
                      transition: background-color 0.3s ease, transform 0.3s ease;
                      text-decoration: none;
                      margin-left: 10px;
                    }

                    .logout-btn:hover {
                      background-color: #e60000;
                      /* Merah lebih gelap saat hover */
                      cursor: pointer;
                      transform: scale(1.05);
                    }

                    .logout-text {
                      margin-left: 8px;
                    }

                    .logout-btn i {
                      box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
                      transition: box-shadow 0.3s ease;
                    }

                    .logout-btn i:hover {
                      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.4);
                    }

                    .nav-item {
                      margin: 0;
                    }

                    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@600&display=swap');
                  </style>
                </ul>
              </div>
            </li>

          </ul>







        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <section class="content">


            <?php
            $page = $_GET['page'] ?? '';
            $aksi = $_GET['aksi'] ?? '';


            if ($page == "pengguna") {
              if ($aksi == "") {
                include "page/pengguna/pengguna.php";
              }
              if ($aksi == "tambahpengguna") {
                include "page/pengguna/tambahpengguna.php";
              }
              if ($aksi == "ubahpengguna") {
                include "page/pengguna/ubahpengguna.php";
              }

              if ($aksi == "hapuspengguna") {
                include "page/pengguna/hapuspengguna.php";
              }
            }


            if ($page == "supplier") {
              if ($aksi == "") {
                include "page/supplier/supplier.php";
              }
              if ($aksi == "tambahsupplier") {
                include "page//supplier/tambahsupplier.php";
              }
              if ($aksi == "ubahsupplier") {
                include "page/supplier/ubahsupplier.php";
              }

              if ($aksi == "hapussupplier") {
                include "page/supplier/hapussupplier.php";
              }
            }


            if ($page == "aset") {
              if ($aksi == "") {
                include "page/aset/aset.php";
              } elseif ($aksi == "tambahaset") {
                include "page/aset/tambahaset.php";
              } elseif ($aksi == "return") {
                include "page/aset/return.php";
              } elseif ($aksi == "serahkan") {
                include "page/aset/serahkan.php";
              } elseif ($aksi == "ubahaset") {
                include "page/aset/ubahaset.php";
              } elseif ($aksi == "hapusaset") {
                include "page/aset/hapusaset.php";
              }
            }

            if ($page == "jenisbarang") {
              if ($aksi == "") {
                include "page/jenisbarang/jenisbarang.php";
              } elseif ($aksi == "tambahjenis") {
                include "page/jenisbarang/tambahjenis.php";
              } elseif ($aksi == "ubahjenis") {
                include "page/jenisbarang/ubahjenis.php";
              } elseif ($aksi == "hapusjenis") {
                include "page/jenisbarang/hapusjenis.php";
              }
            }


            if ($page == "satuanbarang") {
              if ($aksi == "") {
                include "page/satuanbarang/satuanbarang.php";
              } elseif ($aksi == "tambahsatuan") {
                include "page/satuanbarang/tambahsatuan.php";
              } elseif ($aksi == "ubahsatuan") {
                include "page/satuanbarang/ubahsatuan.php";
              } elseif ($aksi == "hapussatuan") {
                include "page/satuanbarang/hapussatuan.php";
              }
            }




            if ($page == "barangmasuk") {
              if ($aksi == "") {
                include "page/barangmasuk/barang_masuk.php";
              }
              if ($aksi == "tambahbarangmasuk") {
                include "page/barangmasuk/tambah_barang_masuk.php";
              }
              if ($aksi == "ubahbarangmasuk") {
                include "page/barangmasuk/ubah_barang_masuk.php";
              }

              if ($aksi == "hapusbarangmasuk") {
                include "page/barangmasuk/hapus_barang_masuk.php";
              }
            }

            if ($page == "barangretur") {
              if ($aksi == "") {
                include "page/barangretur/barangretur.php";
              }
              if ($aksi == "tambahbarangretur") {
                include "page/barangretur/tambahbarangretur.php";
              }
              if ($aksi == "ubahbarangretur") {
                include "page/barangretur/ubahbarangretur.php";
              }

              if ($aksi == "hapusbarangretur") {
                include "page/barangretur/hapusbarangretur.php";
              }
            }

            if ($page == "daftarkaryawan") {
              if ($aksi == "") {
                include "page/daftarkaryawan/daftarkaryawan.php";
              }
              if ($aksi == "tambahdaftarkaryawan") {
                include "page/daftarkaryawan/tambahdaftarkaryawan.php";
              }
              if ($aksi == "ubahdaftarkaryawan") {
                include "page/daftarkaryawan/ubahdaftarkaryawan.php";
              }

              if ($aksi == "hapusdaftarkaryawan") {
                include "page/daftarkaryawan/hapusdaftarkaryawan.php";
              }
            }

            if ($page == "departemen") {
              if ($aksi == "") {
                include "page/departemen/departemen.php";
              }
              if ($aksi == "tambahdepartemen") {
                include "page/departemen/tambahdepartemen.php";
              }
              if ($aksi == "ubahdepartemen") {
                include "page/departemen/ubahdepartemen.php";
              }

              if ($aksi == "hapusdepartemen") {
                include "page/departemen/hapusdepartemen.php";
              }
            }


            if ($page == "gudang") {
              if ($aksi == "") {
                include "page/gudang/gudang.php";
              }
              if ($aksi == "tambahgudang") {
                include "page/gudang/tambahgudang.php";
              }
              if ($aksi == "ubahgudang") {
                include "page/gudang/ubahgudang.php";
              }

              if ($aksi == "hapusgudang") {
                include "page/gudang/hapusgudang.php";
              }
            }


            if ($page == "barangkeluar") {
              if ($aksi == "") {
                include "page/barangkeluar/barangkeluar.php";
              }
              if ($aksi == "tambahbarangkeluar") {
                include "page/barangkeluar/tambahbarangkeluar.php";
              }
              if ($aksi == "ubahbarangkeluar") {
                include "page/barangkeluar/ubahbarangkeluar.php";
              }

              if ($aksi == "hapusbarangkeluar") {
                include "page/barangkeluar/hapusbarangkeluar.php";
              }
            }


            if ($page == "laporan_supplier") {
              if ($aksi == "") {
                include "page/laporan/laporan_supplier.php";
              }
            }
            if ($page == "laporan_barangmasuk") {
              if ($aksi == "") {
                include "laporan_barangmasuk.php";
              }
            }

            if ($page == "laporan_gudang") {
              if ($aksi == "") {
                include "laporan_gudang.php";
              }
            }
            if ($page == "laporan_barangkeluar") {
              if ($aksi == "") {
                include "laporan_barangkeluar.php";
              }
            }
            if ($page == "laporan_barangretur") {
              if ($aksi == "") {
                include "laporan_barangretur.php";
              }
            }



            if ($page == "") {
              include "home3.php";
            }
            if ($page == "home3") {
              include "home3.php";
            }
            ?>


          </section>


        </div>
        <!-- End of Main Content -->

        <!-- Footer -->


      </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->
  </div>

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/datatables-demo.js"></script>

  <!--script for this page-->
  <script>
    jQuery(document).ready(function ($) {
      $('#cmb_barang').change(function () { // Jika Select Box id provinsi dipilih
        var tamp = $(this).val(); // Ciptakan variabel provinsi
        $.ajax({
          type: 'POST', // Metode pengiriman data menggunakan POST
          url: 'page/barangmasuk/get_barang.php', // File yang akan memproses data
          data: 'tamp=' + tamp, // Data yang akan dikirim ke file pemroses
          success: function (data) { // Jika berhasil
            $('.tampung').html(data); // Berikan hasil ke id kota
          }


        });
      });
    });
  </script>

  <script>
    jQuery(document).ready(function ($) {
      $('#cmb_barang').change(function () { // Jika Select Box id provinsi dipilih
        var tamp = $(this).val(); // Ciptakan variabel provinsi
        $.ajax({
          type: 'POST', // Metode pengiriman data menggunakan POST
          url: 'page/barangmasuk/get_satuan.php', // File yang akan memproses data
          data: 'tamp=' + tamp, // Data yang akan dikirim ke file pemroses
          success: function (data) { // Jika berhasil
            $('.tampung1').html(data); // Berikan hasil ke id kota
          }


        });
      });
    });
  </script>

  <script type="text/javascript">
    jQuery(document).ready(function ($) {
      $(function () {
        $('#Myform1').submit(function () {
          $.ajax({
            type: 'POST',
            url: 'page/laporan/export_laporan_barangmasuk_excel.php',
            data: $(this).serialize(),
            success: function (data) {
              $(".tampung1").html(data);
              $('.table').DataTable();

            }
          });

          return false;
          e.preventDefault();
        });
      });
    });
  </script>


  <script type="text/javascript">
    jQuery(document).ready(function ($) {
      $(function () {
        $('#Myform2').submit(function () {
          $.ajax({
            type: 'POST',
            url: 'page/laporan/export_laporan_barangkeluar_excel.php',
            data: $(this).serialize(),
            success: function (data) {
              $(".tampung2").html(data);
              $('.table').DataTable();

            }
          });

          return false;
          e.preventDefault();
        });
      });
    });
  </script>




  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.4/dist/sweetalert2.min.css">

  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.4/dist/sweetalert2.min.js"></script>

  <!-- Hapus versi jQuery 3.3.1 dan gunakan versi 3.6.0 dari CDN -->

  <!-- Gunakan Select2 versi terbaru -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


  <script>
    $(document).ready(function () {
      // Inisialisasi Select2 untuk elemen dengan class 'select2'
      $('.select2').select2({
        placeholder: "Pilih",
        allowClear: true
      });
    });

  </script>
</body>

</html>