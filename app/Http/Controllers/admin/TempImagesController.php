<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Image;

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

            // Generate Thumbnail
            $sourcePath = public_path().'/temp/'. $newName;
            $destPath = public_path().'/temp/thumb/'. $newName;
            $image = Image::make($sourcePath);
            $image->fit(300,275);
            $image->save($destPath);

            return response()->json([
                'status' => true,
                'image_id' => $tempImg->id,
                'ImagePath' => asset('/temp/thumb/'.$newName),
                'message' => 'Image uploaded successfully'
            ]);
        }
    }
}
