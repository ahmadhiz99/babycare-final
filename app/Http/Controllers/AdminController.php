<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminBaby;
use App\Models\User;
use App\Models\Baby;
use App\Models\Report;
use App\Models\AdminBabyTesting;

class AdminController extends Controller
{
    function index(){
        // $id = Auth::id();
        // $dataTraining = AdminBaby::all();

        $id = Auth::id();
        // $data = Baby::all()->where('parent',$id);
        $data = Baby::all();
        
        $datareportsfinal = [];
        $datareports = [];
        
        foreach ($data as $d) {
            $report = Report::where('baby_id',$d->id)->orderBy('id','DESC')->first();

            // var_dump($report->report_monthly);
            // echo $report->report_monthly;
            // dd($report);
            array_push($datareports, $report->report_monthly);
        }
        $dataparent = [];
        
        foreach ($data as $d) {
            $dataparent = [];
            $report = Report::where('parent_id',$d->parent)->orderBy('id','DESC')->first();
            if ( array_search($d->parent, $dataparent) == 0){
                array_push($dataparent, $report->report_monthly_total);
            }
            
            $dataparent = User::where('id',$d->parent)->orderBy('id','DESC')->get();
            // array_push
        }
        $datareportsparent = $dataparent;


        

        $dataStatus = Report::all()->count();
        $countDataStatus = Report::all()->count();

        $gender = [];
        $dataBuruk = Baby::all()->where('status','=','Buruk')->count();
        $dataKurang = Baby::all()->where('status','=','Kurang')->count();
        $dataBaik = Baby::all()->where('status','=','Baik')->count();
        $dataResikoLebih = Baby::all()->where('status','=','ResikoLebih')->count();
        $dataLebih = Baby::all()->where('status','=','Lebih')->count();
        $dataObesitas = Baby::all()->where('status','=','Obesitas')->count();

        $dataLakilaki = Baby::all()->where('gender','=','Laki-laki')->count();
        $dataPerempuan = Baby::all()->where('gender','=','Perempuan')->count();
        $data2 = Report::all();

        $dataLakilakilist = Baby::all()->where('gender','=','Laki-laki');
        $dataPerempuanlist = Baby::all()->where('gender','=','Perempuan');

        $dataGender = [];
        $dataGender['laki'] = $dataLakilaki;
        $dataGender['perempuan'] = $dataPerempuan;

        // $dataListBaby =  Baby::all()->orderBy('id','DESC')->get();
        // $dataListReport = Report::all()->orderBy('id','DESC')->get();
        $dataListBaby =  Baby::all();
        $dataListReport = Report::all();

        $dataListReport->merge($dataListBaby);

        return view('admin.index',[
                    'datareportsparent' => $datareportsparent,
                    'dataparent'=>$dataparent,
                    'datareports' => $datareports,
                    'dataGender'=>$dataGender, 
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
                    'dataLakilakilist' => $dataLakilakilist,
                    'dataPerempuanlist' => $dataPerempuanlist,
                ]);

    }

