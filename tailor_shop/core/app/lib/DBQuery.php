<?php 
namespace App\lib;

use Users\User;
use Auth;
use App\Order;
use App\Orderdetail;
use App\lib\Custom;
use DateTime;
use format;
use App\Service;
use DB;
use App\Payment;

class DBQuery
{
      //Active Query
	 public static function getServices(){
      $services = Service::All();
      return $services;
    }

    public static function saveOrder($request){

            $serviceRef = Custom::orderRef();
            $service_crete_date = Custom::dateConvertor($date = $request['service_crete_date']);
            $service_delivery_date = Custom::dateConvertor($date = $request['service_delivery_date']);
            $order = new Order(array(
                            'service_ref'              =>$serviceRef,
                            'service_status'           =>1,
                            'order_create_by'          =>$request->order_create_by,
                            'service_crete_date'       =>$service_crete_date,
                            'service_delivery_date'    =>$service_delivery_date,
                            'service_cus_name'         =>$request->service_cus_name,
                            'service_cus_email'        =>$request->service_cus_email,
							'service_cus_croptop'      =>$request->service_cus_croptop,
							'service_cus_fulllength'   =>$request->service_cus_fulllength,
							'service_cus_upperchest'   =>$request->service_cus_upperchest,
							'service_cus_waist'   		=>$request->service_cus_waist,
							'service_cus_hip'   		=>$request->service_cus_hip,
							'service_cus_sleeveslength' =>$request->service_cus_sleeveslength,
							'service_cus_armsfold' 		=>$request->service_cus_armsfold,
							'service_cus_frontshoulder' =>$request->service_cus_frontshoulder,
							'service_cus_chest' 		=>$request->service_cus_chest,
							'service_cus_chestpoint' 	=>$request->service_cus_chestpoint,
							'service_cus_shoulder' 		=>$request->service_cus_shoulder,
							'service_cus_sleevesloose' 	=>$request->service_cus_sleevesloose,
							'service_cus_neck' 			=>$request->service_cus_neck,
							'service_cus_chudidar' 		=>$request->service_cus_chudidar,
							'service_cus_salvar' 		=>$request->service_cus_salvar,
							'service_cus_blouse' 		=>$request->service_cus_blouse,
							'service_cus_dress' 		=>$request->service_cus_dress,
							'service_cus_skirt' 		=>$request->service_cus_skirt,
							'service_cus_saree' 		=>$request->service_cus_saree,
							'service_cus_fall' 		=>$request->service_cus_fall,
							'service_cus_pico' 		=>$request->service_cus_pico,
							'service_cus_knots' 		=>$request->service_cus_knots,
                            'cell_number'              =>$request->cell_number,
                            'service_cus_address'      =>$request->service_cus_address,
                            'total_amount'             =>$request->total_amount,
                            'discount_amount'          =>$request->discount_amount,
                           
                            )); 
            $order->save();
            $payment = new Payment(array(
                            'order_id'              =>$order->id,
                            'received_amount'       =>$request->advance_amount,
                              ));
            $payment->save();
            $num_elements = 0;
            $orderdetails = array();
            while($num_elements < count($request->service_price)){
                
             $orderdetail =  new Orderdetail([
                    
                    'service_price'      => $request->service_price[$num_elements],
                    'service_quantity'   => $request->service_quantity[$num_elements],
                    'service_amount'     => $request->service_amount[$num_elements],
                    'service_measer'     => $request->service_measer[$num_elements],
                    'service_id'         => $request->service_id[$num_elements],
					
                ]); 

                $num_elements++;
                $orderdetails[] =  $orderdetail ;               
            }

      $order->orderdetails()->saveMany($orderdetails);
      return $order;
    }

    public static function saveUpdateOrder($request){

        $order = Order::updateOrCreate(
                                  
             ['id' => $request->order_id],
             [
                    'service_cus_name'         =>$request->service_cus_name,
                    'service_cus_email'        =>$request->service_cus_email,
                    'cell_number'              =>$request->cell_number,
                    'service_cus_address'      =>$request->service_cus_address,
                    'total_amount'             =>$request->total_amount,
                    'discount_amount'          =>$request->discount_amount,
					'service_cus_croptop'      =>$request->service_cus_croptop,
							'service_cus_fulllength'   =>$request->service_cus_fulllength,
							'service_cus_upperchest'   =>$request->service_cus_upperchest,
							'service_cus_waist'   		=>$request->service_cus_waist,
							'service_cus_hip'   		=>$request->service_cus_hip,
							'service_cus_sleeveslength' =>$request->service_cus_sleeveslength,
							'service_cus_armsfold' 		=>$request->service_cus_armsfold,
							'service_cus_frontshoulder' =>$request->service_cus_frontshoulder,
							'service_cus_chest' 		=>$request->service_cus_chest,
							'service_cus_chestpoint' 	=>$request->service_cus_chestpoint,
							'service_cus_shoulder' 		=>$request->service_cus_shoulder,
							'service_cus_sleevesloose' 	=>$request->service_cus_sleevesloose,
							'service_cus_neck' 			=>$request->service_cus_neck,
							'service_cus_chudidar' 		=>$request->service_cus_chudidar,
							'service_cus_salvar' 		=>$request->service_cus_salvar,
							'service_cus_blouse' 		=>$request->service_cus_blouse,
							'service_cus_dress' 		=>$request->service_cus_dress,
							'service_cus_skirt' 		=>$request->service_cus_skirt,
							'service_cus_saree' 		=>$request->service_cus_saree,
							'service_cus_fall' 		=>$request->service_cus_fall,
							'service_cus_pico' 		=>$request->service_cus_pico,
							'service_cus_knots' 		=>$request->service_cus_knots,
					
              ]
           );

		   /* Payment::where('order_id',$request->order_id))
                ->update(array('received_amount' =>  $request->advance_amount)); */
		   
         
		  $payment_id=Order::find($request->order_id)->payments->first()->id;
		  //Service::where('id',$service_id)->first()
		  //Payment::where('order_id',Custom::convert_number_to_words($request->order_id))->get();
		  //Order::find($request->order_id)->payments->first()->id;

          Payment::updateOrCreate(
             ['id'=>$payment_id],
             ['received_amount' => $request->advance_amount,]
          ); 
            $num_elements = 0;
            while($num_elements < count($request->service_price)){
            $orderdetail = Orderdetail::updateOrCreate(
                  ['id' => $request->orderdetail_id[$num_elements]],
                  [     
                  'service_price'      => $request->service_price[$num_elements],
                  'order_id'           => $order->id,
                  'service_quantity'   => $request->service_quantity[$num_elements],
                  'service_amount'     => $request->service_amount[$num_elements],
                  'service_measer'     => $request->service_measer[$num_elements],
                  'service_id'         => $request->service_id[$num_elements],
                   ]);
           
                    $num_elements++;
           }
    }

    public static function getOrdersByStatus($id){

      $activeOrders = Order::where('service_status',$id)->get();
      return $activeOrders;
    }

    public static function saveService($request){
       $service = new Service(array(
                            'service_name'            =>$request->service_name,
                            'service_price'           =>$request->service_price,
                            'service_create_by'       =>$request->service_create_by,
                           
                            ));
             
            $service->save();
            return $service;
    }

}
