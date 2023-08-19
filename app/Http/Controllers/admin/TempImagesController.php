<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;

class TempImagesController extends Controller
{
    public function create(Request $request){
        $image = $request->image;

        if (!empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $newName = time(). '.'.$ext;

            $tempImg = new TempImage();
            $tempImg->name = $newName;
            $tempImg->save();

            $image->move(public_path().'/temp', $newName);

            return response()->json([
                'status' => true,
                'image_id' => $tempImg->id,
                'message' => 'Image uploaded successfully'
            ]);
        }
    }
}
