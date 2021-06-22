<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use App\Models\Operation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use \DB;

class OperationController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('auth.role:responsable,admin,chefeq,client');
    }//end __construct()
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {

        $links = DB::table('operations')->where('operations.mission_id',$id)
            ->get();
        return response()->json(['success'=>true,'operations'=>$links]);


    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Mission $mission)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'date_operation'     => 'required',
                'qte_controlée'     => 'required',
                'qte_ok'     => 'required',
                'qte_notOk'     => 'required',
                'détail_defaut'     => 'required',
                'buch_num'     => 'required',
                'Delivery_num'     => 'required',
                'remarque'     => 'required',

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

        $operation = new Operation();
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
                    'operation'   => $operation,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the Operation could not be saved.',
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
        return Auth::guard()->user();

    }//end guard()


}
