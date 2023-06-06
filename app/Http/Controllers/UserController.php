<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    function update(User $user, Request $request){
        $data = $request->all();
        if ($request->isMethod('post') && $request->file('file_upload')) {
            $file = $request->file('file_upload');
            $upload_folder = 'public/user_data/';
            $filename = date('dmyhi').$file->getClientOriginalName(); // image.jpg
            $path = Storage::putFileAs($upload_folder, $file, $filename);
            $data["photo_path"] = $filename;
            unset($data["file_upload"]);
        }
        $newuser = $user->update($data);
        return redirect('home');
    }

    function show(User $user, Request $request){
        return view('user.show', compact('user'));
    }

    function destroy(User $user){
        if(Auth::user()->group == "admin" && $user->id!=1 ){
            $user->delete();
        }
        return redirect('home');
    }
}