    function dataTrainingMenu(){
       
        $data = AdminBaby::all();
        $countData = count($data);
        $dataTestingCount = round($countData / 4);
        $dataTrainingCount = $countData - $dataTestingCount;
        // dd($dataTrainingCount, $dataTestingCount);
        $dataTrainingBackground = AdminBaby::all()->take($dataTrainingCount);
        // $dataTesting = AdminBaby::all()->sortByDesc('id')->take($dataTestingCount);
        // $dataTesting = AdminBabyTesting::all()->take(8);
        $dataTesting = AdminBabyTesting::all();
        // dd($dataTesting);
        // dd($dataTrainingBackground, $dataTesting);

        $arrReal = [];
        $arrPredict = [];

        foreach($dataTesting as $data){
            $age = $data->age;
            $weight = $data->weight;
            $length = $data->length;
            $gender = $data->gender;
            array_push($arrReal, $data->status);
            $status = $this->nutritionCalc($age, $weight, $length, $gender);
            array_push($arrPredict, $status);
        }

        // Real - Predict
        $buruk_buruk = 0;
        $kurang_buruk = 0;
        $baik_buruk = 0;
        $resikolebih_buruk = 0;
        $lebih_buruk = 0;
        $obesitas_buruk = 0;

        $buruk_kurang = 0;
        $kurang_kurang = 0;
        $baik_kurang = 0;
        $resikolebih_kurang = 0;
        $lebih_kurang = 0;
        $obesitas_kurang = 0;

        $buruk_baik = 0;
        $kurang_baik = 0;
        $baik_baik = 0;
        $resikolebih_baik = 0;
        $lebih_baik = 0;
        $obesitas_baik = 0;

        $buruk_resikolebih = 0;
        $kurang_resikolebih = 0;
        $baik_resikolebih = 0;
        $resikolebih_resikolebih = 0;
        $lebih_resikolebih = 0;
        $obesitas_resikolebih = 0;

        $buruk_lebih = 0;
        $kurang_lebih = 0;
        $baik_lebih = 0;
        $resikolebih_lebih = 0;
        $lebih_lebih = 0;
        $obesitas_lebih = 0;

        $buruk_obesitas = 0;
        $kurang_obesitas = 0;
        $baik_obesitas = 0;
        $resikolebih_obesitas = 0;
        $lebih_obesitas = 0;
        $obesitas_obesitas = 0;

        // $baik_baik = 0;
        // $baik_kurang = 0;
        // $baik_lebih = 0;

        // $kurang_baik = 0;
        // $kurang_kurang = 0;
        // $kurang_lebih = 0;

        // $lebih_baik = 0;
        // $lebih_kurang = 0;
        // $lebih_lebih = 0;

        // dd($arrReal, $arrPredict);
    for($i=0;$i<count($arrReal); $i++){
        if($arrReal[$i] == "Buruk"){
            if($arrPredict[$i] == "Buruk"){
                $buruk_buruk += 1;
            }
            if($arrPredict[$i] == "Kurang"){
                $buruk_kurang += 1;
            }
            if($arrPredict[$i] == "Baik"){
                $buruk_baik += 1;
            }
            if($arrPredict[$i] == "ResikoLebih"){
                $buruk_resikolebih += 1;
            }
            if($arrPredict[$i] == "Lebih"){
                $buruk_lebih += 1;
            }
            if($arrPredict[$i] == "Obesitas"){
                $buruk_obesitas += 1;
            }
        }

        if($arrReal[$i] == "Kurang"){
            if($arrPredict[$i] == "Buruk"){
                $kurang_buruk += 1;
            }
            if($arrPredict[$i] == "Kurang"){
                $kurang_kurang += 1;
            }
            if($arrPredict[$i] == "Baik"){
                $kurang_baik += 1;
            }
            if($arrPredict[$i] == "ResikoLebih"){
                $kurang_resikolebih += 1;
            }
            if($arrPredict[$i] == "Lebih"){
                $kurang_lebih += 1;
            }
            if($arrPredict[$i] == "Obesitas"){
                $kurang_obesitas += 1;
            }
        }

        if($arrReal[$i] == "Baik"){
            if($arrPredict[$i] == "Buruk"){
                $baik_buruk += 1;
            }
            if($arrPredict[$i] == "Kurang"){
                $baik_kurang += 1;
            }
            if($arrPredict[$i] == "Baik"){
                $baik_baik += 1;
            }
            if($arrPredict[$i] == "ResikoLebih"){
                $baik_resikolebih += 1;
            }
            if($arrPredict[$i] == "Lebih"){
                $baik_lebih += 1;
            }
            if($arrPredict[$i] == "Obesitas"){
                $baik_obesitas += 1;
            }
        }

        if($arrReal[$i] == "ResikoLebih"){
            if($arrPredict[$i] == "Buruk"){
                $resikolebih_buruk += 1;
            }
            if($arrPredict[$i] == "Kurang"){
                $resikolebih_kurang += 1;
            }
            if($arrPredict[$i] == "Baik"){
                $resikolebih_baik += 1;
            }
            if($arrPredict[$i] == "ResikoLebih"){
                $resikolebih_resikolebih += 1;
            }
            if($arrPredict[$i] == "Lebih"){
                $resikolebih_lebih += 1;
            }
            if($arrPredict[$i] == "Obesitas"){
                $resikolebih_obesitas += 1;
            }
        }   

        if($arrReal[$i] == "Lebih"){
            if($arrPredict[$i] == "Buruk"){
                $lebih_buruk += 1;
            }
            if($arrPredict[$i] == "Kurang"){
                $lebih_kurang += 1;
            }
            if($arrPredict[$i] == "Baik"){
                $lebih_baik += 1;
            }
            if($arrPredict[$i] == "ResikoLebih"){
                $lebih_resikolebih += 1;
            }
            if($arrPredict[$i] == "Lebih"){
                $lebih_lebih += 1;
            }
            if($arrPredict[$i] == "Obesitas"){
                $lebih_obesitas += 1;
            }
        }   
      
        if($arrReal[$i] == "Obesitas"){
            if($arrPredict[$i] == "Buruk"){
                $obesitas_buruk += 1;
            }
            if($arrPredict[$i] == "Kurang"){
                $obesitas_kurang += 1;
            }
            if($arrPredict[$i] == "Baik"){
                $obesitas_baik += 1;
            }
            if($arrPredict[$i] == "ResikoLebih"){
                $obesitas_resikolebih += 1;
            }
            if($arrPredict[$i] == "Lebih"){
                $obesitas_lebih += 1;
            }
            if($arrPredict[$i] == "Obesitas"){
                $obesitas_obesitas += 1;
            }
        }   
    }

    // dd('baik_baik =>' , $baik_baik,
    //    'baik_kurang =>' , $baik_kurang,
    //    'baik_lebih =>' , $baik_lebih,
    //    'kurang_baik =>' , $kurang_baik,
    //    'kurang_kurang =>' , $kurang_kurang,
    //    'kurang_lebih =>' , $kurang_lebih,
    //    'lebih_baik =>' , $lebih_baik,
    //    'lebih_kurang =>' , $lebih_kurang,
    //    'lebih_lebih) =>' , $lebih_lebih);

    $akurasi = (
            $baik_baik + 
            $kurang_kurang + 
            $lebih_lebih + 
            $buruk_buruk + 
            $resikolebih_resikolebih + 
            $obesitas_obesitas) / 
            (
                $buruk_buruk +
                $kurang_buruk +
                $baik_buruk +
                $resikolebih_buruk +
                $lebih_buruk +
                $obesitas_buruk +
        
                $buruk_kurang +
                $kurang_kurang +
                $baik_kurang +
                $resikolebih_kurang +
                $lebih_kurang +
                $obesitas_kurang +
        
                $buruk_baik +
                $kurang_baik +
                $baik_baik +
                $resikolebih_baik +
                $lebih_baik +
                $obesitas_baik +
        
                $buruk_resikolebih +
                $kurang_resikolebih +
                $baik_resikolebih +
                $resikolebih_resikolebih +
                $lebih_resikolebih +
                $obesitas_resikolebih +
        
                $buruk_lebih +
                $kurang_lebih +
                $baik_lebih +
                $resikolebih_lebih +
                $lebih_lebih +
                $obesitas_lebih +
        
                $buruk_obesitas +
                $kurang_obesitas +
                $baik_obesitas +
                $resikolebih_obesitas +
                $lebih_obesitas +
                $obesitas_obesitas 
            ) * 100;

    
    // dd(round(102/4));
    // dd('Akurasi => ' ,$akurasi.'%' );
    

        $id = Auth::id();
        $dataTraining = AdminBaby::paginate(5);

        return view('admin.dataTraining',['dataTraining'=>$dataTraining, 'akurasi'=>$akurasi]);
    }
    

