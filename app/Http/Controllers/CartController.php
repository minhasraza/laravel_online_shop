<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
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
                $message = '<strong>'.$product->title.'</strong> added in your cart successfully.';
                session()->flash('success', $message);

            } else {
                $status = false;
                $message = $product->title . ' already added in your cart successfully.';
            }
        } else {
            // cart is empty
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images) ? $product->product_images->first() : '')]);

            $status = true;
            $message = '<strong>'.$product->title.'</strong> added in your cart successfully.';
            session()->flash('success', $message);

        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function cart()
    {
        $cartContent = Cart::content();
        $data['cartContent'] = $cartContent;
        return view('front-end.cart', $data);
    }

    public function updateCart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = Cart::get($rowId);

        $product = Product::find($itemInfo->id);
        // check qty available in stock
        if ($product->track_qty == 'Yes') {
            if ($qty <= $product->qty) {
                Cart::update($rowId, $qty);
                $message = 'Cart updated successfully';
                $status = true;
            } else {
                $message = 'Requested qty(' . $qty . ') not availabe in stock';
                $status = false;
            }
        } else {
            Cart::update($rowId, $qty);
            $message = 'Cart updated successfully';
            $status = true;
        }

        if ($status == true) {
            session()->flash('success', $message);
        } else {
            session()->flash('error', $message);
        }
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function deleteItem(Request $request){
        $itemInfo = Cart::get($request->rowId);
        if ($itemInfo == null) {
            session()->flash('error','Item not found in cart');

            return response()->json([
                'status' => false,
                'message' => 'Item not found in cart'
            ]);
        }
        Cart::remove($request->rowId);
        session()->flash('success','Item removed from cart successfully');
        
        return response()->json([
            'status' => true,
            'message' => 'Item removed from cart successfully'
        ]);
    }
}
