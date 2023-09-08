<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Image;

class CategotyController extends Controller
{
    public function index(Request $request){
        $categories = Category::latest();
        
        if (!empty($request->get('keyword'))) {
            $categories = $categories->where('name', 'like', '%'.($request->get('keyword').'%'));
        }

        $categories = $categories->paginate(10);
        return view('admin.category.list', compact('categories'));
    }

    public function create(){
        return view('admin.category.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:categories'
        ]);

        if ($validator->passes()) {
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            if (!empty($request->image_id)) {
                $tempImg = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImg->name);
                $ext = last($extArray);

                $newImageName = $category->id.'.'.$ext;
                $spath = public_path().'/temp/'.$tempImg->name;
                $dpath = public_path().'/uploads/category/'.$newImageName;
                File::copy($spath,$dpath);

                // Generate thumbnail image
                $dpath = public_path().'/uploads/category/thumb/'.$newImageName;
                $img = Image::make($spath);
                $img->resize(450, 600);
                $img->save($dpath);

                $category->image = $newImageName;
                $category->save();
            }

            $request->session()->flash('success', 'Category added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Category added successfully'
            ]);

        } else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($categoryId, Request $req){
        $category = Category::find($categoryId);
        if (empty($category)) {
            $req->session()->flash('error', 'Category not found');
            // return redirect()->route('categories.index');
        }
        return view('admin.category.edit', compact('category'));
    }

    public function update($categoryId, Request $request){
        $category = Category::find($categoryId);
        if (empty($category)) {
            return response()->json([
                'status'=> false,
                'notFound' => true,
                'message' => 'Category not found'
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$category->id.',id'

        ]);

        if ($validator->passes()) {
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            $oldImage = $category->image;

            if (!empty($request->image_id)) {
                $tempImg = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImg->name);
                $ext = last($extArray);

                $newImageName = $category->id.'-'.time().'.'.$ext;
                $spath = public_path().'/temp/'.$tempImg->name;
                $dpath = public_path().'/uploads/category/'.$newImageName;
                File::copy($spath,$dpath);

                // Generate thumbnail image
                $dpath = public_path().'/uploads/category/thumb/'.$newImageName;
                $img = Image::make($spath);
                $img->resize(450, 600);
                $img->save($dpath);

                $category->image = $newImageName;
                $category->save();
                // Delete old images here
                File::delete(public_path().'/uploads/category/thumb'. $oldImage);
                File::delete(public_path().'/uploads/category/'. $oldImage);
            }

            $request->session()->flash('success', 'Category updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully'
            ]);

        } else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($categoryId, Request $request){
        $category = Category::find($categoryId);
        if (empty($category)) {
            $request->session()->flash('error', 'Category not found');
            return response()->json([
                'status' => true,
                'message' => 'Category not found'
            ]);
        }

        // Delete old images here
        File::delete(public_path().'/uploads/category/thumb'. $category->image);
        File::delete(public_path().'/uploads/category/'. $category->image);

        $category->delete();

        $request->session()->flash('success', 'Category deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Category Delete successfully'
        ]);
    }
}
