<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\AdminBaby;
use App\Models\Baby;
use App\Models\Articles;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ArticleUserController extends Controller
{
    function index(){
        $dataArticle = Articles::all();
        return view('users.article',['dataArticle'=>$dataArticle]);
    }
    function addArticle(Request $request){
        // dd($request);
        $article= new Articles;
        $article->title = $request->title;
        $article->content = $request->content;
        $article->category = $request->status;

        if($article->save()){
            return redirect()->back()->with('success','You are now successfully registered');
        }else{
            return redirect()->back()->with('error','Failed to register');
        }
    }

    // function users(){
    //     $data = Baby::find($id);
    //     dd($data);
    //     return view('admin.users',['data'=>$data]);
    // }



    function detail($id){
        $data = Articles::find($id);
        $title = $data->title;
        // $content = $article->content;
        // $category = $article->category;
        // var_dump($data);


        return view('users.article_detail',['data' => $data]);

    }
}
