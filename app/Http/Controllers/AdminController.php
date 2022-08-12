<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;
use App\Models\Product;
use App\Models\ProductImages;

class AdminController extends Controller {

    public function loginAdmin(Request $request){
        $formFields = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $user = AdminUser::where('email', $request->email)->first();
        if(!$user || !Hash::check($formFields['password'], $user->password)){
            return response([
                'message' => 'Invalid Credentials'
            ], 401);
        }
        $token = $user->createToken('myapptoken')->plainTextToken;
        $response = [
            'type' => 'success',
            'user' => $user,
            'token' => $token
        ];
        return response($response);
    }

    public function createNewAdminUser(Request $request){
      $formFields = $request->validate([
          'name' => 'required',
          'email' => 'required',
          'password' => 'required',
      ]);

      $profile = AdminUser::create([
          'name' => $formFields['name'],
          'email' => $formFields['email'],
          'password' => bcrypt($formFields['password']),
      ]);

      $response = [
          'type' => 'success'
      ];

      return response ($response);
    }

    public function addProduct(Request $request){
        $formFields = $request->validate([
          'sizes' => 'required',
          'colors' => 'required',
          'fitting' => 'required',
          'fabric' => 'required',
          'fabric_weight' => 'required',
          'wash_type' => 'required',
          'moq' => 'required',
          'price' => 'required',
          'article_no' => 'required',
          'category' => 'required',
          'type' => 'required',
          'length' => 'required',
        ]);
        $profile = Product::create([
          'sizes' => $formFields['sizes'], 
          'colors' => $formFields['colors'], 
          'fitting' => $formFields['fitting'], 
          'fabric' => $formFields['fabric'], 
          'fabric_weight' => $formFields['fabric_weight'], 
          'wash_type' => $formFields['wash_type'],
          'moq' => $formFields['moq'], 
          'price' => $formFields['price'], 
          'article_no' => $formFields['article_no'], 
          'category' => $formFields['category'], 
          'type' => $formFields['type'], 
          'length' => $formFields['length'], 
        ]);
        $response = [
            'type' => 'success'
        ];
        return response ($response);
    }

    public function getAllProducts(){
      $products = Product::with("productImages")->get();
      return response($products);
    }

    public function getProductDetails(Request $request){
      $productDetails = Product::with('productImages')->where('article_no', $request->id)->get();
      return response($productDetails);
    }

    public function searchProducts(Request $request){
      if($request->category != '') {
        $products = Product::with("productImages")->where('category', $request->category)->get();
        if($request->type != '') {
          $products = Product::with("productImages")->where('type', $request->type)->get();
          if($request->length != '') {
            $products = Product::with("productImages")->where('length', $request->length)->get();
          }
          if($request->colors != '') {
            $products = Product::with("productImages")->where('colors', $request->colors)->get();
          }
          if($request->washes != '') {
            $products = Product::with("productImages")->where('washes', $request->washes)->get();
          }
        }
      }
      return response($products);
    }



    public function deleteProduct(Request $request) {
      $delImages = File::deleteDirectory(public_path($request->article_no));
      if($delImages) {
        $findProduct = Product::find($request->article_no);
        $deleteProduct = $findProduct->delete();
        if($deleteProduct) {
          $response = [
            'type' => 'success',
          ];
        }
        return response($response);
      } else {
        return response([
          'type' => 'error'
        ], 400);
      }
      
    }

    public function imageUpload(Request $request) {
      // $article_exist = ProductImages::where('article_no', $request->article_no)->first();
      // if(!$article_exist) {
        if(!$request->hasFile('product_images')) {
          return response('upload_file_not_found', 400);
        }
        if($request->hasFile('product_images')){
          $image = $request->file('product_images');
          foreach($image as $key => $img){
            $key++;
            $name = 'pic-'.$key.'.'.$img->getClientOriginalExtension();
            $destination_path = public_path('/images'.'/'.$request->article_no);
            $db_path = '/images'.'/'.$request->article_no;
            $imgSaved = $img->move($destination_path, $name);
            $saveImg = new ProductImages();
            $saveImg->name = $name;
            $saveImg->path = $db_path;
            $saveImg->article_no = $request->article_no;
            $saveImg->save();
          }
        }
        return response([
          'type' => 'success'
        ]);
      // } else {
      //   return response([
      //     'type' => 'error',
      //     'msg' => 'Article# already exist'
      //   ], 400);
      // }
    }

    public function adminLogout(Request $request){
      $request->user()->tokens()->delete();
      return response ([
        'type' => 'success',
        'message' => 'Logged out'
    ], 200);
    }

    public function getProductListing(Request $request){
      $res = Product::with('ProductImages')
            ->where('category', $request->category)
            ->where('type', $request->type)
            ->limit($request->numberOfRecords)
            ->get();
            
            return response($res);
    }
}
