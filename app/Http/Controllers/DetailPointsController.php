<?php

namespace App\Http\Controllers;

use App\Models\datepointages;
use App\Models\detailPoints;
use App\Models\Operateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use \DB;

class DetailPointsController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('auth.role:responsable,chefeq,admin');
    }//end __construct()
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $links = DB::table('detail_points')->where('detail_points.date_point_id',$id)
            ->get();
        return response()->json($links);

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Datepointages $datepointages)
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

        $detailPoints = new detailPoints();
        $detailPoints->heur_deb     = $request->heur_deb;
        $detailPoints->heure_fin     = $request->heure_fin;
        $detailPoints->absence     = $request->absence;
        $detailPoints->mostif_absence     = $request->mostif_absence;
        $detailPoints->majoration     = $request->majoration;

        if ($datepointages->details()->save($detailPoints)) {
            return response()->json(
                [
                    'status' => true,
                    'detailPoints'   => $detailPoints,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the detailPoints could not be saved.',
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
    public function show(detailPoints $detailPoints)
    {
        return $detailPoints;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  datepointages $datepointages,$id)
    {
        $detailPoints = detailPoints::findOrFail($id);
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

        $detailPoints->heur_deb     = $request->heur_deb;
        $detailPoints->heure_fin     = $request->heure_fin;
        $detailPoints->absence     = $request->absence;
        $detailPoints->mostif_absence     = $request->mostif_absence;
        $detailPoints->heur_deb     = $request->heur_deb;
        $detailPoints->majoration     = $request->majoration;

        if ($datepointages->details()->save($detailPoints)) {
            return response()->json(
                [
                    'status' => true,
                    'detailPoints'   => $detailPoints  ,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the detailPoints could not be updated.',
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
    public function destroy(datepointages $datepointages,$id)
    {
        $detailPoints = detailPoints::findOrFail($id);
        if ($detailPoints->delete()) {

            return response()->json(
                [
                    'status' => true,
                    'detailPoints' => $detailPoints,
                ]
            );
        } else {

            return response()->json(
                [
                    'status' => false,
                    'message' => 'Oops, detailPoints could not be deleted.',
                ]
            );


        }
    }

    protected function guard()
    {
        return Auth::guard()->user();

    }

}
