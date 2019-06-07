<?php

namespace App\Http\Controllers;

use App\User;
use App\Profile;

use Illuminate\Http\Request;
USE Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }
    
    public function index()
    {
        $user = User::with('profile')->get();
        return $user;
    }

  
    public function store(Request $request){

        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|digits:11'  
        ];
        
        try{
        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails()){
            return [
                'created' => false,
                'errors'  => $validator->errors()->all()
            ];
        }
            
        DB::transaction(function () use ($request) {
                $user = User::create([
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'password' => bcrypt(123456)
                ]);
                $user->profile()->create([
                     'email' => $request['email'],
                     'phone' => $request['phone']
                ]);
            });
        
            return ['created' => true];
        
        }catch(Exception $e){
            \Log::info('Error creating user: '.$e);
            return \Response::json(['created' => false], 500);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        //
    }
}
