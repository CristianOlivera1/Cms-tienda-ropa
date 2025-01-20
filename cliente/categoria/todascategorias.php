 <?php include "../header.php"; ?>
 <section class="align-items-center-categories">
     <div class="container">
         <div class="row">
             <div class="col-12">
                 <!-- Breamcrumb Content -->
                 <div class="text-categories text-center">
                     <h1 class="text-white text-uppercase mb-3"><strong> NUESTRAS CATEGORIAS </strong></h1>
                     <ol class="breadcrumb d-flex justify-content-center">
                         <li class="breadcrumb-item"><a class="white-70" href="index.html">Inicio</a></li>
                         <li class="breadcrumb-item white-70">Todas las Categorias</li>
                     </ol>
                 </div>
             </div>
         </div>
         <div class="fond-section-categories">
             <img src="../recursos/img/welcome/all-categories.svg" alt="section-all-categorias">
         </div>
     </div>
 </section>

 <section id="service" class="section service-area ptb_150">
     <div class="shape shape-top">
         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none" fill="#FFFFFF">
             <path class="shape-fill" d="M421.9,6.5c22.6-2.5,51.5,0.4,75.5,5.3c23.6,4.9,70.9,23.5,100.5,35.7c75.8,32.2,133.7,44.5,192.6,49.7
                c23.6,2.1,48.7,3.5,103.4-2.5c54.7-6,106.2-25.6,106.2-25.6V0H0v30.3c0,0,72,32.6,158.4,30.5c39.2-0.7,92.8-6.7,134-22.4
                c21.2-8.1,52.2-18.2,79.7-24.2C399.3,7.9,411.6,7.5,421.9,6.5z"></path>
         </svg>
     </div>
     <div class="container">
         <div class="row justify-content-center">
             <div class="col-12 col-md-10 col-lg-7">
                 <div class="section-heading text-center">
                     <h1>Categorias</h1>
                     <p class="d-none d-sm-block mt-4">Ofrecemos una amplia gama de productos de ropa de alta calidad, diseñados para satisfacer las necesidades y gustos de nuestros clientes.</p>
                 </div>
             </div>
         </div>
         <div class="row">
             <?php
                $qs = "SELECT * FROM  categoria ORDER BY catId DESC LIMIT 6";
                $r1 = mysqli_query($con, $qs);

                while ($rod = mysqli_fetch_array($r1)) {
                    $id = "$rod[catId]";
                    $serviceg = "$rod[catNombre]";
                    $service_desc = "$rod[catDescripcion]";
                    $img = "$rod[catImg]";

                    print "
<div class='col-12 col-md-6 col-lg-4 res-margin mb-20px'>
<!-- Servicio Individual -->
<a class='mt-3' href='producto.php?category=$id'>
<div class='single-service color-1 bg-hover bg-white hover-bottom text-center' style='padding:5px 15px 15px'>
    <h3 class='my-2 mt-2'>$serviceg</h3>
    <p>$service_desc</p>
    <img src='../../paneladministrador/recursos/uploads/categoria/$img' alt='img' class='category-img'>
    <a class='service-btn mt-3 mb-1' href='producto.php?category=$id'>Ver modelos</a>
</div>
</a>
</div>
";
                }
                ?>
         </div>
     </div>
     <div class="shape shape-bottom">
         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none" fill="#FFFFFF">
             <path class="shape-fill" d="M421.9,6.5c22.6-2.5,51.5,0.4,75.5,5.3c23.6,4.9,70.9,23.5,100.5,35.7c75.8,32.2,133.7,44.5,192.6,49.7
        c23.6,2.1,48.7,3.5,103.4-2.5c54.7-6,106.2-25.6,106.2-25.6V0H0v30.3c0,0,72,32.6,158.4,30.5c39.2-0.7,92.8-6.7,134-22.4
        c21.2-8.1,52.2-18.2,79.7-24.2C399.3,7.9,411.6,7.5,421.9,6.5z"></path>
         </svg>
     </div>
 </section>

 <section id="review" class="section review-area bg-overlay ptb_100">
     <div class="container bg-danger p-3">
         <div class="row justify-content-center">
             <div class="col-12 col-md-10 col-lg-7">

                 <div class="section-heading text-center">
                     <h2 class="text-white">Ofertas</h2>
                     <p class="text-white d-none d-sm-block mt-4">Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt eaque at excepturi accusamus illo, adipisci, laudantium expedita saepe ad quo nobis maiores pariatur! Sit pariatur repellendus minima reiciendis ullam molestiae?</p>
                 </div>
             </div>
         </div>
         <div class="row">
             <div class="client-reviews owl-carousel">
                 <?php
                    $q = "SELECT * FROM  oferta ORDER BY ofeId DESC LIMIT 6";

                    $r123 = mysqli_query($con, $q);

                    while ($ro = mysqli_fetch_array($r123)) {

                        $name = "$ro[ofeFechaRegis]";
                        $position = "$ro[ofeFechaRegis]";
                        $message = "$ro[ofeFechaRegis]";
                        $ufile = "$ro[ofeFechaRegis]";

                        print "

<div class='single-review p-5'>
<!-- Review Content -->
<div class='review-content'>
    <!-- Review Text -->
    <div class='review-text'>
        <p>$message</p>
    </div>
    <!-- Quotation Icon -->

</div>
<!-- Reviewer -->
<div class='reviewer media mt-3'>
    <!-- Reviewer Thumb -->
    <div class='reviewer-thumb'>
        <img class='avatar-lg radius-100' src='dashboard/uploads/ofertas/$ufile' alt='img'>
    </div>
    <!-- Reviewer Media -->
    <div class='reviewer-meta media-body align-self-center ml-4'>
        <h5 class='reviewer-name color-primary mb-2'>$name</h5>
        <h6 class='text-secondary fw-6'>$position</h6>
    </div>
</div>
</div>
";
                    }
                    ?>
             </div>
         </div>
     </div>
 </section>

 <section class="section cta-area bg-overlay ptb_100">
     <div class="container-fluid bg-dark p-3">
         <div class="row justify-content-center">
             <div class="col-12 col-lg-10">
                 <!-- Encabezado de Sección -->
                 <div class="section-heading text-center m-0">
                     <h2 class="text-white">Otro más</h2>
                     <p class="text-white d-none d-sm-block mt-4">Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptate expedita, quos accusamus ratione unde suscipit at ut porro consectetur dolor, modi tenetur quas nam obcaecati. Architecto maiores consequatur minus eligendi.</p>
                     <a href="contacto.php" class="btn btn-bordered-white mt-4">Contáctanos</a>
                 </div>
             </div>
         </div>
     </div>
 </section>
 <!--====== Área de Llamado a la Acción Fin ======-->

 <?php include "../footer.php"; ?>