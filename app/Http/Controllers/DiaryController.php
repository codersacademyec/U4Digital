<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\PostType;
use Validator;
use File;
use Illuminate\Support\Facades\Storage;

class DiaryController extends Controller
{
    //
    public function index()
    {
        //$products = Product::all();
        $companies = $this->getCompanies();
        $postTypes = $this->getPostTypes();
        return view('diary.index', ['companies'=>$companies, 'postTypes' => $postTypes]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'title' => 'required|max:255',
            'start' => 'required|date',
            'end' => 'required|date',
            'typePost' => 'required'
        ]);

        $validator->sometimes('text', 'required|max:500', function ($input) {
            return $input->typePost == 1;
        });

        $validator->sometimes('file', 'required|file|max:10240', function ($input) {
            return $input->typePost > 1;
        });

        if($validator->fails()) {
            return response()->json($validator->messages()->first(), 422);
        }

        $calendarEvent = new CalendarEvent();
        $calendarEvent->company_id = $request->input('company_id');
        $calendarEvent->title = $request->input('title');
        $calendarEvent->start = $request->input('start');
        $calendarEvent->end = $request->input('end');
        $calendarEvent->post_types_id = $request->input('typePost');
        $calendarEvent->save();

        if($request->has('text')) {
            $calendarEvent->text_post = $request->input('text');
        }

        if($request->has('file')) {
            $calendarEvent->path_media = $this->saveImageProfile($request->file('file'), $calendarEvent->id);
        }

        $calendarEvent->save();

        return $calendarEvent;
    }

    public function getEventsByCompany($id)
    {
        $calendarEvents = CalendarEvent::where('company_id','=',$id)->get();

        foreach ($calendarEvents as $event) {
            $event->typePost;
        }

        return response()->json($calendarEvents);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'title' => 'required|max:255',
            'start' => 'required|date',
            'end' => 'required|date',
            'typePost' => 'required'
        ]);

        $validator->sometimes('text', 'required|max:500', function ($input) {
            return $input->typePost == 1;
        });

        $validator->sometimes('file', 'required|file|max:10240', function ($input) {
            return $input->typePost > 1;
        });

        if($validator->fails()) {
            return response()->json($validator->messages()->first(), 422);
        }

        $calendarEvent = CalendarEvent::find($id);
        $calendarEvent->company_id = $request->input('company_id');
        $calendarEvent->title = $request->input('title');
        $calendarEvent->start = $request->input('start');
        $calendarEvent->end = $request->input('end');
        $calendarEvent->post_types_id = $request->input('typePost');
        $calendarEvent->save();

        if($request->has('text')) {
            $calendarEvent->text_post = $request->input('text');
        }

        if($request->has('file')) {
            $calendarEvent->path_media = $this->saveImageProfile($request->file('file'), $calendarEvent->id);
        }

        $calendarEvent->save();
        
        return $calendarEvent;
    }

    public function getFileMedia($id) {
        //return storage_path().'/app/events/'.$id;

        $imagePath = '/events/'.$id;
        $path = storage_path().'/app/events/'.$id;

        if (file_exists($path)) {
            $type = File::mimeType($path);

            return response(Storage::get($imagePath), 200, ['Content-Type' => $type]);
        }

        return response()->json(['Status Code' => '404 Not Found'], 404);

        //return $id;
    }

    private function getPostTypes()
    {
        return PostType::all();
    }

    private function getCompanies()
    {
        $id = auth()->user()->id;
        $companies = [];
        if(auth()->user()->hasRole('community_manager')) {
            $communityCompanies = CompanyUser::where('user_id','=',$id)->get();
            foreach ($communityCompanies as $communityCompany) {
                $company = Company::where('id','=',$communityCompany->company_id)->first();
                array_push($companies,$company);
            }
        }
        else if(auth()->user()->hasRole('company_admin') || auth()->user()->hasRole('company_user')) {
            $companyAdmin = CompanyUser::where('user_id','=',$id)->first();
            $company = Company::where('id','=',$companyAdmin->company_id)->first();
            array_push($companies,$company);
        }
        else {
            $companies = Company::all();
        }
        return $companies;
    }

    private function saveImageProfile($fileRequest, $eventId)
    {
        $extension = $fileRequest->extension();
        $nameFile = $eventId.".$extension";
        $fullPath = '/app/events/'.$nameFile;

        if(Storage::exists(storage_path().$fullPath)) {
            Storage::delete(storage_path().$fullPath);
        }

        return $fileRequest->storeAs('events',$nameFile);
    }
}

