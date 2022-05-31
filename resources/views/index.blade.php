<!DOCTYPE html>


<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  @php $locale="nl" @endphp 

    @if( Session::get('locale')=="fr" )
    <title>Nouvelle comparaison</title>
    @else
     <title>start</title>
    @endif
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta name="description" content="Exclusieve Kortingen! Direct en zonder Boetes Switchen met Tariefchecker.be. Vergelijk GRATIS de Belgische Energieleveranciers en Bespaar op je Energiefactuur!">
     <link rel="shortcut icon" type="image/png" href="/images/favicon.ico"/>
            <!--for modal popup-->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="{{url('css/style.css')}}">
    <link rel="stylesheet" href="{{url('css/responsive.css')}}">
	<script
	src="https://code.jquery.com/jquery-3.4.1.min.js"
	integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
	crossorigin="anonymous">
	</script>
	<script>
	Userback = window.Userback || {};
	Userback.access_token = '3783|13144|joO0IGuxtmy9vBXjjARKh5s0WfPB00lw6wOMFeMUVGL4pwzibG';
	(function(id) {
	   var s = document.createElement('script');
	   s.async = 1;s.src = 'https://static.userback.io/widget/v1.js';
	   var parent_node = document.head || document.body;parent_node.appendChild(s);
	})('userback-sdk');
	</script>




<!--End google analytics -->
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

<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

</head>
<body>

  @if(isset($_COOKIE['uuid']))
  @if(Session::get('locale')=='nl') 
    <div class="alert alert-warning alert-dismissible fade show text-center restoreSession" role="alert">
      <span><strong>Welkom terug!</strong> Wil je jouw laatste sessie hernemen?</span>
      <a href="https://{{$_SERVER['HTTP_HOST']}}/overzicht/pack/IMEA-IMEA?&po=2000&u={{$_COOKIE['uuid']}}" type="button" class="btn btn-primary buttonYes">JA</a>
      <a href="{{url('refresh_uuid')}}" type="button" class="btn btn-danger buttonNo">NEE</a>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
  @endif
  @if(Session::get('locale')=='fr') 
    <div class="alert alert-warning alert-dismissible fade show text-center restoreSession" role="alert">
      <span><strong>Re-bonjour!</strong> Voulez-vous retourner à la dernière session?</span>
      <a href="https://{{$_SERVER['HTTP_HOST']}}/overzicht/pack/IMEA-IMEA?&po=2000&u={{$_COOKIE['uuid']}}" type="button" class="btn btn-primary buttonYes">OUI</a>
      <a href="{{url('refresh_uuid')}}" type="button" class="btn btn-danger buttonNo">NON</a>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
  @endif
