<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\DealsMail;
use Session;
use Toastr;

class EmailController extends Controller
{
    public function index()
    {   
        //
    }
    
    public function dealsMail(Request $request)
    {
        $email=$to = $request->recipient;
        Mail::to($to)->send( new DealsMail($to));
        $cat=Session::get('customer_type');
        $postal_code=Session::get('postal_code');


       



     $parameters = Session::get('getParameters');
        // active campaig updating
        
            $activeQueries['uuid']=$uuid=$parameters['parameters']['uuid'];
            $activeQueries['locale']=$parameters['parameters']['values']['locale'];
            $activeQueries['customer_group']=$parameters['parameters']['values']['customer_group'];
            $activeQueries['region']=$parameters['parameters']['values']['region'];
            $activeQueries['usage_single']=$parameters['parameters']['values']['usage_single'];
            $activeQueries['usage_day']=$parameters['parameters']['values']['usage_day'];
            $activeQueries['usage_night']=$parameters['parameters']['values']['usage_night'];
            $activeQueries['usage_excl_night']=$parameters['parameters']['values']['usage_excl_night'];

           
             $activeQueries['estimate_cunsomption'] =$parameters['parameters']['values']['estimate_cunsomption'];
            $activeQueries['residence'] =$parameters['parameters']['values']['residence'];
            $activeQueries['building_type'] =$parameters['parameters']['values']['building_type'];
            $activeQueries['isolation_level'] =$parameters['parameters']['values']['isolation_level'];
            $activeQueries['heating_system'] =$parameters['parameters']['values']['heating_system'];

             $activeQueries['residents']= $parameters['parameters']['values']['residents'];
            $activeQueries['first_residence']= $parameters['parameters']['values']['first_residence'];
            $activeQueries['decentralise_production']= $parameters['parameters']['values']['decentralise_production'];
             if($parameters['parameters']['values']['capacity_decentalise']==null || $parameters['parameters']['values']['capacity_decentalise']==0){

                $capacity_decentalise=0;

            }else{
                $capacity_decentalise=$parameters['parameters']['values']['capacity_decentalise'];    
            }
            $activeQueries['capacity_decentalise']=$capacity_decentalise;
            $activeQueries['includeG']= $parameters['parameters']['values']['includeG'];
            $activeQueries['includeE']= $parameters['parameters']['values']['includeE'];


            $activeQueries['usage_gas']=$parameters['parameters']['values']['usage_gas'];
            
             $activeQueries['meter_type']=$parameters['parameters']['values']['meter_type'];
            
            $activeQueries['comparison_type']=$parameters['parameters']['values']['comparison_type'];
            $activeQueries['email']=$email;
            $activeQueries['postalcode']=$parameters['parameters']['values']['postal_code'];

            $curUrl='https://energievergelijker.tariefchecker.be/overzicht/'.$parameters['parameters']['values']['comparison_type'].'/'.$parameters['parameters']['values']['dgo_id_electricity'].'-'.$parameters['parameters']['values']['dgo_id_gas'].'-'.$parameters['parameters']['values']['postal_code'].'?u='.$uuid;
            Session::put('actual_link',$curUrl);
            $activeQueries['url']=$curUrl;
            
        
       
        
        $activeQueries['CurrentSupplierE'] = $parameters['parameters']['values']['current_supplier_name_electricity'];
       
        
        $activeQueries['CurrentSupplierG'] = $parameters['parameters']['values']['current_supplier_name_gas'];
        
           

        try{ 


       $client = new \GuzzleHttp\Client(); 
                  $request = $client->post('https://api.tariefchecker.be/api/change-data-sync', [
                      'headers' => [
                          'Accept' => 'application/json',
                          'Content-type' => 'application/x-www-form-urlencoded',
                          'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWFmMjVkYWNmZTNiM2I0MmZjOTJkMTU5MjIxY2RjNjNkY2MxMzEwZWU3NDJlM2YzNmRiOWZiMDZhZmMwNGMyNTgyNzEzNjRhYjU5Y2VkZGQiLCJpYXQiOjE2NDMyODY2MjcsIm5iZiI6MTY0MzI4NjYyNywiZXhwIjoyMjc0NDM4NjI3LCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.rmiDd2sM0kduf6CPed5rjbqPL4Fui-MDdOKViiPn49pcJEukW_kA2ByuJfHNUIe9rctIXsovX1T8kgeer6TxgQkGCvruO98zcGklVv470en8ul6NzOCMmaemX4cJj4XQYlcI1_-z6tnHqtbbc7_-TyvezidDGslhMAMmtREicgrubnp9VGyl6YtE_pXHedruJ7PxYsc2_Gqu-osdFOdEW6hxN3uPlpKbuHgrf9DvJr8B3PSDQLPl49Q9HzrL-vPgayZTpNFINpCw1QBKk_ooWo861UZQ_cE33TdNfXyoJ5WnXQ-AjvtInfw7C9skq57C9X4NmfsllWCacNn9IYNs4uocuFo259TbRXNuooHsWTDTty4kalcp3LD7G0exCTTDC3_QsEoZI6694ct8Fi0gOJ05thoS5grKIfKyFkRqu1eOS2wMdNs-6KZXwVQ6fv1sJE-VjdIKXoj-r6wo_FPSceB599yz22gwVQLnDQJAvu0OahSyU8DG3VMH__ItYBuTI0uOTJZerwaRwmnTkSWbWczA4c8AEb1H_W-G4Yblh4D9y_ZOW7FvvFj53dCX83mzUyBN3HahqzD8ZX0IvZXolZHLxluIOlFoR9HiLNzTZFrJSzWru39AjNmbaK8-AAydGlF606uGglo76ES7D7dOvDa5lgUjYzRvby8jDpRSkzY'
                      ],
                      'query' => $activeQueries 
                  ]);
        

        }catch (\Exception $e) {
        
        
        $response = ['status' => false, 'message' => $e->getMessage()];
        }

      
       
        $cat='pack';
        Toastr::success('Email Sent Successfully', 'Updated');
        return redirect('overzicht/'.$cat.'/'.$postal_code);
        
        
       // return redirect()->route('basic-data');
    }
}
