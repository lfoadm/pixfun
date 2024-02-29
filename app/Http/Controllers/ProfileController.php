<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        return view('pages.users.user-profile');
    }


    public function create()
    {
        return view('pages.profile');
    }

    public function update()
    {
        $user = request()->user();
        $attributes = request()->validate([
            'email' => 'required|email|unique:users,email,'.$user->id,
            'name' => 'required',
            'phone' => 'required|max:10',
            'about' => 'required:max:150',
            'location' => 'required',
            'surname' => 'nullable|min:2|max:191|string',
            'image' => 'image'
        ]);

        if(request()->has('image')) {
            $imagePath = request()->file('image')->store('profile', 'public');
            $attributes['image'] = $imagePath;

            if($user->image) {
                Storage::disk('public')->delete(auth()->user()->image);
            }
        }

        auth()->user()->update($attributes);
        return back()->withStatus('Perfil atualizado com sucesso.');
    
}
}
