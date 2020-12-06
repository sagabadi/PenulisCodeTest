<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
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
        $str = preg_replace('/[^A-Za-z0-9 ]/','',$request->title);
        $str    = preg_replace('/\d+/', '', $str );     
        $str    = preg_replace('/[\s,]+/', '-', $str);
        if ( ctype_alnum($str[strlen($str)-1])){
            $str    = strtolower($str);
        }     
        else{ 
            $str[strlen($str)-1] = ' ';
            $str    = preg_replace('/[\s,]+/', '', $str);
            $str    = strtolower($str);
        }
        
        $id     = DB::table('article')->insertGetId(
            [
                'title'     => $request->title,
                'slug'      => $str,
                'body'      => $request->body,
            ]
        );
        
        for ($i = 0 ; $i < count($request->topic_id);$i++){
            $query  = DB::table('content')->insert(
                [
                    'topic_id'  => $request->topic_id[$i],
                    'article_id'=> $id,
                ]
            ) ;    
        }

        $out    = [
            "status"    => true,
            "message"   => "Article: ".$request->title." successfully created",
            "value"     => [
                "article_id"    => $id,
                "topic_id"      => $request->topic_id,
                "title"         => $request->title,
                "slug"          => $str,
                "body"          => $request->body,
            ]
        ];

        return response()->json($out,200);
    }

    public function update($id, Request $request)
    {
        $query = DB::table('content')->where('article_id', $id)->delete();
        $title = DB::table('article')->where('article_id', $id)->pluck('title');
        $slug  = DB::table('article')->where('article_id', $id)->pluck('slug');
        $body  = DB::table('article')->where('article_id', $id)->pluck('body');

        for ($i = 0 ; $i < count($request->topic_id);$i++){
            $query  = DB::table('content')->insert(
                [
                    'topic_id'  => $request->topic_id[$i],
                    'article_id'=> $id,
                ]
            ) ;    
        }

        $out    = [
            "status"    => true,
            "message"   => "Article: ".$title[0]." successfully updated",
            "value"     => [
                "article_id"    => $id,
                "topic_id"      => $request->topic_id,
                "title"         => $title[0],
                "slug"          => $slug[0],
                "body"          => $body[0],
            ]
        ];
        return response()->json($out,200);
    }

    //
}
