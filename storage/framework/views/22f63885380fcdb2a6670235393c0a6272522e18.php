<?php
header('Set-Cookie: cross-site-cookie=uuid; SameSite=None; Secure');

?>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        
        <?php if($page=='request'): ?>
            <?php if(Session::get('locale')=='nl'): ?> 
                <title>Bevestiging</title>
            <?php else: ?>
                <title>Confirmation</title>
            <?php endif; ?>
        <?php else: ?>
            <?php if(Session::get('locale')=='nl'): ?>
                <title>Overzicht resultaten</title>
            <?php else: ?>
                <title>Aperçu des résultats</title>
            <?php endif; ?>
        <?php endif; ?>

            <meta name="description" content="Exclusieve Kortingen! Direct en zonder Boetes Switchen met Tariefchecker.be. Vergelijk GRATIS de Belgische Energieleveranciers en Bespaar op je Energiefactuur!">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <link rel="stylesheet" href="<?php echo e(url('css/bootstrap.min.css')); ?>">
            <link rel="stylesheet" href="<?php echo e(url('css/style.css')); ?>">
            <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap" rel="stylesheet">
            <!--<script src="accordion.js"></script>-->
            <link rel="stylesheet" type="text/css" href="<?php echo e(url('css/teriefchecker.css')); ?>">
            <link rel="stylesheet" type="text/css" href="<?php echo e(url('css/tcrequest.css')); ?>">
            <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/responsive.css')); ?>">
            <link rel="shortcut icon" type="image/png" href="/images/favicon.ico"/>
            <!--for modal popup-->
            <!--<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
            <script
            src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>

            <!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-TRBHG9');</script>
            <!-- End Google Tag Manager -->
            <!-- Google Tag Manager (noscript) -->
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TRBHG9"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
            <!-- End Google Tag Manager (noscript) -->

    </head>

<body>
    


<div class="tharief-checker">
    <div class="header-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-3 logo-com">
                    <?php if(Session::get('locale')=='nl'): ?>
                <a href="/"><img src="<?php echo e(url('images/tariefchecker goedkoopste energieleveranciers vergelijken 400x200 - retina.png')); ?>" alt="tariefchecker"></a>
                <?php else: ?>
                 <a href="/"><img src="<?php echo e(url('images/veriftarif.png')); ?>" alt="tariefchecker"></a>
                <?php endif; ?>
                </div>
                <div class="col-md-6 start-sec">
                                
                  <span class="arrow-sec arrow-sec-1">
                        <p><a href="https://www.tariefchecker.be/"><img src="<?php echo e(url('images/tick-mark.png')); ?>"></i> <?php echo app('translator')->getFromJson('home.start'); ?></a></p>
                    <div>                          
                         <label for="animation1">
                       <div class="arrow"></div>
                         </label>
                    </div>
                  </span>
                   <?php if($page=='request'): ?>   
                   
                   
                    <span class="arrow-sec arrow-sec-2">
                        <p><a href="<?php echo e(Session::get('actual_link')); ?>"><img src="<?php echo e(url('images/tick-mark.png')); ?>"> <?php echo app('translator')->getFromJson('home.Compare_suppliers'); ?></a></p>
                            <div>                           
                                 <label for="animation1">
                               <div class="arrow"></div>
                                 </label>
                            </div>
                    </span>
                    <?php else: ?>  
                    <?php $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 
                    Session::put('actual_link',$actual_link);
                    ?>
                  
                     <span class="arrow-sec arrow-sec-2">
                        <p><a href="<?php echo e(Session::get('actual_link')); ?>"><span class="two-sec"><img src="<?php echo e(url('images/main2(1).png')); ?>"></span> <?php echo app('translator')->getFromJson('home.Compare_suppliers'); ?></a></p>
                            <div>
                                 <label for="animation1">
                               <div class="arrow-1"></div>
                                 </label>
                            </div>
                    </span>                
                    <?php endif; ?>                 
                    <?php if($page=='request'): ?>
                    <span class="arrow-request arrow-sec">
                         <p><span class="three-sec three-sec-blue"><img src="<?php echo e(url('images/icon3req.png')); ?>"></span><?php echo app('translator')->getFromJson('home.Request'); ?></p>
                    </span>                
                    <?php else: ?>
                     <span class="arrow-request">
                        <p><span class="three-sec"><img src="<?php echo e(url('images/main3(1).png')); ?>"></span> <?php echo app('translator')->getFromJson('home.Request'); ?></p>
                    </span>                
                    <?php endif; ?>                
                </div> 
                <div class="col-md-3 faq-sec">
                    <p>
                        <span class="faq">
                        <?php if(Session::get('locale')=='nl'): ?>
                            <a href="https://www.tariefchecker.be/faq/geen-verbrekingsvergoedingen-meer-voor-particulieren-en-kmo-s" target="_blank"><i class="fas fa-question-circle"></i> FAQs</a>
                        <?php else: ?>
                            <a href="https://www.veriftarif.be/faq-foire-aux-questions/les-particuliers-et-petits-consommateurs-professionnels-ne-paieront-plus-de-frais-de-resiliation-de-leurs-contrats-d-energie" target="_blank"><i class="fas fa-question-circle"></i> FAQs</a>
                        <?php endif; ?>
                        </span>
                        <span class="mail">
                        <?php if(Session::get('locale')=='nl'): ?>
                            <a href="https://www.tariefchecker.be/contact" target="_blank"><i class="fas fa-envelope"></i> <?php echo app('translator')->getFromJson('home.Email'); ?></a>
                        <?php else: ?>
                            <a href="https://www.veriftarif.be/contact" target="_blank"><i class="fas fa-envelope"></i> <?php echo app('translator')->getFromJson('home.Email'); ?></a>
                        <?php endif; ?>
                        </span>
                    </p>
                </div>
        </div>
    </div>
</div>

<!-- This site is converting visitors into subscribers and customers with OptinMonster - https://optinmonster.com :: Campaign Title: WFL - popup - signup - NL --><div id="om-wpaugqy5szc7p3oc9tbt-holder"></div><script>var wpaugqy5szc7p3oc9tbt,wpaugqy5szc7p3oc9tbt_poll=function(){var r=0;return function(n,l){clearInterval(r),r=setInterval(n,l)}}();!function(e,t,n){if(e.getElementById(n)){wpaugqy5szc7p3oc9tbt_poll(function(){if(window['om_loaded']){if(!wpaugqy5szc7p3oc9tbt){wpaugqy5szc7p3oc9tbt=new OptinMonsterApp();return wpaugqy5szc7p3oc9tbt.init({"u":"29001.756505","staging":0,"dev":0,"beta":0});}}},25);return;}var d=false,o=e.createElement(t);o.id=n,o.src="https://a.omappapi.com/app/js/api.min.js",o.async=true,o.onload=o.onreadystatechange=function(){if(!d){if(!this.readyState||this.readyState==="loaded"||this.readyState==="complete"){try{d=om_loaded=true;wpaugqy5szc7p3oc9tbt=new OptinMonsterApp();wpaugqy5szc7p3oc9tbt.init({"u":"29001.756505","staging":0,"dev":0,"beta":0});o.onload=o.onreadystatechange=null;}catch(t){}}}};(document.getElementsByTagName("head")[0]||document.documentElement).appendChild(o)}(document,"script","omapi-script");</script><!-- / OptinMonster -->