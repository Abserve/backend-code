<?php

namespace App\Http\Controllers;

use App\Models\datepointages;
use App\Models\Operateur;
use App\Models\Operation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use \DB;
use Tymon\JWTAuth\Facades\JWTAuth;



class DatepointagesController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('auth.role:responsable,chefeq,admin,client');

        $this->user = $this->guard()->user();

    }//end __construct()
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
               $links = DB::table('datepointages')->where('datepointages.operationn_id',$id)
                        ->select()
                        ->get();
        return response()->json(['success'=>true,'operateur'=>$links]);

    }

    public function infoo($id)
    {
               $links = DB::table('datepointages')
                      //  ->join('users', 'users.id','datepointages.user_id')
                        ->where('datepointages.operationn_id',$id)
                       // ->where('users.role','operateur')
                        //->groupBy('datepointages.date_pointage')
                        ->get();
        return response()->json(['success'=>true,'operateur'=>$links]);

    }


    public function show_operateur($id)
    {
        $links = DB::table('datepointages')
            ->select('users.id','users.full_name')
            ->join('users', 'datepointages.user_id', '=', 'users.id')
            ->join('operations', 'datepointages.operationn_id', '=', 'operations.id')
            ->where('datepointages.operationn_id',$id)
            ->get();
        return response()->json(['success'=>true,'operateur'=>$links]);;
    }


    public function getrap($id)
    {
        $links = DB::table('datepointages')
            ->join('operations', 'datepointages.operationn_id', '=', 'operations.id')
            ->join('rapports', 'rapports.operation_id', '=', 'operations.id')
            ->where('datepointages.operationn_id',$id)
            ->get();
        return response()->json(['success'=>true,'rapport'=>$links]);;
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
                'date_pointage'     => 'required',
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

        $datepointages = new datepointages();
        $datepointages->date_pointage     = $request->date_pointage;
        $datepointages->heur_deb     = $request->heur_deb;
        $datepointages->heure_fin     = $request->heure_fin;
        $datepointages->absence     = $request->absence;
        $datepointages->mostif_absence     = $request->mostif_absence;
        $datepointages->majoration     = $request->majoration;

        $datepointages->user_id     = $request->user_id;


        if ($operation->datep()->save($datepointages)) {
            return response()->json(
                [
                    'status' => true,
                    'operateur'   => $datepointages,
                ]
            );
        } else {
            return response()->json(

            [
                    'status'  => false,
                    'message' =>  'cant add date pointage',
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
    public function show(datepointages $datepointages)
    {
        return $datepointages;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  Operation $operation,$id)
    {
        $datepointages = datepointages::findOrFail($id);
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

        $datepointages->date_pointage     = $request->date_pointage;
        $datepointages->heur_deb     = $request->heur_deb;
        $datepointages->heure_fin     = $request->heure_fin;
        $datepointages->absence     = $request->absence;
        $datepointages->mostif_absence     = $request->mostif_absence;
        $datepointages->majoration     = $request->majoration;

        if ($operation->datep()->save($datepointages)) {
            return response()->json(
                [
                    'status' => true,
                    'datepointages'   => $datepointages  ,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the datepointages could not be updated.',
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
    public function destroy(Operation $operation,$id)
    {
        $datepointages = datepointages::findOrFail($id);
        if ($datepointages->delete()) {

            return response()->json(
                [
                    'status' => true,
                    'datepointages' => $datepointages,
                ]
            );
        } else {

            return response()->json(
                [
                    'status' => false,
                    'message' => 'Oops, datepointages could not be deleted.',
                ]
            );


        }
    }

    protected function guard()
    {
        return Auth::guard();

    }

}
