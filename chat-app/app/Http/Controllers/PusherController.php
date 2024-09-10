<?php

namespace App\Http\Controllers;

use App\Events\PusherBroadcast;
use Illuminate\Http\Request;

class PusherController extends Controller
{
public function index()
{
    
    return view("index");
}

public function broadcasts(Request $request)
{
    broadcast(new PusherBroadcast($request->get('message')))->toOthers();

    return view('broadcast', ['message'=> $request->get('message')]);
}

public function receivee(Request $request)
{
    return view('receive', ['message'=> $request->get('message')]);
}


    // Send message (Broadcast)
    public function broadcast(Request $request)
    {
        // Broadcast the message to Pusher
        broadcast(new PusherBroadcast($request->message))->toOthers();

        // Return a JSON response
        return response()->json(['message' => 'Message broadcasted successfully']);
    }

    // Receive message (Handled by Pusher in real-time, not through this route typically)
    public function receive(Request $request)
    {
        // You can process or log the message here if needed
        return response()->json(['message' => $request->message]);
    }

}