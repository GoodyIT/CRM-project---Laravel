<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class Price extends Model {


/**
* Price listing array           
* @access public priceList
* @return array $priceData
*/

    public function priceList() {
        
        $whereConditions = ['is_delete' => '1'];
        $listArray = ['id','name','status'];

        $priceData = DB::table('price_grid')
                         ->select($listArray)
                         ->where($whereConditions)
                         ->get();

        return $priceData;
    }

/**
* Delete Price           
* @access public priceDelete
* @param  int $id
* @return array $result
*/ 

    public function priceDelete($id)
    {
        if(!empty($id))
        {
            $result = DB::table('price_grid')->where('id','=',$id)->update(array("is_delete" => '0'));
            return $result;
        }
        else
        {
            return false;
        }
    }


/**
* Price Detail           
* @access public priceDetail
* @param  int $priceId
* @return array $combine_array
*/  

    public function priceDetail($priceId) {

        $wherePriceConditions = ['id' => $priceId];
        $priceData = DB::table('price_grid')->where($wherePriceConditions)->get();


        $whereConditions = ['price_id' => $priceId];
        $listArray = ['item','time','charge','is_gps_distrib','is_gps_opt','is_per_line','is_per_order','is_per_screen_set'];
        $priceCharge = DB::table('price_grid_charges')->select($listArray)->where($whereConditions)->get();


        $whereConditionsScreenPrimary = ['price_id' => $priceId];
        $listArrayPrimary = ['range_high','range_low','pricing_1c','pricing_2c','pricing_3c','pricing_4c','pricing_5c','pricing_6c','pricing_7c','pricing_8c','pricing_9c','pricing_10c','pricing_11c','pricing_12c'];
        $priceScreenPrimary = DB::table('price_screen_primary')->select($listArrayPrimary)->where($whereConditionsScreenPrimary)->get();


        $whereConditionsScreenSecondary = ['price_id' => $priceId];
        $listArraySecondary = ['range_high','range_low','pricing_1c','pricing_2c','pricing_3c','pricing_4c','pricing_5c','pricing_6c','pricing_7c','pricing_8c','pricing_9c','pricing_10c','pricing_11c','pricing_12c'];
        $priceScreenSecondary = DB::table('price_screen_secondary')->select($listArraySecondary)->where($whereConditionsScreenSecondary)->get();


        $whereConditionsGarmentMackup = ['price_id' => $priceId];
        $listArrayGarmentMackup = ['range_high','range_low','percentage'];
        $priceGarmentMackup = DB::table('price_garment_mackup')->select($listArrayGarmentMackup)->where($whereConditionsGarmentMackup)->get();


        $combine_array['price'] = $priceData;
        $combine_array['allPriceGrid'] = $priceCharge;
        $combine_array['allScreenPrimary'] = $priceScreenPrimary;
        $combine_array['allScreenSecondary'] = $priceScreenSecondary;
        $combine_array['allGarmentMackup'] = $priceGarmentMackup;
        return $combine_array;
    }

/**
* Price Add          
* @access public priceAdd
* @param  array $data
* @return array $result
*/

    public function priceAdd($data,$priceData,$priceScreenPrimary,$priceScreenSecondary,$priceGarmentMackup) {
        $data['created_date'] = date("Y-m-d H:i:s");
        $data['updated_date'] = date("Y-m-d H:i:s");
        $result = DB::table('price_grid')->insert($data);

        $priceid = DB::getPdo()->lastInsertId();

           foreach($priceData as $key => $link) 
              { 
                $priceData[$key]['price_id'] = $priceid;
                $result_price = DB::table('price_grid_charges')->insert($priceData[$key]);
              }

             foreach($priceScreenPrimary as $keyprimary => $linkprimary) 
              { 
                $priceScreenPrimary[$keyprimary]['price_id'] = $priceid;
                $result_primary = DB::table('price_screen_primary')->insert($priceScreenPrimary[$keyprimary]);
              }

              foreach($priceScreenSecondary as $keysecondary => $linksecondary) 
              { 
                $priceScreenSecondary[$keysecondary]['price_id'] = $priceid;
                $result_secondary = DB::table('price_screen_secondary')->insert($priceScreenSecondary[$keysecondary]);
              }

               foreach($priceGarmentMackup as $keygarmack => $linkgarmack) 
              { 
                $priceGarmentMackup[$keygarmack]['price_id'] = $priceid;
                $result_garment_mackup = DB::table('price_garment_mackup')->insert($priceGarmentMackup[$keygarmack]);
              }

        return $priceid;
    }

/**
* Price Edit          
* @access public priceEdit
* @param  array $data
* @return array $result
*/
    public function priceEdit($data) {

        $data['updated_date'] = date("Y-m-d H:i:s");
        $whereConditions = ['id' => $data['id']];
        $result = DB::table('price_grid')->where($whereConditions)->update($data);
        return $result;
    }

/**
* Price charges data           
* @access public priceChargesEdit
* @param  array $data
* @return array $result
*/  

public function priceChargesEdit($priceData,$priceId) {
    
    DB::table('price_grid_charges')->where('price_id', '=', $priceId)->delete();
     
           foreach($priceData as $key => $link) 
              { 
                $priceData[$key]['price_id'] = $priceId;
                $result_price = DB::table('price_grid_charges')->insert($priceData[$key]);
              }
        return  $priceId;
    }


/**
* Price charges Primary data           
* @access public priceChargesPrimaryEdit
* @param  array $data
* @return array $result
*/  

public function priceChargesPrimaryEdit($price_primary,$priceId) {
    
    DB::table('price_screen_primary')->where('price_id', '=', $priceId)->delete();
     
           foreach($price_primary as $key => $link) 
              { 
                $price_primary[$key]['price_id'] = $priceId;
                $result_price_primary = DB::table('price_screen_primary')->insert($price_primary[$key]);
              }
        return  $priceId;
    }

/**
* Price charges Secondary data           
* @access public priceChargesSecondaryEdit
* @param  array $data
* @return array $result
*/  

public function priceChargesSecondaryEdit($price_secondary,$priceId) {
    
    DB::table('price_screen_secondary')->where('price_id', '=', $priceId)->delete();
     
           foreach($price_secondary as $key => $link) 
              { 
                $price_secondary[$key]['price_id'] = $priceId;
                $result_price_secondary = DB::table('price_screen_secondary')->insert($price_secondary[$key]);
              }
        return  $priceId;
    }


/**
* Price charges Garment Mackup data           
* @access public priceGarmentMackupEdit
* @param  array $data
* @return array $result
*/  

public function priceGarmentMackupEdit($garment_mackup,$priceId) {
    
    DB::table('price_garment_mackup')->where('price_id', '=', $priceId)->delete();
     
           foreach($garment_mackup as $key => $link) 
              { 
                $garment_mackup[$key]['price_id'] = $priceId;
                $result_garment_markup = DB::table('price_garment_mackup')->insert($garment_mackup[$key]);
              }
        return  $priceId;
    }


}