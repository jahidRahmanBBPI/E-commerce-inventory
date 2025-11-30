<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class UserController extends Controller
{
    function edit_profile(){
        return view('backend.user.edit_profile');
    }
    function update_profile(Request $request){
        if($request->photo){
            $request->validate([
                'photo' => 'mimes:jpg,jpeg,png,webp|max:1024',
            ]);

            if(Auth::user()->photo != null){
                $delete_form = public_path('uploads/users/'.Auth::user()->photo);
                unlink($delete_form);
            }

            $extension = $request->photo->extension();
            $file_name = uniqid(). '.' . $extension;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->photo);
            $image->scale(width: 300);
            $image->save(public_path('uploads/users/'.$file_name));

            User::find(Auth::id())->update([
                'name'=>$request->name,
                'photo'=>$file_name,
            ]);
            return back()->with('success', 'Profile Updated');

            // dd($request->photo);


        }else{
            User::find(Auth::id())->update([
                'name'=>$request->name,
            ]);
            return back()->with('success', 'Profile Updated');
        }
    }
}
