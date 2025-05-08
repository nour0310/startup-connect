<?php
require_once '../../Model/Investissement.php';

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $inv = Investissement::searchInv($_GET['search']);
} else {
    $inv = Investissement::getAllInv();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Startup - Startup Website Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="../../img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../../lib/animate/animate.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../../css/style.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="../../css/contratListStyle.css" rel="stylesheet">
</head>

<body>


    <!-- Topbar Start -->
    <div class="container-fluid bg-dark px-5 d-none d-lg-block">
        <div class="row gx-0">
            <div class="col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <small class="me-3 text-light"><i class="fa fa-map-marker-alt me-2"></i>123 Rue Tunis, Tunisie, TN</small>
                    <small class="me-3 text-light"><i class="fa fa-phone-alt me-2"></i>+216 29 999 999</small>
                    <small class="text-light"><i class="fa fa-envelope-open me-2"></i>startupconnect@gmail.com</small>
                </div>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

   
        <!-- <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0" style="
    background-color: #06A3DA;"> -->
           
           
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0"style= "background-color: #06A3DA;">
            <a href="../../index.html" class="navbar-brand p-0">
                <h1 class="m-0"><i class="fa fa-user-tie me-2"></i>StartupUp Connect</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="../../index.html" class="nav-item nav-link">Acceuil</a>
                    <a href="startupList.html" class="nav-item nav-link">Startup</a>
                    <a href="ListContratFront.php" class="nav-item nav-link">Mes contrat</a>
                    <a href="ListInvestissementFront.php" class="nav-item nav-link">Mes investissement</a>
                    <div class="nav-item dropdown" style="color: white;">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                        <div class="dropdown-menu m-0">
                            <a href="../BackOffice/dashboard.html" class="dropdown-item">Dashboard</a>
                            <a href="#" class="dropdown-item">Gestion utilisateurs</a>
                            <a href="#" class="dropdown-item">Gestion profiles</a>
                            <a href="#" class="dropdown-item">Gestion startup</a>
                            <a href="#" class="dropdown-item">Gestion evénements</a>
                            <a href="../BackOffice/ListContratBack.php" class="dropdown-item">Gestion des contrats</a>
                            <a href="../BackOffice/ListInvestissementBack.php" class="dropdown-item">Gestion des investissements</a>
                            <a href="#" class="dropdown-item">Gestion documents</a>
                            <a href="../BackOffice/logList.php" class="dropdown-item">Historique</a>
                        </div>
                    </div>
                    <a href="../FrontOffice/login.html" class="nav-item nav-link">Connexion</a>
                </div>
            </div>
        </nav>
    </div>
        
<div class="container py-5" style="margin-top: 100px !important; ">
    

    <div class="event-schedule-area-two bg-color pad100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class=" text-center">
                        <div class="title-text">
                            <h2>Mes investissement </h2>
                        </div>
                        <p>
                            Ce tableau présente la totalité des investissements effectué par vous.
                        </p>
                    </div>
                </div>
                <!-- /.col end-->
            </div>
            <!-- row end-->
            <form method="GET" action="">
    <div class="row"  style="margin-bottom: 30px;">
      <div class="col-md-4"> 
        <input type="text" name="search" class="form-control" placeholder="Taper pour Chercher ..." 
        value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
      </div>
     
      <div class="col-md-4">
        <button class="btn btn-secondary" style="border-radius: 30px;">Chercher</button>
       
      </div>
     
    </div>
    </form>
            <div class="row">
                <div class="col-lg-12">
                   
                    <div class="tab-content" id="myTabContent">
                        <div class="" id="home" role="">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                          <th scope="col">Id</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Contrat Id</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">devise</th>
                                            <th scope="col">montant</th>
                                            <th scope="col">Actions</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($inv as $c): ?>
                                        <tr class="inner-box">
                                        <td>
                                                <div class="event-img">
                                                    <p><?= $c['id'] ?></p>
                                                </div>
                                            </td>
                                            <td scope="row">
                                                <div class="event-date">              
                                                    <p><?= $c['date_inv'] ?></p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="event-img">
                                                    <p><?= $c['type_paiement'] ?></p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="event-img">
                                                    <p><?= $c['contratid'] ?></p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="event-img">
                                                    <p><?= $c['status_inv'] ?></p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="event-img">
                                                    <p><?= $c['devise'] ?></p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="event-img">
                                                    <p><?= $c['montant'] ?></p>
                                                </div>
                                            </td>
                                            
                                           
                                            <td>
                                            
                                          
                                            <?php if ($c['status_inv'] === 'en attente'): ?>
                                                
                                                <a href="modifierInvestissement.php?id=<?= $c['id'] ?>">
                                                 <button class="btn btn-primary" title="Modifier cet investissement">
                                                  <i class="fa fa-share-square"></i>
                                               </button>
                                            </a>
                                            <a href="../../Controller/InvestissementController.php?supprimer=<?= $c['id'] ?>">
                                                  <button class="btn btn-danger" title="Supprimer cet investissement"> 
                                                    <i class="fa fa-trash"></i></button>
                                                  </a>
                                                  <?php elseif ($c['status_inv'] === 'valider'): ?>
                                                    <a href="generateInvPDF.php?id=<?= $c['id'] ?>" target="_blank">
                                               
                                               <button class="btn btn-secondary" title="Imprimer cet investissement">
                                                <i class="fa fa-print" ></i>
                                               </button>
                                               </a>
                                                  <?php endif; ?>   
                                            
                                            
                                                  
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                     
                    </div>
                   
                </div>
                <!-- /col end-->
            </div>
            <!-- /row end-->
        </div>
    </div>


</div>
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../lib/wow/wow.min.js"></script>
    <script src="../../lib/easing/easing.min.js"></script>
    <script src="../../lib/waypoints/waypoints.min.js"></script>
    <script src="../../lib/counterup/counterup.min.js"></script>
    <script src="../../lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="../../js/main.js"></script>


</body>