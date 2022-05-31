<?php

namespace App\Http\Controllers;

use App\Models\Tac;
use Illuminate\Http\Request;
use Auth;
use Session;
use GuzzleHttp\Psr7\Request as GuzzleHttp;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Hash;
use Carbon\Carbon;
use Lang;
use DB;
use DateTime;
use Response;


class TacController extends Controller
{
  

    public function test(Request $request){ 
        
        
        echo phpinfo();
       // return view('test');
        exit();

         $query['locale'] = 'nl';
        $query['postalCode'] = Session::get('po');
        $query['first_residence']=true;
        $query['customerGroup'] = 'residential';
      
        $query['registerG'] = 25000;
       
        
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

        dd(count($json['products']));
        
       

    $arr = array(
            'properties' => array(
                array(
                    'property' => 'email',
                    'value' => 'rpk@wx.agency'
                ),
                array(
                    'property' => 'firstname',
                    'value' => 'wx'
                ),
                array(
                    'property' => 'lastname',
                    'value' => 'developer'
                ),
                array(
                    'property' => 'phone',
                    'value' => '123456789'
                )
            )
        );

    
        $post_json = json_encode($arr);
        $hapikey = "15fe8021-3f44-4533-bdf6-8c6c3182666b";
        $endpoint = 'https://api.hubapi.com/contacts/v1/contact?hapikey=' . $hapikey;

        $ch = @curl_init();
        @curl_setopt($ch, CURLOPT_POST, true);
        @curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
        @curl_setopt($ch, CURLOPT_URL, $endpoint);
        @curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = @curl_exec($ch);
        $status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errors = curl_error($ch);
        @curl_close($ch);
        echo "curl Errors: " . $curl_errors;
        echo "\nStatus code: " . $status_code;
        echo "\nResponse: " . $response;
        dd($hapikey);
        
      

    }

    public function index(){
        
        if(Session::get('locale')==""){
            
            Session::put('locale','nl');
        }
    
        $agent = new \Jenssegers\Agent\Agent;
        $result = $agent->isDesktop();
         return view('index');
        if($agent->isTablet() || $agent->isMobile() || $agent->isDesktop()){
        }
    
    }
  
    public function basic_data(Request $request){      
      
      $gas=$request->gas;
      $elec=$request->elec;
      $post_code=$request->po;  
      return redirect('overview/pack');
      
    }

     public function refresh_uuid(){


        unset($_COOKIE['uuid']); 
        setcookie('uuid', '', time() - 3600);

        return redirect('/');
    }



}
