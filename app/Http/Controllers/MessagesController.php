<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use App\Models\Message;
use App\Models\Participant;
use App\Models\Thread;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $threads = Thread::for(Auth::id());
        if (Auth::user()->newThreadsCount() == 0) {
            Auth::user()->message_count = 0;
            Auth::user()->save();
        }
        return view('messages.index', compact('threads', 'currentUserId'));
    }

    public function show($id)
    {
        $thread = Thread::findOrFail($id);
        $participant = $thread->participant();
        $messages = $thread->messages()->recent()->get();

        // counters
        $unread_message_count = $thread->userUnreadMessagesCount(Auth::id());
        if ($unread_message_count > 0) {
            Auth::user()->message_count -= $unread_message_count;
            Auth::user()->save();
        }
        $thread->markAsRead(Auth::id());

        return view('messages.show', compact('thread', 'participant', 'messages'));
    }

    public function create($id)
    {
        $recipient = User::findOrFail($id);

        $thread = Thread::between([$recipient->id, Auth::id()])->first();
        if ($thread) {
            return redirect()->route('messages.show', $thread->id);
        }

        return view('messages.create', compact('recipient'));
    }

    public function store(Request $request)
    {
        $recipient = User::findOrFail($request->recipient_id);

        if ($request->thread_id) {
            $thread = Thread::findOrFail($request->thread_id);
        } else {
            $subject = Auth::user()->name . ' 给 ' . $recipient->name . ' 的私信。';
            $thread = Thread::create(['subject' => $subject]);
        }

        // Message
        Message::create(['thread_id' => $thread->id, 'user_id' => Auth::id(), 'body' => $request->message]);
        // Sender

        // Add replier as a participant
        $participant = Participant::firstOrCreate(['thread_id' => $thread->id, 'user_id' => Auth::id()]);
        $participant->last_read = new Carbon;
        $participant->save();

        // Recipient
        $thread->addParticipant($recipient->id);

        // notifications count
        $recipient->message_count++;
        $recipient->save();

        return redirect()->route('messages.show', $thread->id);
    }
}