<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\detailPoints;
use App\Models\Refobs;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use \DB;

class RefobsController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('auth.role:client,admin');


    }//end __construct()
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $links = DB::table('refobs')->where('refobs.refobs_id',$id)
            ->get();
        return response()->json($links);

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Article $article)
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

        $refobs = new Refobs();
        $refobs->ref_obs     = $request->ref_obs;
        $refobs->date_refobs     = $request->date_refobs;
        $refobs->comment     = $request->comment;

        if ($article->refobs()->save($refobs)) {
            return response()->json(
                [
                    'status' => true,
                    'site'   => $refobs,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the refobs could not be saved.',
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
    public function show(Refobs $refobs)
    {
        return $refobs;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  Article $article,$id)
    {
        $refobs = Refobs::findOrFail($id);
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

         $refobs->ref_obs     = $request->ref_obs;
        $refobs->date_refobs     = $request->date_refobs;
        $refobs->comment     = $request->comment;

        if ($article->refobs()->save($refobs)) {
            return response()->json(
                [
                    'status' => true,
                    'detailPoints'   => $refobs  ,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the refobs could not be updated.',
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
    public function destroy(Article $article,$id)
    {
        $refobs = Refobs::findOrFail($id);
        if ($refobs->delete()) {

            return response()->json(
                [
                    'status' => true,
                    'detailPoints' => $refobs,
                ]
            );
        } else {

            return response()->json(
                [
                    'status' => false,
                    'message' => 'Oops, refobs could not be deleted.',
                ]
            );


        }
    }

    protected function guard()
    {
        return Auth::guard()->user();

    }

}
