<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCart_itemsRequest;
use App\Http\Resources\Cart_itemsResource;
use App\Models\Cart_items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreCart_itemsRequest;
class CartItemsApiV2Controller extends Controller
{
     /**
     * Display a listing of the resource.
     */

    const cache_key_all = 'cartItem.all' ;  
    
    const cache_key_single = 'cartItem.' ; 
    
    const cache_ttl = 30 ;
    
    public function index(Request $request)
    {
        try {
            $cachekey = 'cartItem_' . md5(json_encode([
                'search' => $request->input('search', ''),
                'quantity' => $request->input('quantity', ''),
                'price' => $request->input('price', ''),
            ]));

            $cart_items = Cache::tags(['CartItems'])->remember(
                $cachekey,
                self::cache_ttl,
                function () use ($request) {
                    $query = Cart_items::query()->with(['Carts', 'products']);

                    if ($request->filled('search')) {
                        $search = $request->search;
                        $query->where(function ($q) use ($search) {
                            $q->where('quantity', 'LIKE', "%{$search}%")
                                ->orWhere('price', 'LIKE', "%{$search}%");
                        });
                    }

                    if ($request->filled('quantity')) {
                        $query->where('quantity', $request->quantity);
                    }

                    if ($request->filled('price')) {
                        $query->where('price', $request->price);
                    }

                    return response()->json([
                        'message' => 'the data is cart items successfully done',
                        'status' => true,
                        'data' => Cart_itemsResource::collection($query->get()),
                    ], 200);
                }
            );
        } catch (\Exception $th) {
            return response()->json([
                'message' => 'failed to get the data of CartItems',
                'status' => 'failed',
                'error' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCart_itemsRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated() ; 
            $data['user_id'] = Auth::id() ; 

            $cart_items = Cart_items::create($data) ; 

            DB::commit();

            $this->ClearCacheCartItems();


            return response()->json([
                'messsage'=> 'the cart_item is created' , 
                'status'=>  'success', 
                'data'=> new Cart_itemsResource($cart_items) 
            ])->setStatusCode(201); 
            
        } catch (\Exception $th) {
            DB::rollBack();

            return response()->json([
                'message'=>'there is error Fatel' , 
                'status'=>'error' ,
                'error'=> $th->getMessage() , 
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCart_itemsRequest $request, string $id)
    {
        try {
            $cart_items = DB::transaction(function () use ($request, $id) {
                $data = $request->validated();

                $cart_items = Cart_items::findOrFail($id);


            if(Auth::id() !== $cart_items->user_id) 
            {
                return response()->json([
                    'message'=>'unathorized' , 
                    'status'=>'error',
                ])->setStatusCode(403); 
            }

                $cart_items->update($data);

                Cache::tags(['CartItems'])->forget('CartItems_' . $id);

                $this->ClearCacheCartItems();

                return $cart_items->fresh();
            });
          



            return response()->json([
                'message'=>'update the data of Cart items',
                'status'=>'success', 
                'data' => new Cart_itemsResource($cart_items),
            ]);

        } catch (\Exception $th) {
            
            return response()->json([
                'message'=>'the error of the update data of Cart items', 
                'status'=>'error' , 
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       
        try {

         $cart_items = Cart_items::findOrFail($id) ; 
            
            if(Auth::id() !== $cart_items->user_id) 
            {
                return response()->json
                ([
                    'message'=>'Unathorized' , 
                    'status'=>'error',
                ])->setStatusCode(403);
            }

            $cart_items->delete();
            $this->ClearCacheCartItems();

            return response()->json([
                'message' => 'Cart item deleted successfully',
                'status' => 'success',
            ]);
        } catch (\Exception $th) {

            Log::error('Error in data: ' . $th->getMessage());


            return response()->json([
                'message'=>'Failed to destory the data of Cart_items' , 
                'status'=>'error', 
                'error' => $th->getMessage(),
            ]);
        }
       
    }

    public function ClearCacheCartItems() 
    {
        Cache::flush(); 
    }

}