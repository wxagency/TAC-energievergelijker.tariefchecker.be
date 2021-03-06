<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\UserLog;
use Carbon\Carbon;
use App\Http\Controllers\Subscription;
use App\Models\Title;
use App\Models\SubtitleContent;
use App\Models\Videocontent;
use Session;
use Jenssegers\Agent\Agent;
use Redirect;

class UserdetailController extends Controller
{
    
    
    public function index(Request $request)
    {
        
  
            if($request->from)
            {
            Session::put('from',$request->from);
            }else
            {
            Session::forget('from');
            }
            Session::forget('sel_type');
            app()->setLocale(Session::get('locale'));
            
            $data['id']=$id= $request->pro_id;
            $data['type']= $request->type;
            $data['pr_type']= $request->pr_type;
            $data['postalcode']= $request->postalcode;
            $data['elect_day']= $request->elect_day;
            $data['elect_night']= $request->elect_night;
            $data['gas_cons']= $request->gas_cons;
            $data['sub_url']= $request->sub_url;
            $data['pri_save']= $request->pri_save;
            $page='request';
            $arr=Session::get('select-pro');
            $collection = Session::get('product_data');
            $selected['packData']=null;
            if($request->pro_type=='electricity')
            {
                
                    $filter = $collection->filter(function($value, $key) use ($id) 
                    {
                    if ($value['product']['id']== $id) 
                    {
                    return true;
                    }
                    });
                    Session::put('elecID',$filter);
                    
                     
                    // $data['product']=$arr['product'];
                    // $data['supplier']=$arr['supplier'];
                    // $data['url1']=$arr['url1'];
                    // $data['pid']=$arr['pid'];
                    // $data['type']=$arr['type'];
                    // $data['egid']=$arr['egid'];
                    // Session::put('url',$data['url1']);
                    // Session::put('from',$request->from);
                    $product=$filter->all();
            
            }elseif($request->pro_type=='gas'){
                
                    $filter = $collection->filter(function($value, $key) use ($id) {
                    if ($value['product']['id']== $id) {
                    return true;
                    }
                    });
                    Session::put('gasID',$filter);
                    $data['product']=$arr['product'];
                    $data['supplier']=$arr['supplier'];
                    $data['url1']=$arr['url1'];
                    $data['pid']=$arr['pid'];
                    $data['type']=$arr['type'];
                    $data['egid']=$arr['egid'];
                    Session::put('url',$data['url1']);
                    Session::put('from',$request->from);
                    
                    $product=$filter->all();
            
            }else{
                
                    Session::forget('gasID');
                    Session::forget('elecID');
                    $collection = Session::get('pro_data');
                    
                    $filter = $collection->filter(function($value, $key) use ($id) {
                    if ($value['product']['id']== $id) {
                    return true;
                    }
                    });
                    
                    $selected['packData']=$filter;
                    $product=$filter->all();
                    
                    // $data['product']=$request->product;
                    // $data['supplier']=$request->supplier;
                    // $data['pid']=$request->pro_id;
                    // $data['type']=$request->pro_type;
                    // $data['egid']=$request->pro_id;
           
            }
            
            // $data['url1']=$arr['url1'];
            // Session::put('url',$data['url1']);
            Session::put('from',$request->from);
            $data['from']=$request->from;
            
            
            
            // $collection = Session::get('product_data');
          
           
         

            // $filter = $collection->filter(function($value, $key) use ($id) {
            // if ($value['product']['id']== $id) {
            // return true;
            // }
            // });
            // $filter->take(1);
            // $product=$filter->all();
            
            
            
            $selected['gasData']=Session::get('gasID');
            $selected['eleData']=Session::get('elecID');
            
 
            Session::put('selected',$selected);
            
            $subcontent = SubtitleContent::select('subtitle_contents.id','subtitle_contents.FR_subtitle','subtitle_contents.NL_subtitle','subtitle_contents.NL_content','subtitle_contents.FR_content','subtitle_contents.title_id','subtitle_contents.subtitle',
            'subtitle_contents.content','titles.title','titles.NL_title','titles.FR_title')
            ->join('titles', 'titles.id', '=', 'subtitle_contents.title_id')
            ->get();
            
            $video = new Videocontent();
            $videocontent = Videocontent::get();
            Session::put('data',$data);
            Session::forget('select-pro');
            $agent = new Agent();
            if($agent->isDesktop()){
            return view('user.tacrequest', compact('subcontent', 'videocontent','product','data','page','video','selected'));
            }elseif($agent->isTablet()){
            return view('user.tacrequest', compact('subcontent', 'videocontent','data','page','video','selected'));
            }else{
            return view('mobile-view.user.tacrequest', compact('subcontent', 'videocontent','data','page','video','selected'));
            
            }
    }

   
    public function store(Request $request)
    {
        
            if($request->type=='pack' || $request->type=='gas' || $request->type=='electricity'){
            $validate = [
            'uuid' => 'required|string',
            'email' => 'required|email',
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'type' => 'required|string',
            'id' => 'required|integer'           
            ];
            }
            
            if($request->type=='dual'){
            $validate = [
            'uuid' => 'required|string',
            'email' => 'required|email',
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'type' => 'required|string',
            'eid' => 'required|integer',
            'gid' => 'required|integer',
            ];
            }
            
            $this->validate($request, $validate);
            
         
            $response = [];
            try {
            $client = new \GuzzleHttp\Client();
            $query = $request->only(array_keys($validate));
            $api = $client->post('https://api.tariefchecker.be/conversions', [
            'headers' => [
            'Accept' => 'application/json',
            'Content-type' => 'application/json',
            'Authorization' => 'Basic anVuZToxNjUyNGZjOTdiMjUyYTBhOGU3YzYyNWUxNTk1NzdmYw=='
            ],
            'query' => $query
            ]);
            $data = $api->getBody()->getContents();
            $response = json_decode($data, true);
            } catch (\Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];
            }
            $data=Session::get('data');
            if(count($response['subscribe_urls'])==1){
            
            Session::forget('url');
            Session::forget('from');
            echo "<script>window.location.replace('".$response['subscribe_urls'][0]."', '_blank')</script>";
            return redirect("overzicht/");
            
            }
            