    function dataTestingMenu(){
        $id = Auth::id();
        $dataTesting = AdminBaby::all();
        return view('admin.dataTesting',['dataTesting'=>$dataTesting]);
    }
    
    function dataTraining($id){
        // $id = Auth::id();
        $dataTraining = AdminBaby::find($id);
        // dd($dataTraining);
        return view('admin.editDataTraining',['dataTraining'=>$dataTraining]);
    }

    function addDataTraining(Request $request){
        $id = Auth::id();
        $dataTraining = new AdminBaby;
        $dataTraining->age = $request->age;
        $dataTraining->length = $request->length;
        $dataTraining->weight = $request->weight;
        $dataTraining->gender = $request->gender;
        $dataTraining->status = $request->status;
        $dataTraining->user_id = $id;
        if($dataTraining->save()){
            // return view('admin.index',['dataTraining'=>$dataTraining]);
            return redirect()->back()->with('success','Data Training Berhasil Ditambahkan');
        }else{
            return redirect()->back()->with('error','Terdapat yang salah');
        }
    }

    function editDataTraining(Request $request){
        $dataTraining = AdminBaby::find($request->id);
        $dataTraining->age = $request->age;
        $dataTraining->length = $request->length;
        $dataTraining->weight = $request->weight;
        $dataTraining->gender = $request->gender;
        $dataTraining->status = $request->status;
        if($dataTraining->save()){
            $id = Auth::id();
            $dataTraining = AdminBaby::all();
            return view('admin.index',['dataTraining'=>$dataTraining]);
        }else{
            return redirect()->back()->with('error','Terdapat yang salah');
        }
    }

    function updateDataTraining(Request $request){
        $dataTraining = AdminBaby::find($request->id);
        $dataTraining->age = $request->age;
        $dataTraining->length = $request->length;
        $dataTraining->weight = $request->weight;
        $dataTraining->gender = $request->gender;
        $dataTraining->status = $request->status;
        if($dataTraining->save()){
            return redirect('admin/data-training')->with('success','Data Training Berhasil Di Update');
        }else{
            return redirect()->back()->with('error','Terdapat yang salah');
        }
    }
    
    function deleteDataTraining(Request $request){

        $dataTraining = AdminBaby::find($request->id);
        if($dataTraining->delete()){
            return redirect('admin/data-training')->with('success','Data berhasil dihapus');
           }else{
            return redirect()->back()->with('error','Failed to register');
           }
    }

    function dataUser($id){
        $dataUser = User::find($id);
        // dd($dataUser);
        return view('admin.editDataUser',['dataUser'=>$dataUser]);
    }

    function updateDataUser(Request $request){
        $dataUser = User::find($request->id);
        $dataUser->name = $request->name;
        $dataUser->email = $request->email;
        if($request->role == "Admin"){
            $dataUser->role = 1;
        }else{
            $dataUser->role = 2;
        }
        if($dataUser->save()){
            return redirect('admin/users')->with('success','Data User Berhasil Di Update');
        }else{
            return redirect()->back()->with('error','Terdapat yang salah');
        }
    }
    
