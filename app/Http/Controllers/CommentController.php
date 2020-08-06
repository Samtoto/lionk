<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\User;
use App\Blog;

use App\Community;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Environment;

class CommentController extends Controller
{

    /**
     * parse comments' content by commonmark through recursive
     *
     * @param \Illuminate\Database\Eloquent\Collection $comments
     * @param \League\CommonMark\CommonMarkConverter $commonMark
     * @return void
     */
    private function recursiveParseContent(\Illuminate\Database\Eloquent\Collection $comments, \League\CommonMark\CommonMarkConverter $commonMark)
        : void
    {
        foreach ($comments as $comment) {
            $comment->content = $commonMark->convertToHtml($comment->content);
            if ($comment->allChildren) {
                $this->recursiveParseContent($comment->allChildren, $commonMark);
            }
        }
    }

    /**
     * Get blog by blog_id
     * with relationships of comments and comments' children
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $blog_id = $request->blog_id;
        $blog = Blog::where('id', $blog_id)->with(['comment'=> function($query){
            $query->where('parent_id', null)->with(['allChildren', 'user']);
        }, 'user'])->get();
        \Debugbar::info($blog[0]->toArray());
        // \Debugbar::info($blog[0]->img_path);

        $environment = Environment::createCommonMarkEnvironment();
        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        $commonMark = new CommonMarkConverter(
            ['html_input' => 'strip', 'allow_unsafe_links' => false],
            $environment
        );

        if ($blog[0]->img_path) {
            $blog[0]->img_path = Storage::disk('public_uploads')->url($blog[0]->img_path);
        }
        if ($blog[0]->content) {
            $blog[0]->content = $commonMark->convertToHtml($blog[0]->content);
            // $blog[0]->content .= ' parsed by commonmark';
        }
        if ($blog[0]->comment) {
            $this->recursiveParseContent($blog[0]->comment, $commonMark);
        }
        // \Debugbar::info($blog[0]->comment);
        return response()->json($blog[0], 200);
    }

    /**
     * Create a comment
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $comment = new Comment;
        $comment->user_id = Auth::id();
        $comment->parent_id = $request->comment_id;
        $comment->content = $request->comment;
        $comment->blog_id = $request->blog_id;
        $comment->save();
        // TODO should return the success one
        return $this->show($request);
    }

    /**
     * Create a sub comment
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addSub(Request $request)
    {
        $comment = new Comment;
        $comment->user_id = Auth::id();
        $comment->parent_id = $request->comment_id;
        $comment->content = $request->comment;
        $comment->blog_id = $request->blog_id;
        $comment->save();

        // Recursive get comments and children comments
        $comment = Comment::where('id', $comment->id)->with(['allChildren'])->get();

        return response()->json($comment[0], 200);
        // return $this->show($request);
    }
}