@endif
    



   <div class="container">
    <div class="main-header-sec">
      <div class="image-sec-outer">
      
      @if(Session::get('locale')=='nl') 
        <img class="desktop" src="https://energievergelijker.tariefchecker.be/images/tariefchecker goedkoopste energieleveranciers vergelijken 400x200 - retina.png" alt="tariefchecker">
        <img class="mobile" src="https://energievergelijker.tariefchecker.be/images/tarifchecker-mob.png" alt="tariefchecker">
        @else
        <img class="desktop" src="https://energievergelijker.tariefchecker.be/images/tariefchecker goedkoopste energieleveranciers vergelijken 400x200 - retina.png" alt="tariefchecker">
        <img class="mobile" src="https://energievergelijker.tariefchecker.be/images/tarifchecker-mob.png" alt="tariefchecker">
        @endif
      </div>
      <div class="float-right-sec">
        <a href="https://www.tariefchecker.be/" @if(Session::get('locale')=='nl') style="color:red" @endif >NL</a>/ <a href="https://www.veriftarif.be/" @if(Session::get('locale')=='fr') style="color:red" @endif >FR</a>
      </div>
    </div>
  </div>
     <div class="front-sec-2">
       <div class="container">  
      <div class="outer-title-sec text-center">
         <div class="header-title">
          @if(Session::get('locale')=='nl')
            <h1>
              Gegarandeerd de goedkoopste energieleverancier
            </h1>
            @else
            <h1>
              Le fournisseur d'énergie le moins chèr - garantie!
            </h1>
          @endif
            
         </div>
         <div class="sub-heading">
          @if(Session::get('locale')=='nl')
           <h3>Toch een goedkopere gezien? Wij betalen jou het verschil dubbel terug!</h3>
           @else
           <h3>Vous avez trouvé une offre moins chère? Veriftarif vous rembourse la différence en double!</h3>
           @endif
         </div>
      </div>
      <div class="outer_sec_left_right">
      <div class="left_content-sec">
        <div class="sub_title_1">
          @if(Session::get('locale')=='nl')
          <p>100% <strong>GRATIS</strong>, onafhankelijk en online</p>
          @else
          <p>100% <strong>GRATUIT</strong>, indépendant et en ligne</p>
          @endif
        </div>
        <form action="consumption-details" class="home_page_form" method="post">
          <div class="outer_sec_form">
              {{ csrf_field()}}
              <div class="form-group">
                <label for="elec" class="form-control_eleck" href="#">&nbsp;</label>
                <input type="checkbox" class="form-control home_page_form_input home_page_form_input_elec" checked="checked" id="elec" value="electricity" placeholder="Enter password" name="elec">
                <span>@if(Session::get('locale')=='nl') Elektriciteit @else Electricité  @endif</span>
            </div>
            <div class="form-group">
              <label for="gas" class="form-control_gas" href="#">&nbsp;</label>
              <input type="checkbox" class="form-control home_page_form_input home_page_form_input_gas" checked="checked" value="gas" id="gas" placeholder="Enter email" name="gas">
              <span >@if(Session::get('locale')=='nl') Gas @else Gaz  @endif</span>
            </div>
            
              
              <div class="form-group">
                <!-- <label for="pwd">@if(Session::get('locale')=='nl') Postcode @else Code postal  @endif</label> -->
                @if(Session::get('locale')=='nl')
                <input type="text" class="form-control po post_code_sec" required="required" id="pwd" placeholder="Postcode" name="po">
                @else
                <input type="text" class="form-control po post_code_sec" required="required" id="pwd" placeholder="Code postal" name="po">
                @endif
              
              </div>
            </div>
              <p class="po-error-msg" style="color:red;display:none;" >@lang('home.invalid_post')</p>
            <!-- <div id="sub-po" >-->
                        
            <!--</div>-->
            <div class="text_and_button">
              <div class="text_left_sec_home">
                @if(Session::get('locale')=='nl')
                <p>Elke maand switchen meer dan 5000 Vlaamse gezinnen. Jij nu ook?</p>
                @else
                <p>Chaque mois, plus de 5000 belges changeant de fournisseur. Maintenant c’est à vous!</p>
                @endif
              </div>
              <div class="button_sec_right_home">
                 @if(Session::has('pin_code_error'))
                    <p style="color:red;"> {{ Session::get('pin_code_error') }}</p>
                  @endif
                  <button type="submit" id="submit1" class="choose">@if(Session::get('locale')=='nl') START NU @else COMPAREZ  @endif</button>
              </div>
            </div>
           
        </form>
        <br/>
          @if (Session::has('message'))

          <div class="alert alert-danger">{{ Session::get('message') }}</div>

          @endif
      </div>
      <div class="right_content_home_form">
          <div class="image_home_sec">
            <img src="https://energievergelijker.tariefchecker.be/images/moskot01.png">
          </div>
      </div>
    </div>





      
   </div>
   <div class="loading_sec_home" style="display: none;">
     <i class="fa fa-spinner fa-spin fa-3x"></i>
   </div>
   </div>
</body>

</htm>
<script type="text/javascript">

  $( ".home_page_form" ).submit(function( event ) {
        $('.loading_sec_home').show();
        
      });
   // $('.radiobtn1-show').hide();
   $('.radiobtn2-show').hide();
   $( document ).ready(function() {
      $('.radiobtn1').on('click', function() {
          $('.radiobtn2-show').hide();
          $('.radiobtn1-show').slideToggle();
      });
      $('.radiobtn2').on('click', function() {
          $('.radiobtn1-show').hide();
          $('.radiobtn2-show').slideToggle();
      });
   });

   $('.home_page_form_input_elec').change(function(){
    var c = this.checked ? '#f00' : '#09f';
    $('.form-control_eleck').toggleClass("e");
});
   $('.home_page_form_input_gas').change(function(){
    var c = this.checked ? '#f00' : '#09f';
    $('.form-control_gas').toggleClass("g");
});
</script>

