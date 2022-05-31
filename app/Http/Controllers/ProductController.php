<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Redirect;
use Illuminate\Support\Facades\Session;
use DB;
use App\Models\Feature;
use App\Models\ServiceLimitation;
use App;
use Jenssegers\Agent\Agent;
use Storage;
use Cookie;
use Response;


class ProductController extends Controller
{
   
   public function package(Request $request)
    {   
     
     
      
        $elec = $request->elec;
        
        $gas = $request->gas;
        
        $po = $request->po;
        
        if(!session::get('locale')){
            
            session::put('locale','nl');
        }
       
        
        Session::put('po',$po);
        App::setLocale('nl'); 
        Session::forget('elecID');
        Session::forget('gasID');
        Session::forget('sel_type');
        Session::forget('selected_product');
        Session::forget('seperate');
  
        if($elec!="" && $gas==""){
        Session::put('elec','1');
        Session::put('gas','0');
        $cat=$elec;
        }elseif ($gas!="" && $elec=="") {
            Session::put('elec','0');
        Session::put('gas','1');
        $cat=$gas;
        }elseif ($elec && $gas) {
            Session::put('elec','1');
        Session::put('gas','1');
        $cat='pack';
        }else{
        return $redirect->back();
        }
        $customer_type='residential';
        
        // default-inputs
        
        if(isset($_COOKIE['uuid'])){
            $query['uuid'] = $_COOKIE['uuid'];
        }
        
        $query['locale'] = 'nl';
        $query['postalCode'] = $po;
        $query['first_residence']=true;
        $query['customerGroup'] = 'residential';
        
        
        if($elec!="" && $gas==""){
        $cat='electricity';
        }elseif ($gas!="" && $elec=="") {
        $cat=$gas;
        }elseif ($elec && $gas) {
        $cat='pack';
        } else {
        $cat='pack';
        }
        
        
        $query['category']=$cat;
        if(isset($_COOKIE['uuid'])){
            $query['uuid'] = $_COOKIE['uuid'];
        }
        
        // default-input-end
        
        Session::put('customer_type',$customer_type);
        $postal_code=Session::get('po');
        Session::put('postal_code',$postal_code); 
        Session::forget('gasID');
        Session::forget('elecID');
        Session::put('promo','true');
        $page='sort'; 
        $currentpage = 1;
        $pages = 1;
        
        

        try {

        $client = new \GuzzleHttp\Client(); 
                  $request = $client->post('https://api.tariefchecker.be/api/calculation', [
                      'headers' => [
                          'Accept' => 'application/json',
                          'Content-type' => 'application/x-www-form-urlencoded',
                          'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWFmMjVkYWNmZTNiM2I0MmZjOTJkMTU5MjIxY2RjNjNkY2MxMzEwZWU3NDJlM2YzNmRiOWZiMDZhZmMwNGMyNTgyNzEzNjRhYjU5Y2VkZGQiLCJpYXQiOjE2NDMyODY2MjcsIm5iZiI6MTY0MzI4NjYyNywiZXhwIjoyMjc0NDM4NjI3LCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.rmiDd2sM0kduf6CPed5rjbqPL4Fui-MDdOKViiPn49pcJEukW_kA2ByuJfHNUIe9rctIXsovX1T8kgeer6TxgQkGCvruO98zcGklVv470en8ul6NzOCMmaemX4cJj4XQYlcI1_-z6tnHqtbbc7_-TyvezidDGslhMAMmtREicgrubnp9VGyl6YtE_pXHedruJ7PxYsc2_Gqu-osdFOdEW6hxN3uPlpKbuHgrf9DvJr8B3PSDQLPl49Q9HzrL-vPgayZTpNFINpCw1QBKk_ooWo861UZQ_cE33TdNfXyoJ5WnXQ-AjvtInfw7C9skq57C9X4NmfsllWCacNn9IYNs4uocuFo259TbRXNuooHsWTDTty4kalcp3LD7G0exCTTDC3_QsEoZI6694ct8Fi0gOJ05thoS5grKIfKyFkRqu1eOS2wMdNs-6KZXwVQ6fv1sJE-VjdIKXoj-r6wo_FPSceB599yz22gwVQLnDQJAvu0OahSyU8DG3VMH__ItYBuTI0uOTJZerwaRwmnTkSWbWczA4c8AEb1H_W-G4Yblh4D9y_ZOW7FvvFj53dCX83mzUyBN3HahqzD8ZX0IvZXolZHLxluIOlFoR9HiLNzTZFrJSzWru39AjNmbaK8-AAydGlF606uGglo76ES7D7dOvDa5lgUjYzRvby8jDpRSkzY'],
                      'query' => $query 
                  ]);

        Session::forget('msg');
        $response = $request->getBody()->getContents();       
        $json = json_decode($response, true);

          } catch (\Exception $e) {

            $response = ['status' => false, 'message' => $e->getMessage()];

            }
            
        

         // dd($json['products'][30]);
          if(count($json['products'])>0){
              //  Session::put('elec_count',count($json['products']));
                $products = collect($json['products']);
                
            if(Session::get('promo')=='true' || Session::get('domi')=='true' || Session::get('email')=='true'){
            
             if(Session::get('promo')=='true' && Session::get('domi')!='true' && Session::get('email')!='true'){
                
               
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')!='true' && Session::get('domi')=='true' && Session::get('email')!='true'){
                
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_slD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_slD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')!='true' && Session::get('domi')!='true' && Session::get('email')=='true'){
                
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')=='true' && Session::get('domi')=='true' && Session::get('email')!='true'){
                
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_slD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_slD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')=='true' && Session::get('domi')!='true' && Session::get('email')=='true'){
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')=='true' && Session::get('domi')=='true' && Session::get('email')=='true'){
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_slD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_slD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')!='true' && Session::get('domi')=='true' && Session::get('email')=='true'){
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_slD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_slD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            }else{
             
             
             $sorted = $products->sort(function($a, $b) {
                  if ($a['price']['totals']['year']['excl_promo'] == $b['price']['totals']['year']['excl_promo']) {
                  return 0;
                  }
                  return ($a['price']['totals']['year']['excl_promo'] < $b['price']['totals']['year']['excl_promo']) ? -1 : 1;
                });
             
         }

                $products = $sorted;
               
                Session::put('pro_data',$products);
                Session::put('getParameters',$products[0]);
                Session::put('packParameterData',$products[0]);
                Session::put('packData',$products);
                
                $cookie_name = "uuid";
                $cookie_value = $products[0]['parameters']['uuid'];
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30 * 365), "/"); // 86400 = 1 day
                $uuid=$products[0]['parameters']['uuid'];
                
               
               
        if($products[0]['parameters']['values']['comparison_type']=='pack'){
        // elec product count
        
        $queryelec['locale'] = 'nl';
        $queryelec['postalCode'] = $products[0]['parameters']['values']['postal_code'];
        $queryelec['first_residence']=true;
        $queryelec['customerGroup'] = $products[0]['parameters']['values']['customer_group'];
        if($products[0]['parameters']['values']['meter_type']=='single'){
            
        $queryelec['registerDay'] = -1;
        $queryelec['registerNight'] = -1;
        $queryelec['registerExclNight'] =-1;
        $queryelec['registerNormal'] = $products[0]['parameters']['values']['usage_single'];
        }
        if($products[0]['parameters']['values']['meter_type']=='double'){
            
            $queryelec['registerDay'] = $products[0]['parameters']['values']['usage_day'];
        $queryelec['registerNight'] = $products[0]['parameters']['values']['usage_night'];
        $queryelec['registerExclNight'] = -1;
        $queryelec['registerNormal'] = -1;
        }
        if($products[0]['parameters']['values']['meter_type']=='single_excl_night'){
            
            $queryelec['registerDay'] = -1;
        $queryelec['registerNight'] = -1;
        $queryelec['registerExclNight'] = $products[0]['parameters']['values']['usage_excl_night'];
        $queryelec['registerNormal'] = $products[0]['parameters']['values']['usage_single'];
        }
        if($products[0]['parameters']['values']['meter_type']=='double_excl_night'){
            
            $queryelec['registerDay'] = $products[0]['parameters']['values']['usage_day'];
        $queryelec['registerNight'] = $products[0]['parameters']['values']['usage_night'];
        $queryelec['registerExclNight'] = $products[0]['parameters']['values']['usage_excl_night'];
        $queryelec['registerNormal'] = -1;
        }
         $queryelec['registerG'] = -1;
        //$queryelec['uuid']= $products[0]['parameters']['uuid'];
        $queryelec['meterType']=$products[0]['parameters']['values']['meter_type'];
        $queryelec['category']='electricity';
        $queryelec['IncludeE'] = 1;
        $queryelec['IncludeG'] = 0;
        
                if($products[0]['parameters']['values']['meter_type']=='single' && $products[0]['parameters']['values']['usage_single']==0){
                    
                    $queryelec['registerday'] =-1;
                    $queryelec['registerNight'] =-1;
                    $queryelec['registerExclNight'] = -1;
                    $queryelec['registerNormal'] = 3500;
                }
         
     
        
        try {
        $client = new \GuzzleHttp\Client(); 
                  $request = $client->post('https://api.tariefchecker.be/api/calculation', [
                      'headers' => [
                          'Accept' => 'application/json',
                          'Content-type' => 'application/x-www-form-urlencoded',
                          'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWFmMjVkYWNmZTNiM2I0MmZjOTJkMTU5MjIxY2RjNjNkY2MxMzEwZWU3NDJlM2YzNmRiOWZiMDZhZmMwNGMyNTgyNzEzNjRhYjU5Y2VkZGQiLCJpYXQiOjE2NDMyODY2MjcsIm5iZiI6MTY0MzI4NjYyNywiZXhwIjoyMjc0NDM4NjI3LCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.rmiDd2sM0kduf6CPed5rjbqPL4Fui-MDdOKViiPn49pcJEukW_kA2ByuJfHNUIe9rctIXsovX1T8kgeer6TxgQkGCvruO98zcGklVv470en8ul6NzOCMmaemX4cJj4XQYlcI1_-z6tnHqtbbc7_-TyvezidDGslhMAMmtREicgrubnp9VGyl6YtE_pXHedruJ7PxYsc2_Gqu-osdFOdEW6hxN3uPlpKbuHgrf9DvJr8B3PSDQLPl49Q9HzrL-vPgayZTpNFINpCw1QBKk_ooWo861UZQ_cE33TdNfXyoJ5WnXQ-AjvtInfw7C9skq57C9X4NmfsllWCacNn9IYNs4uocuFo259TbRXNuooHsWTDTty4kalcp3LD7G0exCTTDC3_QsEoZI6694ct8Fi0gOJ05thoS5grKIfKyFkRqu1eOS2wMdNs-6KZXwVQ6fv1sJE-VjdIKXoj-r6wo_FPSceB599yz22gwVQLnDQJAvu0OahSyU8DG3VMH__ItYBuTI0uOTJZerwaRwmnTkSWbWczA4c8AEb1H_W-G4Yblh4D9y_ZOW7FvvFj53dCX83mzUyBN3HahqzD8ZX0IvZXolZHLxluIOlFoR9HiLNzTZFrJSzWru39AjNmbaK8-AAydGlF606uGglo76ES7D7dOvDa5lgUjYzRvby8jDpRSkzY'],
                      'query' => $queryelec 
                  ]);
        Session::forget('msg');
        $responseelec = $request->getBody()->getContents();       
        $jsonelec = json_decode($responseelec, true);
          } catch (\Exception $e) {
            $responseelec = ['status' => false, 'message' => $e->getMessage()];
        }  
      
      
         $productselec = collect($jsonelec['products']);
         Session::put('elec_count',count($productselec));
         
        // end elec product
                
        // gas product data
        $getParameters=Session::get('getParameters');
        $queryGas['locale'] = $getParameters['parameters']['values']['locale'];
        $queryGas['postalCode'] = $getParameters['parameters']['values']['postal_code'];
        $queryGas['first_residence']=true;
        $queryGas['customerGroup'] = $getParameters['parameters']['values']['customer_group'];
        $queryGas['registerG'] = $getParameters['parameters']['values']['usage_gas'];
        $queryGas['category']=  'gas';
        $queryGas['IncludeE'] = false;
        $queryGas['IncludeG'] = true;
        
        try {

        $client = new \GuzzleHttp\Client(); 
                  $request = $client->post('https://api.tariefchecker.be/api/calculation', [
                      'headers' => [
                          'Accept' => 'application/json',
                          'Content-type' => 'application/x-www-form-urlencoded',
                          'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWFmMjVkYWNmZTNiM2I0MmZjOTJkMTU5MjIxY2RjNjNkY2MxMzEwZWU3NDJlM2YzNmRiOWZiMDZhZmMwNGMyNTgyNzEzNjRhYjU5Y2VkZGQiLCJpYXQiOjE2NDMyODY2MjcsIm5iZiI6MTY0MzI4NjYyNywiZXhwIjoyMjc0NDM4NjI3LCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.rmiDd2sM0kduf6CPed5rjbqPL4Fui-MDdOKViiPn49pcJEukW_kA2ByuJfHNUIe9rctIXsovX1T8kgeer6TxgQkGCvruO98zcGklVv470en8ul6NzOCMmaemX4cJj4XQYlcI1_-z6tnHqtbbc7_-TyvezidDGslhMAMmtREicgrubnp9VGyl6YtE_pXHedruJ7PxYsc2_Gqu-osdFOdEW6hxN3uPlpKbuHgrf9DvJr8B3PSDQLPl49Q9HzrL-vPgayZTpNFINpCw1QBKk_ooWo861UZQ_cE33TdNfXyoJ5WnXQ-AjvtInfw7C9skq57C9X4NmfsllWCacNn9IYNs4uocuFo259TbRXNuooHsWTDTty4kalcp3LD7G0exCTTDC3_QsEoZI6694ct8Fi0gOJ05thoS5grKIfKyFkRqu1eOS2wMdNs-6KZXwVQ6fv1sJE-VjdIKXoj-r6wo_FPSceB599yz22gwVQLnDQJAvu0OahSyU8DG3VMH__ItYBuTI0uOTJZerwaRwmnTkSWbWczA4c8AEb1H_W-G4Yblh4D9y_ZOW7FvvFj53dCX83mzUyBN3HahqzD8ZX0IvZXolZHLxluIOlFoR9HiLNzTZFrJSzWru39AjNmbaK8-AAydGlF606uGglo76ES7D7dOvDa5lgUjYzRvby8jDpRSkzY'],
                      'query' => $queryGas 
                  ]);

        Session::forget('msg');
        $responseGas = $request->getBody()->getContents();       
        $jsonGas = json_decode($responseGas, true);

          } catch (\Exception $e) {

            $responseGas = ['status' => false, 'message' => $e->getMessage()];

        }  
         
         $productsGas = collect($jsonGas['products']);
         Session::put('gas_count',count($jsonGas['products']));
         
        //  end-gas
        
        }

        return redirect('overzicht/'.$products[0]['parameters']['values']['comparison_type'].'/'.$products[0]['parameters']['values']['dgo_id_electricity'].'-'.$products[0]['parameters']['values']['dgo_id_gas'].'-'.$products[0]['parameters']['values']['postal_code'].'?u='.$uuid);

          }else{

               Session::flash('message', "Sorry Tariefs not available now");
               return Redirect::back();

          }
    
        
    }

    public function packages(Request $request ,$cat)
    {  

        $agent = new Agent(); 
        
        Session::get('locale');
        App::setLocale(Session::get('locale'));
        Session::forget('sel_type'); 

        $query = []; 
        $elec=Session::get('elec');
        $gas=Session::get('gas');      
        $po=Session::get('po');
        $json=Session::get('data');
        Session::put('pro',$json);
        $json1=Session::get('pro');
        $get_product=$products = collect(Session::get('pro_data')); 
        Session::put('product_data',$products);
        Session::forget('product_data_sort');
        
            if(Session::get('promo')=='true' || Session::get('domi')=='true' || Session::get('email')=='true'){
            
             if(Session::get('promo')=='true' && Session::get('domi')!='true' && Session::get('email')!='true'){
                
               
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')!='true' && Session::get('domi')=='true' && Session::get('email')!='true'){
                
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_slD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_slD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')!='true' && Session::get('domi')!='true' && Session::get('email')=='true'){
                
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')=='true' && Session::get('domi')=='true' && Session::get('email')!='true'){
                
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_slD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_slD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')=='true' && Session::get('domi')!='true' && Session::get('email')=='true'){
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')=='true' && Session::get('domi')=='true' && Session::get('email')=='true'){
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_slD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_slD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')!='true' && Session::get('domi')=='true' && Session::get('email')=='true'){
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_slD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_slD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            }else{
            
            
            $sorted = $products->sort(function($a, $b) {
            if ($a['price']['totals']['year']['excl_promo'] == $b['price']['totals']['year']['excl_promo']) {
            return 0;
            }
            return ($a['price']['totals']['year']['excl_promo'] < $b['price']['totals']['year']['excl_promo']) ? -1 : 1;
            });
             $sorted1 = $sorted->sortBy(function ($item, $key) {
                    return $item['price']['totals']['year']['excl_promo'];
                })->first();
                
            $min=$sorted1['product']['id'];
            
            }
            
            $products = $sorted;

        $ele_count = $products->filter(function($value, $key) {
            if ($value['product']['type'] == 'pack') {
              if (array_key_exists("electricity", $value['product']["underlying_products"])) {
                  return true;
              }
            } else {
              if ($value['product']['type'] == 'electricity') {
                  return true;
              }
            }
        });
        
       

          
          $customer_type='residential';        
        
        $type="";
         
          if($type){
          $packType=$type;
          }else{

          $packType="";
          }

        $currentpage = 1;
        $pages = 1;
        $offset = 0;
        $perpage = 7;
        $totalProducts = $products->count();
        $totalPages = ceil($totalProducts / $perpage); 
        $currentPage = 1;
        $products =  $products->slice($offset, $perpage);
        $feature = Feature::select('features.id','contract_details.part','contract_details.field', 'features.condition', 'features.NL_description', 'features.FR_description')
                           ->join('contract_details', 'contract_details.id', '=', 'features.contract_id')
                           ->get();
        $service = ServiceLimitation::get();
        
        

        if(isset($_COOKIE['uuid']) || !isset($_COOKIE['uuid']))
        {
                
            foreach($get_product as $get_products)
                {         
                    $uuid = $get_products['parameters']['uuid'];           
                } 
           
               if(!isset($uuid)){
               $uuid=$_GET['u'];
               
              
               if(isset($_GET['po']))
                {
                
               $po=$_GET['po'];
                }
               if(isset($_GET['locale']))
                {
               $locale='nl';
                }
               if(isset($_GET['customer_group']))
                {
               $customer_group=$_GET['customer_group'];
                }


               $elec = 'electricity';
               $gas = 'gas';
        
     
        
        
       
        
        Session::put('po',$po);
        App::setLocale('nl'); 
        Session::forget('elecID');
        Session::forget('gasID');
        Session::forget('sel_type');
        Session::forget('selected_product');
        Session::forget('seperate');
  
        if($elec!="" && $gas==""){
        Session::put('elec','1');
        Session::put('gas','0');
        $cat=$elec;
        }elseif ($gas!="" && $elec=="") {
            Session::put('elec','0');
        Session::put('gas','1');
        $cat=$gas;
        }elseif ($elec && $gas) {
            Session::put('elec','1');
        Session::put('gas','1');
        $cat='pack';
        }else{
        return $redirect->back();
        }
        $customer_type='residential';


        
       
        
        
           // default-inputs
        
      
        $query['uuid'] = $uuid;
        if(isset($locale)){
        $query['locale'] = 'nl'; //$locale
        }else{

        $query['locale'] = 'nl';

        }
        if(isset($po)){

         $query['postalCode'] = $po;//$po
        }else{

          $query['postalCode'] ="2000";  
        }
       
        $query['first_residence']=true;
        if(isset($customerGroup)){
        $query['customerGroup'] = $customerGroup; //$customerGroup
        }else{

            $query['customerGroup'] ='residential';
        }
        if($elec!="" && $gas==""){
            $cat='electricity';
        }elseif($gas!="" && $elec==""){
        $cat=$gas;
        }elseif($elec && $gas){
        $cat='pack';
        } else {
        $cat='pack';
        }
        
        
        // default-input-end

        $query['category']=$cat;
        Session::put('customer_type',$customer_type);
        $postal_code=Session::get('po');
        Session::put('postal_code',$postal_code); 
        Session::forget('gasID');
        Session::forget('elecID');
        Session::put('promo','true');
        $page='sort'; 
        $currentpage = 1;
        $pages = 1;
        
       

        try {

        $client = new \GuzzleHttp\Client(); 
                  $request = $client->post('https://api.tariefchecker.be/api/calculation', [
                      'headers' => [
                          'Accept' => 'application/json',
                          'Content-type' => 'application/x-www-form-urlencoded',
                          'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWFmMjVkYWNmZTNiM2I0MmZjOTJkMTU5MjIxY2RjNjNkY2MxMzEwZWU3NDJlM2YzNmRiOWZiMDZhZmMwNGMyNTgyNzEzNjRhYjU5Y2VkZGQiLCJpYXQiOjE2NDMyODY2MjcsIm5iZiI6MTY0MzI4NjYyNywiZXhwIjoyMjc0NDM4NjI3LCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.rmiDd2sM0kduf6CPed5rjbqPL4Fui-MDdOKViiPn49pcJEukW_kA2ByuJfHNUIe9rctIXsovX1T8kgeer6TxgQkGCvruO98zcGklVv470en8ul6NzOCMmaemX4cJj4XQYlcI1_-z6tnHqtbbc7_-TyvezidDGslhMAMmtREicgrubnp9VGyl6YtE_pXHedruJ7PxYsc2_Gqu-osdFOdEW6hxN3uPlpKbuHgrf9DvJr8B3PSDQLPl49Q9HzrL-vPgayZTpNFINpCw1QBKk_ooWo861UZQ_cE33TdNfXyoJ5WnXQ-AjvtInfw7C9skq57C9X4NmfsllWCacNn9IYNs4uocuFo259TbRXNuooHsWTDTty4kalcp3LD7G0exCTTDC3_QsEoZI6694ct8Fi0gOJ05thoS5grKIfKyFkRqu1eOS2wMdNs-6KZXwVQ6fv1sJE-VjdIKXoj-r6wo_FPSceB599yz22gwVQLnDQJAvu0OahSyU8DG3VMH__ItYBuTI0uOTJZerwaRwmnTkSWbWczA4c8AEb1H_W-G4Yblh4D9y_ZOW7FvvFj53dCX83mzUyBN3HahqzD8ZX0IvZXolZHLxluIOlFoR9HiLNzTZFrJSzWru39AjNmbaK8-AAydGlF606uGglo76ES7D7dOvDa5lgUjYzRvby8jDpRSkzY'],
                      'query' => $query 
                  ]);

        Session::forget('msg');
        $response = $request->getBody()->getContents();       
        $json = json_decode($response, true);

          } catch (\Exception $e) {

            $response = ['status' => false, 'message' => $e->getMessage()];

            }
            
 
          
          if(count($json['products'])>0){
             
                $products = collect($json['products']);
                
                if(Session::get('promo')=='true' || Session::get('domi')=='true' || Session::get('email')=='true'){
            
             if(Session::get('promo')=='true' && Session::get('domi')!='true' && Session::get('email')!='true'){
                
               
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')!='true' && Session::get('domi')=='true' && Session::get('email')!='true'){
                
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_slD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_slD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')!='true' && Session::get('domi')!='true' && Session::get('email')=='true'){
                
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')=='true' && Session::get('domi')=='true' && Session::get('email')!='true'){
                
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_slD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_slD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')=='true' && Session::get('domi')!='true' && Session::get('email')=='true'){
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')=='true' && Session::get('domi')=='true' && Session::get('email')=='true'){
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_slD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_slD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')!='true' && Session::get('domi')=='true' && Session::get('email')=='true'){
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_slD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_slD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            }else{
             
             
             $sorted = $products->sort(function($a, $b) {
                  if ($a['price']['totals']['year']['excl_promo'] == $b['price']['totals']['year']['excl_promo']) {
                  return 0;
                  }
                  return ($a['price']['totals']['year']['excl_promo'] < $b['price']['totals']['year']['excl_promo']) ? -1 : 1;
                });
             
         }

                $products = $sorted;
               
               
                Session::put('pro_data',$products);
                Session::put('getParameters',$products[0]);
                 Session::put('packParameterData',$products[0]);
                 Session::put('packData',$products);
                
                $cookie_name = "uuid";
                $cookie_value = $products[0]['parameters']['uuid'];
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30 * 365), "/"); // 86400 = 1 day
                $uuid=$products[0]['parameters']['uuid'];
                
               
               
        if($products[0]['parameters']['values']['comparison_type']=='pack'){
        // elec product count
        
        $queryelec['locale'] = 'nl';
        $queryelec['postalCode'] = $products[0]['parameters']['values']['postal_code'];
        $queryelec['first_residence']=true;
        $queryelec['customerGroup'] = $products[0]['parameters']['values']['customer_group'];
     if($products[0]['parameters']['values']['meter_type']=='single'){
            
        $queryelec['registerDay'] = 0;
        $queryelec['registerNight'] = 0;
        $queryelec['registerExclNight'] =0;
        $queryelec['registerNormal'] = $products[0]['parameters']['values']['usage_single'];
        }
        if($products[0]['parameters']['values']['meter_type']=='double'){
            
            $queryelec['registerDay'] = $products[0]['parameters']['values']['usage_day'];
        $queryelec['registerNight'] = $products[0]['parameters']['values']['usage_night'];
        $queryelec['registerExclNight'] = 0;
        $queryelec['registerNormal'] = 0;
        }
        if($products[0]['parameters']['values']['meter_type']=='single_excl_night'){
            
            $queryelec['registerDay'] = 0;
        $queryelec['registerNight'] = 0;
        $queryelec['registerExclNight'] = $products[0]['parameters']['values']['usage_excl_night'];
        $queryelec['registerNormal'] = $products[0]['parameters']['values']['usage_single'];
        }
        if($products[0]['parameters']['values']['meter_type']=='double_excl_night'){
            
            $queryelec['registerDay'] = $products[0]['parameters']['values']['usage_day'];
        $queryelec['registerNight'] = $products[0]['parameters']['values']['usage_night'];
        $queryelec['registerExclNight'] = $products[0]['parameters']['values']['usage_excl_night'];
        $queryelec['registerNormal'] = 0;
        }
         $queryelec['registerG'] = 0;
        //$queryelec['uuid']= $products[0]['parameters']['uuid'];
        $queryelec['meterType']=$products[0]['parameters']['values']['meter_type'];
        $queryelec['category']='electricity';
        $queryelec['IncludeE'] = 1;
        $queryelec['IncludeG'] = 0;
        
                if($products[0]['parameters']['values']['meter_type']=='single' && $products[0]['parameters']['values']['usage_single']==0){
                    
                    $queryelec['registerday'] =0;
                    $queryelec['registerNight'] =0;
                    $queryelec['registerExclNight'] = 0;
                    $queryelec['registerNormal'] = 3500;
                }
         
     
        
        try {
        $client = new \GuzzleHttp\Client(); 
                  $request = $client->post('https://api.tariefchecker.be/api/calculation', [
                      'headers' => [
                          'Accept' => 'application/json',
                          'Content-type' => 'application/x-www-form-urlencoded',
                          'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWFmMjVkYWNmZTNiM2I0MmZjOTJkMTU5MjIxY2RjNjNkY2MxMzEwZWU3NDJlM2YzNmRiOWZiMDZhZmMwNGMyNTgyNzEzNjRhYjU5Y2VkZGQiLCJpYXQiOjE2NDMyODY2MjcsIm5iZiI6MTY0MzI4NjYyNywiZXhwIjoyMjc0NDM4NjI3LCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.rmiDd2sM0kduf6CPed5rjbqPL4Fui-MDdOKViiPn49pcJEukW_kA2ByuJfHNUIe9rctIXsovX1T8kgeer6TxgQkGCvruO98zcGklVv470en8ul6NzOCMmaemX4cJj4XQYlcI1_-z6tnHqtbbc7_-TyvezidDGslhMAMmtREicgrubnp9VGyl6YtE_pXHedruJ7PxYsc2_Gqu-osdFOdEW6hxN3uPlpKbuHgrf9DvJr8B3PSDQLPl49Q9HzrL-vPgayZTpNFINpCw1QBKk_ooWo861UZQ_cE33TdNfXyoJ5WnXQ-AjvtInfw7C9skq57C9X4NmfsllWCacNn9IYNs4uocuFo259TbRXNuooHsWTDTty4kalcp3LD7G0exCTTDC3_QsEoZI6694ct8Fi0gOJ05thoS5grKIfKyFkRqu1eOS2wMdNs-6KZXwVQ6fv1sJE-VjdIKXoj-r6wo_FPSceB599yz22gwVQLnDQJAvu0OahSyU8DG3VMH__ItYBuTI0uOTJZerwaRwmnTkSWbWczA4c8AEb1H_W-G4Yblh4D9y_ZOW7FvvFj53dCX83mzUyBN3HahqzD8ZX0IvZXolZHLxluIOlFoR9HiLNzTZFrJSzWru39AjNmbaK8-AAydGlF606uGglo76ES7D7dOvDa5lgUjYzRvby8jDpRSkzY'],
                      'query' => $queryelec 
                  ]);
        Session::forget('msg');
        $responseelec = $request->getBody()->getContents();       
        $jsonelec = json_decode($responseelec, true);
          } catch (\Exception $e) {
            $responseelec = ['status' => false, 'message' => $e->getMessage()];
        }  
      
     
         $productselec = collect($jsonelec['products']);
         Session::put('elec_count',count($productselec));
         
        // end elec product
             
        // gas product data
        $getParameters=Session::get('getParameters');
        $queryGas['locale'] = 'nl';
        $queryGas['postalCode'] = $getParameters['parameters']['values']['postal_code'];
        $queryGas['first_residence']=true;
        $queryGas['customerGroup'] = $getParameters['parameters']['values']['customer_group'];
        $queryGas['registerG'] = $getParameters['parameters']['values']['usage_gas'];
        $queryGas['category']=  'gas';
        $queryGas['IncludeE'] = false;
        $queryGas['IncludeG'] = true;
         
        
        try {

        $client = new \GuzzleHttp\Client(); 
                  $request = $client->post('https://api.tariefchecker.be/api/calculation', [
                      'headers' => [
                          'Accept' => 'application/json',
                          'Content-type' => 'application/x-www-form-urlencoded',
                          'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWFmMjVkYWNmZTNiM2I0MmZjOTJkMTU5MjIxY2RjNjNkY2MxMzEwZWU3NDJlM2YzNmRiOWZiMDZhZmMwNGMyNTgyNzEzNjRhYjU5Y2VkZGQiLCJpYXQiOjE2NDMyODY2MjcsIm5iZiI6MTY0MzI4NjYyNywiZXhwIjoyMjc0NDM4NjI3LCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.rmiDd2sM0kduf6CPed5rjbqPL4Fui-MDdOKViiPn49pcJEukW_kA2ByuJfHNUIe9rctIXsovX1T8kgeer6TxgQkGCvruO98zcGklVv470en8ul6NzOCMmaemX4cJj4XQYlcI1_-z6tnHqtbbc7_-TyvezidDGslhMAMmtREicgrubnp9VGyl6YtE_pXHedruJ7PxYsc2_Gqu-osdFOdEW6hxN3uPlpKbuHgrf9DvJr8B3PSDQLPl49Q9HzrL-vPgayZTpNFINpCw1QBKk_ooWo861UZQ_cE33TdNfXyoJ5WnXQ-AjvtInfw7C9skq57C9X4NmfsllWCacNn9IYNs4uocuFo259TbRXNuooHsWTDTty4kalcp3LD7G0exCTTDC3_QsEoZI6694ct8Fi0gOJ05thoS5grKIfKyFkRqu1eOS2wMdNs-6KZXwVQ6fv1sJE-VjdIKXoj-r6wo_FPSceB599yz22gwVQLnDQJAvu0OahSyU8DG3VMH__ItYBuTI0uOTJZerwaRwmnTkSWbWczA4c8AEb1H_W-G4Yblh4D9y_ZOW7FvvFj53dCX83mzUyBN3HahqzD8ZX0IvZXolZHLxluIOlFoR9HiLNzTZFrJSzWru39AjNmbaK8-AAydGlF606uGglo76ES7D7dOvDa5lgUjYzRvby8jDpRSkzY'],
                      'query' => $queryGas 
                  ]);

        Session::forget('msg');
        $responseGas = $request->getBody()->getContents();       
        $jsonGas = json_decode($responseGas, true);

          } catch (\Exception $e) {

            $responseGas = ['status' => false, 'message' => $e->getMessage()];

        }  
         $productsGas = collect($jsonGas['products']);
         Session::put('gas_count',count($jsonGas['products']));
         
        //  end-gas
        
        }

        return redirect('overzicht/'.$products[0]['parameters']['values']['comparison_type'].'/'.$products[0]['parameters']['values']['dgo_id_electricity'].'-'.$products[0]['parameters']['values']['dgo_id_gas'].'-'.$products[0]['parameters']['values']['postal_code'].'?u='.$uuid);

          }else{

               Session::flash('message', "Sorry Tariefs not available now");
               return Redirect::back();

          }
            
            
            // test
               
               exit();
           }
           $cookie_value = $uuid;
           
           setcookie('uuid', $cookie_value, time() + (86400 * 365), "/"); 
        }

        $response = Storage::disk('local')->get('suppliers.json');
        $json = json_decode($response, true);
        $all_suppliers=$json['suppliers'];
        $data['test']=$all_suppliers;
        $usage_elec_day2="";
        $consuption1="";
        $consuption2="";
        $consuption_excl_night="";
        $consuption_excl_night2="";
        $consuption_excl_day="";
        $consuption_excl_day2="";
        $page='sort'; 
        $currentpage = 1;
        $pages = 1;
        
        $getParameters=Session::get('getParameters');
        Session::put('locale','nl');
       

        if($agent->isDesktop()){
           
         return view('home.index', compact('getParameters','totalProducts','totalPages','currentPage','elec', 'gas', 'po', 'products','customer_type','ele_count','gas_count','page', 'currentpage','usage_elec_day2','consuption1','consuption2','consuption_excl_night','consuption_excl_day','consuption_excl_night2','consuption_excl_day2','packType','feature','min','service','all_suppliers'));        

        }elseif($agent->isTablet()){
           
         return view('home.index', compact('getParameters','totalProducts','totalPages','currentPage','elec', 'gas', 'po', 'products','customer_type','ele_count','gas_count','page', 'currentpage','usage_elec_day2','consuption1','consuption2','consuption_excl_night','consuption_excl_day','consuption_excl_night2','consuption_excl_day2','packType','feature','min','service','all_suppliers'));        

        }else{
           
        return view('mobile-view.home.index', compact('getParameters','totalProducts','totalPages','currentPage','elec', 'gas', 'po', 'products','customer_type','ele_count','gas_count','page', 'currentpage','usage_elec_day2','consuption1','consuption2','consuption_excl_night','consuption_excl_day','consuption_excl_night2','consuption_excl_day2','packType','feature','min','service','all_suppliers'));    

        }
        
    }
    
     public function loadMore(Request $request)
    {
  
         $packType=$request->packType;

         if(Session::get('product_data_sort')){             
              $product_data = Session::get('product_data_sort');              
         }else{             
              $product_data = Session::get('product_data');
         }
       
        $customer_type = Session::get('customer_type');
        $postal_code = Session::get('postal_code');
        $usage_elec_day = Session::get('usage_elec_day');
        $usage_elec_night = Session::get('usage_elec_night');
        $usage_gas = Session::get('usage_gas');

            $sorted = collect($product_data)->sortBy(function ($item, $key) {
            return $item['price']['totals']['year']['incl_promo'];
            })->first();
            $min=$sorted['product']['id'];
            
            $products=collect($product_data);
            
             if(Session::get('promo')=='true' || Session::get('domi')=='true' || Session::get('email')=='true'){
            
             if(Session::get('promo')=='true' && Session::get('domi')!='true' && Session::get('email')!='true'){
                
               
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')!='true' && Session::get('domi')=='true' && Session::get('email')!='true'){
                
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_slD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_slD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')!='true' && Session::get('domi')!='true' && Session::get('email')=='true'){
                
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')=='true' && Session::get('domi')=='true' && Session::get('email')!='true'){
                
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_slD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_slD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')=='true' && Session::get('domi')!='true' && Session::get('email')=='true'){
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')=='true' && Session::get('domi')=='true' && Session::get('email')=='true'){
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_slD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_slD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')!='true' && Session::get('domi')=='true' && Session::get('email')=='true'){
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_slD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_slD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            }else{
            
            
            $sorted = $products->sort(function($a, $b) {
            if ($a['price']['totals']['year']['excl_promo'] == $b['price']['totals']['year']['excl_promo']) {
            return 0;
            }
            return ($a['price']['totals']['year']['excl_promo'] < $b['price']['totals']['year']['excl_promo']) ? -1 : 1;
            });
             $sorted1 = $sorted->sortBy(function ($item, $key) {
                    return $item['price']['totals']['year']['excl_promo'];
                })->first();
                
            $min=$sorted1['product']['id'];
            
            }
            

        $product_data=$sorted;
        $totalProducts = count($product_data);
        $pageNumber = (int) $request->get('page', 1);
        $perpage = 7;
        $totalPages = ceil($totalProducts / $perpage);
        $offset = ($pageNumber - 1) * $perpage;       
        $products = collect($product_data)->slice($offset, $perpage);
        $html = '';
        $si='0';

        $feature = Feature::select('features.id','contract_details.part','contract_details.field', 'features.condition', 'features.NL_description', 'features.FR_description')
                           ->join('contract_details', 'contract_details.id', '=', 'features.contract_id')
                           ->get();
        $service = ServiceLimitation::get();
        $type="";
        $agent = new Agent();
      
         if($agent->isDesktop()){
             
        $si="0"; 

              foreach ($products as $getdetails) {
                 $si++; 
                 if(Session::get('sel_type')!="" && Session::get('sel_type')==$packType){
                $html .= \View::make('elements.product-item', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas', 'totalPages', 'pageNumber','si','packType', 'feature','min','service'))->render();
                      }elseif(Session::get('sel_type')!="" && Session::get('sel_type')!=$packType){
                        
                $html .= \View::make('elements.product-item-sep', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','type'))->render();   
                        
                    }else{
                        
                $html .= \View::make('elements.product-item', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service'))->render();
                        
                    }
              }


         }elseif($agent->isTablet()){
             
             $si="0"; 

              foreach ($products as $getdetails) {
                 $si++; 
                 if(Session::get('sel_type')!="" && Session::get('sel_type')==$packType){
                $html .= \View::make('elements.product-item', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas', 'totalPages', 'pageNumber','si','packType', 'feature','min','service'))->render();
                      }elseif(Session::get('sel_type')!="" && Session::get('sel_type')!=$packType){
                        
                $html .= \View::make('elements.product-item-sep', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','type'))->render();   
                        
                    }else{
                        
                $html .= \View::make('elements.product-item', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service'))->render();
                        
                    }
              }
       }else{
              $si="0"; 

              foreach ($products as $getdetails) {
                 $si++; 
                 if(Session::get('sel_type')!="" && Session::get('sel_type')==$packType){
                $html .= \View::make('elements.product-item', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas', 'totalPages', 'pageNumber','si','packType', 'feature','min','service'))->render();
                      }elseif(Session::get('sel_type')!="" && Session::get('sel_type')!=$packType){
                        
                $html .= \View::make('mobile-view.home.elements.product-item-sep', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','type'))->render();   
                        
                    }else{
                        
                $html .= \View::make('mobile-view.home.elements.product-item', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service'))->render();
                        
                    }
              }
         }
        return ['status' => 'success', 'html' => $html];
    }
    
    public function find_pack(Request $request){
        $el_cons=$request->ele;
        $gas_cons=$request->gas;
        $po=$request->po;
        return Redirect::route('packages', array('elec' => $el_cons,'gas'=>$gas_cons,'po'=>$po));        
    }    

   
    
    public function filter(Request $request){

        $elec =Session::get('elec');
        $gas =Session::get('gas');
        $po =Session::get('po');
        $customer_type=Session::get('customer_type');
        $postal_code=Session::get('postal_code');
        $usage_elec_day=Session::get('usage_elec_day');
        $usage_elec_night=Session::get('usage_elec_night');
        $usage_gas=Session::get('usage_gas');          
        $year = $request->year;      
        $price_type = $request->price_type;
        $green = $request->green;
        $green_score = $request->green_score;
        $type = $request->type;
        $dual='dual';
        $ser_lim = $request->ser_lim;
        $disc = $request->disc;   
        $refresh = $request->refresh;
        Session::forget('currentValue');
        
       
         if(isset($request->cur_invoice_moth_year)){
            
            Session::put('cur_invoice_moth_year',$request->cur_invoice_moth_year);
           
        }else{
            
            Session::forget('cur_invoice_moth_year');
        }
         if(isset($request->currentValue)){
             
             
            
            Session::put('currentValue',$request->currentValue);
           
        }else{
            
            Session::forget('currentValue');
            Session::forget('cur_invoice_moth_year');
        }
        
        
        
        if($refresh){            
            Session::forget('select-pro');
            Session::forget('promo');
        }
        
        
        
        $collection = Session::get('product_data');
        $filter = $collection->filter(function($value, $key) use ($collection, $year, $price_type, $green, $type,$green_score,$ser_lim,$disc) {
            $satisfied = 0;
            $total_condition = 0;
            
            if (!empty($year)) {
                  $total_condition++;
                  if (in_array(1, $year)) {
                  array_push($year, 123);
                  }
                  if (in_array(2, $year)) {
                  array_push($year, 123);
                  }
                  if (in_array(3, $year)) {
                  array_push($year, 123);
                  }
                  if (in_array($value['product']['contract_duration'], $year)) {
                  $satisfied ++;  
                  }

            } 
            if (!empty($price_type)) {
            if($value['product']['type']=='pack'){

                  if((in_array('variable', $price_type) || in_array('fixed', $price_type)) && !in_array('combinatioin', $price_type)){
                        $total_condition++;
                        if (in_array($value['product']['underlying_products']['gas']['pricing_type'], $price_type) && in_array($value['product']['underlying_products']['electricity']['pricing_type'], $price_type) ) {
                        $satisfied ++;  
                        }
                  }

                  if((!in_array('variable', $price_type) || !in_array('fixed', $price_type)) && in_array('combinatioin', $price_type)){
                        $total_condition++;
                        if ($value['product']['underlying_products']['gas']['pricing_type'] != $value['product']['underlying_products']['electricity']['pricing_type'] )  {
                        $satisfied ++;
                        }

                  }

                  if(in_array('variable', $price_type)  && in_array('combinatioin', $price_type)){
                      $total_condition++;
                      if (in_array($value['product']['underlying_products']['gas']['pricing_type'], $price_type) && in_array($value['product']['underlying_products']['electricity']['pricing_type'], $price_type) && ($value['product']['underlying_products']['gas']['pricing_type'] != $value['product']['underlying_products']['electricity']['pricing_type']) )  {
                      $satisfied ++;
                      }
                  }

                  if(in_array('fixed', $price_type) && in_array('combinatioin', $price_type)){
                      $total_condition++;
                      if ((in_array($value['product']['underlying_products']['gas']['pricing_type'], $price_type) && in_array($value['product']['underlying_products']['electricity']['pricing_type'], $price_type)) && ($value['product']['underlying_products']['gas']['pricing_type'] != $value['product']['underlying_products']['electricity']['pricing_type']) )  {
                      $satisfied ++;
                      }
                  }

            }else{

                  $total_condition++;
                  if (in_array($value['product']['pricing_type'], $price_type)) {
                    $satisfied ++;  
                  }
            }

            }
            
            if (!empty($green)) {
                
                  $total_condition++;
                  
              
                  
                  if (in_array(0,$green)) {
                        if ($value['product']['green_percentage'] == 0) {
                        $satisfied ++;
                      }
                  }
                  if (in_array(100,$green)) {
                        if ($value['product']['green_percentage'] == 100) {
                        $satisfied ++;
                      }
                  }
                  
                  if (in_array(50,$green)) {
                        if ($value['product']['green_percentage'] == 100 && $value['product']['origin'] == 'BE') {
                        $satisfied ++;
                      }
                  }
                  
            }            
            if ($value['product']['type']=='pack' && $type=='electricity') {
                  $total_condition++;
                  if ($value['product']['underlying_products']['electricity']['type'] == $type) {
                    $satisfied ++;
                  }
            }
             if ($value['product']['type']=='pack' && $type=='gas') {
                  $total_condition++;
                  if ($value['product']['underlying_products']['gas']['type'] == $type) {                   
                    $satisfied ++;
                  }
            }
             if ($value['product']['type']!='pack' && $type!="") {
                  $total_condition++;
                  if ($value['product']['type'] == $type) {                   
                    $satisfied ++;
                  }
            }
            
             if (!empty($green_score)) {
                
               $total_condition++;
                if(in_array(0.5,$green_score)){
                    
                  
                    if ($value['supplier']['greenpeace_rating'] == 0.5) {
                    $satisfied ++;  
                    }
                }
                if(in_array(0.75,$green_score)){
                   
                  
                    if ($value['supplier']['greenpeace_rating'] == 0.75) {
                    $satisfied ++;  
                    }
                }
                if(in_array(1,$green_score)){
                    
                  
                    if ($value['supplier']['greenpeace_rating'] == 1) {
                    $satisfied ++;  
                    }
                }
    
            }
            
            if (!empty($ser_lim)) {
                
               if(in_array('domi',$ser_lim)){
                    $total_condition++;
                    if ($value['product']['service_level_payment'] == 'domi') {                   
                    $satisfied ++;
                    }
               }
               if(in_array('email',$ser_lim)){
                    $total_condition++;
                    if ($value['product']['service_level_invoicing'] == 'email') {                   
                    $satisfied ++;
                    }
               }
               if(in_array('online',$ser_lim)){
                    $total_condition++;
                    if ($value['product']['service_level_contact'] == 'online') {                   
                    $satisfied ++;
                    }
               }
               if(in_array('none',$ser_lim)){
                    $total_condition++;
                    if ($value['product']['service_level_payment'] == 'free' && $value['product']['service_level_invoicing'] == 'free' && $value['product']['service_level_contact'] == 'free') {                   
                    $satisfied ++;
                    }
               }
            }
            
          
            
            return $satisfied == $total_condition;
        });
        
        $getProducts=$products = $filter->all();
        $products= collect($products);
         if (!empty($disc)) {
              
                    if(in_array('promo',$disc)){
                    
                    Session::put('promo','true');
                    
                    }else{
                    
                    Session::put('promo','false');
                    }
                    
                    if(in_array('domi',$disc)){
                    
                    Session::put('domi','true');
                    
                    }else{
                    
                    Session::forget('domi');
                    
                    }
                    
                    if(in_array('email',$disc)){
                    
                    Session::put('email','true');
                    
                    }else{
                    
                    Session::forget('email');
                    }
                    
        if(Session::get('promo')=='true' || Session::get('domi')=='true' || Session::get('email')=='true'){
            
            
            if(Session::get('promo')=='true' && Session::get('domi')!='true' && Session::get('email')!='true'){
                
               
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')!='true' && Session::get('domi')=='true' && Session::get('email')!='true'){
                
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_slD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_slD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')!='true' && Session::get('domi')!='true' && Session::get('email')=='true'){
                
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')=='true' && Session::get('domi')=='true' && Session::get('email')!='true'){
                
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_slD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_slD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')=='true' && Session::get('domi')!='true' && Session::get('email')=='true'){
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')=='true' && Session::get('domi')=='true' && Session::get('email')=='true'){
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_slD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_slD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')!='true' && Session::get('domi')=='true' && Session::get('email')=='true'){
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_slD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_slD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
              
                
                
         }else{
             
             
             $sorted = $products->sort(function($a, $b) {
                  if ($a['price']['totals']['year']['excl_promo'] == $b['price']['totals']['year']['excl_promo']) {
                  return 0;
                  }
                  return ($a['price']['totals']['year']['excl_promo'] < $b['price']['totals']['year']['excl_promo']) ? -1 : 1;
                });
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['excl_promo'];
                })->first();
                $min=$sortedSingle['product']['id'];
             
         }
                    $products = $sorted;
              
            }else{
                
                Session::put('promo','false');
                Session::put('domi','false');
                Session::put('email','false');
             
             
             $sorted = $products->sort(function($a, $b) {
                  if ($a['price']['totals']['year']['excl_promo'] == $b['price']['totals']['year']['excl_promo']) {
                  return 0;
                  }
                  return ($a['price']['totals']['year']['excl_promo'] < $b['price']['totals']['year']['excl_promo']) ? -1 : 1;
                });
                
            $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['excl_promo'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
                $products = $sorted;
             }
             
             
             if(isset($request->activateDisc)){
                 
                Session::put('promo','true');
                Session::put('domi','true');
                Session::put('email','true');
                 
             }
             
             
        
        
        Session::put('product_data_sort',$products);
        $currentpage = 1;
        $pages = 1;
        $pageNumber=1;
        $html=""; $per="0"; $per1="0"; $si='0';
        $offset = 0;
        $perpage = 7;
        $totalProducts = $products->count();
        $totalPages = ceil($totalProducts / $perpage); 
        $currentPage = 1;
        $products =  $products->slice($offset, $perpage);
        $dual="";
        if($type){
            $packType=$type;
        }else{
            
            $packType="";
        }
        
       
        
       
         
        $service = ServiceLimitation::get();
        $feature = Feature::select('features.id','contract_details.part','contract_details.field', 'features.condition', 'features.NL_description', 'features.FR_description')
                           ->join('contract_details', 'contract_details.id', '=', 'features.contract_id')
                           ->get();
              if($totalProducts=='0'){
              $html.='<h3 class="not-fount">Er zijn geen leveranicers of producten die beantwoorden aan jouw voorkeuren.Zet je instellingen minder streng om een product te kunnen selecteren.</h3>';
              }
        $html.='<input type="hidden" class="count" value="'.$totalProducts.'">';
        $agent = new Agent();
              
        if($agent->isDesktop()){
              $si="0"; 
              foreach($products as $getdetails){
                  $si++;
                  if(Session::get('sel_type')!="" && Session::get('sel_type')==$packType){
                  $html .= \View::make('elements.product-item', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','dual'))->render();    

                  }elseif(Session::get('sel_type')!="" && Session::get('sel_type')!=$packType){

                  $html .= \View::make('elements.product-item-sep', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','type','dual'))->render();  

                  }else{

                  $html .= \View::make('elements.product-item', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','dual'))->render();
                  }

              }
         }elseif($agent->isTablet()){
              $si="0"; 
              foreach($products as $getdetails){
              $si++;
              $html .= \View::make('elements.product-item', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','dual'))->render();   
              }
       }else{
              $si="0"; 
              foreach($products as $getdetails){
              $si++;
              $html .= \View::make('mobile-view.home.elements.product-item', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','dual'))->render(); 
            }
      }
        echo $html;       
        
    }
    
    
    public function seperete_electricity(Request $request){
        
        // electric product data
        Session::put('seperate','seperate');
        $getParameters=Session::get('getParameters');
        
      
        $query['locale'] = $getParameters['parameters']['values']['locale'];
        $query['postalCode'] = $getParameters['parameters']['values']['postal_code'];
        $query['first_residence']=true;
        
        $query['customerGroup'] = $getParameters['parameters']['values']['customer_group'];
        
        $query['registerDay'] = (int)$getParameters['parameters']['values']['usage_day'];
        $query['registerNight'] = (int)$getParameters['parameters']['values']['usage_night'];
        $query['registerExclNight'] = (int)$getParameters['parameters']['values']['usage_excl_night'];
        $query['registerNormal'] = (int)$getParameters['parameters']['values']['usage_single'];
        
        //$query['uuid']= $getParameters['parameters']['uuid'];
        $query['meterType']= $getParameters['parameters']['values']['meter_type'];
        $query['category']= 'electricity';
        //Session::forget('select-pro');
        
        $query['IncludeE'] = 1;
        $query['IncludeG'] = 0;
        
        $query['decentralisedProduction'] =false;
        if($getParameters['parameters']['values']['decentralise_production']){
        $query['dec_pro'] = $getParameters['parameters']['values']['decentralise_production'];
        $query['capacity_decen_pro'] = $getParameters['parameters']['values']['capacity_decentalise'];
        $query['decentralisedProduction'] =true;
        }
        
        $query['residents'] =1;
        
        
        if(isset($request->refresh)){
           
            Session::forget('elecID');
            Session::forget('gasID');
            Session::forget('sel_type');
            Session::forget('selected_product');
            
            
        }
        if(isset($request->sep)){
            Session::forget('elecID');
            Session::forget('gasID');
            Session::forget('sel_type');
            Session::forget('selected_product');
            Session::put('seperate','seperate');
        }
       
       $query['uuid'] = $getParameters['parameters']['uuid'];
   
        
        try {

        $client = new \GuzzleHttp\Client(); 
                  $request = $client->post('https://api.tariefchecker.be/api/calculation', [
                      'headers' => [
                          'Accept' => 'application/json',
                          'Content-type' => 'application/x-www-form-urlencoded',
                          'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWFmMjVkYWNmZTNiM2I0MmZjOTJkMTU5MjIxY2RjNjNkY2MxMzEwZWU3NDJlM2YzNmRiOWZiMDZhZmMwNGMyNTgyNzEzNjRhYjU5Y2VkZGQiLCJpYXQiOjE2NDMyODY2MjcsIm5iZiI6MTY0MzI4NjYyNywiZXhwIjoyMjc0NDM4NjI3LCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.rmiDd2sM0kduf6CPed5rjbqPL4Fui-MDdOKViiPn49pcJEukW_kA2ByuJfHNUIe9rctIXsovX1T8kgeer6TxgQkGCvruO98zcGklVv470en8ul6NzOCMmaemX4cJj4XQYlcI1_-z6tnHqtbbc7_-TyvezidDGslhMAMmtREicgrubnp9VGyl6YtE_pXHedruJ7PxYsc2_Gqu-osdFOdEW6hxN3uPlpKbuHgrf9DvJr8B3PSDQLPl49Q9HzrL-vPgayZTpNFINpCw1QBKk_ooWo861UZQ_cE33TdNfXyoJ5WnXQ-AjvtInfw7C9skq57C9X4NmfsllWCacNn9IYNs4uocuFo259TbRXNuooHsWTDTty4kalcp3LD7G0exCTTDC3_QsEoZI6694ct8Fi0gOJ05thoS5grKIfKyFkRqu1eOS2wMdNs-6KZXwVQ6fv1sJE-VjdIKXoj-r6wo_FPSceB599yz22gwVQLnDQJAvu0OahSyU8DG3VMH__ItYBuTI0uOTJZerwaRwmnTkSWbWczA4c8AEb1H_W-G4Yblh4D9y_ZOW7FvvFj53dCX83mzUyBN3HahqzD8ZX0IvZXolZHLxluIOlFoR9HiLNzTZFrJSzWru39AjNmbaK8-AAydGlF606uGglo76ES7D7dOvDa5lgUjYzRvby8jDpRSkzY'],
                      'query' => $query 
                  ]);

        Session::forget('msg');
        $response = $request->getBody()->getContents();       
        $json = json_decode($response, true);

          } catch (\Exception $e) {

            $response = ['status' => false, 'message' => $e->getMessage()];

        }  
           
        
         
         $products = collect($json['products']);
         Session::put('elec_count',count($products));
         Session::put('product_data',$products);
         
         if(Session::get('promo')=='true' || Session::get('domi')=='true' || Session::get('email')=='true'){
              
                if(Session::get('promo')=='true' && Session::get('domi')!='true' && Session::get('email')!='true'){
                
               
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')!='true' && Session::get('domi')=='true' && Session::get('email')!='true'){
                
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_slD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_slD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')!='true' && Session::get('domi')!='true' && Session::get('email')=='true'){
                
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')=='true' && Session::get('domi')=='true' && Session::get('email')!='true'){
                
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_slD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_slD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')=='true' && Session::get('domi')!='true' && Session::get('email')=='true'){
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')=='true' && Session::get('domi')=='true' && Session::get('email')=='true'){
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_slD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_slD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')!='true' && Session::get('domi')=='true' && Session::get('email')=='true'){
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_slD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_slD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
                
         }else{
             
             
             $sorted = $products->sort(function($a, $b) {
                  if ($a['price']['totals']['year']['excl_promo'] == $b['price']['totals']['year']['excl_promo']) {
                  return 0;
                  }
                  return ($a['price']['totals']['year']['excl_promo'] < $b['price']['totals']['year']['excl_promo']) ? -1 : 1;
                });
                
                 $sorted1 = $products->sortBy(function ($item, $key) {
        return $item['price']['totals']['year']['excl_promo'];
        })->first();
         $min=$sorted1['product']['id']; 
             
         }

        $products = $sorted;
        
        
        // electric product end
        
       // $products= collect($products);
        Session::put('product_data_sort',$products);
        $currentpage = 1;
        $pages = 1;
        $pageNumber=1;
        $html=""; $per="0"; $per1="0"; $si='0';
        $offset = 0;
        $perpage = 7;
        $totalProducts = $products->count();
        $totalPages = ceil($totalProducts / $perpage); 
        $currentPage = 1;
        $products =  $products->slice($offset, $perpage);
        $dual="";
        $packType='electricity';
        $gas_count="";
        
       
        //$min=$sorted1['product']['id'];  
        $service = ServiceLimitation::get();
        $feature = Feature::select('features.id','contract_details.part','contract_details.field', 'features.condition', 'features.NL_description', 'features.FR_description')
                           ->join('contract_details', 'contract_details.id', '=', 'features.contract_id')
                           ->get();
              if($totalProducts=='0'){
              $html.='<h3 class="not-fount">Er zijn geen leveranicers of producten die beantwoorden aan jouw voorkeuren.Zet je instellingen minder streng om een product te kunnen selecteren.</h3>';
              }
        $html.='<input type="hidden" class="count" value="'.$totalProducts.'">';
        $agent = new Agent();
              
        if($agent->isDesktop()){
              $si="0"; 
              foreach($products as $getdetails){
                  $si++;
                  if(Session::get('sel_type')!="" && Session::get('sel_type')==$packType){
                  $html .= \View::make('elements.product-item', compact('totalProducts','getdetails','gas_count', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','dual'))->render();    

                  }elseif(Session::get('sel_type')!="" && Session::get('sel_type')!=$packType){

                  $html .= \View::make('elements.product-item-sep', compact('totalProducts','getdetails','gas_count', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','type','dual'))->render();  

                  }else{

                  $html .= \View::make('elements.product-item', compact('totalProducts','getdetails','gas_count', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','dual'))->render();
                  }

              }
         }elseif($agent->isTablet()){
              $si="0"; 
              foreach($products as $getdetails){
              $si++;
              $html .= \View::make('elements.product-item', compact('totalProducts','getdetails','gas_count', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','dual'))->render();   
              }
       }else{
              $si="0"; 
              foreach($products as $getdetails){
              $si++;
              $html .= \View::make('mobile-view.home.elements.product-item', compact('totalProducts','getdetails','gas_count', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','dual'))->render(); 
            }
      }
        echo $html;       
    }
    
    
    public function seperete_gas(){
        
        
        Session::put('seperate','seperate');
         // gas product data
        
        $getParameters=Session::get('getParameters');
        $query['locale'] = $getParameters['parameters']['values']['locale'];
        $query['postalCode'] = $getParameters['parameters']['values']['postal_code'];
        $query['first_residence']=true;
        $query['customerGroup'] = $getParameters['parameters']['values']['customer_group'];
        $query['registerG'] = $getParameters['parameters']['values']['usage_gas'];
        //$query['uuid']= $getParameters['parameters']['uuid'];
       // $query['category']= 'gas';
        $query['IncludeE'] = 0;
        $query['IncludeG'] = 1;
         $query['uuid'] = $getParameters['parameters']['uuid'];
      
        
        try {

        $client = new \GuzzleHttp\Client(); 
                  $request = $client->post('https://api.tariefchecker.be/api/calculation', [
                      'headers' => [
                          'Accept' => 'application/json',
                          'Content-type' => 'application/x-www-form-urlencoded',
                          'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWFmMjVkYWNmZTNiM2I0MmZjOTJkMTU5MjIxY2RjNjNkY2MxMzEwZWU3NDJlM2YzNmRiOWZiMDZhZmMwNGMyNTgyNzEzNjRhYjU5Y2VkZGQiLCJpYXQiOjE2NDMyODY2MjcsIm5iZiI6MTY0MzI4NjYyNywiZXhwIjoyMjc0NDM4NjI3LCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.rmiDd2sM0kduf6CPed5rjbqPL4Fui-MDdOKViiPn49pcJEukW_kA2ByuJfHNUIe9rctIXsovX1T8kgeer6TxgQkGCvruO98zcGklVv470en8ul6NzOCMmaemX4cJj4XQYlcI1_-z6tnHqtbbc7_-TyvezidDGslhMAMmtREicgrubnp9VGyl6YtE_pXHedruJ7PxYsc2_Gqu-osdFOdEW6hxN3uPlpKbuHgrf9DvJr8B3PSDQLPl49Q9HzrL-vPgayZTpNFINpCw1QBKk_ooWo861UZQ_cE33TdNfXyoJ5WnXQ-AjvtInfw7C9skq57C9X4NmfsllWCacNn9IYNs4uocuFo259TbRXNuooHsWTDTty4kalcp3LD7G0exCTTDC3_QsEoZI6694ct8Fi0gOJ05thoS5grKIfKyFkRqu1eOS2wMdNs-6KZXwVQ6fv1sJE-VjdIKXoj-r6wo_FPSceB599yz22gwVQLnDQJAvu0OahSyU8DG3VMH__ItYBuTI0uOTJZerwaRwmnTkSWbWczA4c8AEb1H_W-G4Yblh4D9y_ZOW7FvvFj53dCX83mzUyBN3HahqzD8ZX0IvZXolZHLxluIOlFoR9HiLNzTZFrJSzWru39AjNmbaK8-AAydGlF606uGglo76ES7D7dOvDa5lgUjYzRvby8jDpRSkzY'],
                      'query' => $query 
                  ]);

        Session::forget('msg');
        $response = $request->getBody()->getContents();       
        $json = json_decode($response, true);

          } catch (\Exception $e) {

            $response = ['status' => false, 'message' => $e->getMessage()];

        }  
        
      
        
         $products = collect($json['products']);
         Session::put('product_data',$products);
                
             if(Session::get('promo')=='true' || Session::get('domi')=='true' || Session::get('email')=='true'){
              
                if(Session::get('promo')=='true' && Session::get('domi')!='true' && Session::get('email')!='true'){
                
               
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')!='true' && Session::get('domi')=='true' && Session::get('email')!='true'){
                
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_slD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_slD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')!='true' && Session::get('domi')!='true' && Session::get('email')=='true'){
                
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')=='true' && Session::get('domi')=='true' && Session::get('email')!='true'){
                
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_slD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_slD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')=='true' && Session::get('domi')!='true' && Session::get('email')=='true'){
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')=='true' && Session::get('domi')=='true' && Session::get('email')=='true'){
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_slD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_slD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')!='true' && Session::get('domi')=='true' && Session::get('email')=='true'){
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_slD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_slD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
                
         }else{
             
             
             $sorted = $products->sort(function($a, $b) {
                  if ($a['price']['totals']['year']['excl_promo'] == $b['price']['totals']['year']['excl_promo']) {
                  return 0;
                  }
                  return ($a['price']['totals']['year']['excl_promo'] < $b['price']['totals']['year']['excl_promo']) ? -1 : 1;
                });
                
                 $sorted1 = $products->sortBy(function ($item, $key) {
        return $item['price']['totals']['year']['excl_promo'];
        })->first();
         $min=$sorted1['product']['id']; 
             
         }

        $products = $sorted;
        
        
        // gas product end
        
       // $products= collect($products);
        Session::put('product_data_sort',$products);
        $currentpage = 1;
        $pages = 1;
        $pageNumber=1;
        $html=""; $per="0"; $per1="0"; $si='0';
        $offset = 0;
        $perpage = 7;
        $totalProducts = $products->count();
        $totalPages = ceil($totalProducts / $perpage); 
        $currentPage = 1;
        $products =  $products->slice($offset, $perpage);
        $dual="";
        
            $packType='electricity';
        
        
       
        //$min=$sorted1['product']['id'];  
        $service = ServiceLimitation::get();
        $feature = Feature::select('features.id','contract_details.part','contract_details.field', 'features.condition', 'features.NL_description', 'features.FR_description')
                           ->join('contract_details', 'contract_details.id', '=', 'features.contract_id')
                           ->get();
              if($totalProducts=='0'){
              $html.='<h3 class="not-fount">Er zijn geen leveranicers of producten die beantwoorden aan jouw voorkeuren.Zet je instellingen minder streng om een product te kunnen selecteren.</h3>';
              }
        $html.='<input type="hidden" class="count" value="'.$totalProducts.'">';
        $agent = new Agent();
              
        if($agent->isDesktop()){
              $si="0"; 
              foreach($products as $getdetails){
                  $si++;
                  if(Session::get('sel_type')!="" && Session::get('sel_type')==$packType){
                  $html .= \View::make('elements.product-item', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','dual'))->render();    

                  }elseif(Session::get('sel_type')!="" && Session::get('sel_type')!=$packType){

                  $html .= \View::make('elements.product-item-sep', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','type','dual'))->render();  

                  }else{

                  $html .= \View::make('elements.product-item', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','dual'))->render();
                  }

              }
         }elseif($agent->isTablet()){
              $si="0"; 
              foreach($products as $getdetails){
              $si++;
              $html .= \View::make('elements.product-item', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','dual'))->render();   
              }
       }else{
              $si="0"; 
              foreach($products as $getdetails){
              $si++;
              $html .= \View::make('mobile-view.home.elements.product-item', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','dual'))->render(); 
            }
      }
        echo $html;       
        
        
    }
    
     public function seperete_pack(){
        
        
         // electric product data
        $getParameters=Session::get('getParameters');
        $query['locale'] = $getParameters['parameters']['values']['locale'];
        $query['postalCode'] = $getParameters['parameters']['values']['postal_code'];
        $query['first_residence']=$getParameters['parameters']['values']['first_residence'];
        
        $query['customerGroup'] = $getParameters['parameters']['values']['customer_group'];
        
        $query['registerDay'] = $getParameters['parameters']['values']['usage_day'];
        $query['registerNight'] = $getParameters['parameters']['values']['usage_night'];
        $query['registerExclNight'] = $getParameters['parameters']['values']['usage_excl_night'];
        $query['registerNormal'] = $getParameters['parameters']['values']['usage_single'];
        
        $query['meterType']= $getParameters['parameters']['values']['meter_type'];
        $query['registerG'] = $getParameters['parameters']['values']['usage_gas'];
         //$query['category'] = 'pack';
        $query['IncludeG'] =$getParameters['parameters']['values']['includeG'];
        $query['IncludeE'] =$getParameters['parameters']['values']['includeE'];
        Session::forget('elecID');
        Session::forget('gasID');
        Session::forget('selected_product');
        Session::forget('seperate');
         $query['uuid'] = $getParameters['parameters']['uuid'];
       
      
        
        try {

        $client = new \GuzzleHttp\Client(); 
                  $request = $client->post('https://api.tariefchecker.be/api/calculation', [
                      'headers' => [
                          'Accept' => 'application/json',
                          'Content-type' => 'application/x-www-form-urlencoded',
                          'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWFmMjVkYWNmZTNiM2I0MmZjOTJkMTU5MjIxY2RjNjNkY2MxMzEwZWU3NDJlM2YzNmRiOWZiMDZhZmMwNGMyNTgyNzEzNjRhYjU5Y2VkZGQiLCJpYXQiOjE2NDMyODY2MjcsIm5iZiI6MTY0MzI4NjYyNywiZXhwIjoyMjc0NDM4NjI3LCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.rmiDd2sM0kduf6CPed5rjbqPL4Fui-MDdOKViiPn49pcJEukW_kA2ByuJfHNUIe9rctIXsovX1T8kgeer6TxgQkGCvruO98zcGklVv470en8ul6NzOCMmaemX4cJj4XQYlcI1_-z6tnHqtbbc7_-TyvezidDGslhMAMmtREicgrubnp9VGyl6YtE_pXHedruJ7PxYsc2_Gqu-osdFOdEW6hxN3uPlpKbuHgrf9DvJr8B3PSDQLPl49Q9HzrL-vPgayZTpNFINpCw1QBKk_ooWo861UZQ_cE33TdNfXyoJ5WnXQ-AjvtInfw7C9skq57C9X4NmfsllWCacNn9IYNs4uocuFo259TbRXNuooHsWTDTty4kalcp3LD7G0exCTTDC3_QsEoZI6694ct8Fi0gOJ05thoS5grKIfKyFkRqu1eOS2wMdNs-6KZXwVQ6fv1sJE-VjdIKXoj-r6wo_FPSceB599yz22gwVQLnDQJAvu0OahSyU8DG3VMH__ItYBuTI0uOTJZerwaRwmnTkSWbWczA4c8AEb1H_W-G4Yblh4D9y_ZOW7FvvFj53dCX83mzUyBN3HahqzD8ZX0IvZXolZHLxluIOlFoR9HiLNzTZFrJSzWru39AjNmbaK8-AAydGlF606uGglo76ES7D7dOvDa5lgUjYzRvby8jDpRSkzY'],
                      'query' => $query 
                  ]);

        Session::forget('msg');
        $response = $request->getBody()->getContents();       
        $json = json_decode($response, true);

          } catch (\Exception $e) {

            $response = ['status' => false, 'message' => $e->getMessage()];

        }  
         
         
        
         $products = collect($json['products']);
         Session::put('product_data',$products);
                
                if(Session::get('promo')=='true' || Session::get('domi')=='true' || Session::get('email')=='true'){
                    
                    
              
                if(Session::get('promo')=='true' && Session::get('domi')!='true' && Session::get('email')!='true'){
                
               
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')!='true' && Session::get('domi')=='true' && Session::get('email')!='true'){
                
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_slD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_slD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')!='true' && Session::get('domi')!='true' && Session::get('email')=='true'){
                
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')=='true' && Session::get('domi')=='true' && Session::get('email')!='true'){
                
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_slD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_slD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')=='true' && Session::get('domi')!='true' && Session::get('email')=='true'){
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')=='true' && Session::get('domi')=='true' && Session::get('email')=='true'){
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_slD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_slD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')!='true' && Session::get('domi')=='true' && Session::get('email')=='true'){
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_slD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_slD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
                
         }else{
             
             
             $sorted = $products->sort(function($a, $b) {
                  if ($a['price']['totals']['year']['excl_promo'] == $b['price']['totals']['year']['excl_promo']) {
                  return 0;
                  }
                  return ($a['price']['totals']['year']['excl_promo'] < $b['price']['totals']['year']['excl_promo']) ? -1 : 1;
                });
                
                 $sorted1 = $products->sortBy(function ($item, $key) {
        return $item['price']['totals']['year']['excl_promo'];
        })->first();
        
        $min=$sorted1['product']['id']; 
             
         }

        $products = $sorted;
        
        
        // electric product end
        
       // $products= collect($products);
        Session::put('product_data_sort',$products);
        $currentpage = 1;
        $pages = 1;
        $pageNumber=1;
        $html=""; $per="0"; $per1="0"; $si='0';
        $offset = 0;
        $perpage = 7;
        $totalProducts = $products->count();
        $totalPages = ceil($totalProducts / $perpage); 
        $currentPage = 1;
        $products =  $products->slice($offset, $perpage);
        $dual="";
        
            $type="";
         
          if($type){
          $packType=$type;
          }else{

          $packType="";
          }
        
        
       
         
        $service = ServiceLimitation::get();
        $feature = Feature::select('features.id','contract_details.part','contract_details.field', 'features.condition', 'features.NL_description', 'features.FR_description')
                           ->join('contract_details', 'contract_details.id', '=', 'features.contract_id')
                           ->get();
              if($totalProducts=='0'){
              $html.='<h3 class="not-fount">Er zijn geen leveranicers of producten die beantwoorden aan jouw voorkeuren.Zet je instellingen minder streng om een product te kunnen selecteren.</h3>';
              }
        $html.='<input type="hidden" class="count" value="'.$totalProducts.'">';
        $agent = new Agent();
              
        if($agent->isDesktop()){
              $si="0"; 
              foreach($products as $getdetails){
                  $si++;
                //   if(Session::get('sel_type')!="" && Session::get('sel_type')==$packType){
                  $html .= \View::make('elements.product-item', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service'))->render();    

                //   }elseif(Session::get('sel_type')!="" && Session::get('sel_type')!=$packType){

                //   $html .= \View::make('elements.product-item-sep', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','type','dual'))->render();  

                //   }else{

                //   $html .= \View::make('elements.product-item', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','dual'))->render();
                //   }

              }
         }elseif($agent->isTablet()){
              $si="0"; 
              foreach($products as $getdetails){
              $si++;
              $html .= \View::make('elements.product-item', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service'))->render();   
              }
       }else{
              $si="0"; 
              foreach($products as $getdetails){
              $si++;
              $html .= \View::make('mobile-view.home.elements.product-item', compact('totalProducts','getdetails', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service'))->render(); 
            }
      }
        echo $html;       
        
        
    }
    
    public function filter_seperate(Request $request){

        $elec =Session::get('elec');
        $gas =Session::get('gas');
        $po =Session::get('po'); 
        Session::put('seperate','seperate');        
        $customer_type=Session::get('customer_type');
        $postal_code=Session::get('postal_code');
        $usage_elec_day=Session::get('usage_elec_day');
        $usage_elec_night=Session::get('usage_elec_night');
        $usage_gas=Session::get('usage_gas');          
        $year = $request->year;      
        $price_type = $request->price_type;
        $green = $request->green;
        $green_score = $request->green_score;
        $type = $request->type;
        Session::forget('selected_product');
        
        
        
        $dual='dual';
        $ser_lim = $request->ser_lim;
        $disc = $request->disc;
        $choose = $request->choose;
        
        
        
         // gas product data
         
        
        if($type=='gas'){
            
        $getParameters=Session::get('getParameters');
        $query['locale'] = $getParameters['parameters']['values']['locale'];
        $query['postalCode'] = $getParameters['parameters']['values']['postal_code'];
        $query['first_residence']=true;
        $query['customerGroup'] = $getParameters['parameters']['values']['customer_group'];
        $query['registerG'] = $getParameters['parameters']['values']['usage_gas'];
        //$query['uuid']= $getParameters['parameters']['uuid'];
        $query['IncludeE'] = false;
        $query['IncludeG'] = true;
        $query['category'] = 'gas';
        
        
    //     $getParameters=Session::get('getParameters');
    //     $queryGas['locale'] = $getParameters['parameters']['values']['locale'];
    //     $queryGas['postalCode'] = $getParameters['parameters']['values']['postal_code'];
    //     $queryGas['first_residence']=true;
    //     $queryGas['customerGroup'] = $getParameters['parameters']['values']['customer_group'];
    //     $queryGas['registerG'] = $getParameters['parameters']['values']['usage_gas'];
    //   // $queryGas['category']=  $getParameters['parameters']['values']['comparison_type'];
    //     $queryGas['IncludeE'] = false;
    //     $queryGas['IncludeG'] = true;
       
      
        
        try {

        $client = new \GuzzleHttp\Client(); 
                  $request = $client->post('https://api.tariefchecker.be/api/calculation', [
                      'headers' => [
                          'Accept' => 'application/json',
                          'Content-type' => 'application/x-www-form-urlencoded',
                          'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWFmMjVkYWNmZTNiM2I0MmZjOTJkMTU5MjIxY2RjNjNkY2MxMzEwZWU3NDJlM2YzNmRiOWZiMDZhZmMwNGMyNTgyNzEzNjRhYjU5Y2VkZGQiLCJpYXQiOjE2NDMyODY2MjcsIm5iZiI6MTY0MzI4NjYyNywiZXhwIjoyMjc0NDM4NjI3LCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.rmiDd2sM0kduf6CPed5rjbqPL4Fui-MDdOKViiPn49pcJEukW_kA2ByuJfHNUIe9rctIXsovX1T8kgeer6TxgQkGCvruO98zcGklVv470en8ul6NzOCMmaemX4cJj4XQYlcI1_-z6tnHqtbbc7_-TyvezidDGslhMAMmtREicgrubnp9VGyl6YtE_pXHedruJ7PxYsc2_Gqu-osdFOdEW6hxN3uPlpKbuHgrf9DvJr8B3PSDQLPl49Q9HzrL-vPgayZTpNFINpCw1QBKk_ooWo861UZQ_cE33TdNfXyoJ5WnXQ-AjvtInfw7C9skq57C9X4NmfsllWCacNn9IYNs4uocuFo259TbRXNuooHsWTDTty4kalcp3LD7G0exCTTDC3_QsEoZI6694ct8Fi0gOJ05thoS5grKIfKyFkRqu1eOS2wMdNs-6KZXwVQ6fv1sJE-VjdIKXoj-r6wo_FPSceB599yz22gwVQLnDQJAvu0OahSyU8DG3VMH__ItYBuTI0uOTJZerwaRwmnTkSWbWczA4c8AEb1H_W-G4Yblh4D9y_ZOW7FvvFj53dCX83mzUyBN3HahqzD8ZX0IvZXolZHLxluIOlFoR9HiLNzTZFrJSzWru39AjNmbaK8-AAydGlF606uGglo76ES7D7dOvDa5lgUjYzRvby8jDpRSkzY'],
                      'query' => $query 
                  ]);

        Session::forget('msg');
        $response = $request->getBody()->getContents();       
        $json = json_decode($response, true);

          } catch (\Exception $e) {

            $response = ['status' => false, 'message' => $e->getMessage()];

        }  
        
        
       
         $products = collect($json['products']);
         Session::put('product_data',$products);
                
               if(Session::get('promo')=='true' || Session::get('domi')=='true' || Session::get('email')=='true'){
              
                 if(Session::get('promo')=='true' && Session::get('domi')!='true' && Session::get('email')!='true'){
                
               
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')!='true' && Session::get('domi')=='true' && Session::get('email')!='true'){
                
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_slD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_slD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')!='true' && Session::get('domi')!='true' && Session::get('email')=='true'){
                
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')=='true' && Session::get('domi')=='true' && Session::get('email')!='true'){
                
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_slD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_slD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')=='true' && Session::get('domi')!='true' && Session::get('email')=='true'){
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')=='true' && Session::get('domi')=='true' && Session::get('email')=='true'){
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_slD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_slD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')!='true' && Session::get('domi')=='true' && Session::get('email')=='true'){
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_slD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_slD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
                
         }else{
             
             
             $sorted = $products->sort(function($a, $b) {
                  if ($a['price']['totals']['year']['excl_promo'] == $b['price']['totals']['year']['excl_promo']) {
                  return 0;
                  }
                  return ($a['price']['totals']['year']['excl_promo'] < $b['price']['totals']['year']['excl_promo']) ? -1 : 1;
                });
                
                 $sorted1 = $products->sortBy(function ($item, $key) {
        return $item['price']['totals']['year']['excl_promo'];
        })->first();
              $min=$sorted1['product']['id']; 
         }

        $products = $sorted;
        
        }
        
        
        // gas product end
        
        // electric product data
        if($type=='electricity'){
           
        $getParameters=Session::get('packParameterData');
        $query['locale'] = $getParameters['parameters']['values']['locale'];
        $query['postalCode'] = $getParameters['parameters']['values']['postal_code'];
        $query['first_residence']=true;
        
        $query['customerGroup'] = $getParameters['parameters']['values']['customer_group'];
        
        $query['registerDay'] = $getParameters['parameters']['values']['usage_day'];
        $query['registerNight'] = $getParameters['parameters']['values']['usage_night'];
        $query['registerExclNight'] = $getParameters['parameters']['values']['usage_excl_night'];
        $query['registerNormal'] = $getParameters['parameters']['values']['usage_single'];
        
        $query['meter_type']= $getParameters['parameters']['values']['meter_type'];
        $query['IncludeE'] = 1;
        $query['IncludeG'] = 0;
         $query['category'] = 'electricity';
      
        // $query['category'] = 'pack';
        
        
        try {

        $client = new \GuzzleHttp\Client(); 
                  $request = $client->post('https://api.tariefchecker.be/api/calculation', [
                      'headers' => [
                          'Accept' => 'application/json',
                          'Content-type' => 'application/x-www-form-urlencoded',
                          'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWFmMjVkYWNmZTNiM2I0MmZjOTJkMTU5MjIxY2RjNjNkY2MxMzEwZWU3NDJlM2YzNmRiOWZiMDZhZmMwNGMyNTgyNzEzNjRhYjU5Y2VkZGQiLCJpYXQiOjE2NDMyODY2MjcsIm5iZiI6MTY0MzI4NjYyNywiZXhwIjoyMjc0NDM4NjI3LCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.rmiDd2sM0kduf6CPed5rjbqPL4Fui-MDdOKViiPn49pcJEukW_kA2ByuJfHNUIe9rctIXsovX1T8kgeer6TxgQkGCvruO98zcGklVv470en8ul6NzOCMmaemX4cJj4XQYlcI1_-z6tnHqtbbc7_-TyvezidDGslhMAMmtREicgrubnp9VGyl6YtE_pXHedruJ7PxYsc2_Gqu-osdFOdEW6hxN3uPlpKbuHgrf9DvJr8B3PSDQLPl49Q9HzrL-vPgayZTpNFINpCw1QBKk_ooWo861UZQ_cE33TdNfXyoJ5WnXQ-AjvtInfw7C9skq57C9X4NmfsllWCacNn9IYNs4uocuFo259TbRXNuooHsWTDTty4kalcp3LD7G0exCTTDC3_QsEoZI6694ct8Fi0gOJ05thoS5grKIfKyFkRqu1eOS2wMdNs-6KZXwVQ6fv1sJE-VjdIKXoj-r6wo_FPSceB599yz22gwVQLnDQJAvu0OahSyU8DG3VMH__ItYBuTI0uOTJZerwaRwmnTkSWbWczA4c8AEb1H_W-G4Yblh4D9y_ZOW7FvvFj53dCX83mzUyBN3HahqzD8ZX0IvZXolZHLxluIOlFoR9HiLNzTZFrJSzWru39AjNmbaK8-AAydGlF606uGglo76ES7D7dOvDa5lgUjYzRvby8jDpRSkzY'],
                      'query' => $query 
                  ]);

        Session::forget('msg');
        $response = $request->getBody()->getContents();       
        $json = json_decode($response, true);

          } catch (\Exception $e) {

            $response = ['status' => false, 'message' => $e->getMessage()];

        }  
         
         $products = collect($json['products']);
        
         Session::put('product_data',$products);
                
               if(Session::get('promo')=='true' || Session::get('domi')=='true' || Session::get('email')=='true'){
              
                if(Session::get('promo')=='true' && Session::get('domi')!='true' && Session::get('email')!='true'){
                
               
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')!='true' && Session::get('domi')=='true' && Session::get('email')!='true'){
                
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_slD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_slD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')!='true' && Session::get('domi')!='true' && Session::get('email')=='true'){
                
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            if(Session::get('promo')=='true' && Session::get('domi')=='true' && Session::get('email')!='true'){
                
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_slD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_slD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')=='true' && Session::get('domi')!='true' && Session::get('email')=='true'){
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')=='true' && Session::get('domi')=='true' && Session::get('email')=='true'){
                
                
                 $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_promoD_slD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_promoD_slD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
            
            if(Session::get('promo')!='true' && Session::get('domi')=='true' && Session::get('email')=='true'){
                
                
                $sorted = $products->sortBy(function ($product, $key) {
                    return $product['price']['totals']['year']['incl_slD_loyaltyD'];
                });

                $sorted->values()->all();
                
                
                $sortedSingle = $sorted->sortBy(function ($item, $key) {
                return $item['price']['totals']['year']['incl_slD_loyaltyD'];
                })->first();
                $min=$sortedSingle['product']['id'];
                
            }
                
         }else{
             
             
             $sorted = $products->sort(function($a, $b) {
                  if ($a['price']['totals']['year']['excl_promo'] == $b['price']['totals']['year']['excl_promo']) {
                  return 0;
                  }
                  return ($a['price']['totals']['year']['excl_promo'] < $b['price']['totals']['year']['excl_promo']) ? -1 : 1;
                });
                
                 $sorted1 = $products->sortBy(function ($item, $key) {
        return $item['price']['totals']['year']['excl_promo'];
        })->first();
        $min=$sorted1['product']['id'];
             
         }

        $products = $sorted;
        }
        
        // electric product end
        
        
       
        
        $collection = $products;
        
        // $products= collect($products);
        Session::put('product_data_sort',$products);
        $currentpage = 1;
        $pages = 1;
        $pageNumber=1;
        $html=""; $per="0"; $per1="0"; $si='0';
        $offset = 0;
        $perpage = 7;
        $totalProducts = $products->count();
        $totalPages = ceil($totalProducts / $perpage); 
        $currentPage = 1;
        $products =  $products->slice($offset, $perpage);
        $dual="";
        $packType='electricity';
        $gas_count="";
        
       
        
        $service = ServiceLimitation::get();
        $feature = Feature::select('features.id','contract_details.part','contract_details.field', 'features.condition', 'features.NL_description', 'features.FR_description')
                           ->join('contract_details', 'contract_details.id', '=', 'features.contract_id')
                           ->get();
              if($totalProducts=='0'){
              $html.='<h3 class="not-fount">Er zijn geen leveranicers of producten die beantwoorden aan jouw voorkeuren.Zet je instellingen minder streng om een product te kunnen selecteren.</h3>';
              }
        $html.='<input type="hidden" class="count" value="'.$totalProducts.'">';
        $agent = new Agent();
              
              
             
        if($agent->isDesktop()){
              $si="0"; 
              foreach($products as $getdetails){
                  $si++;
                 
                  $html .= \View::make('elements.product-item-sep', compact('totalProducts','getdetails','gas_count', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','type','dual'))->render();  

                  

              }
         }elseif($agent->isTablet()){
              $si="0"; 
              foreach($products as $getdetails){
              $si++;
              $html .= \View::make('elements.product-item', compact('totalProducts','getdetails','gas_count', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','dual'))->render();   
              }
       }else{
              $si="0"; 
              foreach($products as $getdetails){
              $si++;
              $html .= \View::make('mobile-view.home.elements.product-item-sep', compact('totalProducts','getdetails','gas_count', 'customer_type', 'postal_code', 'usage_elec_day', 'usage_elec_night', 'usage_gas','totalPages', 'pageNumber','si','totalProducts','packType','feature','min','service','type','dual'))->render(); 
            }
      }
        echo $html;       
    }
    
      public function back_to_packages()
      {             
        $elec =Session::get('elec');
        $gas =Session::get('gas');
        $po =Session::get('po');
        $customer_type=Session::get('customer_type');
        $postal_code=Session::get('postal_code');
        $usage_elec_day=Session::get('usage_elec_day');
        $usage_elec_night=Session::get('usage_elec_night');
        $usage_gas=Session::get('usage_gas');
        $products = Session::get('product_data');
        $page='sort';
        $ele_count = $products->filter(function($value, $key) {
            if ($value['product']['type'] == 'pack') {
                  if (array_key_exists("electricity", $value['product']["underlying_products"])) {
                  return true;
                  }
            } else {
                  if ($value['product']['type'] == 'electricity') {
                  return true;
                  }
            }
        });
        $gas_count = $products->filter(function($value, $key) {
              if ($value['product']['type'] == 'pack') {
                  if (array_key_exists("gas", $value['product']["underlying_products"])) {
                  return true;
                  }
              } else {
                  if ($value['product']['type'] == 'gas') {
                  return true;
                  }
              }
        });
        return view('home.index', compact('elec', 'gas', 'po', 'products','customer_type','postal_code','usage_elec_day','usage_elec_night','usage_gas','ele_count','gas_count','page'));
    }
    
    public function detailPack($id,$id2){
        
       
        $po=Session::get('postal_code');
        return redirect('overzicht/pack');        
        
    }
    
    public function get_save_price(){        
        echo 'hii';
    }
    
    public function product_select(Request $request){

      $id=$request->id;
      $type=$request->type;
      $supplier=$request->supplier;
      $product=$request->product;
      Session::put('selected_product',$id);
      
      $collection = Session::get('product_data');
      
      
      
      $array = array();
      
      if($type=='gas'){
          
          $filter = $collection->filter(function($value, $key) use ($id) {
                if ($value['product']['id']== $id) {
                return true;
            }
            });
            
          Session::put('gasID',$filter);
          
      }else{
          
          $filter = $collection->filter(function($value, $key) use ($id) {
                if ($value['product']['id']== $id) {
                return true;
            }
            });
          
          Session::put('elecID',$filter);
          
      }

       
     
      
      $array['type'] = $type;
      $array['id'] = $id;
      $array['supplier'] = $supplier;
      $array['product'] = $product;
      $array['url1'] =$request->url;
      $array['pid'] =$request->pid;
      $array['egid'] =$request->egid;
      
   
      Session::put('sel_type',$type);

      if (App::isLocale('nl')) {          
          $for='voor';          
          if($type=='gas'){              
            $type=trans('home.Gas');
          }else{             
           $type=trans('home.Electricity');              
          }    
       }else{           
          $for='pour';
          if($type=='gas'){
          $type=trans('home.Gas');
          }else{
          $type=trans('home.Electricity');
          }           
           
       }

      Session::put('select-pro',$array);
          $html="";
          $html.='<span class="selected-sec"><span class="select-sec-i"><i class="fa fa-info-circle"></i></span><span class="select-sec-cont"> '.trans("home.You_have_selected").' <b>'.$supplier.' - '.$product.'</b> '.$for.' '.$type.'. 
          </span><span class="close-selected" id="close-select"><i class="fa fa-times"></i></span>
          </span>';

      echo $html;
    }
    
  public function check_po(Request $request){

          $query['postal_code']=$postal=$request->po;
          $query['locale'] = Session::get('locale');
          $query['customer_type'] ='residential';
          $query['usage[electricity][single]'] =1000;
          $response = Storage::disk('local')->get('po.json');
          $json = json_decode($response, true);    
          $check=array_key_exists($postal,$json['electricity']);
          if($request->mun){
          $mun=$request->mun;
          }else
          {
          $mun=null;
          }
    
          if($check==true){        
                $html="";
                $data['available']='true';
                $sub=count($json['electricity'][$postal]); 
                if($sub > 0){
                    $data['sub']='true';
                    $sub_po=$json['electricity'][$postal];
                    foreach ($sub_po as $sub_pos) {
                      foreach ($sub_pos as $value) {
                      $sub_pos1[]=$value;
                      }
                    }
                    if(count($sub_pos1)>1){
                      $data['sub_po']=$html .= \View::make('elements.postal', compact('sub_pos1','mun'))->render(); 
                    }else{
                      $data['sub']='false';
                    }
                }
          }else{        
              $data['available']='false';        
          }            
         return $data; 
  }
  
//   public function defaultData(Request $request)
//   {
      
//   }

    public function modalData(Request $request)
    {
        $email = $request->email;
        
        $parameters = Session::get('getParameters');
        // active campaig updating
        
            $activeQueries['uuid']=$parameters['parameters']['uuid'];
            $activeQueries['customer_group']=$parameters['parameters']['values']['customer_group'];
            $activeQueries['region']=$parameters['parameters']['values']['region'];
            $activeQueries['usage_single']=Session::get('usage_single');
            $activeQueries['usage_day']=Session::get('usage_day');
            $activeQueries['usage_night']=Session::get('usage_night');
            $activeQueries['usage_excl_night']=Session::get('usage_excl_night');
            $activeQueries['usage_gas']=Session::get('usage_gas');
            $activeQueries['meter_type']=$parameters['parameters']['values']['meter_type'];
          
            $activeQueries['comparison_type']=Session::get('comparison_type');
            $activeQueries['email']=$email;
            $activeQueries['postalcode']=Session::get('postal_code');
            $activeQueries['url']=Session::get('actual_link');
            $activeQueries['locale']=Session::get('locale');
            
        try { 
       $client = new \GuzzleHttp\Client(); 
                  $request = $client->post('https://api.tariefchecker.be/api/change-data-sync', [
                      'headers' => [
                          'Accept' => 'application/json',
                          'Content-type' => 'application/x-www-form-urlencoded',
                          'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWFmMjVkYWNmZTNiM2I0MmZjOTJkMTU5MjIxY2RjNjNkY2MxMzEwZWU3NDJlM2YzNmRiOWZiMDZhZmMwNGMyNTgyNzEzNjRhYjU5Y2VkZGQiLCJpYXQiOjE2NDMyODY2MjcsIm5iZiI6MTY0MzI4NjYyNywiZXhwIjoyMjc0NDM4NjI3LCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.rmiDd2sM0kduf6CPed5rjbqPL4Fui-MDdOKViiPn49pcJEukW_kA2ByuJfHNUIe9rctIXsovX1T8kgeer6TxgQkGCvruO98zcGklVv470en8ul6NzOCMmaemX4cJj4XQYlcI1_-z6tnHqtbbc7_-TyvezidDGslhMAMmtREicgrubnp9VGyl6YtE_pXHedruJ7PxYsc2_Gqu-osdFOdEW6hxN3uPlpKbuHgrf9DvJr8B3PSDQLPl49Q9HzrL-vPgayZTpNFINpCw1QBKk_ooWo861UZQ_cE33TdNfXyoJ5WnXQ-AjvtInfw7C9skq57C9X4NmfsllWCacNn9IYNs4uocuFo259TbRXNuooHsWTDTty4kalcp3LD7G0exCTTDC3_QsEoZI6694ct8Fi0gOJ05thoS5grKIfKyFkRqu1eOS2wMdNs-6KZXwVQ6fv1sJE-VjdIKXoj-r6wo_FPSceB599yz22gwVQLnDQJAvu0OahSyU8DG3VMH__ItYBuTI0uOTJZerwaRwmnTkSWbWczA4c8AEb1H_W-G4Yblh4D9y_ZOW7FvvFj53dCX83mzUyBN3HahqzD8ZX0IvZXolZHLxluIOlFoR9HiLNzTZFrJSzWru39AjNmbaK8-AAydGlF606uGglo76ES7D7dOvDa5lgUjYzRvby8jDpRSkzY'
                      ],
                      'query' => $activeQueries 
                  ]);
        
        } catch (\Exception $e) {
        $response = ['status' => false, 'message' => $e->getMessage()];
        }
        return 'success';
    }
    
    public function estimate(Request $request){
        
        
        $query['residence']=$request->residence;
        $query['building_type']=$request->building_type;
        $query['isolation_level']=$request->isolation_level;
        $query['heating_system']=$request->heating_system;
        
        
         try {

        $client = new \GuzzleHttp\Client(); 
                  $request = $client->post('https://api.tariefchecker.be/api/estimate-consumtion', [
                      'headers' => [
                          'Accept' => 'application/json',
                          'Content-type' => 'application/x-www-form-urlencoded',
                          'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWFmMjVkYWNmZTNiM2I0MmZjOTJkMTU5MjIxY2RjNjNkY2MxMzEwZWU3NDJlM2YzNmRiOWZiMDZhZmMwNGMyNTgyNzEzNjRhYjU5Y2VkZGQiLCJpYXQiOjE2NDMyODY2MjcsIm5iZiI6MTY0MzI4NjYyNywiZXhwIjoyMjc0NDM4NjI3LCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.rmiDd2sM0kduf6CPed5rjbqPL4Fui-MDdOKViiPn49pcJEukW_kA2ByuJfHNUIe9rctIXsovX1T8kgeer6TxgQkGCvruO98zcGklVv470en8ul6NzOCMmaemX4cJj4XQYlcI1_-z6tnHqtbbc7_-TyvezidDGslhMAMmtREicgrubnp9VGyl6YtE_pXHedruJ7PxYsc2_Gqu-osdFOdEW6hxN3uPlpKbuHgrf9DvJr8B3PSDQLPl49Q9HzrL-vPgayZTpNFINpCw1QBKk_ooWo861UZQ_cE33TdNfXyoJ5WnXQ-AjvtInfw7C9skq57C9X4NmfsllWCacNn9IYNs4uocuFo259TbRXNuooHsWTDTty4kalcp3LD7G0exCTTDC3_QsEoZI6694ct8Fi0gOJ05thoS5grKIfKyFkRqu1eOS2wMdNs-6KZXwVQ6fv1sJE-VjdIKXoj-r6wo_FPSceB599yz22gwVQLnDQJAvu0OahSyU8DG3VMH__ItYBuTI0uOTJZerwaRwmnTkSWbWczA4c8AEb1H_W-G4Yblh4D9y_ZOW7FvvFj53dCX83mzUyBN3HahqzD8ZX0IvZXolZHLxluIOlFoR9HiLNzTZFrJSzWru39AjNmbaK8-AAydGlF606uGglo76ES7D7dOvDa5lgUjYzRvby8jDpRSkzY'],
                      'query' => $query 
                  ]);

        
        $response = $request->getBody()->getContents();       
        $json = json_decode($response, true);

          } catch (\Exception $e) {

            $response = ['status' => false, 'message' => $e->getMessage()];

        }
        
        return $response;
        
    }
    
        public function estimate_cal(Request $request){
         
        
        $query['capacity_decen_pro']=$request->capacity_decen_pro;
        $query['consuption1']=$request->consuption1;
        $query['consuption1se']=$request->consuption1se;
        $query['consuption_day_elec1']=$request->consuption_day_elec1;
        $query['consuption_night_elec1']=$request->consuption_night_elec1;
        $query['consuption_excl_night']=$request->consuption_excl_night;
        $query['consumtion_gas1']=$request->consumtion_gas1;
        $query['consuption_day_elec1de']=$request->consuption_day_elec1de;
        $query['consuption_night_elec1de']=$request->consuption_night_elec1de;
        $query['consuption_excl_nightde']=$request->consuption_excl_nightde;
        
        
         try {

        $client = new \GuzzleHttp\Client(); 
                  $request = $client->post('https://api.tariefchecker.be/api/estimate-consumtion-cal', [
                      'headers' => [
                          'Accept' => 'application/json',
                          'Content-type' => 'application/x-www-form-urlencoded',
                          'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWFmMjVkYWNmZTNiM2I0MmZjOTJkMTU5MjIxY2RjNjNkY2MxMzEwZWU3NDJlM2YzNmRiOWZiMDZhZmMwNGMyNTgyNzEzNjRhYjU5Y2VkZGQiLCJpYXQiOjE2NDMyODY2MjcsIm5iZiI6MTY0MzI4NjYyNywiZXhwIjoyMjc0NDM4NjI3LCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.rmiDd2sM0kduf6CPed5rjbqPL4Fui-MDdOKViiPn49pcJEukW_kA2ByuJfHNUIe9rctIXsovX1T8kgeer6TxgQkGCvruO98zcGklVv470en8ul6NzOCMmaemX4cJj4XQYlcI1_-z6tnHqtbbc7_-TyvezidDGslhMAMmtREicgrubnp9VGyl6YtE_pXHedruJ7PxYsc2_Gqu-osdFOdEW6hxN3uPlpKbuHgrf9DvJr8B3PSDQLPl49Q9HzrL-vPgayZTpNFINpCw1QBKk_ooWo861UZQ_cE33TdNfXyoJ5WnXQ-AjvtInfw7C9skq57C9X4NmfsllWCacNn9IYNs4uocuFo259TbRXNuooHsWTDTty4kalcp3LD7G0exCTTDC3_QsEoZI6694ct8Fi0gOJ05thoS5grKIfKyFkRqu1eOS2wMdNs-6KZXwVQ6fv1sJE-VjdIKXoj-r6wo_FPSceB599yz22gwVQLnDQJAvu0OahSyU8DG3VMH__ItYBuTI0uOTJZerwaRwmnTkSWbWczA4c8AEb1H_W-G4Yblh4D9y_ZOW7FvvFj53dCX83mzUyBN3HahqzD8ZX0IvZXolZHLxluIOlFoR9HiLNzTZFrJSzWru39AjNmbaK8-AAydGlF606uGglo76ES7D7dOvDa5lgUjYzRvby8jDpRSkzY'],
                      'query' => $query 
                  ]);

        
        $response = $request->getBody()->getContents();       
        $json = json_decode($response, true);

          } catch (\Exception $e) {

            $response = ['status' => false, 'message' => $e->getMessage()];

        }
        
        return $response;
        
    }
   
}
