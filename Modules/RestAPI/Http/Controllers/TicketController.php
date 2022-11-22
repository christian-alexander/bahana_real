<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiResponse;
use Modules\RestAPI\Entities\Ticket;
use Modules\RestAPI\Http\Requests\Tickets\IndexRequest;
use Modules\RestAPI\Http\Requests\Tickets\CreateRequest;
use Modules\RestAPI\Http\Requests\Tickets\UpdateRequest;
use Modules\RestAPI\Http\Requests\Tickets\ShowRequest;
use Modules\RestAPI\Http\Requests\Tickets\DeleteRequest;
use App\TicketType;
use App\TicketReply;
use App\TicketFile;
use App\Http\Requests\API\APIRequest;
use App\Helper\Files;

use App\TicketAgentGroups;
class TicketController extends ApiBaseController
{
    protected $model = Ticket::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function modifyIndex($query)
    {
        return $query->visibility();
    }

    public function stored(Ticket $ticket)
    {	/*
        $user = \Auth::user();
        $member = new ProjectMember;
        $member->user_id = $user->id;
        $member->project_id = $project->id;
        $member->save();
        */
    }

    public function getTicketType()
    {
        $user = auth()->user();
        $type =  TicketType::where('company_id', $user->company_id)->get();
        return ApiResponse::make('List ticket type', [
                    'type' => $type
        ]);
    }

    public function postTicketReply(APIRequest $request)
    {
        $user = auth()->user();
      	$ticket = Ticket::find($request->ticket_id);
      
      	$ticketReply = new TicketReply();
      	$ticketReply->ticket_id	= $ticket->id;
      	$ticketReply->user_id = $user->id;
      	$ticketReply->message = $request->message;
      	$ticketReply->save();    
         
      	$ticket->status = $request->status_ticket;
      	$ticket->save();
      
        $upload_file = [];	
        for($i=0;$i<30;$i++){
            $name = "files".$i;
            if(isset($request->$name) && !empty($request->$name)){
              $upload_file[] = $request->$name;
            }  
        }
        foreach($upload_file as $fileData){
            $filename = Files::upload($fileData,'ticket-files/'.$ticketReply->id);
            $ticketFile = new TicketFile();
            $ticketFile->user_id = $user->id;
            $ticketFile->ticket_reply_id = $ticketReply->id;
            $ticketFile->filename = $fileData->getClientOriginalName();
            $ticketFile->hashname = $filename;
            $ticketFile->size = $fileData->getSize();
            $ticketFile->save();
        }
      /*
        if(isset($request->file) && !empty($request->file)){
          $filename = Files::upload($request->file,'ticket-files/'.$ticketReply->id);
          $ticketFile = new TicketFile();
          $ticketFile->user_id = $user->id;
          $ticketFile->ticket_reply_id = $ticketReply->id;
          $ticketFile->filename = $request->file->getClientOriginalName();
          $ticketFile->hashname = $filename;
          $ticketFile->size = $request->file->getSize();
          $ticketFile->save();
        }
      */
        return ApiResponse::make('Reply saved', [
            'ticketReply' => $ticketReply
        ]);
    }
    public function deleteTicketReply(APIRequest $request)
    {
        $user = auth()->user();
      	$ticketReply = TicketReply::find($request->ticket_reply_id);
      	$ticketReply->delete();    
         
        return ApiResponse::make('Reply deleted', [
        ]);
    }
  
  
    public function getTicketAgent()
    {
        $agents = TicketAgentGroups::all();
      	$arr = [];
      	foreach($agents as $agent){
          $arr[$agent->agent_id] = $agent->group->group_name." - ".$agent->user->name;
        }
        return ApiResponse::make('List ticket type', [
                    'agents' => $arr,
        ]);
    }
  
  
}
