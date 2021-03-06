
  <link rel="stylesheet" href="<?php echo e(url('css/bootstrap.min.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(url('css/style.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(url('css/responsive.css')); ?>">
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <!--<script src="accordion.js"></script>-->
  <link rel="stylesheet" type="text/css" href="<?php echo e(url('css/teriefchecker.css')); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo e(url('css/tcrequest.css')); ?>">

<div class="header-sec mobile-menu">
        <div class="container">
            <div class="row">
                <div class="col-md-12 submenus">
                    <nav class="navbar navbar-expand-lg navbar-light bg-light">
                        <div class="row">
                            <div class="col-md-6 col-9 logo-com">
                                 <?php if(Session::get('locale')=='nl'): ?>
                <img class="desktop" src="https://energievergelijker.tariefchecker.be/images/tariefchecker goedkoopste energieleveranciers vergelijken 400x200 - retina.png" alt="tariefchecker">
        <img class="mobile" src="https://energievergelijker.tariefchecker.be/images/tarifchecker-mob.png" alt="tariefchecker">
                <?php else: ?>
                <img class="desktop" src="https://energievergelijker.tariefchecker.be/images/tariefchecker goedkoopste energieleveranciers vergelijken 400x200 - retina.png" alt="tariefchecker">
        <img class="mobile" src="https://energievergelijker.tariefchecker.be/images/tarifchecker-mob.png" alt="tariefchecker">
                <?php endif; ?> 
                </div>
                            <div class="col-md-6 col-3 text-right responsive-menu">
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".collapse" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                            </div>
                            <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav">
                              <li class="nav-item active">
                                <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                              </li>
                              <li class="nav-item">
                                    <?php if(Session::get('locale')=='nl'): ?>
                            <a class="nav-link" href="https://www.tariefchecker.be/faq/geen-verbrekingsvergoedingen-meer-voor-particulieren-en-kmo-s" target="_blank">FAQs</a>
                        <?php else: ?>
                            <a class="nav-link" href="https://www.veriftarif.be/faq-foire-aux-questions/les-particuliers-et-petits-consommateurs-professionnels-ne-paieront-plus-de-frais-de-resiliation-de-leurs-contrats-d-energie" target="_blank"> FAQs</a>
                        <?php endif; ?>
                              </li>
                              <li class="nav-item">
                            <?php if(Session::get('locale')=='nl'): ?>
                            <a class="nav-link" href="https://www.tariefchecker.be/contact" target="_blank"> <?php echo app('translator')->getFromJson('home.Email'); ?></a>
                        <?php else: ?>
                            <a class="nav-link" href="https://www.veriftarif.be/contact" target="_blank"> <?php echo app('translator')->getFromJson('home.Email'); ?></a>
                        <?php endif; ?>
                              </li>
                            </ul>
                          </div>
                        </div>
                          
                          
                          
                    </nav>
                </div>
            </div>
        </div>
    </div>