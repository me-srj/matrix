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
        $input['imagename'] = time() . '.' . $image->getClientOriginalExtension();
        $img = Image::make($image->getRealPath());
        $img->resize(320, 240, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path("/uploads") .  '/' . $input['imagename']);
        return $input;
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
}
