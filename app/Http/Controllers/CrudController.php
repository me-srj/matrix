<?php

namespace App\Http\Controllers;

use App\Models\UserMatrix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;
use DB;


class CrudController extends Controller
{
    public function index()
    {
        $MUsers = UserMatrix::orderBy('id', 'desc')->paginate(5);
        return view('index', compact('MUsers'));
    }
    public function upload(Request $request)
    {
        $image = $request->file('file');
        $id="";
        if(isset($request->id))
        {
            $id=$request->input("id");
        }
        $input['imagename'] = time() . '.' . $image->getClientOriginalExtension();
        $img = Image::make($image->getRealPath());
        $img->resize(320, 240, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path("/uploads") .  '/' . $input['imagename']);
        if($id!="")
        {
            $old=DB::table('user_matrices')
            ->where('id', $id)->first();
            unlink(public_path("uploads/".$old->photo));
            DB::table('user_matrices')
            ->where('id', $id)
            ->update(['photo' => $input['imagename']]);
        }
        return $input;
    }
    public function Edit(Request $request)
    {
        $user=UserMatrix::where('id', $request->id)
        ->first();
        return $user;
        $name = $request->input("name");
        $email = $request->input("email");
        $photo = $request->input("photo");
        $res=array();
        $res['data']= DB::table('user_matrices')->insert(
            [
                "name" => $name,
                "email" => $email,
                "photo"=>$photo
            ]
        );
        if($res['data'])
        {
            $res['status']=true;
        }
        else
        {
            $res['status']=false;
        }
        return json_encode($res);
    }
    public function Save(Request $request)
    {
        $name = $request->input("name");
        $email = $request->input("email");
        $photo = $request->input("photo");
        $res=array();
        $res['data']= DB::table('user_matrices')->insert(
            [
                "name" => $name,
                "email" => $email,
                "photo"=>$photo
            ]
        );
        if($res['data'])
        {
            $res['status']=true;
        }
        else
        {
            $res['status']=false;
        }
        return json_encode($res);
    }
    public function GetAll()
    {
        return DB::table('user_matrices')->get();
    }
    public function Delete(Request $req)
    {
        $replyid=$req->input("id");
        $del=DB::table('user_matrices')->where('id', $replyid)->first();
        $up = DB::table('user_matrices')->where('id', $replyid)->delete();
        unlink(public_path("uploads/".$del->photo));
        return $up;
    }
}
