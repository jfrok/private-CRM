<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Wiki;

class WikiController extends Controller
{
    public function show($id)
    {
        $post = Wiki::findOrFail($id);
        $wiki = DB::table('wiki')
            ->select('*')
            ->orderBy('id', 'desc')
            ->get();

        $comments = DB::table('wiki_comments')
            ->where('post_id', '=', $id)
            ->orderBy('id', 'desc')
            ->get();

        return view('wiki.show', [
            'post' => $post,
            'wiki' => $wiki,
            'comments' => $comments,
        ]);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'titel' => 'required',
            'body' => 'required',
        ]);

        Wiki::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->name,
            'titel' => $request->titel,
            'body' => $request->body,
        ]);

        return redirect('/wiki');
    }

    public function submitComment(Request $request)
    {
        $this->validate($request, [
            'body' => 'required',
            'hidden_id' => 'required',
        ]);

        Comment::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->name,
            'post_id' => $request->hidden_id,
            'body' => $request->body,
        ]);

        return redirect('/wiki-post/' . $request->hidden_id);
    }

    public function deleteComment(Request $request)
    {
        DB::table('wiki_comments')
            ->where('id', '=', $request->id)
            ->delete();

        return redirect('/wiki-post/' . $request->parameter);
    }

    public function deletePostAndComments(Request $request)
    {
        DB::table('wiki')
            ->where('id', '=', $request->id)
            ->delete();

        DB::table('wiki_comments')
            ->where('post_id', '=', $request->id)
            ->delete();

        return redirect('/wiki');
    }

    public function editPost(Request $request)
    {
        $this->validate($request, [
            'titel' => 'required',
            'body' => 'required',
        ]);

        DB::table('wiki')
            ->where('id', '=', $request->id)
            ->update(['titel' => $request->titel,
                'body' => $request->body]);

        return redirect('/wiki-post/' . $request->id);
    }

    public function editComment(Request $request)
    {
        $this->validate($request, [
            'body' => 'required',
        ]);

        DB::table('wiki_comments')
            ->where('id', '=', $request->id)
            ->update(['body' => $request->body]);

        return redirect('/wiki-post/' . $request->post_id);
    }
}
