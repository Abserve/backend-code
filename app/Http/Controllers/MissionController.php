<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use \DB;


class MissionController extends Controller
{

    protected $user;


    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('auth.role:admin,responsable,client');

    }//end __construct()
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $links = DB::table('missions')
            ->get();
        return response()->json(['sucess'=>true,'missions'=>$links->toArray()]);
    }
    public function clientmis($id)
    {
        $links = DB::table('missions')
            ->join('operations','missions.id','=','operations.mission_id')
            ->join('datepointages','operations.id','=','datepointages.operationn_id')
            ->join('users', 'datepointages.user_id', '=', 'users.id')
            ->join('clientmissions', 'clientmissions.client_miss', '=', 'users.id')
            ->where('missions.id',$id)
            ->where('clientmissions.clientID',$id)
            ->get();
        return response()->json(['sucess'=>true,'missions'=>$links->toArray()]);
    }

    public function missrap($id)
    {
        $links = DB::table('missions')
            ->join('operations','missions.id','=','operations.mission_id')
            ->join('articles', 'operations.id', '=', 'articles.op_id')
            ->join('rapports','operations.id','=','rapports.operation_id')
            ->join('datepointages','operations.id','=','datepointages.operationn_id')
            ->join('detail_points','datepointages.id','=','detail_points.date_point_id')
            ->join('sites','detail_points.id','=','sites.detail_site')
            ->join('users', 'datepointages.user_id', '=', 'users.id')
            ->where('missions.id',$id)
            ->groupBy('missions.date_declanche')
            ->get();
        return response()->json(['success'=>true,'all'=>$links->toArray()]);
    }
    public function missid($id)
    {
        $links = DB::table('missions')
            ->where('missions.id',$id)
           // ->join('operations','missions.id','=','operations.mission_id')
            //->join('articles', 'operations.id', '=', 'articles.op_id')
            //->groupBy('missions.id')
            ->get();
        return response()->json(['sucess'=>true,'missions'=>$links->toArray()]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'date_declanche'     => 'required',
                'date_end'      => 'required',
                'qt_totale_article' => 'required',
                'nbr_operateur' => 'required',
                'jours_qte' => 'required',
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

        $mission            = new Mission();
        $mission->date_declanche     = $request->date_declanche;
        $mission->date_end      = $request->date_end;
        $mission->qt_totale_article = $request->qt_totale_article;
        $mission->description = $request->description;
        $mission->nbr_operateur = $request->nbr_operateur;
        $mission->jours_qte = $request->jours_qte;


        if ($mission->save()) {
            return response()->json(
                [
                    'status' => true,
                    'mission'   => $mission,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the Mission could not be saved.',
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
    public function show(Mission $mission)
    {
        return $mission;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $mission = Mission::findOrFail($id);
        $validator = Validator::make(
            $request->all(),
            [
                'date_declanche'     => 'required',
                'date_end'      => 'required',
                'qt_totale_article' => 'required',
                'nbr_operateur' => 'required',
                'jours_qte' => 'required',
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

        $mission->date_declanche     = $request->date_declanche;
        $mission->date_end      = $request->date_end;
        $mission->qt_totale_article = $request->qt_totale_article;
        $mission->description = $request->description;
        $mission->nbr_operateur = $request->nbr_operateur;
        $mission->jours_qte = $request->jours_qte;

        if ($mission->save()) {
            return response()->json(
                [
                    'status' => true,
                    'mission'   => $mission  ,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the Mission could not be updated.',
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
    public function destroy($id)
    {
        $mission = Mission::findOrFail($id);

        if ($mission->delete()) {
            return response()->json(
                [
                    'status' => true,
                    'mission' => $mission,
                ]
            );
        } else {

            return response()->json(
                [
                    'status' => false,
                    'message' => 'Oops, Mission could not be deleted.',
                ]
            );

        }
    }
    protected function guard()
    {
        return Auth::guard()->user();

    }//end guard()


}
