<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Hash;
use Auth;


class MemberController extends Controller
{
    /**
     * Create a new MemberController instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }

    public function index()
    {
        $member = Member::with(['users'])->get();
        return response()->json([
            'response' => [
                'data' => $member,
            ]
        ], 200);
    }

    public function me(Member $Member)
    {
        $id = Auth::user()->user_id;

        $member = Member::with(['users'])->where('user_id', $id)->first();
        return response()->json([
            'response' => [
                'data' => $member,
            ]
        ], 200);
    }

    public function store(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:App\Models\User,email',
            'password' => 'required|string|min:6',
            'position' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        \DB::beginTransaction();
        try {
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->save();

            $member = new Member();
            $member->status = $request->input('status');
            $member->position = $request->input('position');
            $member = $user->members()->save($member);
            \DB::commit();
            return response()->json([
                'response' => [
                    'status' => "success add data",
                ]
            ], 200);
        } catch (\Exception $ex) {
        \DB::rollback();
            return $ex;
        }
    }

    public function update(Request $request, $id)
    {

        $update = Member::findOrFail($id)->update($request->all());
        if($update) {
            return response()->json([
                'response' => [
                    'status' => "success update data",
                ]
            ], 200);
        }
    }

    public function delete(Request $request, $id)
    {
        $delete = Member::where('id_member',$id)->delete();
        if($delete) {
            return response()->json([
                'response' => [
                    'status' => "success delete data",
                ]
            ], 200);
        }
    }
}
