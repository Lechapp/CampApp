<?php

namespace App\Http\Controllers;

use App\Models\Camp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampController extends Controller
{

    public function showAllCamps(){
        return view('welcome');
    }

    public function showUserCamps(){
        return view('home');
    }

    public function showCamp(){
        return view('show_camp');
    }

    public function create(){
        return view('create_camp');
    }

    public function index(){
        $camp = Camp::orderBy('id', 'DESC')
                ->get();
        return response()->json($camp);
    }

    public function myCamps(){
        $user = auth()->user();
        $camps = Camp::where('user_id', $user->id)
            ->get();
        return response()->json($camps);
    }


    public function store(Request $request){
        $user = auth()->user();
        $newCamp = new Camp($request->all());
        $user->camps()->save($newCamp);

        return response()->json([],201);
    }


    public function show($id){
        $camp = Camp::find($id);
        return response()->json($camp);
    }


    public function destroy($id){
        $camp = Camp::find($id);
        $user = auth()->user();

        //disable removal by another user (other than owner)
        if($user->id !== $camp->user_id)
            return response()->json([], 405);

        $camp->participants()->delete();
        $camp->delete();
        return response()->json();
    }
}
