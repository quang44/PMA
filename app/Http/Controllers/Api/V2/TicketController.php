<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\V2\TicketCollection;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(){
        $tickets = Ticket::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(10);
        return new TicketCollection($tickets);
    }

    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->client_viewed = 1;
        $ticket->save();
        $ticket_replies = $ticket->ticketreplies;
        return response([
            'result' => true,
            'data' => $ticket
        ]);
    }

    public function store(Request $request)
    {
        //dd();
        $ticket = new Ticket;
        $ticket->code = max(100000, (Ticket::latest()->first() != null ? Ticket::latest()->first()->code + 1 : 0)).date('s');
        $ticket->user_id = auth()->user()->id;
        $ticket->subject = $request->subject;
        $ticket->details = $request->details;
        $ticket->save();
        return response([
            'result' => true
        ]);
    }
}
