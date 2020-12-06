<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopicController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware("login");
        //
    }

    public function create(Request $request)
    {
        $str    = preg_replace('/[\s,]+/', '-', $request->topic_name);
        $str    = strtolower($str);
        $id     = DB::table('topic')->insertGetId(
            [
                'topic_name' => $request->topic_name,
                'slug' => $str
            ]
        );

        $out    = [
            "status"    => true,
            "message"   => "Topic: ".$request->topic_name." successfully created ",
            "value"     => [
                "topic_id"      => $id,
                "topic_name"    => $request->topic_name
            ]
        ]; 


        return response()->json($out,200);
    }

    public function update($id, Request $request)
    {
        $topicname  = DB::table('topic')->where('topic_id', $id)->pluck('topic_name');
        $str    = preg_replace('/[\s,]+/', '-', $request->topic_name);
        $str    = strtolower($str);
        $query  = DB::table('topic')
              ->where('topic_id', $id)
              ->update([
                    'topic_name' => $request->topic_name,
                    'slug' =>$str
                  ]);

        $out    = [
            "status"    => true,
            "message"   => "Topic: ".$topicname[0]." updated to ".$request->topic_name,
            "value"     => [
                "topic_id"      => $id,
                "topic_name"    => $request->topic_name
            ]
        ]; 

        return response()->json($out,200);
    }

    //
}
