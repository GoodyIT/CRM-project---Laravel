<?php

namespace App\Http\Controllers;

require_once(app_path() . '/constants.php');

use App\Product;
use Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use SplFileInfo;
use DB;
use Image;
use Request;

class ProductController extends Controller {  

/**
* Create a new controller instance.      
* @return void
*/
    public function __construct(Product $product) {

        $this->product = $product;
       
    }

/**
* Product Listing controller        
* @access public index
* @return json data
*/


/**
* Product Listing controller        
* @access public index
* @return json data
*/


/** 
 * @SWG\Definition(
 *      definition="productList",
 *      type="object",
 *     
 *      @SWG\Property(
 *          property="cond",
 *          type="object",
 *          required={"company_id"},
 *          @SWG\Property(
 *          property="company_id",
 *          type="integer",
 *         )
 *
 *      )
 *  )
 */

 /**
 * @SWG\Post(
 *  path = "/api/public/admin/product",
 *  summary = "Product Listing",
 *  tags={"Product"},
 *  description = "Product Listing",
 *  @SWG\Parameter(
 *     in="body",
 *     name="body",
 *     description="Product Listing",
 *     required=true,
 *     @SWG\Schema(ref="#/definitions/productList")
 *  ),
 *  @SWG\Response(response=200, description="Product Listing"),
 *  @SWG\Response(response="default", description="Product Listing"),
 * )
 */

    public function index() {
 $post = Input::all();
        $result = $this->product->productList($post);
       
       
        if (count($result) > 0) {
            $response = array('success' => 1, 'message' => GET_RECORDS,'records' => $result);
        } else {
           
            $response = array('success' => 0, 'message' => NO_RECORDS,'records' => $result);
        }
        
        return response()->json(["data" => $response]);
    }

/**
* Product Delete controller      
* @access public delete
* @param  array $post
* @return json data
*/
    public function delete()
    {
        $post = Input::all();
      
        if(!empty($post[0]))
        {

            $getData = $this->product->productDelete($post[0]);
            if($getData)
            {
                $message = DELETE_RECORD;
                $success = 1;
            }
            else
            {
                $message = MISSING_PARAMS;
                $success = 0;
            }
        }
        else
        {
            $message = MISSING_PARAMS;
            $success = 0;
        }
        $data = array("success"=>$success,"message"=>$message);
        return response()->json(['data'=>$data]);

    }

/**
* Product Detail controller      
* @access public detail
* @param  array $data
* @return json data
*/
    public function detail() {
 
         $data = Input::all();
         
          $result = $this->product->productDetail($data);


          $result['product'][0]->all_url_photo = UPLOAD_PATH.$data['company_id'].'/product/'.$result["product"][0]->id.'/'.$result['product'][0]->photo;

           if (count($result) > 0) {
            $response = array('success' => 1, 'message' => GET_RECORDS,'records' => $result['product']);
            } else {
                $response = array('success' => 0, 'message' => NO_RECORDS,'records' => $result['product']);
            }
        
        return response()->json(["data" => $response]);

    }

/**
* Making the directory and given path     
* @access public create_dir
* @param  string $dir_path
*/

public function create_dir($dir_path) {

        if (!file_exists($dir_path)) {
            mkdir($dir_path, 0777, true);
        } else {
           exec("chmod $dir_path 0777");
        }
    }

    public function getProductByVendor()
    {
        $post = Input::all();
        $whereData = array();
        
        $whereData['vendor_id'] = $post['vendor_id'];
        if($post['vendor_id'] != '')
        {
            $whereData['search'] = $post['search'];
        }
        $data['where'] = $whereData;
        $data['fields'] = array();
        
        $result = $this->product->getVendorProducts($data);
        
        if (count($result) > 0) {
            $response = array('success' => 1, 'message' => GET_RECORDS,'records' => $result);
        } else {
           
            $response = array('success' => 0, 'message' => NO_RECORDS,'records' => $result);
        }
        
        return response()->json(["data" => $response]);
    }

    public function productDetailData() {
 
        $data = Input::all();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api.ssactivewear.com/v2/products/?style=".$data['product_id']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl,CURLOPT_USERPWD,"13955:d672ddc8-0cd6-4981-95e4-391b2538887e");
        $result = curl_exec($curl);
        curl_close($curl);

       $all_data = json_decode($result);
       
        foreach($all_data as $key => $data) {

           if($key == 0) {
             $productAllData['colorSelection'] = $data->colorName;
           }
           
            $productAllData['colorData'][$data->colorName]['sizes'][$key]['sizeName'] = $data->sizeName;
            $productAllData['colorData'][$data->colorName]['sizes'][$key]['caseQty'] = $data->caseQty;
            $productAllData['colorData'][$data->colorName]['colorSwatchImage'] = $data->colorSwatchImage;
            $productAllData['colorData'][$data->colorName]['colorSwatchTextColor'] = $data->colorSwatchTextColor;
            $productAllData['colorData'][$data->colorName]['sizes'][$key]['customerPrice'] = $data->customerPrice;
            $productAllData['colorData'][$data->colorName]['colorFrontImage'] = $data->colorFrontImage;
            $productAllData['colorData'][$data->colorName]['colorSideImage'] = $data->colorSideImage;
            $productAllData['colorData'][$data->colorName]['colorBackImage'] = $data->colorBackImage;
        }
       
        return response()->json(["data" => $productAllData]);
        

    }
}