    function deleteDataUser(Request $request){

        $dataUser = User::find($request->id);
        if($dataUser->delete()){
            return redirect('admin/users')->with('success','Data berhasil dihapus');
           }else{
            return redirect()->back()->with('error','Failed to register');
           }
    }

    function profile(){
        return view('admin.profile');
    }

    function settings(){
        return view('admin.settings');
    }

    function nutritionCalc($age, $weight, $length, $gender){
        $dataTraining = new AdminBaby;
        $data = AdminBaby::all()->take(60);

         //GET DATA BY CLASS/LABEL
         $dataBuruk = [];
         foreach ($data as $d){
            if($d->status == "Buruk"){
                array_push($dataBuruk, $d);
            }
         }
 
         $dataKurang = [];
         foreach ($data as $d){
            if($d->status == "Kurang"){
                array_push($dataKurang, $d);
            }
         }
 
         $dataBaik = [];
         foreach ($data as $d){
            if($d->status == "Baik"){
                array_push($dataBaik, $d);
            }
         }
 
         $dataResikoLebih = [];
         foreach ($data as $d){
            if($d->status == "ResikoLebih"){
                array_push($dataResikoLebih, $d);
            }
         }
 
         $dataLebih = [];
         foreach ($data as $d){
            if($d->status == "Lebih"){
                array_push($dataLebih, $d);
            }
         }
 
         $dataObesitas = [];
         foreach ($data as $d){
            if($d->status == "Obesitas"){
                array_push($dataObesitas, $d);
            }
         }
 
         //CARI PROBABILITAS PER CLASS
         ///Class Buruk (jumlah data baik / jumlah data semua)
         $probabilitasKelasBuruk = count($dataBuruk)/count($data);
         ///Class Kurang
         $probabilitasKelasKurang = count($dataKurang)/count($data);
         ///Class Baik 
         $probabilitasKelasBaik = count($dataBaik)/count($data);
         ///Class ResikoLebih
         $probabilitasKelasResikoLebih = count($dataResikoLebih)/count($data);
         ///Class Lebih
         $probabilitasKelasLebih = count($dataLebih)/count($data);
         ///Class Obesitas
         $probabilitasKelasObesitas = count($dataObesitas)/count($data);
 
         
 
 
         //CARI MEAN PER CLASS
         ///Jumlah nilai Baik / jumlah semua data.
         ///Umur
         $jmlUmur = 0;
         foreach($dataBuruk as $d){
             $jmlUmur += $d->age;
         }
         $meanUmurBuruk = $jmlUmur/count($dataBuruk);
 
         $jmlUmur = 0;
         foreach($dataKurang as $d){
             $jmlUmur += $d->age;
         }
         $meanUmurKurang = $jmlUmur/count($dataKurang);
 
         $jmlUmur = 0;
         foreach($dataBaik as $d){
             $jmlUmur += $d->age;
         }
         $meanUmurBaik = $jmlUmur/count($dataBaik);
         
         $jmlUmur = 0;
         foreach($dataResikoLebih as $d){
             $jmlUmur += $d->age;
         }
         $meanUmurResikoLebih = $jmlUmur/count($dataResikoLebih);
 
         $jmlUmur = 0;
         foreach($dataLebih as $d){
             $jmlUmur += $d->age;
         }
         $meanUmurLebih = $jmlUmur/count($dataLebih);
 
         $jmlUmur = 0;
         foreach($dataObesitas as $d){
             $jmlUmur += $d->age;
         }
         $meanUmurObesitas = $jmlUmur/count($dataObesitas);
 
         /// BB
         $jmlBB = 0;
         foreach($dataBuruk as $d){
             $jmlBB += $d->weight;
         }
         $meanBBBuruk = $jmlBB/count($dataBuruk);
 
         $jmlBB = 0;
         foreach($dataKurang as $d){
             $jmlBB += $d->weight;
         }
         $meanBBKurang = $jmlBB/count($dataKurang);
 
         $jmlBB = 0;
         foreach($dataBaik as $d){
             $jmlBB += $d->weight;
         }
         $meanBBBaik = $jmlBB/count($dataBaik);
 
         $jmlBB = 0;
         foreach($dataResikoLebih as $d){
             $jmlBB += $d->weight;
         }
         $meanBBResikoLebih = $jmlBB/count($dataResikoLebih);
 
         $jmlBB = 0;
         foreach($dataLebih as $d){
             $jmlBB += $d->weight;
         }
         $meanBBLebih = $jmlBB/count($dataLebih);
               
         $jmlBB = 0;
         foreach($dataObesitas as $d){
             $jmlBB += $d->weight;
         }
         $meanBBObesitas = $jmlBB/count($dataObesitas);
 
         /// TB
         $jmlTB = 0;
         foreach($dataBuruk as $d){
             $jmlTB += $d->length;
         }
         $meanTBBuruk = $jmlTB/count($dataBuruk);
 
         $jmlTB = 0;
         foreach($dataKurang as $d){
             $jmlTB += $d->length;
         }
         $meanTBKurang = $jmlTB/count($dataKurang);
 
         $jmlTB = 0;
         foreach($dataBaik as $d){
             $jmlTB += $d->length;
         }
         $meanTBBaik = $jmlTB/count($dataBaik);
         
         $jmlTB = 0;
         foreach($dataResikoLebih as $d){
             $jmlTB += $d->length;
         }
         $meanTBResikoLebih = $jmlTB/count($dataResikoLebih);
 
         $jmlTB = 0;
         foreach($dataLebih as $d){
             $jmlTB += $d->length;
         }
         $meanTBLebih = $jmlTB/count($dataLebih);
 
         $jmlTB = 0;
         foreach($dataObesitas as $d){
             $jmlTB += $d->length;
         }
         $meanTBObesitas = $jmlTB/count($dataObesitas);
      
         //CARI DEVIASI STANDARD
         /// a = tentukan jumlah data per atribut perclass
         /// b = n-1 => jumlah data peratribut perclass kurangi 1
         /// x = masing-masing nilai data peratribut perclass ditambahkan (jumlahkan keseleruhan anggota data(sigma))
         /// y = masing-masing nilai data per atribut perclass dipangkat 2 / kuadratkan lalu ditambahkan (jumlahkan keselruhan anggota (sigma))
         /// z = hasil dari perhitungan data peratribut perclass tadi di pangkatkan 2 (kuadratkan).
         /// (((a) x (y)) - (x^2)) / (a x b)
         
         //UMUR
         ///Buruk
         $x = 0;
         $y = 0;
         $sigma=0;
         foreach($dataBuruk as $d){
             $x += $d->age;
             $y += $d->age * $d->age;
             $sigma += ($d->age - $meanUmurBuruk)*($d->age - $meanUmurBuruk);
 
         }
         $stdevUmurBuruk = sqrt($sigma / (count($dataBuruk)-1));
         ///Kurang
         $x = 0;
         $y = 0;
         $sigma=0;
         foreach($dataKurang as $d){
             $x += $d->age;
             $y += $d->age * $d->age;
             $sigma += ($d->age - $meanUmurKurang)*($d->age - $meanUmurKurang);
 
         }
         $stdevUmurKurang = sqrt($sigma / (count($dataKurang)-1));
         ///Baik
         $x = 0;
         $y = 0;
         $sigma=0;
         foreach($dataBaik as $d){
             $x += $d->age;
             $y += $d->age * $d->age;
             $sigma += ($d->age - $meanUmurBaik)*($d->age - $meanUmurBaik);
 
         }
         $stdevUmurBaik = sqrt($sigma / (count($dataBaik)-1));
         ///ResikoLebih
         $x = 0;
         $y = 0;
         $sigma=0;
         foreach($dataResikoLebih as $d){
             $x += $d->age;
             $y += $d->age * $d->age;
             $sigma += ($d->age - $meanUmurResikoLebih)*($d->age - $meanUmurResikoLebih);
         }
         $stdevUmurResikoLebih = sqrt($sigma / (count($dataResikoLebih)-1));
         ///Lebih
         $x = 0;
         $y = 0;
         $sigma=0;
         foreach($dataLebih as $d){
             $x += $d->age;
             $y += $d->age * $d->age;
             $sigma += ($d->age - $meanUmurLebih)*($d->age - $meanUmurLebih);
         }
         $stdevUmurLebih = sqrt($sigma / (count($dataLebih)-1));
         ///Obesitas
         $x = 0;
         $y = 0;
         $sigma=0;
         foreach($dataObesitas as $d){
             $x += $d->age;
             $y += $d->age * $d->age;
             $sigma += ($d->age - $meanUmurObesitas)*($d->age - $meanUmurObesitas);
         }
         $stdevUmurObesitas = sqrt($sigma / (count($dataObesitas)-1));
 
         //BB
         ///Buruk
         $x = 0;
         $y = 0;
         $sigma=0;
         foreach($dataBuruk as $d){
             $x += $d->weight;
             $y += $d->weight * $d->weight;
             $sigma += ($d->weight - $meanBBBuruk)*($d->weight - $meanBBBuruk);
 
         }
         $stdevBBBuruk = sqrt($sigma / (count($dataBuruk)-1));
         ///Kurang
         $x = 0;
         $y = 0;
         $sigma=0;
         foreach($dataKurang as $d){
             $x += $d->weight;
             $y += $d->weight * $d->weight;
             $sigma += ($d->weight - $meanBBKurang)*($d->weight - $meanBBKurang);
 
         }
         $stdevBBKurang = sqrt($sigma / (count($dataKurang)-1));
         ///Baik
         $x = 0;
         $y = 0;
         $sigma=0;
         foreach($dataBaik as $d){
             $x += $d->weight;
             $y += $d->weight * $d->weight;
             $sigma += ($d->weight - $meanBBBaik)*($d->weight - $meanBBBaik);
         }
         $stdevBBBaik = sqrt($sigma / (count($dataBaik)-1));
         ///ResikoLebih
         $x = 0;
         $y = 0;
         $sigma=0;
         foreach($dataResikoLebih as $d){
             $x += $d->weight;
             $y += $d->weight * $d->weight;
             $sigma += ($d->weight - $meanBBResikoLebih)*($d->weight - $meanBBResikoLebih);
         }
         $stdevBBResikoLebih = sqrt($sigma / (count($dataResikoLebih)-1));
         ///Lebih
         $x = 0;
         $y = 0;
         $sigma=0;
         foreach($dataLebih as $d){
             $x += $d->weight;
             $y += $d->weight * $d->weight;
             $sigma += ($d->weight - $meanBBLebih)*($d->weight - $meanBBLebih);
         }
         $stdevBBLebih = sqrt($sigma / (count($dataLebih)-1));
         ///Obesitas
         $x = 0;
         $y = 0;
         $sigma=0;
         foreach($dataObesitas as $d){
             $x += $d->weight;
             $y += $d->weight * $d->weight;
             $sigma += ($d->weight - $meanBBObesitas)*($d->weight - $meanBBObesitas);
         }
         $stdevBBObesitas = sqrt($sigma / (count($dataObesitas)-1));
 
         //TB
         ///Buruk
         $x = 0;
         $y = 0;
         $sigma=0;
         foreach($dataBuruk as $d){
             $x += $d->length;
             $y += $d->length * $d->length;
             $sigma += ($d->length - $meanTBBuruk)*($d->length - $meanTBBuruk);
         }
         $stdevTBBuruk = sqrt($sigma / (count($dataBuruk)-1));
         ///Kurang
         $x = 0;
         $y = 0;
         $sigma=0;
         foreach($dataKurang as $d){
             $x += $d->length;
             $y += $d->length * $d->length;
             $sigma += ($d->length - $meanTBKurang)*($d->length - $meanTBKurang);
         }
         $stdevTBKurang = sqrt($sigma / (count($dataKurang)-1));
         ///Baik
         $x = 0;
         $y = 0;
         $sigma=0;
         foreach($dataBaik as $d){
             $x += $d->length;
             $y += $d->length * $d->length;
             $sigma += ($d->length - $meanTBBaik)*($d->length - $meanTBBaik);
         }
         $stdevTBBaik = sqrt($sigma / (count($dataBaik)-1));
         ///ResikoLebih
         $x = 0;
         $y = 0;
         $sigma=0;
         foreach($dataResikoLebih as $d){
             $x += $d->length;
             $y += $d->length * $d->length;
             $sigma += ($d->length - $meanTBResikoLebih)*($d->length - $meanTBResikoLebih);
         }
         $stdevTBResikoLebih = sqrt($sigma / (count($dataResikoLebih)-1));
         ///Lebih
         $x = 0;
         $y = 0;
         $sigma=0;
         foreach($dataLebih as $d){
             $x += $d->length;
             $y += $d->length * $d->length;
             $sigma += ($d->length - $meanTBLebih)*($d->length - $meanTBLebih);
         }
         $stdevTBLebih = sqrt($sigma / (count($dataLebih)-1));
         ///Obesitas
         $x = 0;
         $y = 0;
         $sigma=0;
         foreach($dataObesitas as $d){
             $x += $d->length;
             $y += $d->length * $d->length;
             $sigma += ($d->length - $meanTBObesitas)*($d->length - $meanTBObesitas);
         }
         $stdevTBObesitas = sqrt($sigma / (count($dataObesitas)-1));
 
         //RUMUS GAUSSIAN
        //  $age = $request->age;
        //  $weight =$request->weight;
        //  $length = $request->length;
 
         //Umur
         //P(Umur|Buruk)
         $probUmurBuruk = (1/sqrt(2 * 3.14  * $stdevUmurBuruk)) * (exp(-( pow( $age - $meanUmurBuruk ,2) / (2 * pow($stdevUmurBuruk,2)))));
         //P(Umur|Kurang)
         $probUmurKurang = (1/sqrt(2 * 3.14  * $stdevUmurKurang)) * (exp(-( pow( $age - $meanUmurKurang ,2) / (2 * pow($stdevUmurKurang,2)))));
         //P(Umur|Baik)
         $probUmurBaik = (1/sqrt(2 * 3.14  * $stdevUmurBaik)) * (exp(-( pow( $age - $meanUmurBaik ,2) / (2 * pow($stdevUmurBaik,2)))));
         //P(Umur|ResikoLebih)
         $probUmurResikoLebih = (1/sqrt(2 * 3.14  * $stdevUmurResikoLebih)) * (exp(-( pow( $age - $meanUmurResikoLebih ,2) / (2 * pow($stdevUmurResikoLebih,2)))));
         //P(Umur|Lebih)
         $probUmurLebih = (1/sqrt(2 * 3.14  * $stdevUmurLebih)) * (exp(-( pow( $age - $meanUmurLebih ,2) / (2 * pow($stdevUmurLebih,2)))));
         //P(Umur|Obesitas)
         $probUmurObesitas = (1/sqrt(2 * 3.14  * $stdevUmurObesitas)) * (exp(-( pow( $age - $meanUmurObesitas ,2) / (2 * pow($stdevUmurObesitas,2)))));
         
         //BB
         //P(BB|Buruk)
         $probBBBuruk = (1/sqrt(2 * 3.14  * $stdevBBBuruk)) * (exp(-( pow( $weight - $meanBBBuruk ,2) / (2 * pow($stdevBBBuruk,2)))));
         //P(BB|Kurang)
         $probBBKurang = (1/sqrt(2 * 3.14  * $stdevBBKurang)) * (exp(-( pow( $weight - $meanBBKurang ,2) / (2 * pow($stdevBBKurang,2)))));
         //P(BB|Baik)
         $probBBBaik = (1/sqrt(2 * 3.14  * $stdevBBBaik)) * (exp(-( pow( $weight - $meanBBBaik ,2) / (2 * pow($stdevBBBaik,2)))));
         //P(BB|ResikoLebih)
         $probBBResikoLebih = (1/sqrt(2 * 3.14  * $stdevBBResikoLebih)) * (exp(-( pow( $weight - $meanBBResikoLebih ,2) / (2 * pow($stdevBBResikoLebih,2)))));
         //P(BB|Lebih)
         $probBBLebih = (1/sqrt(2 * 3.14  * $stdevBBLebih)) * (exp(-( pow( $weight - $meanBBLebih ,2) / (2 * pow($stdevBBLebih,2)))));
         //P(BB|Obesitas)
         $probBBObesitas = (1/sqrt(2 * 3.14  * $stdevBBObesitas)) * (exp(-( pow( $weight - $meanBBObesitas ,2) / (2 * pow($stdevBBObesitas,2)))));
 
         //TB
         //P(TB|Buruk)
         $probTBBuruk = (1/sqrt(2 * 3.14  * $stdevTBBuruk)) * (exp(-( pow( $length - $meanTBBuruk ,2) / (2 * pow($stdevTBBuruk,2)))));
         //P(TB|Kurang)
         $probTBKurang = (1/sqrt(2 * 3.14  * $stdevTBKurang)) * (exp(-( pow( $length - $meanTBKurang ,2) / (2 * pow($stdevTBKurang,2)))));
         //P(TB|Baik)
         $probTBBaik = (1/sqrt(2 * 3.14  * $stdevTBBaik)) * (exp(-( pow( $length - $meanTBBaik ,2) / (2 * pow($stdevTBBaik,2)))));
         //P(TB|ResikoLebih)
         $probTBResikoLebih = (1/sqrt(2 * 3.14  * $stdevTBResikoLebih)) * (exp(-( pow( $length - $meanTBResikoLebih ,2) / (2 * pow($stdevTBResikoLebih,2)))));
         //P(TB|Lebih)
         $probTBLebih = (1/sqrt(2 * 3.14  * $stdevTBLebih)) * (exp(-( pow( $length - $meanTBLebih ,2) / (2 * pow($stdevTBLebih,2)))));
         //P(TB|Obesitas)
         $probTBObesitas = (1/sqrt(2 * 3.14  * $stdevTBObesitas)) * (exp(-( pow( $length - $meanTBObesitas ,2) / (2 * pow($stdevTBObesitas,2)))));
 
         //PERHITUNGAN DISKRIT
         $jmlLakilakiBuruk = AdminBaby::get()->where('gender','Laki-laki')->where('status','Buruk')->count();
         $jmlLakilakiKurang = AdminBaby::get()->where('gender','Laki-laki')->where('status','Kurang')->count();
         $jmlLakilakiBaik = AdminBaby::get()->where('gender','Laki-laki')->where('status','Baik')->count();
         $jmlLakilakiResikoLebih = AdminBaby::get()->where('gender','Laki-laki')->where('status','ResikoLebih')->count();
         $jmlLakilakiLebih = AdminBaby::get()->where('gender','Laki-laki')->where('status','Lebih')->count();
         $jmlLakilakiObesitas = AdminBaby::get()->where('gender','Laki-laki')->where('status','Obesitas')->count();
 
         $jmlPerempuanBuruk = AdminBaby::get()->where('gender','Perempuan')->where('status','Buruk')->count();
         $jmlPerempuanKurang = AdminBaby::get()->where('gender','Perempuan')->where('status','Kurang')->count();
         $jmlPerempuanBaik = AdminBaby::get()->where('gender','Perempuan')->where('status','Baik')->count();
         $jmlPerempuanResikoLebih = AdminBaby::get()->where('gender','Perempuan')->where('status','ResikoLebih')->count();
         $jmlPerempuanLebih = AdminBaby::get()->where('gender','Perempuan')->where('status','Lebih')->count();
         $jmlPerempuanObesitas = AdminBaby::get()->where('gender','Perempuan')->where('status','Obesitas')->count();
 
         $jmlBuruk = AdminBaby::get()->where('status','Buruk')->count();
         $jmlKurang = AdminBaby::get()->where('status','Kurang')->count();
         $jmlBaik = AdminBaby::get()->where('status','Baik')->count();
         $jmlResikoLebih = AdminBaby::get()->where('status','ResikoLebih')->count();
         $jmlLebih = AdminBaby::get()->where('status','Lebih')->count();
         $jmlObesitas = AdminBaby::get()->where('status','Obesitas')->count();
 
         if($gender == 'Laki-laki'){
             $probGenderBuruk = $jmlLakilakiBuruk / $jmlBuruk;
             $probGenderKurang = $jmlLakilakiKurang / $jmlKurang;
             $probGenderBaik = $jmlLakilakiBaik / $jmlBaik;
             $probGenderResikoLebih = $jmlLakilakiResikoLebih / $jmlResikoLebih;
             $probGenderLebih = $jmlLakilakiLebih / $jmlLebih;
             $probGenderObesitas = $jmlLakilakiObesitas / $jmlObesitas;
         }        
         
         if($gender == 'Perempuan'){
             $probGenderBuruk = $jmlPerempuanBuruk / $jmlBuruk;
             $probGenderKurang = $jmlPerempuanKurang / $jmlKurang;
             $probGenderBaik = $jmlPerempuanBaik / $jmlBaik;
             $probGenderResikoLebih = $jmlPerempuanResikoLebih / $jmlResikoLebih;
             $probGenderLebih = $jmlPerempuanLebih / $jmlLebih;
             $probGenderObesitas = $jmlPerempuanObesitas / $jmlObesitas;
         }        
 
         //LIKELIHOOD
         ///Likelihood Buruk
         $likelihoodBuruk = $probUmurBuruk * $probBBBuruk * $probTBBuruk * $probGenderBuruk;
       
         ///Likelihood Kurang
         $likelihoodKurang = $probUmurKurang * $probBBKurang * $probTBKurang * $probGenderKurang;
         
         ///Likelihood Baik
         $likelihoodBaik = $probUmurBaik * $probBBBaik * $probTBBaik * $probGenderBaik;
         
         ///Likelihood ResikoLebih
         $likelihoodResikoLebih = $probUmurResikoLebih * $probBBResikoLebih * $probTBResikoLebih * $probGenderResikoLebih;
      
         ///Likelihood Lebih
         $likelihoodLebih = $probUmurLebih * $probBBLebih * $probTBLebih * $probGenderLebih;
      
         ///Likelihood Obesitas
         $likelihoodObesitas = $probUmurObesitas * $probBBObesitas * $probTBObesitas * $probGenderObesitas;
 
         //KALLIKAN PROBABILITAS
         $probabilitasBuruk = $likelihoodBuruk * $probabilitasKelasBuruk;
         $probabilitasKurang = $likelihoodKurang * $probabilitasKelasKurang;
         $probabilitasBaik = $likelihoodBaik * $probabilitasKelasBaik;
         $probabilitasResikoLebih = $likelihoodResikoLebih * $probabilitasKelasResikoLebih;
         $probabilitasLebih = $likelihoodLebih * $probabilitasKelasLebih;
         $probabilitasObesitas = $likelihoodObesitas * $probabilitasKelasObesitas;
         
         //HASIL STATUS GIZI
         $highestProbability = 0;
         $LabelProbabilty = "";

         $max_arr= [
            $probabilitasBuruk,
            $probabilitasKurang,
            $probabilitasBaik,
            $probabilitasResikoLebih,
            $probabilitasLebih,
            $probabilitasObesitas,
        ];

        $max = max($max_arr);

        //HASIL STATUS GIZI
        $highestProbability = 0;
        $LabelProbabilty = "";
        if ($probabilitasBuruk == $max )
        {
            $highestProbability = $probabilitasBuruk;
            $LabelProbabilty = "Buruk";
        }
        else if($probabilitasKurang == $max)
        {
            $highestProbability = $probabilitasKurang;
            $LabelProbabilty = "Kurang";
        }
        else if($probabilitasBaik == $max)
        {
            $highestProbability = $probabilitasBaik;
            $LabelProbabilty = "Baik";
        }
        else if($probabilitasResikoLebih == $max)
        {
            $highestProbability = $probabilitasResikoLebih;
            $LabelProbabilty = "ResikoLebih";
        }
        else if($probabilitasLebih == $max)
        {
            $highestProbability = $probabilitasLebih;
            $LabelProbabilty = "Lebih";
        }
        else if($probabilitasObesitas == $max)
        {
            $highestProbability = $probabilitasObesitas;
            $LabelProbabilty = "Obesitas";
        }
 
         // dd($probabilitasKurang, $probabilitasBaik, $probabilitasLebih);
 
         ///MASUKAN HASIL NAIVE BAYES KEDATABASE
        //  $baby->status = $LabelProbabilty;

        // dd($probabilitasKurang, $probabilitasBaik, $probabilitasLebih);

        ///MASUKAN HASIL NAIVE BAYES KEDATABASE
        return $LabelProbabilty;
    }

}
