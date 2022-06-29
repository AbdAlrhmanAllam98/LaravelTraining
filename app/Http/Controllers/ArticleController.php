<?php
namespace App\Http\Controllers;
use App\Models\Article;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles=Article::with('user')->get();
        return response()->json(['data'=>new ArticleCollection($articles),'status'=>'Success','Message'=>'Articles Returned']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreArticleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        $article=auth()->user()->articles()->create($request->all());
        if($article){
            return response()->json(['data'=>new ArticleResource($article->load('user')),'status'=>'Success','Message'=>'Article Created Successfully'],200);
        }
        else{
            return response()->json(['data'=>[],'status'=>'Failed','Message'=>'Article Not Created'],405);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateArticleRequest  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, Article $oldArticle)
    {
        $article=auth()->user()->articles()->find($oldArticle->id);
        if(!$article){
            return response()->json(['data'=>[],'status'=>'Failed','Message'=>'Article Not Found'],404);

        }
        else{
            if($article->update($request->all())){
                return response()->json(['data'=>new ArticleResource($article->load('user')),'status'=>'Success','Message'=>'Article Updated Successfully'],200);
            }
            else{
                return response()->json(['data'=>[],'status'=>'Failed','Message'=>'Article Not Updated'],405);
            }
        }

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $oldArticle)
    {
        $article=auth()->user()->articles()->find($oldArticle->id);
        if(!$article){
            return response()->json(['data'=>[],'status'=>'Failed','Message'=>'Article Not Found'],404);

        }
        else{
            if($article->delete()){
                return response()->json(['data'=>[],'status'=>'Success','Message'=>'Article Deleted Successfully'],200);
            }
            else{
                return response()->json(['data'=>[],'status'=>'Failed','Message'=>'Article Not Deleted'],405);
            }
        }

    }
}