<style>
.send-button{
background: #54C7C3;
width:100%;
font-weight: 600;
color:#fff;
padding: 8px 25px;
}
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
  margin: 0; 
}
.g-button{
color: #fff !important;
border: 1px solid #EA4335;
background: #ea4335 !important;
width:100%;
font-weight: 600;
color:#fff;
padding: 8px 25px;
}
.my-input{
box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
cursor: text;
padding: 8px 10px;
transition: border .1s linear;
}
.header-title{
margin: 5rem 0;
}
h1{
font-size: 31px;
line-height: 40px;
font-weight: 600;
color:#4c5357;
}
h2{
color: #5e8396;
font-size: 21px;
line-height: 32px;
font-weight: 400;
}
.login-or {
position: relative;
color: #aaa;
margin-top: 10px;
margin-bottom: 10px;
padding-top: 10px;
padding-bottom: 10px;
}
.span-or {
display: block;
position: absolute;
left: 50%;
top: -2px;
margin-left: -25px;
background-color: #fff;
width: 50px;
text-align: center;
}
.hr-or {
height: 1px;
margin-top: 0px !important;
margin-bottom: 0px !important;
}
@media screen and (max-width:480px){
h1{
font-size: 26px;
}
h2{
font-size: 20px;
}
}
</style>


<script type="text/javascript">
  $(document).ready(function(){
  
   if($('#elec').prop("checked") == false){
                $('.form-control_eleck').addClass('e');
            }
   if($('#gas').prop("checked") == false){
                $('.form-control_gas').addClass('g');
  }

 $('.po').keyup(function(){
        
        var po=$(this).val();
        $('.po-error-msg').hide(); 
        $('.po-load').show();
        $.ajax({
            url: '{{url('check-po')}}',
            type: 'GET',
            data: {'po':po},
            success: function(data) {
                
               // console.log(data['available']);
            
          if(data['available']=='false'){
              
            if(po==""){

            $('.po-error-msg').hide(); 

            }else{
              
              $('.po-error-msg').show();
              $('#submit1').prop('disabled', true);
              $('#submit_pr').prop('disabled', true);
            
        }
         $('#sub-po').html('');
          }else{


              if(data['sub']=='true'){
                  
                  //var html_content=data['sub_po'];
                 // alert('true');
                  
                  $('#sub-po').html(data['sub_po']);
                  $('#dropList').addClass('form-control');
                  
              }else{

                 
                  
                  $('#sub-po').html('');
                  
              }
              
             
            $('.po-error-msg').hide();
                 $('#submit1').prop('disabled', false);
               $('#submit_pr').prop('disabled', false);
        
              
               
          }
            
            $('.po-load').hide();
            },
            error: function(e) {

            console.log(e.message);
            }
        });  
        
        
    }); 


     $('.close,.cookie-yes').click(function(){


    $('.alert').hide();
  


    });


  });
</script>
<!-- This site is converting visitors into subscribers and customers with OptinMonster - https://optinmonster.com :: Campaign Title: WFL - popup - signup - NL --><div id="om-wpaugqy5szc7p3oc9tbt-holder"></div><script>var wpaugqy5szc7p3oc9tbt,wpaugqy5szc7p3oc9tbt_poll=function(){var r=0;return function(n,l){clearInterval(r),r=setInterval(n,l)}}();!function(e,t,n){if(e.getElementById(n)){wpaugqy5szc7p3oc9tbt_poll(function(){if(window['om_loaded']){if(!wpaugqy5szc7p3oc9tbt){wpaugqy5szc7p3oc9tbt=new OptinMonsterApp();return wpaugqy5szc7p3oc9tbt.init({"u":"29001.756505","staging":0,"dev":0,"beta":0});}}},25);return;}var d=false,o=e.createElement(t);o.id=n,o.src="https://a.omappapi.com/app/js/api.min.js",o.async=true,o.onload=o.onreadystatechange=function(){if(!d){if(!this.readyState||this.readyState==="loaded"||this.readyState==="complete"){try{d=om_loaded=true;wpaugqy5szc7p3oc9tbt=new OptinMonsterApp();wpaugqy5szc7p3oc9tbt.init({"u":"29001.756505","staging":0,"dev":0,"beta":0});o.onload=o.onreadystatechange=null;}catch(t){}}}};(document.getElementsByTagName("head")[0]||document.documentElement).appendChild(o)}(document,"script","omapi-script");</script><!-- / OptinMonster -->