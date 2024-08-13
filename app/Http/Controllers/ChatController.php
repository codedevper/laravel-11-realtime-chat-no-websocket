<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Message;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = Message::where('chanel_id', '101')->get();

        return Inertia::render('Chat/Index', [
            'messages' => $messages
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:500'
        ]);

        $user = $request->user();

        $chat = new Message;
        $chat->chanel_id = '101';
        $chat->text = $request['text'];
        $chat->user_id = $user->id;
        $user->messages()->save($chat);

        return back()->with('flash', [
            'title' => 'Chat',
            'text' => 'message created successfully.',
            'success' => true
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $messages = Message::where('chanel_id', '101')->get();

        return $messages;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
