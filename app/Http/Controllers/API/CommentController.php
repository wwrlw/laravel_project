<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Gate;
use App\Models\Article;
use App\Jobs\VeryLongJob;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::where('accept', null)->latest()->paginate(10);
        return response(['comments'=>$comments]);
    }

    public function accept(Comment $comment){
        $comment->accept = 1;
        Cache::forget("article/show/".$comment->article_id);
        $comment->save();
        return response()->back();
    }

    public function reject(Comment $comment){
        $comment->accept = 0;
        $comment->save();
        return response()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'text' => 'required',
        ]);
        $comment = new Comment();
        $comment->title = request('title');
        $comment->text = request('text');
        $comment->article()->associate(request('id'));
        $comment->user()->associate(auth()->user());
        $result = $comment->save();
        $article = Article::where('id', $comment->article_id)->first();
        if ($request){
            VeryLongJob::dispatch($article, $comment);
        }
        return response($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $comment = Comment::FindOrFail($id);
        Gate::authorize('update-comment', $comment);
        return response(['comment' => $comment]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'text' => 'required',
        ]);
        $comment = Comment::FindOrFail($id);
        Gate::authorize('update-comment', $comment);
        $comment->title = request('title');
        $comment->text = request('text');
        $comment->save();
        return response($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::FindOrFail($id);
        Gate::authorize('update-comment', $comment);
        $comment->delete();
        Cache::forget("article/show/".$comment->article_id);
        return response()->route('show', ['id' => $comment->article_id]);
    }
}
