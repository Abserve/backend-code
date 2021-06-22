<?php

namespace App\Http\Controllers;

use App\Models\datepointages;
use App\Models\detailPoints;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\mission;

use \DB;

class SiteController extends Controller
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
        $links = DB::table('sites')->where('sites.detail_site',$id)
            ->get();
        return response()->json(['sites'=>$links]);

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

        $site = new Site();
        $site->name     = $request->name;
        $site->map     = $request->map;
      
        if ($mission->sites()->save($site)) {
            return response()->json(
                [
                    'status' => true,
                    'site'   => $site,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the site could not be saved.',
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
    public function show(Site $site)
    {
        return $site;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  Mission $mission,$id)
    {
        $site = Site::findOrFail($id);
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

        $site->name     = $request->name;
        $site->map     = $request->map;


        if ($mission->sites()->save($site)) {
            return response()->json(
                [
                    'status' => true,
                    'site'   => $site  ,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the site could not be updated.',
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
        $site = Site::findOrFail($id);
        if ($site->delete()) {

            return response()->json(
                [
                    'status' => true,
                    'site' => $site,
                ]
            );
        } else {

            return response()->json(
                [
                    'status' => false,
                    'message' => 'Oops, site could not be deleted.',
                ]
            );


        }
    }

    protected function guard()
    {
        return Auth::guard()->user();

    }

}
