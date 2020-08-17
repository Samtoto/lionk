<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\User;
use App\Blog;

use App\Community;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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

    public function index(Request $request)
    {
        $blog_id = $request->blog;
        $commentModel = new Comment();
        $comments = $commentModel->getByBlogId($blog_id);
        \Debugbar::info($comments->toArray());
        return response()->json($comments, 200);
    }

    public function edit(Request $request)
    {
        if (Auth::check()) {
            $comment = Comment::find($request->comment_id);
            if (Gate::allows('edit-comments', $comment)) {
                return response()->json($comment, 200);
            }
        }
        throw new AuthorizationException();
    }

    public function update(Request $request)
    {
        if (Auth::check()) {
            $comment = Comment::find($request->comment_id);
            if (Gate::allows('update-comments', $comment)) {
                $validatedData = $request->validate([
                    'content'=> 'required'
                ]);
                $comment->content = $request->content;
                $comment->save();

                // parse markdown
                $environment = Environment::createCommonMarkEnvironment();
                $environment->addExtension(new GithubFlavoredMarkdownExtension());

                $commonMark = new CommonMarkConverter(
                    ['html_input' => 'strip', 'allow_unsafe_links' => false],
                    $environment
                );
                $comment->content = $commonMark->convertToHtml($comment->content);
                // parse done

                return response()->json($comment, 200);
            } 
        }
        throw new AuthorizationException();
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

    public function delete(Request $request)
    {
        if (Auth::check()) {
            $comment = Comment::find($request->comment_id);
            if (Gate::allows('delete-comments', $comment)) {
                
                $comment->delete();

                return response()->json($request->comment_id, 200);
            }
        }
        throw new AuthorizationException();
    }

    /**
     * Create a sub comment
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addSub(Request $request)
    {
        \Debugbar::info($request->input());
        $comment = new Comment;
        $comment->user_id = Auth::id();
        $comment->parent_id = $request->parent_id;
        $comment->content = $request->content;
        $comment->blog_id = $request->blog_id;
        $comment->save();

        $environment = Environment::createCommonMarkEnvironment();
        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        $commonMark = new CommonMarkConverter(
            ['html_input' => 'strip', 'allow_unsafe_links' => false],
            $environment
        );

        // Recursive get comments and children comments
        $comment = Comment::where('id', $comment->id)->with(['allChildren', 'user'])->get();

        $comment[0]->content = $commonMark->convertToHtml($comment[0]->content);
        \Debugbar::info($comment[0]);
        return response()->json($comment[0], 200);
        // return $this->show($request);
    }
}
