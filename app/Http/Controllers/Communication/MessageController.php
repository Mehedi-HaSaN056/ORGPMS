<?php
namespace App\Http\Controllers\Communication;
use App\Http\Controllers\Controller;
use App\Models\{Message,User,Department};
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function inbox()
    {
        $user = auth()->user();
        $messages = Message::where('receiver_id',$user->id)
            ->orWhere(function($q) use($user) {
                $q->where('type','department')->where('department_id',$user->department_id);
            })
            ->orWhere('type','broadcast')
            ->with(['sender'])
            ->latest()
            ->paginate(20);
        $unreadCount = Message::where('receiver_id',$user->id)->unread()->count();
        return view('communication.inbox', compact('messages','unreadCount'));
    }

    public function compose()
    {
        $user = auth()->user();
        $recipients = User::active()->where('id','!=',$user->id)->get();
        $departments = Department::active()->get();
        return view('communication.compose', compact('recipients','departments'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'receiver_id'  => 'nullable|exists:users,id',
            'department_id'=> 'nullable|exists:departments,id',
            'subject'      => 'nullable|string|max:255',
            'body'         => 'required|string',
            'type'         => 'required|in:direct,department,broadcast,announcement',
            'priority'     => 'required|in:normal,high,urgent',
            'parent_id'    => 'nullable|exists:messages,id',
            'attachment'   => 'nullable|file|max:10240',
        ]);
        $validated['sender_id'] = auth()->id();

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments/messages','public');
            $validated['attachment'] = $path;
        }

        Message::create($validated);
        return redirect()->route('messages.inbox')->with('success','Message sent successfully!');
    }

    public function show(Message $message)
    {
        // Mark as read
        if ($message->receiver_id === auth()->id() && !$message->is_read) {
            $message->update(['is_read'=>true,'read_at'=>now()]);
        }
        $message->load(['sender','receiver','replies.sender']);
        return view('communication.show', compact('message'));
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return redirect()->route('messages.inbox')->with('success','Message deleted.');
    }

    public function unreadCount()
    {
        return response()->json(['count' => Message::where('receiver_id',auth()->id())->unread()->count()]);
    }

    public function broadcast(Request $request)
    {
        $this->authorize('broadcast', Message::class);
        $request->validate(['subject'=>'required|string','body'=>'required|string','priority'=>'required|in:normal,high,urgent']);
        Message::create([
            'sender_id' => auth()->id(),
            'subject'   => $request->subject,
            'body'      => $request->body,
            'type'      => 'broadcast',
            'priority'  => $request->priority,
        ]);
        return back()->with('success','Broadcast message sent to all users.');
    }
}
