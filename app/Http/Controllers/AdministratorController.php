<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdministratorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $administrators = User::where('role', 'administrator')->get();
        return view('administrators.index', compact('administrators'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administrators.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'security_pin' => 'nullable|string|size:6|regex:/^[0-9]+$/',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $name = $request->first_name . ($request->last_name ? ' ' . $request->last_name : '');

        $data = [
            'name' => $name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'administrator',
            'security_pin' => $request->security_pin ? Hash::make($request->security_pin) : null,
        ];

        if ($request->hasFile('photo')) {
            $imageName = time().'.'.$request->photo->extension();
            $request->photo->move(public_path('photos'), $imageName);
            $data['photo'] = $imageName;
        }

        User::create($data);

        return redirect()->route('administrators.index')
            ->with('success', 'Administrator created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $administrator)
    {
        if ($administrator->role !== 'administrator') {
            abort(404);
        }
        return view('administrators.edit', compact('administrator'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $administrator)
    {
        if ($administrator->role !== 'administrator') {
            abort(404);
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($administrator->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($administrator->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'security_pin' => 'nullable|string|size:6|regex:/^[0-9]+$/',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $name = $request->first_name . ($request->last_name ? ' ' . $request->last_name : '');
        $administrator->name = $name;
        $administrator->username = $request->username;
        $administrator->email = $request->email;
        
        if ($request->filled('password')) {
            $administrator->password = Hash::make($request->password);
        }

        if ($request->filled('security_pin')) {
            $administrator->security_pin = Hash::make($request->security_pin);
        }

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($administrator->photo && file_exists(public_path('photos/' . $administrator->photo))) {
                unlink(public_path('photos/' . $administrator->photo));
            }

            $imageName = time().'.'.$request->photo->extension();
            $request->photo->move(public_path('photos'), $imageName);
            $administrator->photo = $imageName;
        }

        $administrator->save();

        return redirect()->route('administrators.index')
            ->with('success', 'Administrator updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $administrator)
    {
        if ($administrator->role !== 'administrator') {
            abort(404);
        }

        if ($administrator->photo && file_exists(public_path('photos/' . $administrator->photo))) {
            unlink(public_path('photos/' . $administrator->photo));
        }

        $administrator->delete();

        return redirect()->route('administrators.index')
            ->with('success', 'Administrator deleted successfully');
    }
}
