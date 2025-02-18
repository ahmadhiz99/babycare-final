<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Report;
use App\Models\Baby;
use App\Models\Articles;


class UserController extends Controller
{
    function index(){
        $id = Auth::id();
        $data = Baby::all()->where('parent',$id);
        
        $dataStatus = Report::all()->where('parent_id',$id)->count();
        $countDataStatus = Report::all()->where('parent_id',$id)->count();

        $gender = [];
        $dataBuruk = Baby::all()->where('parent',$id)->where('status','=','Buruk')->count();
        $dataBaik = Baby::all()->where('parent',$id)->where('status','=','Baik')->count();
        $dataKurang = Baby::all()->where('parent',$id)->where('status','=','Kurang')->count();
        $dataResikoLebih = Baby::all()->where('parent',$id)->where('status','=','ResikoLebih')->count();
        $dataLebih = Baby::all()->where('parent',$id)->where('status','=','Lebih')->count();
        $dataObesitas = Baby::all()->where('parent',$id)->where('status','=','Obesitas')->count();
        $dataLakilaki = Baby::all()->where('parent',$id)->where('gender','=','Laki-laki')->count();
        $dataPerempuan = Baby::all()->where('parent',$id)->where('gender','=','Perempuan')->count();
        
        $data2 = Report::all()->where('parent_id',$id);
        $dataLaporanBuruk = Report::all()->where('parent_id',$id)->where('status','=','Buruk')->count();
        $dataLaporanKurang = Report::all()->where('parent_id',$id)->where('status','=','Kurang')->count();
        $dataLaporanBaik = Report::all()->where('parent_id',$id)->where('status','=','Baik')->count();
        $dataLaporanResikoLebih = Report::all()->where('parent_id',$id)->where('status','=','ResikoLebih')->count();
        $dataLaporanLebih = Report::all()->where('parent_id',$id)->where('status','=','Lebih')->count();
        $dataLaporanObesitas = Report::all()->where('parent_id',$id)->where('status','=','Obesitas')->count();

        $dataListBaby =  Baby::where('parent',$id)->orderBy('id','DESC')->get();
        $dataListReport = Report::where('parent_id',$id)->orderBy('id','DESC')->get();

        $dataListReport->merge($dataListBaby);
        // dd($dataListBaby);
        
        return view('users.index',[
            'data'=>$data, 
            'data2'=>$data2, 
            'gender'=>$gender,
            'dataBuruk'=>$dataBuruk,
            'dataKurang'=>$dataKurang,
            'dataBaik'=>$dataBaik, 
            'dataResikoLebih'=>$dataResikoLebih,
            'dataLebih'=>$dataLebih,
            'dataObesitas'=>$dataObesitas,
            'dataLakiLaki'=>$dataLakilaki,
            'dataPerempuan'=>$dataPerempuan,
            'dataStatus'=>$dataStatus,
            'dataListReport'=>$dataListReport,
            'dataLaporanBuruk'=>$dataLaporanBuruk,
            'dataLaporanKurang'=>$dataLaporanKurang,
            'dataLaporanBaik'=>$dataLaporanBaik,
            'dataLaporanResikoLebih'=>$dataLaporanResikoLebih,
            'dataLaporanLebih'=>$dataLaporanLebih,
            'dataLaporanObesitas'=>$dataLaporanObesitas
            ]);
    }
    function addBaby(){
        // $id = Auth::id();
        $id = Auth::id();
        $data = Baby::all()->where('parent',$id);
        // $data = User::find($id);
        // dd($data);
        // return view('user.addBaby',['data'=>$data]);
        return view('users.addBaby',['data'=>$data]);
    }
    
    function babyDetail(){
        // $id = Auth::id();
        $id = Auth::id();
        $data = Baby::all()->where('parent',$id);
        // $data = User::find($id);
        // dd($data);
        // return view('users.addBaby',['data'=>$data]);
        return view('users.babyDetail',['data'=>$data]);
    }
    function article(){
        $dataArticle = Articles::all();
        return view('users.article',['dataArticle'=>$dataArticle]);
    }
    function history(){
        $id = Auth::id();
        $data = User::find($id);
        $dataListReport = Report::where('parent_id',$id)->orderBy('id','DESC')->get();
        // dd($data);
        return view('users.history',['dataListReport'=>$dataListReport]);
    }
    function profile(){
        $id = Auth::id();
        $data = User::find($id);
        return view('users.profile',['data'=>$data]);
    }
    function settings(){
        return view('users.settings');
    }
}
