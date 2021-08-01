<?php

namespace App\Http\Controllers;

use App\Models\Camp;
use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{

    public function campParticipants($id)
    {
        $camp = Camp::with(['participants'])->find($id);
        $participants = $camp->participants;

        $user = auth()->user();
        //show users only to owner camp offer
        if ($user->id !== $camp->user_id || count($participants) === 0)
            $participants = [];

        return response()->json([
            "participants" => $participants,
            "owner" => $user->id === $camp->user_id]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $user = auth()->user();

        $checkExist = Participant::where('camp_id', $data['camp_id'])
            ->where('name', $data['name'])
            ->where('surname', $data['surname'])
            ->first();
        if($checkExist){
            return response()->json([], 409);
        }

        $camp = Camp::find($data['camp_id']);
        //disable removal by another user (other than owner)
        if ($user->id !== $camp->user_id)
            return response()->json([], 405);

        $newParticipant = new Participant($data);
        $newParticipant->save();
        return response()->json([], 201);
    }


    public function destroy($id)
    {
        $user = auth()->user();
        $participant = Participant::with(['camp'])
            ->find($id);
        //disable removal by another user (other than owner)
        if ($user->id !== $participant->camp->user_id)
            return response()->json([], 405);

        $participant->delete();
        return response()->json();
    }
}
