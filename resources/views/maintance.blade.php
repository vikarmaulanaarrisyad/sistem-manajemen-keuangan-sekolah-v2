<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMK Presiden</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .header-top {
            background: linear-gradient(to right, #003366, #00a67e);
            color: white;
            padding: 5px 0;
            font-size: 14px;
        }

        .header-main {
            background: linear-gradient(to right, #003366, #00a67e);
            color: white;
            padding: 15px 0;
        }

        .navbar-custom {
            background: white;
            padding: 10px 0;
        }

        .navbar-custom .nav-link {
            color: black;
            font-weight: bold;
        }

        .navbar-custom .nav-link:hover {
            color: #00a67e;
        }
    </style>
</head>

<body>
    <!-- Header Top -->
    <div class="header-top">
        <span><strong>Terbaru:</strong> Aktivitas Seru Siswa Mengenal Macam-</span>
    </div>

    <!-- Header Main -->
    <div class="header-main">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="logo d-flex align-items-center">
                <img src="logo.png" alt="Logo SMK Presiden" width="50" class="me-2">
                <h4 class="m-0">SMK PRESIDEN<br><small>ENTREPRENEUR INDONESIA SRAGEN</small></h4>
            </div>
            <div class="contact-info text-end">
                <p class="m-0"><i class="fas fa-phone"></i> 0271-8856311</p>
                <p class="m-0"><i class="fas fa-envelope"></i> info@smkpresiden.sch.id</p>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link active" href="#">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Berita Terbaru</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Prestasi</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Kegiatan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">PPDB</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Artikel</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Karya Siswa</a></li>
                </ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search...">
                    <button class="btn btn-outline-success" type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