            if(count($response['subscribe_urls'])==2){
                
            Session::forget('url');
            Session::forget('from');
            echo "<script>window.open('".$response['subscribe_urls'][0]."', '_blank')</script>";
            echo "<script>window.location.replace('".$response['subscribe_urls'][1]."')</script>";
            
            }
        
    }
    
    
    
    
    public function dataRequest(Request $request){
        
        
            $allSelected=Session::get('selected');
     
            $email=$request->email;
            $firstname=$request->firstname;
            $lastname=$request->lastname;
            $analytics_id=$request->analytics_id;
            $query['analytics_id']=$request->analytics_id;
            $query['packid'] =null;
            $gas_id=Session::get('gasID');
            $elec_id=Session::get('elecID');
            $getParameters=Session::get('getParameters');
            $queryGas['locale'] = $getParameters['parameters']['values']['locale'];
            $query['region']= $getParameters['parameters']['values']['region'];
            $query['usage_single']= $getParameters['parameters']['values']['usage_single'];
            $query['usage_day']= $getParameters['parameters']['values']['usage_day'];
            $query['usage_night']= $getParameters['parameters']['values']['usage_night'];
            if($getParameters['parameters']['values']['usage_excl_night']==null){
                $usage_excl_night=0;
            }else{
                $usage_excl_night=$getParameters['parameters']['values']['usage_excl_night'];
            }
            $query['usage_excl_night']= $usage_excl_night;
            $query['usage_gas']= $getParameters['parameters']['values']['usage_gas'];
            $query['comparison_type']= $getParameters['parameters']['values']['comparison_type'];
            $query['price_type']= $getParameters['product']['pricing_type'];
            $query['postcode']= $getParameters['parameters']['values']['postal_code'];
            $query['email'] = $request->email;
            $query['firstname'] =$request->firstname;
            $query['lastname'] = $request->lastname;
            $query['estimate_cunsomption'] =$getParameters['parameters']['values']['estimate_cunsomption'];
            $query['residence'] =$getParameters['parameters']['values']['residence'];
            $query['building_type'] =$getParameters['parameters']['values']['building_type'];
            $query['isolation_level'] =$getParameters['parameters']['values']['isolation_level'];
            $query['heating_system'] =$getParameters['parameters']['values']['heating_system'];
            $query['active_price'] = $request->active_price;
            $query['active_title'] = $request->active_title;
            $query['locale']= 'nl';
            $query['url']=Session::get('actual_link');


           if($allSelected['packData']){
               
                                        foreach($allSelected['packData'] as $product){
                                        $uuid=$product['parameters']['uuid'];
                                        $email=$request->email;
                                        $firstname=$request->firstname;
                                        $lastname=$request->lastname;
                                        $type=$request->type;
                                        $eid=$request->eid;
                                        $gas_id=Session::get('gasID');
                                        $elec_id=Session::get('elecID');
                                        $query['eid'] = $product['product']['underlying_products']['electricity']['id'];
                                        $query['gid'] = $product['product']['underlying_products']['gas']['id'];
                                        $query['proid'] = $product['product']['id'];
                                        $query['meter_type']= $product['parameters']['values']['meter_type'];
                                        $query['customer_group']=$product['parameters']['values']['customer_group'];
                                        $query['type']='pack';
                                        $query['uuid']= $product['parameters']['uuid'];
                                        $query['comp_type']= $product['parameters']['values']['comparison_type'];
                                        $query['curr_supplierE']= $product['parameters']['values']['current_supplier_name_electricity'];
                                        $query['curr_supplierG']= $product['parameters']['values']['current_supplier_name_electricity'];
                                        $query['energycostE']= (($product['price']['breakdown']['electricity']['energy_cost']['single']+$product['price']['breakdown']['electricity']['energy_cost']['day']+$product['price']['breakdown']['electricity']['energy_cost']['night']+$product['price']['breakdown']['electricity']['energy_cost']['excl_night'])/100);

                                        $query['residents']= $product['parameters']['values']['residents'];
                                        $query['first_residence']= $product['parameters']['values']['first_residence'];
                                        $query['decentralise_production']= $product['parameters']['values']['decentralise_production'];
                                        if($product['parameters']['values']['capacity_decentalise']==null){

                                        $capacity_decentalise=0;

                                        }else{
                                        $capacity_decentalise=$product['parameters']['values']['capacity_decentalise'];    
                                        }
                                        $query['capacity_decentalise']=$capacity_decentalise;
                                        $query['includeG']= $product['parameters']['values']['includeG'];
                                        $query['includeE']= $product['parameters']['values']['includeE'];
                                        $ele_disc="0";
                                        $gas_disc="0";
                                        $all_disc="0";
                                        foreach($product['price']['breakdown']['discounts'] as $disc){

                                        if($disc['parameters']['fuel_type']=='electricity'){

                                        $ele_disc=$ele_disc+$disc['amount'];
                                        }
                                        if($disc['parameters']['fuel_type']=='gas'){

                                        $gas_disc=$gas_disc+$disc['amount'];
                                        }
                                        if($disc['parameters']['fuel_type']=='all'){

                                        $all_disc=$gas_disc+$disc['amount'];
                                        }
                                        }


                                        $query['promoAmountE']= ($ele_disc);
                                        $query['energycostG']= ($product['price']['breakdown']['gas']['energy_cost']['usage'])/100;
                                        $query['promoAmountG']= ($gas_disc);
                                        $query['savings']= (($product['price']['totals']['year']['excl_promo']-$product['price']['totals']['year']['incl_promo'])/100);
                                        $query['supplier']= $product['supplier']['name'];
                                        $query['supplierID']= $product['supplier']['id'];
                                        $query['tariff']= $product['product']['name'];
                                        $query['tariffID']= $product['product']['id'];

                                        $replace = ['(FIRSTNAME)','(LASTNAME)','(POSTCODE)','(MONTHLYGAS)','(MONTHLYELEK)','(USAGE_G)','(USAGE_E)','(EMAIL)','(ElecMeterType)','(USAGE_E_NIGHT)','(USAGE_E_EXCL_NIGHT)','(DISCOUNTCODE_E)','(DISCOUNTCODE_G)','(DISCOUNTCODE_P)','(POSTALCODE)','(ANALYTIC)'];
                                        $info = [
                                        'FIRSTNAME' => $firstname,
                                        'LASTNAME' => $lastname,
                                        'POSTCODE' => $product['parameters']['values']['postal_code'], 
                                        'MONTHLYGAS'=> $request->monthly_amount_g, 
                                        'MONTHLYELEK'=> $request->monthly_amount_e,
                                        'USAGE_G' => $product['parameters']['values']['usage_gas'],
                                        'USAGE_E' => $product['parameters']['values']['usage_single']+$product['parameters']['values']['usage_day']+$product['parameters']['values']['usage_night']+$product['parameters']['values']['usage_excl_night'],
                                        'EMAIL'=> $email,
                                        'ElecMeterType'=> $product['parameters']['values']['meter_type'],
                                        'USAGE_E_NIGHT'=> $product['parameters']['values']['usage_night'],
                                        'USAGE_E_EXCL_NIGHT'=> $product['parameters']['values']['usage_excl_night'],
                                        'DISCOUNTCODE_E'=>$product['parameters']['values']['promo_discount']['discountcodeE'],
                                        'DISCOUNTCODE_G'=>$product['parameters']['values']['promo_discount']['discountcodeG'],
                                        'DISCOUNTCODE_P'=>$product['parameters']['values']['promo_discount']['discountcodeP'],
                                        'POSTALCODE' => $product['parameters']['values']['postal_code'],
                                        'ANALYTIC'=>$analytics_id

                                        ];
                                        $signupURL=$product['product']['subscribe_url'];
                                        $signupURLE=$product['product']['underlying_products']['electricity']['subscribe_url'];
                                        $signupURLG=$product['product']['underlying_products']['gas']['subscribe_url'];

                                        $query['signupURL']= str_replace($replace, $info, $signupURL);
                                        $query['signupURLE']= str_replace($replace, $info, $signupURLE);
                                        $query['signupURLG']= str_replace($replace, $info, $signupURLG);

                                        $query['signdate']= date("Y-m-d");
                                        $query['startdate']= $product['price']['validity_period']['start'];
                                        $query['durationdb']= $product['product']['contract_duration'];
                                        $query['duration']= $product['product']['contract_duration'];
                                        $query['enddate']= $product['price']['validity_period']['end'];
                                        $query['total']=($product['price']['totals']['year']['incl_promo']/100);
                                        $query['contract_energy_costE']= (($product['price']['breakdown']['electricity']['energy_cost']['single']+$product['price']['breakdown']['electricity']['energy_cost']['day']+$product['price']['breakdown']['electricity']['energy_cost']['night']+$product['price']['breakdown']['electricity']['energy_cost']['excl_night'])/100);
                                        $query['contract_promoamountE']= '';
                                        $query['contract_energy_costG']= (($product['price']['breakdown']['gas']['energy_cost']['usage'])/100);
                                        $query['contract_promoamountG']= '';
                                        $query['contract_duration']= $product['product']['contract_duration'];
                                        $query['packid'] =$product['product']['id'];
                                        $query['price_typeE']= $product['product']['underlying_products']['electricity']['pricing_type'];
                                        $query['price_typeG']= $product['product']['underlying_products']['gas']['pricing_type'];

                                        }
            
           }
           
           
           if($allSelected['eleData'] && !$allSelected['gasData']){
               
                                        foreach($allSelected['eleData'] as $product){
                                        $uuid=$product['parameters']['uuid'];
                                        $type=$product['parameters']['values']['comparison_type'];

                                        $query['id'] = $product['product']['id'];
                                        $query['proid'] = $product['product']['id'];

                                        $query['eid'] = $gid= $product['product']['id']; 
                                        $query['meter_type']= $product['parameters']['values']['meter_type'];
                                        $query['customer_group']=$product['parameters']['values']['customer_group'];
                                        $query['type']='electricity';
                                        $query['uuid']= $product['parameters']['uuid'];
                                        $query['comp_type']= $product['parameters']['values']['comparison_type'];
                                        $query['curr_supplierE']= $product['parameters']['values']['current_supplier_name_electricity'];
                                        $query['curr_supplierG']= $product['parameters']['values']['current_supplier_name_gas'];
                                        $query['energycostE']= (($product['price']['breakdown']['electricity']['energy_cost']['single']+$product['price']['breakdown']['electricity']['energy_cost']['day']+$product['price']['breakdown']['electricity']['energy_cost']['night']+$product['price']['breakdown']['electricity']['energy_cost']['excl_night'])/100);
                                        $query['promoAmountE']= '';
                                        $query['energycostG']= '';
                                        $query['residents']= $product['parameters']['values']['residents'];
                                        $query['first_residence']= $product['parameters']['values']['first_residence'];
                                        $query['decentralise_production']= $product['parameters']['values']['decentralise_production'];
                                        if($product['parameters']['values']['capacity_decentalise']==null){

                                        $capacity_decentalise=0;

                                        }else{

                                        $capacity_decentalise=$product['parameters']['values']['capacity_decentalise'];    
                                        }
                                        $query['capacity_decentalise']=$capacity_decentalise;
                                        $query['includeG']= $product['parameters']['values']['includeG'];
                                        $query['includeE']= $product['parameters']['values']['includeE'];
                                        $ele_disc="0";
                                        $gas_disc="0";
                                        $all_disc="0";
                                        foreach($product['price']['breakdown']['discounts'] as $disc){

                                        if($disc['parameters']['fuel_type']=='electricity'){

                                        $ele_disc=$ele_disc+$disc['amount'];
                                        }
                                        if($disc['parameters']['fuel_type']=='gas'){

                                        $gas_disc=$gas_disc+$disc['amount'];
                                        }
                                        if($disc['parameters']['fuel_type']=='all'){

                                        $all_disc=$gas_disc+$disc['amount'];
                                        }


                                        }


                                        $query['promoAmountE']= ($ele_disc);
                                        $query['energycostG']= "";
                                        $query['promoAmountG']= "";
                                        $query['savings']= (($product['price']['totals']['year']['excl_promo']-$product['price']['totals']['year']['incl_promo'])/100);

                                        $query['supplier']= $product['supplier']['name'];
                                        $query['supplierID']= $product['supplier']['id'];

                                        $query['tariff']= $product['product']['name'];
                                        $query['tariffID']= $product['product']['id'];

                                        $replace = ['(FIRSTNAME)','(LASTNAME)','(POSTCODE)','(MONTHLYGAS)','(MONTHLYELEK)','(USAGE_G)','(USAGE_E)','(EMAIL)','(ElecMeterType)','(USAGE_E_NIGHT)','(USAGE_E_EXCL_NIGHT)','(DISCOUNTCODE_E)','(DISCOUNTCODE_G)','(DISCOUNTCODE_P)','(POSTALCODE)','(ANALYTIC)'];
                                        $info = [
                                        'FIRSTNAME' => $firstname,
                                        'LASTNAME' => $lastname,
                                        'POSTCODE' => $product['parameters']['values']['postal_code'], 
                                        'MONTHLYGAS'=> $request->monthly_amount_g, 
                                        'MONTHLYELEK'=> $request->monthly_amount_e,
                                        'USAGE_G' => $product['parameters']['values']['usage_gas'],
                                        'USAGE_E' => $product['parameters']['values']['usage_single']+$product['parameters']['values']['usage_day']+$product['parameters']['values']['usage_night']+$product['parameters']['values']['usage_excl_night'],
                                        'EMAIL'=> $email,
                                        'ElecMeterType'=> $product['parameters']['values']['meter_type'],
                                        'USAGE_E_NIGHT'=> $product['parameters']['values']['usage_night'],
                                        'USAGE_E_EXCL_NIGHT'=> $product['parameters']['values']['usage_excl_night'],
                                        'DISCOUNTCODE_E'=>$product['parameters']['values']['promo_discount']['discountcodeE'],
                                        'DISCOUNTCODE_G'=>$product['parameters']['values']['promo_discount']['discountcodeG'],
                                        'DISCOUNTCODE_P'=>$product['parameters']['values']['promo_discount']['discountcodeP'],
                                        'POSTALCODE' => $product['parameters']['values']['postal_code'],
                                        'ANALYTIC'=>$analytics_id

                                        ];
                                        $signupURL=$product['product']['subscribe_url'];
                                        $query['signupURL']= str_replace($replace, $info, $signupURL);
                                        $query['signupURLE']= '';
                                        $query['signupURLG']= '';
                                        $query['signdate']= date("Y-m-d");
                                        $query['startdate']= $product['price']['validity_period']['start'];
                                        $query['durationdb']= $product['product']['contract_duration'];
                                        $query['duration']= $product['product']['contract_duration'];
                                        $query['enddate']= $product['price']['validity_period']['end'];
                                        $query['total']=($product['price']['totals']['year']['incl_promo']/100);

                                        $query['contract_energy_costE']= (($product['price']['breakdown']['electricity']['energy_cost']['single']+$product['price']['breakdown']['electricity']['energy_cost']['day']+$product['price']['breakdown']['electricity']['energy_cost']['night']+$product['price']['breakdown']['electricity']['energy_cost']['excl_night'])/100);
                                        $query['contract_promoamountE']= '';

                                        $query['contract_energy_costG']= '';
                                        $query['contract_promoamountG']= '';

                                        $query['contract_duration']= $product['product']['contract_duration'];
                                        $query['price_typeE']= $product['product']['pricing_type'];


                                        }
               
               
    }
           
    if($allSelected['gasData'] && !$allSelected['eleData']){
               
                                        foreach($allSelected['gasData'] as $product){

                                        $uuid=$product['parameters']['uuid']; 
                                        $type=$product['parameters']['values']['comparison_type'];
                                        $query['id'] = $product['product']['id'];
                                        $query['gid'] =$eid= $product['product']['id'];
                                        $query['customer_group']=$product['parameters']['values']['customer_group'];
                                        $query['proid'] = $product['product']['id'];
                                        $query['type']='gas';
                                        $query['uuid']= $product['parameters']['uuid'];
                                        $query['comp_type']= $product['parameters']['values']['comparison_type'];
                                        $query['curr_supplierE']= $product['parameters']['values']['current_supplier_name_electricity'];
                                        $query['curr_supplierG']= $product['parameters']['values']['current_supplier_name_gas'];
                                        $query['energycostE']= "";
                                        $query['residents']= $product['parameters']['values']['residents'];
                                        $query['first_residence']= $product['parameters']['values']['first_residence'];
                                        $query['decentralise_production']= $product['parameters']['values']['decentralise_production'];
                                        if($product['parameters']['values']['capacity_decentalise']==null){

                                        $capacity_decentalise=0;

                                        }else{
                                        $capacity_decentalise=$product['parameters']['values']['capacity_decentalise'];    
                                        }
                                        $query['capacity_decentalise']=$capacity_decentalise;
                                        $query['includeG']= $product['parameters']['values']['includeG'];
                                        $query['includeE']= $product['parameters']['values']['includeE'];

                                        $ele_disc="0";
                                        $gas_disc="0";
                                        $all_disc="0";
                                        foreach($product['price']['breakdown']['discounts'] as $disc){

                                        if($disc['parameters']['fuel_type']=='electricity'){

                                        $ele_disc=$ele_disc+$disc['amount'];
                                        }
                                        if($disc['parameters']['fuel_type']=='gas'){

                                        $gas_disc=$gas_disc+$disc['amount'];
                                        }
                                        if($disc['parameters']['fuel_type']=='all'){

                                        $all_disc=$gas_disc+$disc['amount'];
                                        }
                                        }


                                        $query['promoAmountE']= "";

                                        $query['energycostG']= ($product['price']['breakdown']['gas']['energy_cost']['usage'])/100;
                                        $query['promoAmountG']= ($gas_disc);
                                        $query['savings']= (($product['price']['totals']['year']['excl_promo']-$product['price']['totals']['year']['incl_promo'])/100);

                                        $query['supplier']= $product['supplier']['name'];
                                        $query['supplierID']= $product['supplier']['id'];

                                        $query['tariff']= $product['product']['name'];
                                        $query['tariffID']= $product['product']['id'];


                                        $replace = ['(FIRSTNAME)','(LASTNAME)','(POSTCODE)','(MONTHLYGAS)','(MONTHLYELEK)','(USAGE_G)','(USAGE_E)','(EMAIL)','(ElecMeterType)','(USAGE_E_NIGHT)','(USAGE_E_EXCL_NIGHT)','(DISCOUNTCODE_E)','(DISCOUNTCODE_G)','(DISCOUNTCODE_P)','(POSTALCODE)','(ANALYTIC)'];
                                        $info = [
                                        'FIRSTNAME' => $firstname,
                                        'LASTNAME' => $lastname,
                                        'POSTCODE' => $product['parameters']['values']['postal_code'], 
                                        'MONTHLYGAS'=> $request->monthly_amount_g, 
                                        'MONTHLYELEK'=> $request->monthly_amount_e,
                                        'USAGE_G' => $product['parameters']['values']['usage_gas'],
                                        'USAGE_E' => $product['parameters']['values']['usage_single']+$product['parameters']['values']['usage_day']+$product['parameters']['values']['usage_night']+$product['parameters']['values']['usage_excl_night'],
                                        'EMAIL'=> $email,
                                        'ElecMeterType'=> $product['parameters']['values']['meter_type'],
                                        'USAGE_E_NIGHT'=> $product['parameters']['values']['usage_night'],
                                        'USAGE_E_EXCL_NIGHT'=> $product['parameters']['values']['usage_excl_night'],
                                        'DISCOUNTCODE_E'=>$product['parameters']['values']['promo_discount']['discountcodeE'],
                                        'DISCOUNTCODE_G'=>$product['parameters']['values']['promo_discount']['discountcodeG'],
                                        'DISCOUNTCODE_P'=>$product['parameters']['values']['promo_discount']['discountcodeP'],
                                        'POSTALCODE' => $product['parameters']['values']['postal_code'],
                                        'ANALYTIC'=>$analytics_id

                                        ];
                                        $signupURL=$product['product']['subscribe_url'];
                                        $signupURLE="";
                                        $signupURLG="";

                                        $query['signupURL']= str_replace($replace, $info, $signupURL);
                                        $query['signupURLE']= "";
                                        $query['signupURLG']= "";

                                        $query['signdate']= date("Y-m-d");
                                        $query['startdate']= $product['price']['validity_period']['start'];
                                        $query['durationdb']= $product['product']['contract_duration'];
                                        $query['duration']= $product['product']['contract_duration'];
                                        $query['enddate']= $product['price']['validity_period']['end'];
                                        $query['total']=($product['price']['totals']['year']['incl_promo']/100);

                                        $query['contract_energy_costE']= "";
                                        $query['contract_promoamountE']= '';
                                        $query['contract_energy_costG']= (($product['price']['breakdown']['gas']['energy_cost']['usage'])/100);
                                        $query['contract_promoamountG']= '';
                                        $query['contract_duration']= $product['product']['contract_duration'];
                                        $query['price_typeG']= $product['product']['pricing_type'];


                                        }
               
           }
           
          
           if($allSelected['gasData'] && $allSelected['eleData']){
               
                                        foreach($allSelected['gasData'] as $product){
                                        $uuid=$product['parameters']['uuid'];  
                                        $type=$product['parameters']['values']['comparison_type'];
                                        $query['id'] = $product['product']['id'];
                                        $query['gid'] =$eid= $product['product']['id'];
                                        $query['customer_group']=$product['parameters']['values']['customer_group'];
                                        $query['type']='dual';
                                        $query['uuid']= $product['parameters']['uuid'];
                                        $query['comp_type']= 'dual';
                                        $query['curr_supplierE']= "";
                                        $query['curr_supplierG']= $product['parameters']['values']['current_supplier_name_gas'];
                                        $query['energycostE']= "";
                                        $query['includeG']= 1;
                                        $query['includeE']= 1;
                                        $ele_disc="0";
                                        $gas_disc="0";
                                        $all_disc="0";
                                        foreach($product['price']['breakdown']['discounts'] as $disc){

                                            if($disc['parameters']['fuel_type']=='electricity'){
                                            $ele_disc=$ele_disc+$disc['amount'];
                                            }
                                            if($disc['parameters']['fuel_type']=='gas'){
                                            $gas_disc=$gas_disc+$disc['amount'];
                                            }
                                            if($disc['parameters']['fuel_type']=='all'){
                                            $all_disc=$gas_disc+$disc['amount'];
                                            }
                                        }
                                        $query['promoAmountE']= "";
                                        $query['energycostG']= ($product['price']['breakdown']['gas']['energy_cost']['usage'])/100;
                                        $query['promoAmountG']= ($gas_disc);
                                        $query['savings']= (($product['price']['totals']['year']['excl_promo']-$product['price']['totals']['year']['incl_promo'])/100);
                                        $query['supplier']= $product['supplier']['name'];
                                        $query['supplierID']= $product['supplier']['id'];
                                        $query['tariff']= $product['product']['name'];
                                        $query['tariffID']= $product['product']['id'];
                                        $query['supplier_G']= $product['supplier']['name'];
                                        $query['supplierID_G']= $product['supplier']['id'];
                                        $query['tariff_G']= $product['product']['name'];
                                        $query['tariffID_G']= $product['product']['id'];

                                        $replace = ['(FIRSTNAME)','(LASTNAME)','(POSTCODE)','(MONTHLYGAS)','(MONTHLYELEK)','(USAGE_G)','(USAGE_E)','(EMAIL)','(ElecMeterType)','(USAGE_E_NIGHT)','(USAGE_E_EXCL_NIGHT)','(DISCOUNTCODE_E)','(DISCOUNTCODE_G)','(DISCOUNTCODE_P)','(POSTALCODE)','(ANALYTIC)'];
                                        $info = [
                                        'FIRSTNAME' => $firstname,
                                        'LASTNAME' => $lastname,
                                        'POSTCODE' => $product['parameters']['values']['postal_code'], 
                                        'MONTHLYGAS'=> $request->monthly_amount_g, 
                                        'MONTHLYELEK'=> $request->monthly_amount_e,
                                        'USAGE_G' => $product['parameters']['values']['usage_gas'],
                                        'USAGE_E' => $product['parameters']['values']['usage_single']+$product['parameters']['values']['usage_day']+$product['parameters']['values']['usage_night']+$product['parameters']['values']['usage_excl_night'],
                                        'EMAIL'=> $email,
                                        'ElecMeterType'=> $product['parameters']['values']['meter_type'],
                                        'USAGE_E_NIGHT'=> $product['parameters']['values']['usage_night'],
                                        'USAGE_E_EXCL_NIGHT'=> $product['parameters']['values']['usage_excl_night'],
                                        'DISCOUNTCODE_E'=>$product['parameters']['values']['promo_discount']['discountcodeE'],
                                        'DISCOUNTCODE_G'=>$product['parameters']['values']['promo_discount']['discountcodeG'],
                                        'DISCOUNTCODE_P'=>$product['parameters']['values']['promo_discount']['discountcodeP'],
                                        'POSTALCODE' => $product['parameters']['values']['postal_code'],
                                        'ANALYTIC'=>$analytics_id

                                        ];

                                        $signupURLE="";
                                        $signupURL="";
                                        $signupURLG=$product['product']['subscribe_url'];
                                        $query['signupURL']= "";
                                        $query['signupURLE']= "";
                                        $query['signupURLG']= str_replace($replace, $info, $signupURLG);
                                        $query['signdate']= date("Y-m-d");
                                        $query['startdate']= $product['price']['validity_period']['start'];
                                        $query['durationdb']= $product['product']['contract_duration'];
                                        $query['duration']= $product['product']['contract_duration'];
                                        $query['enddate']= $product['price']['validity_period']['end'];
                                        $total=$product['price']['breakdown']['gas']['unit_cost']['total'];
                                        $query['contract_energy_costE']= "";
                                        $query['contract_promoamountE']= '';
                                        $query['contract_energy_costG']= (($product['price']['breakdown']['gas']['energy_cost']['usage'])/100);
                                        $query['contract_promoamountG']= (($product['price']['totals']['year']['excl_promo']-$product['price']['totals']['year']['incl_promo'])/100);
                                        $query['contract_duration']= $product['product']['contract_duration'];
                                        $query['price_typeG']= $product['product']['pricing_type'];

                                        }

                                        foreach($allSelected['eleData'] as $product){
                                        $uuid=$product['parameters']['uuid'];  
                                        $type=$product['parameters']['values']['comparison_type'];
                                        $query['id'] = $product['product']['id'];
                                        $query['eid'] = $gid= $product['product']['id']; 
                                        $query['meter_type']= $product['parameters']['values']['meter_type'];
                                        $query['curr_supplierE']= $product['parameters']['values']['current_supplier_name_electricity'];
                                        $query['energycostE']= (($product['price']['breakdown']['electricity']['energy_cost']['single']+$product['price']['breakdown']['electricity']['energy_cost']['day']+$product['price']['breakdown']['electricity']['energy_cost']['night']+$product['price']['breakdown']['electricity']['energy_cost']['excl_night'])/100);
                                        $ele_disc="0";
                                        $gas_disc="0";
                                        $all_disc="0";
                                        foreach($product['price']['breakdown']['discounts'] as $disc){

                                                if($disc['parameters']['fuel_type']=='electricity'){
                                                $ele_disc=$ele_disc+$disc['amount'];
                                                }
                                                if($disc['parameters']['fuel_type']=='gas'){
                                                $gas_disc=$gas_disc+$disc['amount'];
                                                }
                                                if($disc['parameters']['fuel_type']=='all'){
                                                $all_disc=$gas_disc+$disc['amount'];
                                                }


                                        }


                                        $query['promoAmountE']= ($ele_disc);
                                        $query['savings']= (($product['price']['totals']['year']['excl_promo']-$product['price']['totals']['year']['incl_promo'])/100);
                                        $query['supplier']= $product['supplier']['name'];
                                        $query['supplierID ']= $product['supplier']['id'];
                                        $query['tariff']= $product['product']['name'];
                                        $query['tariffID']= $product['product']['id'];
                                        $replace = ['(FIRSTNAME)','(LASTNAME)','(POSTCODE)','(MONTHLYGAS)','(MONTHLYELEK)','(USAGE_G)','(USAGE_E)','(EMAIL)','(ElecMeterType)','(USAGE_E_NIGHT)','(USAGE_E_EXCL_NIGHT)','(DISCOUNTCODE_E)','(DISCOUNTCODE_G)','(DISCOUNTCODE_P)','(POSTALCODE)','(ANALYTIC)'];
                                        $info = [
                                        'FIRSTNAME' => $firstname,
                                        'LASTNAME' => $lastname,
                                        'POSTCODE' => $product['parameters']['values']['postal_code'], 
                                        'MONTHLYGAS'=> $request->monthly_amount_g, 
                                        'MONTHLYELEK'=> $request->monthly_amount_e,
                                        'USAGE_G' => $product['parameters']['values']['usage_gas'],
                                        'USAGE_E' => $product['parameters']['values']['usage_single']+$product['parameters']['values']['usage_day']+$product['parameters']['values']['usage_night']+$product['parameters']['values']['usage_excl_night'],
                                        'EMAIL'=> $email,
                                        'ElecMeterType'=> $product['parameters']['values']['meter_type'],
                                        'USAGE_E_NIGHT'=> $product['parameters']['values']['usage_night'],
                                        'USAGE_E_EXCL_NIGHT'=> $product['parameters']['values']['usage_excl_night'],
                                        'DISCOUNTCODE_E'=>$product['parameters']['values']['promo_discount']['discountcodeE'],
                                        'DISCOUNTCODE_G'=>$product['parameters']['values']['promo_discount']['discountcodeG'],
                                        'DISCOUNTCODE_P'=>$product['parameters']['values']['promo_discount']['discountcodeP'],
                                        'POSTALCODE' => $product['parameters']['values']['postal_code'],
                                        'ANALYTIC'=>$analytics_id
                                        ];
                                        $signupURL="";
                                        $signupURLE=$product['product']['subscribe_url'];
                                        $query['signupURLE']= str_replace($replace, $info, $signupURLE);
                                        $query['signdate']= date("Y-m-d");
                                        $query['startdate']= $product['price']['validity_period']['start'];
                                        $query['durationdb']= $product['product']['contract_duration'];
                                        $query['duration']= $product['product']['contract_duration'];
                                        $query['enddate']= $product['price']['validity_period']['end'];
                                        $query['total']= ($product['price']['totals']['year']['incl_promo']/100);
                                        $query['contract_energy_costE']= (($product['price']['breakdown']['electricity']['energy_cost']['single']+$product['price']['breakdown']['electricity']['energy_cost']['day']+$product['price']['breakdown']['electricity']['energy_cost']['night']+$product['price']['breakdown']['electricity']['energy_cost']['excl_night'])/100);
                                        $query['contract_promoamountE']= '';
                                        $query['contract_duration']= $product['product']['contract_duration'];
                                        $query['price_typeE']= $product['product']['pricing_type'];
                                        if($product['parameters']['values']['estimate_cunsomption']==null || $product['parameters']['values']['estimate_cunsomption']==0){
                                        $estimate_cunsomption='false';
                                        }else{
                                        $estimate_cunsomption=$product['parameters']['values']['estimate_cunsomption'];
                                        }
                                        $query['estimate_cunsomption'] =$estimate_cunsomption;
                                        $query['residents']= $product['parameters']['values']['residents'];
                                        $query['first_residence']= $product['parameters']['values']['first_residence'];
                                        $query['decentralise_production']= $product['parameters']['values']['decentralise_production'];
                                        if($product['parameters']['values']['capacity_decentalise']==null){
                                        $capacity_decentalise=0;
                                        }else{
                                        $capacity_decentalise=$product['parameters']['values']['capacity_decentalise'];    
                                        }
                                        $query['capacity_decentalise']=$capacity_decentalise;

                                }

                    }

 
                    try {

                    $client = new \GuzzleHttp\Client();
                    $api = $client->post('https://api.tariefchecker.be/api/conversion', [
                    'headers' => [
                    'Accept' => 'application/json',
                    'Content-type' => 'application/x-www-form-urlencoded',
                    'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWFmMjVkYWNmZTNiM2I0MmZjOTJkMTU5MjIxY2RjNjNkY2MxMzEwZWU3NDJlM2YzNmRiOWZiMDZhZmMwNGMyNTgyNzEzNjRhYjU5Y2VkZGQiLCJpYXQiOjE2NDMyODY2MjcsIm5iZiI6MTY0MzI4NjYyNywiZXhwIjoyMjc0NDM4NjI3LCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.rmiDd2sM0kduf6CPed5rjbqPL4Fui-MDdOKViiPn49pcJEukW_kA2ByuJfHNUIe9rctIXsovX1T8kgeer6TxgQkGCvruO98zcGklVv470en8ul6NzOCMmaemX4cJj4XQYlcI1_-z6tnHqtbbc7_-TyvezidDGslhMAMmtREicgrubnp9VGyl6YtE_pXHedruJ7PxYsc2_Gqu-osdFOdEW6hxN3uPlpKbuHgrf9DvJr8B3PSDQLPl49Q9HzrL-vPgayZTpNFINpCw1QBKk_ooWo861UZQ_cE33TdNfXyoJ5WnXQ-AjvtInfw7C9skq57C9X4NmfsllWCacNn9IYNs4uocuFo259TbRXNuooHsWTDTty4kalcp3LD7G0exCTTDC3_QsEoZI6694ct8Fi0gOJ05thoS5grKIfKyFkRqu1eOS2wMdNs-6KZXwVQ6fv1sJE-VjdIKXoj-r6wo_FPSceB599yz22gwVQLnDQJAvu0OahSyU8DG3VMH__ItYBuTI0uOTJZerwaRwmnTkSWbWczA4c8AEb1H_W-G4Yblh4D9y_ZOW7FvvFj53dCX83mzUyBN3HahqzD8ZX0IvZXolZHLxluIOlFoR9HiLNzTZFrJSzWru39AjNmbaK8-AAydGlF606uGglo76ES7D7dOvDa5lgUjYzRvby8jDpRSkzY'
                    ],
                    'query' => $query
                    ]);
                    $data = $api->getBody()->getContents();
                    $response = json_decode($data, true);            

                    } catch (\Exception $e) {

                    $response = ['status' => false, 'message' => $e->getMessage()];

                    }  
                    $data=Session::get('data');
                    $query['postal_code']= $data['postalcode'];
                    $user = UserLog::create([
                    'first_name' => $request->firstname,
                    'last_name' => $request->lastname,
                    'email' => $request->email,
                    'product_id'=>$data['id'],
                    'postal_code'=>$data['postalcode'],
                    'last_seen' => Carbon::now(),
                    'ip_address' => $request->getClientIp(),
                    ]);           
            
                    if(isset($response['pack'])){            
                        Session::forget('url');
                        Session::forget('from');            
                        $url[1]=$response['pack'];
                        $url[2]=""; 
                    }            
                    if(isset($response['gas'])){                
                        Session::forget('url');
                        Session::forget('from');
                        $url[1]=$response['gas'];            
                    }
                    if(isset($response['electricity'])){                
                        Session::forget('url');
                        Session::forget('from');
                        $url[2]=$response['electricity'];              
                    }
            
            
            // user-search-details
            
                    $query['req_from'] = 'request';

                    try {

                    $client = new \GuzzleHttp\Client();
                    $api = $client->post('https://api.tariefchecker.be/api/user-search-details', [
                    'headers' => [
                    'Accept' => 'application/json',
                    'Content-type' => 'application/x-www-form-urlencoded',
                    'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWFmMjVkYWNmZTNiM2I0MmZjOTJkMTU5MjIxY2RjNjNkY2MxMzEwZWU3NDJlM2YzNmRiOWZiMDZhZmMwNGMyNTgyNzEzNjRhYjU5Y2VkZGQiLCJpYXQiOjE2NDMyODY2MjcsIm5iZiI6MTY0MzI4NjYyNywiZXhwIjoyMjc0NDM4NjI3LCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.rmiDd2sM0kduf6CPed5rjbqPL4Fui-MDdOKViiPn49pcJEukW_kA2ByuJfHNUIe9rctIXsovX1T8kgeer6TxgQkGCvruO98zcGklVv470en8ul6NzOCMmaemX4cJj4XQYlcI1_-z6tnHqtbbc7_-TyvezidDGslhMAMmtREicgrubnp9VGyl6YtE_pXHedruJ7PxYsc2_Gqu-osdFOdEW6hxN3uPlpKbuHgrf9DvJr8B3PSDQLPl49Q9HzrL-vPgayZTpNFINpCw1QBKk_ooWo861UZQ_cE33TdNfXyoJ5WnXQ-AjvtInfw7C9skq57C9X4NmfsllWCacNn9IYNs4uocuFo259TbRXNuooHsWTDTty4kalcp3LD7G0exCTTDC3_QsEoZI6694ct8Fi0gOJ05thoS5grKIfKyFkRqu1eOS2wMdNs-6KZXwVQ6fv1sJE-VjdIKXoj-r6wo_FPSceB599yz22gwVQLnDQJAvu0OahSyU8DG3VMH__ItYBuTI0uOTJZerwaRwmnTkSWbWczA4c8AEb1H_W-G4Yblh4D9y_ZOW7FvvFj53dCX83mzUyBN3HahqzD8ZX0IvZXolZHLxluIOlFoR9HiLNzTZFrJSzWru39AjNmbaK8-AAydGlF606uGglo76ES7D7dOvDa5lgUjYzRvby8jDpRSkzY'
                    ],
                    'query' => $query
                    ]);
                    $data = $api->getBody()->getContents();
                    $response = json_decode($data, true);            

                    } catch (\Exception $e) {

                    $response = ['status' => false, 'message' => $e->getMessage()];

                    }

           // user-search-details-end

            
                    return $url;        
    }


    public function dataRequestPost(Request $request)
    {
       


            // code

            $allSelected=Session::get('selected');           
            $email=$request->email;
            $firstname=$request->firstname;
            $lastname=$request->lastname;
            $analytics_id=$request->analytics_id;
            $query['analytics_id']=$request->analytics_id;
            $query['packid'] =null;
            $gas_id=Session::get('gasID');
            $elec_id=Session::get('elecID');
            $getParameters=Session::get('getParameters');
            $queryGas['locale'] = $getParameters['parameters']['values']['locale'];
            $query['region']= $getParameters['parameters']['values']['region'];
            $query['usage_single']= $getParameters['parameters']['values']['usage_single'];
            $query['usage_day']= $getParameters['parameters']['values']['usage_day'];
            $query['usage_night']= $getParameters['parameters']['values']['usage_night'];
            if($getParameters['parameters']['values']['usage_excl_night']==null){
                $usage_excl_night=0;
            }else{
                $usage_excl_night=$getParameters['parameters']['values']['usage_excl_night'];
            }
            $query['usage_excl_night']= $usage_excl_night;
            $query['usage_gas']= $getParameters['parameters']['values']['usage_gas'];
            $query['comparison_type']= $getParameters['parameters']['values']['comparison_type'];
            $query['price_type']= $getParameters['product']['pricing_type'];
            $query['postcode']= $getParameters['parameters']['values']['postal_code'];
            $query['email'] = $request->email;
            $query['firstname'] =$request->firstname;
            $query['lastname'] = $request->lastname;
            $query['estimate_cunsomption'] =$getParameters['parameters']['values']['estimate_cunsomption'];
            $query['residence'] =$getParameters['parameters']['values']['residence'];
            $query['building_type'] =$getParameters['parameters']['values']['building_type'];
            $query['isolation_level'] =$getParameters['parameters']['values']['isolation_level'];
            $query['heating_system'] =$getParameters['parameters']['values']['heating_system'];
            $query['active_price'] = $request->active_price;
            $query['active_title'] = $request->active_title;
            $query['locale']= 'nl';
            $query['url']=Session::get('actual_link');


           if($allSelected['packData']){
               
                                        foreach($allSelected['packData'] as $product){
                                        $uuid=$product['parameters']['uuid'];
                                        $email=$request->email;
                                        $firstname=$request->firstname;
                                        $lastname=$request->lastname;
                                        $type=$request->type;
                                        $eid=$request->eid;
                                        $gas_id=Session::get('gasID');
                                        $elec_id=Session::get('elecID');
                                        $query['eid'] = $product['product']['underlying_products']['electricity']['id'];
                                        $query['gid'] = $product['product']['underlying_products']['gas']['id'];
                                        $query['proid'] = $product['product']['id'];
                                        $query['meter_type']= $product['parameters']['values']['meter_type'];
                                        $query['customer_group']=$product['parameters']['values']['customer_group'];
                                        $query['type']='pack';
                                        $query['uuid']= $product['parameters']['uuid'];

                                        $query['comp_type']= $product['parameters']['values']['comparison_type'];
                                        $query['curr_supplierE']= $product['parameters']['values']['current_supplier_name_electricity'];
                                        $query['curr_supplierG']= $product['parameters']['values']['current_supplier_name_electricity'];
                                        $query['energycostE']= (($product['price']['breakdown']['electricity']['energy_cost']['single']+$product['price']['breakdown']['electricity']['energy_cost']['day']+$product['price']['breakdown']['electricity']['energy_cost']['night']+$product['price']['breakdown']['electricity']['energy_cost']['excl_night'])/100);


                                        $query['residents']= $product['parameters']['values']['residents'];
                                        $query['first_residence']= $product['parameters']['values']['first_residence'];
                                        $query['decentralise_production']= $product['parameters']['values']['decentralise_production'];
                                        if($product['parameters']['values']['capacity_decentalise']==null){

                                        $capacity_decentalise=0;

                                        }else{
                                        $capacity_decentalise=$product['parameters']['values']['capacity_decentalise'];    
                                        }
                                        $query['capacity_decentalise']=$capacity_decentalise;
                                        $query['includeG']= $product['parameters']['values']['includeG'];
                                        $query['includeE']= $product['parameters']['values']['includeE'];
                                        $ele_disc="0";
                                        $gas_disc="0";
                                        $all_disc="0";
                                        foreach($product['price']['breakdown']['discounts'] as $disc){

                                        if($disc['parameters']['fuel_type']=='electricity'){

                                        $ele_disc=$ele_disc+$disc['amount'];
                                        }
                                        if($disc['parameters']['fuel_type']=='gas'){

                                        $gas_disc=$gas_disc+$disc['amount'];
                                        }
                                        if($disc['parameters']['fuel_type']=='all'){

                                        $all_disc=$gas_disc+$disc['amount'];
                                        }
                                        }


                                        $query['promoAmountE']= ($ele_disc);
                                        $query['energycostG']= ($product['price']['breakdown']['gas']['energy_cost']['usage'])/100;
                                        $query['promoAmountG']= ($gas_disc);
                                        $query['savings']= (($product['price']['totals']['year']['excl_promo']-$product['price']['totals']['year']['incl_promo'])/100);
                                        $query['supplier']= $product['supplier']['name'];
                                        $query['supplierID']= $product['supplier']['id'];
                                        $query['tariff']= $product['product']['name'];
                                        $query['tariffID']= $product['product']['id'];

                                        $replace = ['(FIRSTNAME)','(LASTNAME)','(POSTCODE)','(MONTHLYGAS)','(MONTHLYELEK)','(USAGE_G)','(USAGE_E)','(EMAIL)','(ElecMeterType)','(USAGE_E_NIGHT)','(USAGE_E_EXCL_NIGHT)','(DISCOUNTCODE_E)','(DISCOUNTCODE_G)','(DISCOUNTCODE_P)','(POSTALCODE)','(ANALYTIC)'];
                                        $info = [
                                        'FIRSTNAME' => "",
                                        'LASTNAME' => "",
                                        'POSTCODE' => $product['parameters']['values']['postal_code'], 
                                        'MONTHLYGAS'=> $request->monthly_amount_g, 
                                        'MONTHLYELEK'=> $request->monthly_amount_e,
                                        'USAGE_G' => $product['parameters']['values']['usage_gas'],
                                        'USAGE_E' => $product['parameters']['values']['usage_single']+$product['parameters']['values']['usage_day']+$product['parameters']['values']['usage_night']+$product['parameters']['values']['usage_excl_night'],
                                        'EMAIL'=> "",
                                        'ElecMeterType'=> $product['parameters']['values']['meter_type'],
                                        'USAGE_E_NIGHT'=> $product['parameters']['values']['usage_night'],
                                        'USAGE_E_EXCL_NIGHT'=> $product['parameters']['values']['usage_excl_night'],
                                        'DISCOUNTCODE_E'=>$product['parameters']['values']['promo_discount']['discountcodeE'],
                                        'DISCOUNTCODE_G'=>$product['parameters']['values']['promo_discount']['discountcodeG'],
                                        'DISCOUNTCODE_P'=>$product['parameters']['values']['promo_discount']['discountcodeP'],
                                        'POSTALCODE' => $product['parameters']['values']['postal_code'],
                                        'ANALYTIC'=>$analytics_id

                                        ];
                                        $signupURL=$product['product']['subscribe_url'];
                                        $signupURLE=$product['product']['underlying_products']['electricity']['subscribe_url'];
                                        $signupURLG=$product['product']['underlying_products']['gas']['subscribe_url'];

                                        $query['signupURL']= str_replace($replace, $info, $signupURL);
                                        $query['signupURLE']= str_replace($replace, $info, $signupURLE);
                                        $query['signupURLG']= str_replace($replace, $info, $signupURLG);

                                        $query['signdate']= date("Y-m-d");
                                        $query['startdate']= $product['price']['validity_period']['start'];
                                        $query['durationdb']= $product['product']['contract_duration'];
                                        $query['duration']= $product['product']['contract_duration'];
                                        $query['enddate']= $product['price']['validity_period']['end'];

                                        $query['total']=($product['price']['totals']['year']['incl_promo']/100);

                                        $query['contract_energy_costE']= (($product['price']['breakdown']['electricity']['energy_cost']['single']+$product['price']['breakdown']['electricity']['energy_cost']['day']+$product['price']['breakdown']['electricity']['energy_cost']['night']+$product['price']['breakdown']['electricity']['energy_cost']['excl_night'])/100);
                                        $query['contract_promoamountE']= '';
                                        $query['contract_energy_costG']= (($product['price']['breakdown']['gas']['energy_cost']['usage'])/100);
                                        $query['contract_promoamountG']= '';
                                        $query['contract_duration']= $product['product']['contract_duration'];
                                        $query['packid'] =$product['product']['id'];
                                        $query['price_typeE']= $product['product']['underlying_products']['electricity']['pricing_type'];
                                        $query['price_typeG']= $product['product']['underlying_products']['gas']['pricing_type'];

                                        }
            
           }
           
           
           if($allSelected['eleData'] && !$allSelected['gasData']){
               
                                        foreach($allSelected['eleData'] as $product){
                                        $uuid=$product['parameters']['uuid'];
                                        $type=$product['parameters']['values']['comparison_type'];

                                        $query['id'] = $product['product']['id'];
                                        $query['proid'] = $product['product']['id'];

                                        $query['eid'] = $gid= $product['product']['id']; 
                                        $query['meter_type']= $product['parameters']['values']['meter_type'];
                                        $query['customer_group']=$product['parameters']['values']['customer_group'];
                                        $query['type']='electricity';
                                        $query['uuid']= $product['parameters']['uuid'];
                                        $query['comp_type']= $product['parameters']['values']['comparison_type'];
                                        $query['curr_supplierE']= $product['parameters']['values']['current_supplier_name_electricity'];
                                        $query['curr_supplierG']= $product['parameters']['values']['current_supplier_name_gas'];
                                        $query['energycostE']= (($product['price']['breakdown']['electricity']['energy_cost']['single']+$product['price']['breakdown']['electricity']['energy_cost']['day']+$product['price']['breakdown']['electricity']['energy_cost']['night']+$product['price']['breakdown']['electricity']['energy_cost']['excl_night'])/100);
                                        $query['promoAmountE']= '';
                                        $query['energycostG']= '';
                                        $query['residents']= $product['parameters']['values']['residents'];
                                        $query['first_residence']= $product['parameters']['values']['first_residence'];
                                        $query['decentralise_production']= $product['parameters']['values']['decentralise_production'];
                                        if($product['parameters']['values']['capacity_decentalise']==null){

                                        $capacity_decentalise=0;

                                        }else{

                                        $capacity_decentalise=$product['parameters']['values']['capacity_decentalise'];    
                                        }
                                        $query['capacity_decentalise']=$capacity_decentalise;
                                        $query['includeG']= $product['parameters']['values']['includeG'];
                                        $query['includeE']= $product['parameters']['values']['includeE'];
                                        $ele_disc="0";
                                        $gas_disc="0";
                                        $all_disc="0";
                                        foreach($product['price']['breakdown']['discounts'] as $disc){

                                        if($disc['parameters']['fuel_type']=='electricity'){

                                        $ele_disc=$ele_disc+$disc['amount'];
                                        }
                                        if($disc['parameters']['fuel_type']=='gas'){

                                        $gas_disc=$gas_disc+$disc['amount'];
                                        }
                                        if($disc['parameters']['fuel_type']=='all'){

                                        $all_disc=$gas_disc+$disc['amount'];
                                        }

                                        }

                                        $query['promoAmountE']= ($ele_disc);
                                        $query['energycostG']= "";
                                        $query['promoAmountG']= "";
                                        $query['savings']= (($product['price']['totals']['year']['excl_promo']-$product['price']['totals']['year']['incl_promo'])/100);

                                        $query['supplier']= $product['supplier']['name'];
                                        $query['supplierID']= $product['supplier']['id'];

                                        $query['tariff']= $product['product']['name'];
                                        $query['tariffID']= $product['product']['id'];

                                        $replace = ['(FIRSTNAME)','(LASTNAME)','(POSTCODE)','(MONTHLYGAS)','(MONTHLYELEK)','(USAGE_G)','(USAGE_E)','(EMAIL)','(ElecMeterType)','(USAGE_E_NIGHT)','(USAGE_E_EXCL_NIGHT)','(DISCOUNTCODE_E)','(DISCOUNTCODE_G)','(DISCOUNTCODE_P)','(POSTALCODE)','(ANALYTIC)'];
                                        $info = [
                                        'FIRSTNAME' => "",
                                        'LASTNAME' => "",
                                        'POSTCODE' => $product['parameters']['values']['postal_code'], 
                                        'MONTHLYGAS'=> $request->monthly_amount_g, 
                                        'MONTHLYELEK'=> $request->monthly_amount_e,
                                        'USAGE_G' => $product['parameters']['values']['usage_gas'],
                                        'USAGE_E' => $product['parameters']['values']['usage_single']+$product['parameters']['values']['usage_day']+$product['parameters']['values']['usage_night']+$product['parameters']['values']['usage_excl_night'],
                                        'EMAIL'=> "",
                                        'ElecMeterType'=> $product['parameters']['values']['meter_type'],
                                        'USAGE_E_NIGHT'=> $product['parameters']['values']['usage_night'],
                                        'USAGE_E_EXCL_NIGHT'=> $product['parameters']['values']['usage_excl_night'],
                                        'DISCOUNTCODE_E'=>$product['parameters']['values']['promo_discount']['discountcodeE'],
                                        'DISCOUNTCODE_G'=>$product['parameters']['values']['promo_discount']['discountcodeG'],
                                        'DISCOUNTCODE_P'=>$product['parameters']['values']['promo_discount']['discountcodeP'],
                                        'POSTALCODE' => $product['parameters']['values']['postal_code'],
                                        'ANALYTIC'=>$analytics_id

                                        ];
                                        $signupURL=$product['product']['subscribe_url'];

                                        $query['signupURL']= str_replace($replace, $info, $signupURL);
                                        $query['signupURLE']= '';
                                        $query['signupURLG']= '';

                                        $query['signdate']= date("Y-m-d");
                                        $query['startdate']= $product['price']['validity_period']['start'];
                                        $query['durationdb']= $product['product']['contract_duration'];
                                        $query['duration']= $product['product']['contract_duration'];
                                        $query['enddate']= $product['price']['validity_period']['end'];
                                        $query['total']=($product['price']['totals']['year']['incl_promo']/100);

                                        $query['contract_energy_costE']= (($product['price']['breakdown']['electricity']['energy_cost']['single']+$product['price']['breakdown']['electricity']['energy_cost']['day']+$product['price']['breakdown']['electricity']['energy_cost']['night']+$product['price']['breakdown']['electricity']['energy_cost']['excl_night'])/100);
                                        $query['contract_promoamountE']= '';

                                        $query['contract_energy_costG']= '';
                                        $query['contract_promoamountG']= '';

                                        $query['contract_duration']= $product['product']['contract_duration'];
                                        $query['price_typeE']= $product['product']['pricing_type'];


                                        }
               
               
    }
           
    if($allSelected['gasData'] && !$allSelected['eleData']){
               
                                        foreach($allSelected['gasData'] as $product){

                                        $uuid=$product['parameters']['uuid']; 
                                        $type=$product['parameters']['values']['comparison_type'];
                                        $query['id'] = $product['product']['id'];
                                        $query['gid'] =$eid= $product['product']['id'];
                                        $query['customer_group']=$product['parameters']['values']['customer_group'];
                                        $query['proid'] = $product['product']['id'];
                                        $query['type']='gas';
                                        $query['uuid']= $product['parameters']['uuid'];
                                        $query['comp_type']= $product['parameters']['values']['comparison_type'];
                                        $query['curr_supplierE']= $product['parameters']['values']['current_supplier_name_electricity'];
                                        $query['curr_supplierG']= $product['parameters']['values']['current_supplier_name_gas'];
                                        $query['energycostE']= "";
                                        $query['residents']= $product['parameters']['values']['residents'];
                                        $query['first_residence']= $product['parameters']['values']['first_residence'];
                                        $query['decentralise_production']= $product['parameters']['values']['decentralise_production'];
                                        if($product['parameters']['values']['capacity_decentalise']==null){

                                        $capacity_decentalise=0;

                                        }else{
                                        $capacity_decentalise=$product['parameters']['values']['capacity_decentalise'];    
                                        }
                                        $query['capacity_decentalise']=$capacity_decentalise;
                                        $query['includeG']= $product['parameters']['values']['includeG'];
                                        $query['includeE']= $product['parameters']['values']['includeE'];

                                        $ele_disc="0";
                                        $gas_disc="0";
                                        $all_disc="0";
                                        foreach($product['price']['breakdown']['discounts'] as $disc){

                                        if($disc['parameters']['fuel_type']=='electricity'){

                                        $ele_disc=$ele_disc+$disc['amount'];
                                        }
                                        if($disc['parameters']['fuel_type']=='gas'){

                                        $gas_disc=$gas_disc+$disc['amount'];
                                        }
                                        if($disc['parameters']['fuel_type']=='all'){

                                        $all_disc=$gas_disc+$disc['amount'];
                                        }
                                        }


                                        $query['promoAmountE']= "";

                                        $query['energycostG']= ($product['price']['breakdown']['gas']['energy_cost']['usage'])/100;
                                        $query['promoAmountG']= ($gas_disc);
                                        $query['savings']= (($product['price']['totals']['year']['excl_promo']-$product['price']['totals']['year']['incl_promo'])/100);

                                        $query['supplier']= $product['supplier']['name'];
                                        $query['supplierID']= $product['supplier']['id'];

                                        $query['tariff']= $product['product']['name'];
                                        $query['tariffID']= $product['product']['id'];


                                        $replace = ['(FIRSTNAME)','(LASTNAME)','(POSTCODE)','(MONTHLYGAS)','(MONTHLYELEK)','(USAGE_G)','(USAGE_E)','(EMAIL)','(ElecMeterType)','(USAGE_E_NIGHT)','(USAGE_E_EXCL_NIGHT)','(DISCOUNTCODE_E)','(DISCOUNTCODE_G)','(DISCOUNTCODE_P)','(POSTALCODE)','(ANALYTIC)'];
                                        $info = [
                                        'FIRSTNAME' => "",
                                        'LASTNAME' => "",
                                        'POSTCODE' => $product['parameters']['values']['postal_code'], 
                                        'MONTHLYGAS'=> $request->monthly_amount_g, 
                                        'MONTHLYELEK'=> $request->monthly_amount_e,
                                        'USAGE_G' => $product['parameters']['values']['usage_gas'],
                                        'USAGE_E' => $product['parameters']['values']['usage_single']+$product['parameters']['values']['usage_day']+$product['parameters']['values']['usage_night']+$product['parameters']['values']['usage_excl_night'],
                                        'EMAIL'=> "",
                                        'ElecMeterType'=> $product['parameters']['values']['meter_type'],
                                        'USAGE_E_NIGHT'=> $product['parameters']['values']['usage_night'],
                                        'USAGE_E_EXCL_NIGHT'=> $product['parameters']['values']['usage_excl_night'],
                                        'DISCOUNTCODE_E'=>$product['parameters']['values']['promo_discount']['discountcodeE'],
                                        'DISCOUNTCODE_G'=>$product['parameters']['values']['promo_discount']['discountcodeG'],
                                        'DISCOUNTCODE_P'=>$product['parameters']['values']['promo_discount']['discountcodeP'],
                                        'POSTALCODE' => $product['parameters']['values']['postal_code'],
                                        'ANALYTIC'=>$analytics_id

                                        ];
                                        $signupURL=$product['product']['subscribe_url'];
                                        $signupURLE="";
                                        $signupURLG="";

                                        $query['signupURL']= str_replace($replace, $info, $signupURL);
                                        $query['signupURLE']= "";
                                        $query['signupURLG']= "";

                                        $query['signdate']= date("Y-m-d");
                                        $query['startdate']= $product['price']['validity_period']['start'];
                                        $query['durationdb']= $product['product']['contract_duration'];
                                        $query['duration']= $product['product']['contract_duration'];
                                        $query['enddate']= $product['price']['validity_period']['end'];
                                        $query['total']=($product['price']['totals']['year']['incl_promo']/100);

                                        $query['contract_energy_costE']= "";
                                        $query['contract_promoamountE']= '';
                                        $query['contract_energy_costG']= (($product['price']['breakdown']['gas']['energy_cost']['usage'])/100);
                                        $query['contract_promoamountG']= '';
                                        $query['contract_duration']= $product['product']['contract_duration'];
                                        $query['price_typeG']= $product['product']['pricing_type'];


                                        }
               
           }
           
          
           if($allSelected['gasData'] && $allSelected['eleData']){
               
                                        foreach($allSelected['gasData'] as $product){
                                        $uuid=$product['parameters']['uuid'];  
                                        $type=$product['parameters']['values']['comparison_type'];
                                        $query['id'] = $product['product']['id'];
                                        $query['gid'] =$eid= $product['product']['id'];
                                        $query['customer_group']=$product['parameters']['values']['customer_group'];
                                        $query['type']='dual';
                                        $query['uuid']= $product['parameters']['uuid'];
                                        $query['comp_type']= 'dual';
                                        $query['curr_supplierE']= "";
                                        $query['curr_supplierG']= $product['parameters']['values']['current_supplier_name_gas'];
                                        $query['energycostE']= "";
                                        $query['includeG']= 1;
                                        $query['includeE']= 1;
                                        $ele_disc="0";
                                        $gas_disc="0";
                                        $all_disc="0";
                                        foreach($product['price']['breakdown']['discounts'] as $disc){

                                        if($disc['parameters']['fuel_type']=='electricity'){

                                        $ele_disc=$ele_disc+$disc['amount'];
                                        }
                                        if($disc['parameters']['fuel_type']=='gas'){

                                        $gas_disc=$gas_disc+$disc['amount'];
                                        }
                                        if($disc['parameters']['fuel_type']=='all'){

                                        $all_disc=$gas_disc+$disc['amount'];
                                        }
                                        }
                                        $query['promoAmountE']= "";
                                        $query['energycostG']= ($product['price']['breakdown']['gas']['energy_cost']['usage'])/100;
                                        $query['promoAmountG']= ($gas_disc);
                                        $query['savings']= (($product['price']['totals']['year']['excl_promo']-$product['price']['totals']['year']['incl_promo'])/100);
                                        $query['supplier']= $product['supplier']['name'];
                                        $query['supplierID']= $product['supplier']['id'];
                                        $query['tariff']= $product['product']['name'];
                                        $query['tariffID']= $product['product']['id'];
                                        $query['supplier_G']= $product['supplier']['name'];
                                        $query['supplierID_G']= $product['supplier']['id'];
                                        $query['tariff_G']= $product['product']['name'];
                                        $query['tariffID_G']= $product['product']['id'];

                                        $replace = ['(FIRSTNAME)','(LASTNAME)','(POSTCODE)','(MONTHLYGAS)','(MONTHLYELEK)','(USAGE_G)','(USAGE_E)','(EMAIL)','(ElecMeterType)','(USAGE_E_NIGHT)','(USAGE_E_EXCL_NIGHT)','(DISCOUNTCODE_E)','(DISCOUNTCODE_G)','(DISCOUNTCODE_P)','(POSTALCODE)','(ANALYTIC)'];
                                        $info = [
                                        'FIRSTNAME' => "",
                                        'LASTNAME' => "",
                                        'POSTCODE' => $product['parameters']['values']['postal_code'], 
                                        'MONTHLYGAS'=> $request->monthly_amount_g, 
                                        'MONTHLYELEK'=> $request->monthly_amount_e,
                                        'USAGE_G' => $product['parameters']['values']['usage_gas'],
                                        'USAGE_E' => $product['parameters']['values']['usage_single']+$product['parameters']['values']['usage_day']+$product['parameters']['values']['usage_night']+$product['parameters']['values']['usage_excl_night'],
                                        'EMAIL'=> "",
                                        'ElecMeterType'=> $product['parameters']['values']['meter_type'],
                                        'USAGE_E_NIGHT'=> $product['parameters']['values']['usage_night'],
                                        'USAGE_E_EXCL_NIGHT'=> $product['parameters']['values']['usage_excl_night'],
                                        'DISCOUNTCODE_E'=>$product['parameters']['values']['promo_discount']['discountcodeE'],
                                        'DISCOUNTCODE_G'=>$product['parameters']['values']['promo_discount']['discountcodeG'],
                                        'DISCOUNTCODE_P'=>$product['parameters']['values']['promo_discount']['discountcodeP'],
                                        'POSTALCODE' => $product['parameters']['values']['postal_code'],
                                        'ANALYTIC'=>$analytics_id

                                        ];

                                        $signupURLE="";
                                        $signupURL="";
                                        $signupURLG=$product['product']['subscribe_url'];
                                        $query['signupURL']= "";
                                        $query['signupURLE']= "";
                                        $query['signupURLG']= str_replace($replace, $info, $signupURLG);
                                        $query['signdate']= date("Y-m-d");
                                        $query['startdate']= $product['price']['validity_period']['start'];
                                        $query['durationdb']= $product['product']['contract_duration'];
                                        $query['duration']= $product['product']['contract_duration'];
                                        $query['enddate']= $product['price']['validity_period']['end'];
                                        $total=$product['price']['breakdown']['gas']['unit_cost']['total'];
                                        $query['contract_energy_costE']= "";
                                        $query['contract_promoamountE']= '';
                                        $query['contract_energy_costG']= (($product['price']['breakdown']['gas']['energy_cost']['usage'])/100);
                                        $query['contract_promoamountG']= (($product['price']['totals']['year']['excl_promo']-$product['price']['totals']['year']['incl_promo'])/100);
                                        $query['contract_duration']= $product['product']['contract_duration'];
                                        $query['price_typeG']= $product['product']['pricing_type'];

                                        }

                                        foreach($allSelected['eleData'] as $product)
                                        {
                                        $uuid=$product['parameters']['uuid'];  
                                        $type=$product['parameters']['values']['comparison_type'];
                                        $query['id'] = $product['product']['id'];
                                        $query['eid'] = $gid= $product['product']['id']; 
                                        $query['meter_type']= $product['parameters']['values']['meter_type'];
                                        $query['curr_supplierE']= $product['parameters']['values']['current_supplier_name_electricity'];
                                        $query['energycostE']= (($product['price']['breakdown']['electricity']['energy_cost']['single']+$product['price']['breakdown']['electricity']['energy_cost']['day']+$product['price']['breakdown']['electricity']['energy_cost']['night']+$product['price']['breakdown']['electricity']['energy_cost']['excl_night'])/100);
                                        $ele_disc="0";
                                        $gas_disc="0";
                                        $all_disc="0";
                                        foreach($product['price']['breakdown']['discounts'] as $disc)
                                        {

                                                if($disc['parameters']['fuel_type']=='electricity'){
                                                $ele_disc=$ele_disc+$disc['amount'];
                                                }
                                                if($disc['parameters']['fuel_type']=='gas'){
                                                $gas_disc=$gas_disc+$disc['amount'];
                                                }
                                                if($disc['parameters']['fuel_type']=='all'){
                                                $all_disc=$gas_disc+$disc['amount'];
                                                }


                                        }
                                        $query['promoAmountE']= ($ele_disc);
                                        $query['savings']= (($product['price']['totals']['year']['excl_promo']-$product['price']['totals']['year']['incl_promo'])/100);
                                        $query['supplier']= $product['supplier']['name'];
                                        $query['supplierID ']= $product['supplier']['id'];
                                        $query['tariff']= $product['product']['name'];
                                        $query['tariffID']= $product['product']['id'];
                                        $replace = ['(FIRSTNAME)','(LASTNAME)','(POSTCODE)','(MONTHLYGAS)','(MONTHLYELEK)','(USAGE_G)','(USAGE_E)','(EMAIL)','(ElecMeterType)','(USAGE_E_NIGHT)','(USAGE_E_EXCL_NIGHT)','(DISCOUNTCODE_E)','(DISCOUNTCODE_G)','(DISCOUNTCODE_P)','(POSTALCODE)','(ANALYTIC)'];
                                        $info = [
                                        'FIRSTNAME' => "",
                                        'LASTNAME' => "",
                                        'POSTCODE' => $product['parameters']['values']['postal_code'], 
                                        'MONTHLYGAS'=> $request->monthly_amount_g, 
                                        'MONTHLYELEK'=> $request->monthly_amount_e,
                                        'USAGE_G' => $product['parameters']['values']['usage_gas'],
                                        'USAGE_E' => $product['parameters']['values']['usage_single']+$product['parameters']['values']['usage_day']+$product['parameters']['values']['usage_night']+$product['parameters']['values']['usage_excl_night'],
                                        'EMAIL'=> "",
                                        'ElecMeterType'=> $product['parameters']['values']['meter_type'],
                                        'USAGE_E_NIGHT'=> $product['parameters']['values']['usage_night'],
                                        'USAGE_E_EXCL_NIGHT'=> $product['parameters']['values']['usage_excl_night'],
                                        'DISCOUNTCODE_E'=>$product['parameters']['values']['promo_discount']['discountcodeE'],
                                        'DISCOUNTCODE_G'=>$product['parameters']['values']['promo_discount']['discountcodeG'],
                                        'DISCOUNTCODE_P'=>$product['parameters']['values']['promo_discount']['discountcodeP'],
                                        'POSTALCODE' => $product['parameters']['values']['postal_code'],
                                        'ANALYTIC'=>$analytics_id
                                        ];
                                        $signupURL="";
                                        $signupURLE=$product['product']['subscribe_url'];
                                        $query['signupURLE']= str_replace($replace, $info, $signupURLE);
                                        $query['signdate']= date("Y-m-d");
                                        $query['startdate']= $product['price']['validity_period']['start'];
                                        $query['durationdb']= $product['product']['contract_duration'];
                                        $query['duration']= $product['product']['contract_duration'];
                                        $query['enddate']= $product['price']['validity_period']['end'];
                                        $query['total']= ($product['price']['totals']['year']['incl_promo']/100);
                                        $query['contract_energy_costE']= (($product['price']['breakdown']['electricity']['energy_cost']['single']+$product['price']['breakdown']['electricity']['energy_cost']['day']+$product['price']['breakdown']['electricity']['energy_cost']['night']+$product['price']['breakdown']['electricity']['energy_cost']['excl_night'])/100);
                                        $query['contract_promoamountE']= '';
                                        $query['contract_duration']= $product['product']['contract_duration'];
                                        $query['price_typeE']= $product['product']['pricing_type'];
                                        if($product['parameters']['values']['estimate_cunsomption']==null || $product['parameters']['values']['estimate_cunsomption']==0){
                                        $estimate_cunsomption='false';
                                        }else{
                                        $estimate_cunsomption=$product['parameters']['values']['estimate_cunsomption'];
                                        }
                                        $query['estimate_cunsomption'] =$estimate_cunsomption;
                                        $query['residents']= $product['parameters']['values']['residents'];
                                        $query['first_residence']= $product['parameters']['values']['first_residence'];
                                        $query['decentralise_production']= $product['parameters']['values']['decentralise_production'];
                                        if($product['parameters']['values']['capacity_decentalise']==null){
                                        $capacity_decentalise=0;
                                        }else{
                                        $capacity_decentalise=$product['parameters']['values']['capacity_decentalise'];    
                                        }
                                        $query['capacity_decentalise']=$capacity_decentalise;

                                }
                    }
                    try {

                    $client = new \GuzzleHttp\Client();
                    $api = $client->post('https://api.tariefchecker.be/api/conversion', [
                    'headers' => [
                    'Accept' => 'application/json',
                    'Content-type' => 'application/x-www-form-urlencoded',
                    'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWFmMjVkYWNmZTNiM2I0MmZjOTJkMTU5MjIxY2RjNjNkY2MxMzEwZWU3NDJlM2YzNmRiOWZiMDZhZmMwNGMyNTgyNzEzNjRhYjU5Y2VkZGQiLCJpYXQiOjE2NDMyODY2MjcsIm5iZiI6MTY0MzI4NjYyNywiZXhwIjoyMjc0NDM4NjI3LCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.rmiDd2sM0kduf6CPed5rjbqPL4Fui-MDdOKViiPn49pcJEukW_kA2ByuJfHNUIe9rctIXsovX1T8kgeer6TxgQkGCvruO98zcGklVv470en8ul6NzOCMmaemX4cJj4XQYlcI1_-z6tnHqtbbc7_-TyvezidDGslhMAMmtREicgrubnp9VGyl6YtE_pXHedruJ7PxYsc2_Gqu-osdFOdEW6hxN3uPlpKbuHgrf9DvJr8B3PSDQLPl49Q9HzrL-vPgayZTpNFINpCw1QBKk_ooWo861UZQ_cE33TdNfXyoJ5WnXQ-AjvtInfw7C9skq57C9X4NmfsllWCacNn9IYNs4uocuFo259TbRXNuooHsWTDTty4kalcp3LD7G0exCTTDC3_QsEoZI6694ct8Fi0gOJ05thoS5grKIfKyFkRqu1eOS2wMdNs-6KZXwVQ6fv1sJE-VjdIKXoj-r6wo_FPSceB599yz22gwVQLnDQJAvu0OahSyU8DG3VMH__ItYBuTI0uOTJZerwaRwmnTkSWbWczA4c8AEb1H_W-G4Yblh4D9y_ZOW7FvvFj53dCX83mzUyBN3HahqzD8ZX0IvZXolZHLxluIOlFoR9HiLNzTZFrJSzWru39AjNmbaK8-AAydGlF606uGglo76ES7D7dOvDa5lgUjYzRvby8jDpRSkzY'
                    ],
                    'query' => $query
                    ]);
                    $data = $api->getBody()->getContents();
                    $response = json_decode($data, true);            

                    } catch (\Exception $e) {

                    $response = ['status' => false, 'message' => $e->getMessage()];

                    }  

                    $data=Session::get('data');
                    $query['postal_code']= $data['postalcode'];
                    $user = UserLog::create([
                    'first_name' => $request->firstname,
                    'last_name' => $request->lastname,
                    'email' => $request->email,
                    'product_id'=>$data['id'],
                    'postal_code'=>$data['postalcode'],
                    'last_seen' => Carbon::now(),
                    'ip_address' => $request->getClientIp(),
                    ]);           
            
                    if(isset($response['pack'])){            
                        Session::forget('url');
                        Session::forget('from');            
                        $url[1]=$response['pack'];
                        $url[2]=""; 
                    }            
                    if(isset($response['gas'])){                
                        Session::forget('url');
                        Session::forget('from');
                        $url[1]=$response['gas']; 
                        $url[2]="";           
                    }
                    if(isset($response['electricity'])){                
                        Session::forget('url');
                        Session::forget('from');
                        $url[2]=$response['electricity']; 
                        $url[1]="";             
                    }
            
            
                    // user-search-details
            
                    $query['req_from'] = 'request';

                    // try {

                    // $client = new \GuzzleHttp\Client();
                    // $api = $client->post('https://api.tariefchecker.be/api/user-search-details', [
                    // 'headers' => [
                    // 'Accept' => 'application/json',
                    // 'Content-type' => 'application/x-www-form-urlencoded',
                    // 'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWFmMjVkYWNmZTNiM2I0MmZjOTJkMTU5MjIxY2RjNjNkY2MxMzEwZWU3NDJlM2YzNmRiOWZiMDZhZmMwNGMyNTgyNzEzNjRhYjU5Y2VkZGQiLCJpYXQiOjE2NDMyODY2MjcsIm5iZiI6MTY0MzI4NjYyNywiZXhwIjoyMjc0NDM4NjI3LCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.rmiDd2sM0kduf6CPed5rjbqPL4Fui-MDdOKViiPn49pcJEukW_kA2ByuJfHNUIe9rctIXsovX1T8kgeer6TxgQkGCvruO98zcGklVv470en8ul6NzOCMmaemX4cJj4XQYlcI1_-z6tnHqtbbc7_-TyvezidDGslhMAMmtREicgrubnp9VGyl6YtE_pXHedruJ7PxYsc2_Gqu-osdFOdEW6hxN3uPlpKbuHgrf9DvJr8B3PSDQLPl49Q9HzrL-vPgayZTpNFINpCw1QBKk_ooWo861UZQ_cE33TdNfXyoJ5WnXQ-AjvtInfw7C9skq57C9X4NmfsllWCacNn9IYNs4uocuFo259TbRXNuooHsWTDTty4kalcp3LD7G0exCTTDC3_QsEoZI6694ct8Fi0gOJ05thoS5grKIfKyFkRqu1eOS2wMdNs-6KZXwVQ6fv1sJE-VjdIKXoj-r6wo_FPSceB599yz22gwVQLnDQJAvu0OahSyU8DG3VMH__ItYBuTI0uOTJZerwaRwmnTkSWbWczA4c8AEb1H_W-G4Yblh4D9y_ZOW7FvvFj53dCX83mzUyBN3HahqzD8ZX0IvZXolZHLxluIOlFoR9HiLNzTZFrJSzWru39AjNmbaK8-AAydGlF606uGglo76ES7D7dOvDa5lgUjYzRvby8jDpRSkzY'
                    // ],
                    // 'query' => $query
                    // ]);
                    // $data = $api->getBody()->getContents();
                    // $response = json_decode($data, true);            

                    // } catch (\Exception $e) {

                    // $response = ['status' => false, 'message' => $e->getMessage()];

                    // }

                    // user-search-details-end

                    if($url[1]!="" && $url[2]==""){

                        return Redirect::away($url[1]);
                    }

                    if($url[1]=="" && $url[2]!=""){

                        return Redirect::away($url[2]);
                    }

                    if($url[1]!="" && $url[2]!=""){

                        return Redirect::away($url[1]);
                    }

                    // return Redirect::away($url[1]);
                    // dd($url);   


                    // code end





         }
    
   

}
