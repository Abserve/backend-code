<?php

namespace App\Http\Controllers;


use App\Models\Article;
use App\Models\datepointages;
use App\Models\Operation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use \DB;

class ArticleController extends Controller
{

    protected $user;


    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('auth.role:admin,responsable,client');

        $this->user = $this->guard()->user();

    }//end __construct()
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $links = DB::table('articles')
            ->where('articles.id',$id)
            ->get();
        return response()->json(['success'=>true,'articles'=>$links->toArray()]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Operation $operation)
    {
        $validator = Validator::make(
            $request->all(),
            [

            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'errors' => $validator->errors(),
                ],
                400
            );
        }

        $article   = new Article();
        $article->ref_abs     = $request->ref_abs;
        $article->designation     = $request->designation;
        $article->cmj     = $request->cmj;
        $article->type_op     = $request->type_op;
        $article->cadence_dti     = $request->cadence_dti;
        $article->cadence_abs     = $request->cadence_abs;


        if ($operation->articles()->save($article)) {
            return response()->json(
                [
                    'status' => true,
                    'mission'   => $article,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the Article could not be saved.',
                ]
            );
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return $article;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Operation $operation,  $id)
    {
        $article = Article::findOrFail($id);
        $validator = Validator::make(
            $request->all(),
            [
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'errors' => $validator->errors(),
                ],
                400
            );
        }

        $article->ref_abs     = $request->ref_abs;
        $article->designation     = $request->designation;
        $article->cmj     = $request->cmj;
        $article->type_op     = $request->type_op;
        $article->cadence_dti     = $request->cadence_dti;
        $article->cadence_abs     = $request->cadence_abs;


        if ($operation->articles()->save($article)) {
            return response()->json(
                [
                    'status' => true,
                    'mission'   => $article  ,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the Article could not be updated.',
                ]
            );
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($operation,$id)
    {
        $article = Article::findOrFail($id);

        if ($article->delete()) {
            return response()->json(
                [
                    'status' => true,
                    'mission' => $article,
                ]
            );
        } else {

            return response()->json(
                [
                    'status' => false,
                    'message' => 'Oops, Article could not be deleted.',
                ]
            );

        }
    }
    protected function guard()
    {
        return Auth::guard();

    }//end guard()

}
