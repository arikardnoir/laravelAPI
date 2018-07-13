<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;


class ProductController extends Controller
{
    private $product;
    private $totalPage=3;
    
    //Construct for initialize variable product
    public function __construct(Product $product)
    {
        $this->product = $product;
    }
    
    public function index(Request $request)
    {
        //$products = $this->product->getResults($request->all(), $this->totalPage);
          $products = $this->product->all();

        return response()->json([$products,'status' => true], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Get all datas coming from Request
        $data = $request->all();
        $status = false;
        
        //Validates a data come of table products
        $validate = validator($data, $this->product->rules());
        if( $validate->fails() ){
            $messages = $validate->messages();
            return response()->json(['validate.error', $messages, 'status' => $status], 422);
        }
        
        //Verify if datas already exists in table products
        if( !$insert =  $this->product->create($data))
            return response()->json(['error' => 'error_insert', 'status' => $status], 500);
         

        $status = true;    
        //Returning true or false from insert    
        return response()->json(['data' => $insert, 'status' => $status], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        //Verify if the product was finded
        if( !$product = $this->product->find($id) ){
            return response()->json(['error' => 'product_not_found'], 404);
        }
        
        //Returning especific data product you required by ID from table product
        return response()->json(['data' => $product], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        //Catch dates in table products
        $data = $request->all();
        
        //Validates a data come of table products
        $validate = validator($data, $this->product->rules($id));
        if( $validate->fails() ){
            $messages = $validate->messages();
            
            return response()->json(['validate.error', $messages], 422);
        }
        
        //Verify if the product was finded
        if( !$product = $this->product->find($id) ){
            return response()->json(['error' => 'product_not_found'], 404);
        }
        
        //Verify if data in table product already was updated
        if(!$update = $product->update($data)){
            return response()->json(['error' => 'product_not_update'], 500); 
        }
        
        //Returning true or false from update
        return response()->json(['response' => $update]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Verify if the product was finded
        if( !$product = $this->product->find($id) ){
            return response()->json(['error' => 'product_not_found'], 404);
        }
        
        //Verify if product already was not deleted
        if(!$delete = $product->delete($id)){
            return response()->json(['error' => 'product_not_delete'], 500); 
        }
        
        //Returning true or false from delete
        return response()->json(['response' => $delete]);
    }
    
    
    public function search(Request $request){
        
        $data = $request->all();
        
         //Validates a data come of table products
         $validate = validator($data, $this->product->rulesSearch());
         if( $validate->fails() ){
             $messages = $validate->messages();
             return response()->json(['validate.error', $messages], 422);
             
         }
         
         $products = $this->product->search($data,$this->totalPage);
        
        
        return response()->json(['data' => $products]);
    }
    
}