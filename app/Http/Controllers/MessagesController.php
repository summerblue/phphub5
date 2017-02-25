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
        return view('messages.index', compact('threads', 'currentUserId'));
    }

    public function show($id)
    {
        $thread = Thread::findOrFail($id);
        $thread->markAsRead(Auth::id());
        $participant = $thread->participant();
        $messages = $thread->messages()->recent()->get();
        return view('messages.show', compact('thread', 'participant', 'messages'));
    }

    public function create($id)
    {
        $recipient = User::findOrFail($id);

        // @TODO 如果已经聊过天的话，直接跳转 到 thread 里
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
        Participant::create(['thread_id' => $thread->id, 'user_id' => Auth::id(), 'last_read' => new Carbon]);
        // Recipient
        $thread->addParticipant($recipient->id);

        return redirect()->route('messages.show', $thread->id);
    }

    /**
     * Adds a new message to a current thread.
     *
     * @param $id
     * @return mixed
     */
    public function update($id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');

            return redirect('messages');
        }

        $thread->activateAllParticipants();

        // Message
        Message::create(
            [
                'thread_id' => $thread->id,
                'user_id'   => Auth::id(),
                'body'      => Input::get('message'),
            ]
        );

        // Add replier as a participant
        $participant = Participant::firstOrCreate(
            [
                'thread_id' => $thread->id,
                'user_id'   => Auth::id(),
            ]
        );
        $participant->last_read = new Carbon;
        $participant->save();

        // Recipients
        if (Input::has('recipients')) {
            $thread->addParticipants(Input::get('recipients'));
        }

        return redirect('messages/' . $id);
    }
}