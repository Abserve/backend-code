<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Operation;
use App\Models\Rapport;
use App\Models\Refobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use \DB;

class RapportController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('auth.role:responsable,admin,client');
    }//end __construct()
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $links = DB::table('rapports')
            ->select('rapports.id','rapports.qte_ok','articles.ref_client','rapports.date_op','datepointages.date_pointage','rapports.qte_notOk'
                ,'rapports.qte_controlée','rapports.détail_defaut','rapports.remarque')
            ->join("operations", "operations.id", '=',"rapports.operation_id")
            ->join('datepointages', 'datepointages.operationn_id', '=', 'operations.id')
            ->join('users', 'datepointages.user_id', '=', 'users.id')
            ->join('articles', 'articles.op_id', '=', 'operations.id')
            ->groupBy('rapports.id')
            ->where('rapports.operation_id',$id)
            ->get();
        return response()->json(['success'=>true,'rapports'=>$links]);

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
        $rapport = new Rapport();
        $rapport->date_op     = $request->date_op;
        $rapport->qte_controlée     = $request->qte_controlée;
        $rapport->qte_ok     = $request->qte_ok;
        $rapport->qte_notOk     = $request->qte_notOk;
        $rapport->détail_defaut     = $request->détail_defaut;
        $rapport->buch_num     = $request->buch_num;
        $rapport->Delivery_num     = $request->Delivery_num;
        $rapport->remarque     = $request->remarque;

        if ($operation->rapport()->save($rapport)) {
            return response()->json(
                [
                    'status' => true,
                    'site'   => $rapport,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the rapport could not be saved.',
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
    public function show(Rapport $rapport)
    {
        return $rapport;
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
        $rapport = Rapport::findOrFail($id);
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

        $rapport->date_op     = $request->date_op;
        $rapport->qte_controlée     = $request->qte_controlée;
        $rapport->qte_ok     = $request->qte_ok;
        $rapport->qte_notOk     = $request->qte_notOk;
        $rapport->détail_defaut     = $request->détail_defaut;

        if ($operation->rapport()->save($rapport)) {
            return response()->json(
                [
                    'status' => true,
                    'detailPoints'   => $rapport  ,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the Rapport could not be updated.',
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
        $rapport = Rapport::findOrFail($id);
        if ($rapport->delete()) {

            return response()->json(
                [
                    'status' => true,
                    'detailPoints' => $rapport,
                ]
            );
        } else {

            return response()->json(
                [
                    'status' => false,
                    'message' => 'Oops, rapport could not be deleted.',
                ]
            );


        }
    }

    protected function guard()
    {
        return Auth::guard()->user();

    }

}
