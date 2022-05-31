$(document).ready(function(){
    
    
   var api_comparison_type= $('#api_comparison_type').val();
   var api_category_type= $('#api_category_type').val();
   var api_locale= $('#api_locale').val();
   var api_postal_code= $('#api_postal_code').val();
   var api_usage_single= $('#api_usage_single').val();
   var api_usage_gas= $('#api_usage_gas').val();
   var api_only_energy_cost= $('#api_only_energy_cost').val();
   var api_promo= $('#api_promo').val();
// var field = element.getAttribute("data-ps-field");
   var elements = document.querySelectorAll('[data-ps-product="'+ "Groene" +'"]');
   var analytic_id =$('#analytic_id').val();
  
//  elements.attr('src','asassas');
//  var field = elements.getAttribute("data-ps-field");
//  elements[1].innerHTML = "Hello World!"; 
//  alert(elements[1]);
   
   
     $.ajax({
            url: 'https://api.tariefchecker.be/productset',
            type: 'GET',
            data: {
                'api_comparison_type':api_comparison_type,
                'api_locale':api_locale,
                'api_postal_code':api_postal_code,
                'api_usage_single':api_usage_single,
                'api_usage_gas':api_usage_gas,
                'api_only_energy_cost':api_only_energy_cost,
                'api_promo':api_promo,
                'api_category_type':api_category_type,
                'analytic_id':analytic_id
            },
            success: function(data) {
                
                //console.log(data);
                
                var Groene=data['Groene'];
                var elements = document.querySelectorAll('[data-ps-product="'+ "Groene" +'"]');
                elements[1].innerHTML = Groene.name;  
                elements[0].src = Groene.supplier_logo;
                elements[2].innerHTML = Groene.cheapest_price;  
                elements[3].href = Groene.product_subscribe_url;
                elements[4].src = Groene.supplier_logo;
                elements[6].innerHTML = Groene.name;
                elements[5].href = Groene.product_subscribe_url;
                elements[7].innerHTML = Groene.product_contract_duration;
                elements[8].innerHTML = Groene.product_pricing_type;
                elements[9].innerHTML = Groene.service_text;
                elements[10].innerHTML = Groene.price_promo_year;
                elements[11].innerHTML = Groene.name;
                elements[12].innerHTML = Groene.name;
                elements[12].href = Groene.product_subscribe_url;
                elements[13].innerHTML = Groene.product_name;
                elements[14].innerHTML = Groene.cheapest_price;
                
                
                
                
                var jrvast=data['1jrvast'];
                var elements1 = document.querySelectorAll('[data-ps-product="'+ "1jrvast" +'"]');
                elements1[1].innerHTML = jrvast.name;  
                elements1[0].src = jrvast.supplier_logo;
                elements1[2].innerHTML = jrvast.cheapest_price;
                elements1[3].href = jrvast.product_subscribe_url;
                elements1[4].src = jrvast.supplier_logo;
                elements1[4].innerHTML = jrvast.name; 
                elements1[6].innerHTML = jrvast.price_promo_year;
                elements1[5].innerHTML = jrvast.price_end_month;
                elements1[7].src = jrvast.supplier_logo;
                elements1[8].href = jrvast.price_end_month;
                elements1[9].innerHTML = jrvast.name;
                elements1[10].innerHTML = jrvast.product_contract_duration;
                elements1[11].innerHTML = jrvast.product_pricing_type;
                //elements1[11].href = jrvast.product_subscribe_url;
                elements1[12].innerHTML = jrvast.service_text;
                elements1[13].innerHTML = jrvast.price_promo_year;
                elements1[14].href = jrvast.product_subscribe_url;
                elements1[14].innerHTML = jrvast.name;
                
                elements1[16].innerHTML = jrvast.cheapest_price;//delta
                
                 
                 
                
                var Goedkoopste=data['Goedkoopste'];
                var elements2 = document.querySelectorAll('[data-ps-product="'+ "Goedkoopste" +'"]');
                elements2[1].innerHTML = Goedkoopste.name;  
                elements2[0].src = Goedkoopste.supplier_logo;
                elements2[2].innerHTML = Goedkoopste.price_promo_year;  
                elements2[3].href = Goedkoopste.product_subscribe_url;
                elements2[4].innerHTML = Goedkoopste.price_end_month;
                elements2[6].innerHTML = Goedkoopste.price_incl_promo_year;
                elements2[5].innerHTML = Goedkoopste.supplier_name;
                elements2[7].src = Goedkoopste.supplier_logo;
                elements2[8].href = Goedkoopste.product_subscribe_url;
                elements2[9].innerHTML = Goedkoopste.name;
                elements2[10].innerHTML = Goedkoopste.product_contract_duration;
                elements2[11].innerHTML = Goedkoopste.product_pricing_type;
                elements2[11].href = Goedkoopste.product_subscribe_url;
                elements2[12].innerHTML = Goedkoopste.service_text;
                elements2[13].innerHTML = Goedkoopste.price_promo_year;
                elements2[14].innerHTML = Goedkoopste.supplier_name;
                
                elements2[15].innerHTML = Goedkoopste.price_promo_year;
                elements2[16].innerHTML = Goedkoopste.product_name;
                elements2[17].innerHTML = Goedkoopste.price_end_month;
                elements2[18].innerHTML = Goedkoopste.name;
                elements2[18].href = Goedkoopste.product_subscribe_url;
                elements2[19].innerHTML = Goedkoopste.name;
                
                elements2[20].href = Goedkoopste.product_subscribe_url;
                elements2[21].innerHTML = Goedkoopste.name;
                
                
              
               
                
                
                
                var jrvast3=data['3jrvast'];
               
                var elements3 = document.querySelectorAll('[data-ps-product="'+ "3jrvast" +'"]');
                elements3[1].src = jrvast3.supplier_logo;  
                elements3[0].innerHTML = jrvast3.price_end_month+" "+"2022";
                elements3[2].innerHTML = jrvast3.name;  
                elements3[3].innerHTML = jrvast3.cheapest_price;  
                elements3[4].href = jrvast3.product_subscribe_url;
                elements3[6].innerHTML = jrvast3.product_name;
                elements3[5].innerHTML = jrvast3.price_end_month+" "+"2022";
                elements3[7].innerHTML = "";
                elements3[8].src = jrvast3.supplier_logo;
                elements3[9].href = jrvast3.product_subscribe_url;
                elements3[10].innerHTML = jrvast3.name;
                elements3[11].innerHTML = "3";
                
                elements3[12].innerHTML = jrvast3.product_pricing_type;
                elements3[13].innerHTML = jrvast3.service_text;
                elements3[14].innerHTML = jrvast3.price_promo_year;
                elements3[15].innerHTML = jrvast3.price_end_month+" "+2022;
                elements3[16].innerHTML = jrvast3.supplier_name;
                elements3[17].href = jrvast3.product_subscribe_url;
                elements3[17].innerHTML = jrvast3.name;
                
                elements3[18].innerHTML = jrvast3.supplier_name;
                elements3[19].innerHTML = jrvast3.cheapest_price;
                elements3[20].innerHTML = jrvast3.price_end_month+" "+2022;
                elements3[21].href = jrvast3.product_subscribe_url;
                elements3[22].innerHTML = jrvast3.supplier_name;
                elements3[23].innerHTML = jrvast3.price_promo_year;
                 console.log(elements3);
                
                
                var Poweo=data['Poweo'];
                var elements4 = document.querySelectorAll('[data-ps-product="'+ "Poweo" +'"]');
                elements4[1].href = Poweo.product_subscribe_url;
                elements4[0].innerHTML = Poweo.supplier_name; 
                elements4[2].innerHTML = Poweo.supplier_name;
                elements4[3].innerHTML = Poweo.price_promo_year;
                // elements4[4].src = Poweo.supplier_logo;
                // elements4[6].innerHTML = Poweo.supplier_name;
                // elements4[5].href = Poweo.product_subscribe_url;
                // elements4[7].innerHTML = Poweo.product_name; 
                // elements4[8].innerHTML = Poweo.product_pricing_type;
                // elements4[9].innerHTML = Poweo.price_promo_year;
                // elements4[10].innerHTML = Poweo.product_name;
                // elements4[11].innerHTML = Poweo.product_name;
                // elements4[11].href = Poweo.product_subscribe_url;
                // elements4[12].innerHTML = Poweo.product_name;
                // elements4[13].innerHTML = Poweo.price_promo_year;
                
               

                  //console.log(elements2[21]);
            },
            error: function(e) {

                console.log(e.message);
            }
        });
   
  // alert(api_comparison_type); 
   
   
   
   
   
    
    
});

