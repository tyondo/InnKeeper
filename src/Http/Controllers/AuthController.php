<?php

namespace Tyondo\Innkeeper\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tyondo\Innkeeper\Infrastructure\Services\ActivityLoggerService;

class AuthController extends Controller
{
    public function loginForm(Request $request){
        if (\auth()->check()){
            return redirect()->route('innkeeper.dashboard');
        }
        return view('innkeeper::layouts.auth');
    }
    public function login(Request $request){
        //return $request->all();

        ActivityLoggerService::addToLog("Login");

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()){
            if ($request->ajax()){
                return response()->json($validator->errors(),422);
            }

            if ($request->expectsJson()){
                return response()->json([
                    'success' => false,
                    'message' => 'Fail. Both username and password required.',
                    'data' => $validator->errors()
                ],422);
            }

            return redirect()->back()->withErrors($validator->errors());
        }else {
            if (Auth::attempt($request->only(["email", "password"]))) {
                if ($request->ajax()){
                    return response()->json(["status"=>true,"redirect_location"=> route("innkeeper.dashboard")]);
                }
                if ($request->expectsJson()){
                    $user = auth()->user();
                    return response()->json([
                        'success' => true,
                        'message' => 'Authenticated Successfully.',
                        'data' => $user
                    ],422);
                }
                return redirect()->route('innkeeper.dashboard');
            } else {
                if ($request->ajax()){
                    return response()->json([["Invalid credentials"]],401);
                }
                return redirect()->route('innkeeper.auth.login')->withErrors(['message' => "Invalid credentials"]);
            }
        }
    }
    public function logoutUser(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('front.index');
    }

}
