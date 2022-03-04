<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Models\Category;
use App\Models\Product;
use CreateProductsTable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class ProductController extends Controller
{


    public function __construct()
    {
        $this->middleware('is_admin')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $inputs= $request->all();
        $user = auth()->user()->role;
        $products = Product::with('categories');
        if (isset($inputs['categories'])){
            $products = $products->whereHas('categories',function($query)use ($inputs){
                $query->whereIn('categories.id',$inputs['categories']);
            });
        }
        if (isset($inputs['search'])){
            $products = $products->where(function ($query)use ($inputs) {
                $query->where('name',$inputs['search'])
                      ->orWhere('description',$inputs['search']);
            });
        }
        $take = 3;
        $page = $inputs['page'] ?? 0;
        $products = $products->take($take)->skip($page * $take);
        $products = $products->get();
        if (isset($inputs['sortBy'])){
            $products = $products->sortBy('price',SORT_REGULAR,boolval($inputs['sortBy']));
        }
        $categories = Category::all();
        $allProducts = '';
        if ($request->ajax()) {

            foreach ($products as $result) {
                $allProducts.='<div class="card mb-2"> 
                <div class="card-body">'.$result->id.' <h5 class="card-title">'.$result->name.'</h5> '.$result->description.
                '<br>Price : '.$result->price.'<br>
                <br>Categories : <br>';
                if (isset($result->categories)){
                    foreach($result->categories as $cat){
                        $allProducts.= $cat->name.' ';
                    }
                    $allProducts.= '<br>';
                }
                
                if (isset($role) && $role->name == 'Admin'){
                    $allProducts.= '<form action="products/'.$result->id.'" method="POST">
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" class="btn btn-danger btn-sm" value="Delete">
                    </form>
                    <a href="products/'.$result->id.'/edit" class="btn btn-primary btn-sm active" role="button" aria-pressed="true">Edit</a>';
                    
                }

                
                $allProducts.= '</div></div>';
            }
            return $allProducts;
        }
        return view('products.index',compact('categories'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return View::make('products.create',compact('categories'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        $inputs = $request->all();
        try{
            $product = Product::create($inputs);
            if (isset($inputs['categories'])){
                $product->categories()->attach($inputs['categories']);
            }
            return response()->json(['success' => 'Product Created Successfully !!!'],200);
        }
        catch(Exception $ex){
            Log::error($ex);
            return response()->json(['all-error' => 'Oops! Something went wrong'],422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $product->load('categories');
        return View::make('products.create',compact('categories','product'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateProductRequest $request, Product $product)
    {
        $inputs = $request->all();
        try{
            $product->update($inputs);
            if (isset($inputs['categories'])){
                $product->categories()->detach();
                $product->categories()->attach($inputs['categories']);
            }
            return response()->json(['success' => 'Product Updated Successfully !!!'],200);
        }
        catch(Exception $ex){
            Log::error($ex);
            return response()->json(['all-error' => 'Oops! Something went wrong'],422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if ($product){
            $product->categories()->detach();
            $product->delete();
        }
        return redirect()->back()->with('success', 'Product Deleted Successfully!!!');   
    }
}
