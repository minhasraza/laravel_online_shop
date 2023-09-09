<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function addToCart(Request $request){
        $product = Product::with('product_images')->find($request->id);

        if ($product == null) {
            return response()->json([
                'status' => true,
                'message' => 'Product Not Found'
            ]);
        }

        if (Cart::count() > 0) {
            // echo 'Product already in cart';
            // Products found in cart
            // Check if products already in the cart
            // Return a message that product already added in your cart
            // If product not found in the cart, then add in product cart

            $cartContent = Cart::content();
            $productAlreadyExist = false;

            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExist = true;
                }
            }

            if ($productAlreadyExist == false) {
                Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images) ? $product->product_images->first() : '')]);    
                $status = true;
                $message = $product->title.' added in cart';

            }else{
                $status = false;
                $message = $product->title.' already added in cart';
            }
        }else{
            // cart is empty
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images) ? $product->product_images->first() : '')]);
            $status = true;
            $message = $product->title.' added in cart';
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function cart(){
        $cartContent = Cart::content();
        $data['cartContent'] = $cartContent;
        return view('front-end.cart',$data);
    }
}
