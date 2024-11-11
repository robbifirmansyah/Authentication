<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        if(!Auth::check()) {
            return redirect()->route('login')
            ->withErrors(['email' => 'Silakan login terlebih dahulu.',])->onlyInput('email');
        }
        $users = User::get();
        return view('users')->with('userss', $users);
    }
    public function destroy(string $id)
    {
        $user = User::find($id);
        $file = public_path().'/storage/photos/'.$user->photo;
        try {
            if (File::exists($file)) {
                File::delete($file);
                $user->delete();
            }
        } catch (\Exception $th) {
            return redirect('users')->with(['error' => 'Gagal menghapus data.']);
        }
        return redirect('users')->with(['success' => 'Data berhasil dihapus.']);
    }
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('edit')->with('user', $user);
    }
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250',
            'password' => 'required|min:8|confirmed',
            'photo' => 'image|nullable'
        ]);

        if ($request->hasFile('photo')) {
            $filenameWithExt = $request->file('photo')->getClientOriginalName();    
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);  
            $extension = $request->file('photo')->getClientOriginalExtension(); 
            $fileNameToStore = $filename.'_'.time().'.'.$extension; 
            $path = $request->file('photo')->storeAs('photos', $fileNameToStore);    
            } else {$path = null;
        }

        $user = User::find($id);
        if ($path !== null) {
            $file = public_path().'/storage/photos/'.$user->photo;
            if (File::exists($file)) {
                File::delete($file);
            }
        } else {
            $path = $user->photo;
        }
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'photo' => $path
        ]);

        return redirect()->route('users.index')
            ->withSuccess('You have successfully edited the user!');
    }
}


