<?php

namespace App\Http\Controllers;

use App\Models\Clientmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use \DB;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\User;


class ClientmissionController extends Controller
{
   
    protected $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('auth.role:client,admin,responsable');
    }//end __construct()
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {

        $links = DB::table('clientmissions')->where('clientmissions.id',$id)
            ->get();
        return response()->json(['success'=>true,'clientmission'=>$links]);


    }
    public function getclientmission($clientID)
    {

           $links = DB::table('clientmissions')
            ->select('clientmissions.missid')
            ->where('clientmissions.clientID',$clientID)
            ->get();

        $cart = array();
        foreach($links as $l){
            array_push($cart,$l->missid);
        }
        $missions = array();
        for($i=0 ; $i<count($cart);$i++){
            array_push($missions,
           (DB::table('missions')
             ->where('missions.id',$cart[$i])
              ->get()));
        }

        //return response()->json($missions);
        return json_encode(['missions'=>$missions, JSON_FORCE_OBJECT]);
    }
    public function getmissionClient($missid)
    {

           $links = DB::table('clientmissions')
            ->select('clientmissions.clientID')
            ->where('clientmissions.missid',$missid)
            ->get();

        $cart = array();
        foreach($links as $l){
            array_push($cart,$l->clientID);
        }
        $clients = array();
        for($i=0 ; $i<count($cart);$i++){
            array_push($clients,
           (DB::table('users')
             ->where('users.id',$cart[$i])
              ->get()));
        }

        return json_encode(['clients'=>$clients, JSON_FORCE_OBJECT]);
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

        //$user=User::find(Auth::user()->id)->first();
        $user=JWTAuth::user();

        $clientmission = new Clientmission();
        $clientmission->missid       = $request->missid;
        $clientmission->clientID     = $request->clientID;
        $clientmission->client_miss     = $user->id;
        if ($clientmission->save()) {
            return response()->json(
                [
                    'status' => true,
                    'clientmission'   => $clientmission,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the clientmission could not be saved.',
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
    public function show(operation $operation)
    {
        return $operation;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  Mission  $mission,$id)
    {
        $operation = Operation::findOrFail($id);
        $validator = Validator::make(
            $request->all(),
            [
                'date_operation'     => 'required',
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

        $operation->date_operation=$request->date_operation;
        $operation->qte_controlée     = $request->qte_controlée;
        $operation->qte_ok     = $request->qte_ok;
        $operation->qte_notOk     = $request->qte_notOk;
        $operation->détail_defaut     = $request->détail_defaut;
        $operation->buch_num     = $request->buch_num;
        $operation->Delivery_num     = $request->Delivery_num;
        $operation->remarque     = $request->remarque;

        if ($mission->operations()->save($operation)) {
            return response()->json(
                [
                    'status' => true,
                    'operation'   => $operation  ,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the operation could not be updated.',
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
    public function destroy(Mission $mission,$id)
    {
        $operation = Operation::findOrFail($id);

        if ($operation->delete()) {
            return response()->json(
                [
                    'status' => true,
                    'operation' => $operation,
                ]
            );
        } else {

            return response()->json(
                [
                    'status' => false,
                    'message' => 'Oops, operation could not be deleted.',
                ]
            );

        }
    }
    protected function guard()
    {
        return Auth::guard();

    }//end guard()

}
