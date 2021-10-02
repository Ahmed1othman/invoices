<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $sections = Section::all();
            $products = Product::with('section')->get();


            return view('pages.settings.products.index',compact(['products','sections']));
        }catch (\Exception $ex){
            return $ex;
            return view('404');
        }
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
    public function store(ProductRequest $request)
    {
        try {
            DB::beginTransaction();
            $product = $request->only(['product_name','description','section_id']);
            $state = Product::create($product);
            if($state){
                DB::commit();
                return redirect()->route('products.index')->with('Add',__(('messages.Product has been added successfully')));
            }

            return redirect()->route('products.index')->with('Error',__(('messages.Product has not been added')));
        }catch (\Exception $ex){
            DB::rollBack();
            return redirect()->route('products.index')->with('Error',__(('messages.An error occurred, please try again later')));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request)
    {
        try {
            DB::beginTransaction();
            $product = Product::find($request->id);
            if (!(isset($product) && $product->count() > 0)) {
                    return redirect()->route('products.index')->with('Error',__(('messages.Product has not been updated')));
            }else{
                $state = $product->update($request->only(['product_name','description','section_id']));
                if($state){
                    DB::commit();
                    return redirect()->route('products.index')->with('Edit',__(('messages.Product has been updated successfully')));
                }
                DB::rollBack();
                return redirect()->route('products.index')->with('Error',__(('messages.This Product not found')));
            }

        }catch (\Exception $ex){
            DB::rollBack();
            return redirect()->route('sections.index')->with('Error',__(('messages.An error occurred, please try again later')));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductRequest $request)
    {
        try {
            $product = Product::find($request->id);

            if(!$product)
                return redirect()->route('products.index')->with('Error',__(('messages.This Product not found')));

            $state = $product->delete();
            if(!$state)
                return redirect()->route('products.index')->with('Error',__(('messages.Product has not been deleted')));
            return redirect()->route('products.index')->with('Delete',__(('messages.Section has been deleted successfully')));

        }catch (\Exception $ex){
            return redirect()->route('products.index')->with('Error',__(('messages.An error occurred, please try again later')));
        }
    }
}
