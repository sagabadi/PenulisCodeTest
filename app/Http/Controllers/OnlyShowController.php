<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OnlyShowController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function showAllTopics()
    {
        $query  = DB::table('topic')->get();
        $out    = [
            "status" => true,
            "message" => "All topics fetched",
            "value" => $query
        ];
        return response()->json($out,200);
    }

    public function showAllArticle()
    {
        $query = DB::table('article')
        ->rightJoin('content', 'article.article_id', '=', 'content.article_id')
        ->get();
        return response()->json($query,200);
    }

    public function ArticletoTopics($id)
    {
        $query  = DB::table('topic')->where('slug',$id)->get();
        $topicid = DB::table('topic')->where('slug', $id)->pluck('topic_id');
        $topicname = DB::table('topic')->where('slug', $id)->pluck('topic_name');
        $content  = DB::table('article')
        ->join('content', 'article.article_id', '=', 'content.article_id')
        ->join('topic','content.topic_id','=','topic.topic_id')
        ->where('content.topic_id',$topicid[0])->get();
        $out    = [
            "status" => true,
            "message" => "All articles in ".$topicname[0]." fetched",
            "value" => $content
        ];
        return response()->json($out,200);
    }

    public function ArticletoSlug($id)
    {
        $query  = DB::table('article')->where('slug',$id)->get();
        $artid = DB::table('article')->where('slug', $id)->pluck('article_id');
        $artname = DB::table('article')->where('slug', $id)->pluck('title');
        $content  = DB::table('article')
        ->join('content', 'article.article_id', '=', 'content.article_id')
        ->join('topic','content.topic_id','=','topic.topic_id')
        ->where('content.article_id',$artid[0])->get();
        $out    = [
            "status" => true,
            "message" => "Article: ".$artname[0]." successfully fetched",
            "value" => $content
        ];
        return response()->json($out,200);
    }



    //
}
