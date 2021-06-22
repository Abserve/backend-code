<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Mission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use \DB;

class UsersController extends Controller
{

    protected $user;


    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('auth.role:admin,chefeq,responsable');
    }//end __construct()
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $links = DB::table('users')
            ->get();
        return response()->json(['success'=>true,'operateur'=>$links->toArray()]);
    }
    public function getemp()
    {
        $links = DB::table('users')
            ->where('users.role','admin')
            ->orWhere('users.role','chefeq')
            ->orWhere('users.role','responsable')
           // ->orWhere('users.role','operateur')
            ->get();
        return response()->json(['success'=>true,'operateur'=>$links->toArray()]);
    }
    public function getclient()
    {
        $links = DB::table('users')
            ->where('users.role','client')
      //      ->join('datepointages','datepointages.user_id','=','users.id')
        //    ->join('operations','operations.id','=','datepointages.operationn_id')
          //  ->join('missions','missions.id','=','operations.mission_id')
            //->groupBy('users.id')
            ->get();
        return response()->json(['success'=>true,'client'=>$links->toArray()]);
    }

    public function LoadEmbs()
    {
        $links = DB::table('users')
            ->select('users.embSet','users.full_name')
            ->where('users.embSet','<>','')
            ->where('users.role','operateur')
            ->get();
        return response()->json(['success'=>true,'emsets'=>$links->toArray()]);
    }




    public function getid(Request $request)
    {
        $links = DB::table('users')
            ->select('users.id')
            ->where('users.embSet',$request->embSet)
            ->get();
        return response()->json(['success'=>true,'id_is'=>$links->toArray()]);
    }
//////////////////////////////////////////////store employees with vue pffff////////////////
public function storeemp(Request $request)
{
    $validator = Validator::make(
        $request->all(),
        [
            'full_name'     => 'required|string|between:2,100',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required'
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

        $user   = new User();
        $user->full_name     = $request->full_name;
        $user->email      = $request->email;
        $user->phone = $request->phone;
        $user->cin = $request->cin;
        $user->adresse = $request->adresse;
        $user->d_naissance = $request->d_naissance;
        $user->qulification = $request->qulification;
        $user->embauche_date = $request->embauche_date;
        $user->indirect = $request->indirect;
        $user->actif = $request->actif;
        $user->date_inactivite = $request->date_inactivite;
        $user->tva_code = $request->tva_code;
        $user->email_accounting = $request->email_accounting;
        $user->email_demandeur = $request->email_demandeur;
        $user->role = $request->role;
        if ($user->create(
            array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            )
        )) {
            return response()->json(
                [
                    'status' => true,
                    'operateur' => $user,
                ]
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Oops, the user could not be saved.',
                ]
            );
        }
}
////////////////get employees and their old jobs(operations)//////////////////
public function empjob()
{
    $links = DB::table('users')
        ->join('datepointages','datepointages.user_id','=','users.id')
        ->join('operations','operations.id','=','datepointages.operationn_id')
        ->groupBy('users.email')
        ->get();
    return response()->json(['success'=>true,'operateur'=>$links->toArray()]);
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

        if($request->role!=='admin') {
            $user   = new User();
            $user->full_name     = $request->full_name;
            $user->email      = $request->email;
            $user->phone = $request->phone;
            $user->cin = $request->cin;
            $user->adresse = $request->adresse;
            $user->d_naissance = $request->d_naissance;
            $user->qulification = $request->qulification;
            $user->embauche_date = $request->embauche_date;
            $user->indirect = $request->indirect;
            $user->actif = $request->actif;
            $user->date_inactivite = $request->date_inactivite;
            $user->tva_code = $request->tva_code;
            $user->email_accounting = $request->email_accounting;
            $user->email_demandeur = $request->email_demandeur;
            $user->role = $request->role;
            $user->embSet = $request->embSet;
            $user->password = bcrypt($request->password);

            if ($user->save()) {
                return response()->json(
                    [
                        'status' => true,
                        'operateur' => $user,
                    ]
                );
            } else {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Oops, the user could not be saved.',
                    ]
                );
            }
        }else{
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Oops, cant add an admin',
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
    public function show(User $user)
    {
        return $user;
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
        $user = User::findOrFail($id);
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


         $user->full_name     = $request->full_name;
        $user->email      = $request->email;
        $user->phone = $request->phone;
        $user->cin = $request->cin;
        $user->adresse = $request->adresse;
        $user->d_naissance = $request->d_naissance;
        $user->qulification = $request->qulification;
        $user->embauche_date = $request->embauche_date;
        $user->indirect = $request->indirect;
        $user->actif = $request->actif;
        $user->date_inactivite = $request->date_inactivite;
        $user->tva_code = $request->tva_code;
        $user->email_accounting = $request->email_accounting;
        $user->email_demandeur = $request->email_demandeur;
        $user->password = bcrypt($request->password);
        if( $user->role!=='admin') {
            $user->role=$request->role;
            if ($user->save()) {
                return response()->json(
                    [
                        'status' => true,
                        'mission' => $user,
                    ]
                );
            } else {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Oops, the user could not be updated.',
                    ]
                );
            }
        }{

        return response()->json(
            [
                'status' => false,
                'message' => 'Oops, cant edit an admin.',
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
        $user = User::findOrFail($id);

        if ($user->delete()) {
            return response()->json(
                [
                    'status' => true,
                    'mission' => $user,
                ]
            );
        } else {

            return response()->json(
                [
                    'status' => false,
                    'message' => 'Oops, user could not be deleted.',
                ]
            );

        }
    }
    protected function guard()
    {
        return Auth::guard()->user();

    }//end guard()


}
