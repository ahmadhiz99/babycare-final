<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Baby;
use App\Models\BabyUserDataTransform;
use App\Models\AdminBabyDataTransform;
use App\Models\AdminBabyRule;
use App\Models\AdminRule;
use App\Models\AdminBaby;
use App\Models\Report;
use App\Models\ZscoreLakilaki;
use App\Models\ZscorePerempuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class BabyController extends Controller
{

    // protected function validator(array $data)
    // {
    //     return Validator::make($data, [
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    //         'favorite_color' => 'required',
    //         'password' => ['required', 'string', 'min:8', 'confirmed'],
    //     ]);
    // }

    function addData(Request $request){

        $request->validate([
            'name' => ['required', 'string'],
            'age' =>['required', 'numeric'],
            'length'=>['required', 'numeric'],
            'weight'=>['required', 'numeric'],
            'gender'=>['required', 'string'],
        ]);

        $parent_id = Auth::id();
        $baby= new Baby;
        $baby->name = $request->name;
        $baby->parent = $parent_id;
        $baby->age = $request->age;
        $baby->length = $request->length;
        $baby->weight = $request->weight;
        $baby->gender = $request->gender;
        // $baby->status = $request->status;

        // Menarik data dulu data dari baby total [data baby inputan user + data training admin]
        // $baby = new Baby;

        // Mencar.diknwlk==i 
        $dataTraining = new AdminBaby;
        // $data = Baby::select('name','surname')->where('status','Lebih');
        // $data = AdminBaby::find("Lebih");
        // $data = AdminBaby::whereStatus('Lebih');
        // $data = Baby::where('status', '=', 'Lebih')->first();
        // $data = AdminBaby::with('status')->whereIn('');
        $data = AdminBaby::all();
        // $dataLebih = $data->status("Lebih");

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
        $age = $request->age;
        $weight =$request->weight;
        $length = $request->length;

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

        if($request->gender == 'Laki-laki'){
            $probGenderBuruk = $jmlLakilakiBuruk / $jmlBuruk;
            $probGenderKurang = $jmlLakilakiKurang / $jmlKurang;
            $probGenderBaik = $jmlLakilakiBaik / $jmlBaik;
            $probGenderResikoLebih = $jmlLakilakiResikoLebih / $jmlResikoLebih;
            $probGenderLebih = $jmlLakilakiLebih / $jmlLebih;
            $probGenderObesitas = $jmlLakilakiObesitas / $jmlObesitas;
        }        
        
        if($request->gender == 'Perempuan'){
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
        $baby->status = $LabelProbabilty;
        
        if($baby->save()){
            $idUser = $baby->id;
            $idParent =$parent_id;;
            
            $report_monthly = 0;
            $report_monthly_total = 0;
            
            $get_report_monthly_total =Report::where('parent_id', '=', $idParent)->orderBy('id','DESC')->first();
    
            if($get_report_monthly_total != null){
                $report_monthly_total = $get_report_monthly_total->report_monthly_total + 1; //aslinya get count data yang ada
            }else{
                $report_monthly_total = 1;
            }
    
            $z_score = $this->z_score($request->age, $request->weight, $request->length, $request->gender);
            
            $report= new Report;
            $report->name = $request->name;
            $report->baby_id = $baby->id;
            $report->parent_id = $parent_id;
            $report->report_monthly =1; 
            $report->report_monthly_total = $report_monthly_total;
            $report->age = $request->age;
            $report->length = $request->length;
            $report->weight = $request->weight;
            $report->gender = $request->gender;
            $report->status = $LabelProbabilty;

            $report->prob_buruk = $probabilitasBuruk;
            $report->prob_kurang = $probabilitasKurang;
            $report->prob_baik = $probabilitasBaik;
            $report->prob_resikolebih = $probabilitasResikoLebih;
            $report->prob_lebih = $probabilitasLebih;
            $report->prob_obesitas = $probabilitasObesitas;

            $report->z_score = $z_score;

            $report->save();

            return redirect()->back()->with('success','Data anak berhasil ditambahkan');
        }else{
            return redirect()->back()->with('error','Terdapat data yang salah');
        }
    }

    function retrieveData($id){
        $dataBaby = Baby::find($id);
        $data = Report::where('baby_id', '=', $id)->orderBy('id','DESC')->first();
        // dd($data);
       
        // if($data!=null){
            $allBabyReport = Report::where('baby_id', '=', $id)->get();
            $data3 = [];
            foreach($allBabyReport as $b){
                array_push($data3, $b->age);
            }
            $dataWeight = [];
            foreach($allBabyReport as $b){
                array_push($dataWeight, $b->weight);
            }
            $dataLength = [];
            foreach($allBabyReport as $b){
                array_push($dataLength, $b->length);
            }

            $buruk = Report::where('baby_id', '=', $id)->where('status', '=', 'Buruk')->count();
            $baik = Report::where('baby_id', '=', $id)->where('status', '=', 'Baik')->count();
            $kurang = Report::where('baby_id', '=', $id)->where('status', '=', 'Kurang')->count();
            $resikolebih = Report::where('baby_id', '=', $id)->where('status', '=', 'ResikoLebih')->count();
            $lebih = Report::where('baby_id', '=', $id)->where('status', '=', 'Lebih')->count();
            $obesitas = Report::where('baby_id', '=', $id)->where('status', '=', 'Obesitas')->count();
            $report_total_peranak = Report::where('baby_id', '=', $id)->orderBy('report_monthly','DESC')->first();
            
            $data2 = array( 
                'buruk' => $buruk, 
                'kurang' => $kurang, 
                'baik' => $baik, 
                'resikolebih'  => $resikolebih,
                'lebih'  => $lebih,
                'obesitas'  => $obesitas,
                'report_total_peranak' => $report_total_peranak->report_monthly
                );
            return view('users.baby',['dataBaby' => $dataBaby ,'data'=>$data,'data2'=>$data2,'data3'=>$data3, 'dataWeight'=>$dataWeight,'dataLength'=>$dataLength]);
        // }else{
        //     // $allBabyReport = Baby::where('baby_id', '=', $id)->get();

        //     $dataWeight = [];
        //     $dataLength = [];
        //     $data3 = [];

        //     $babyData = Baby::where('id', '=', $id)->first();
        //     array_push($dataWeight, $babyData->weight);
        //     array_push($dataWeight, $babyData->weight);
        //     array_push($dataLength, $babyData->length);
        //     array_push($dataLength, $babyData->length);
        //     array_push($data3, $babyData->age);


        //     $data = Baby::find($id);
        //     $kurang = Baby::where('id', '=', $id)->where('status', '=', 'Kurang')->count();
        //     $baik = Baby::where('id', '=', $id)->where('status', '=', 'Baik')->count();
        //     $lebih = Baby::where('id', '=', $id)->where('status', '=', 'Lebih')->count();
        //     $report_total_peranak = 1;

        //     $data2 = array( 'baik' => $baik, 
        //                     'kurang' => $kurang, 
        //                     'lebih'  => $lebih,
        //                     'report_total_peranak' => $report_total_peranak
        //                 );
                        
                        
        //     return view('users.baby',['dataBaby' => $dataBaby,'data'=>$data,'data2'=>$data2,'data3'=>$data3,'dataWeight'=>$dataWeight,'dataLength'=>$dataLength]);
        // }
      
    }

    function editbaby(Request $request){
        $this->validate($request, [
                'name'     => 'required',
            ]);
            
            $baby = Baby::find($request->id);
            $report = Report::where('baby_id','=',$request->id)->get();
            $baby->name = $request->name;
            foreach ($report as $report){
                $report->name = $request->name;
            }
            $baby->save();
            
        if($report->save()){
            return redirect()->back()->with('success','Data anak berhasil diedit');
        }else{
            return redirect()->back()->with('error','Terdapat yang salah');
        }

    }
    function deleteBaby(Request $request){
            $baby = Baby::find($request->id);
            $report = Report::where('baby_id','=',$request->id)->get();
            // dd($request->id);
        if($baby->delete()){
            $id = Auth::id();
            $data = Baby::all()->where('parent',$id);
            return view('users.babyDetail',['data'=>$data])->with('success','Data anak berhasil dihapus');
        }else{
            return redirect()->back()->with('error','Terdapat yang salah');
        }

    }


    function z_score($age, $weight, $length, $gender){
        if($gender=="Laki-laki"){
            if($age<24 && $length<=110){
                $data = ZscoreLakilaki::where('length',$length)->orderBy('id','ASC')->first();
                if($weight < $data->min_3_sd ){
                    return "< - 3 SD";
                }
                if($weight >= $data->min_3_sd && $weight < $data->min_2_sd){
                    return "- 3 SD < - 2 SD";
                }
                if($weight >= $data->min_2_sd && $weight <= $data->plus_1_sd){
                    return "- 2 SD <  1 SD";
                }
                if($weight > $data->plus_1_sd && $weight <= $data->plus_2_sd){
                    return "> 1 SD <  2 SD";
                }
                if($weight > $data->plus_2_sd && $weight <= $data->plus_3_sd){
                    return "> 2 SD <  3 SD";
                }
                if($weight > $data->plus_3_sd){
                    return "> 3 SD";
                }
                
            }else if($age>=24 && $length<=120){
                $data = ZscoreLakilaki::where('length',$length)->orderBy('id','DESC')->first();
                if($weight < $data->min_3_sd ){
                    return "< - 3 SD";
                }
                if($weight >= $data->min_3_sd && $weight < $data->min_2_sd){
                    return "- 3 SD < - 2 SD";
                }
                if($weight >= $data->min_2_sd && $weight <= $data->plus_1_sd){
                    return "- 2 SD <  1 SD";
                }
                if($weight > $data->plus_1_sd && $weight <= $data->plus_2_sd){
                    return "> 1 SD <  2 SD";
                }
                if($weight > $data->plus_2_sd && $weight <= $data->plus_3_sd){
                    return "> 2 SD <  3 SD";
                }
                if($weight > $data->plus_3_sd){
                    return "> 3 SD";
                }

            }else{
                    return "Z-Score Tidak Tersedia";
            }
            
            // echo "<br>";
            // echo $weight;
            // echo "<br>";
            // echo $length;
            // dd($data);
        }
        if($gender=="Perempuan"){
            if($age<24 && $length<=110){
                $data = ZscorePerempuan::where('length',$length)->orderBy('id','ASC')->first();
                if($weight < $data->min_3_sd ){
                    return "< - 3 SD";
                }
                if($weight >= $data->min_3_sd && $weight < $data->min_2_sd){
                    return "- 3 SD < - 2 SD";
                }
                if($weight >= $data->min_2_sd && $weight <= $data->plus_1_sd){
                    return "- 2 SD <  1 SD";
                }
                if($weight > $data->plus_1_sd && $weight <= $data->plus_2_sd){
                    return "> 1 SD <  2 SD";
                }
                if($weight > $data->plus_2_sd && $weight <= $data->plus_3_sd){
                    return "> 2 SD <  3 SD";
                }
                if($weight > $data->plus_3_sd){
                    return "> 3 SD";
                }
                
            }else if($age>=24 && $length<=120){
                $data = ZscorePerempuan::where('length',$length)->orderBy('id','DESC')->first();
                if($weight < $data->min_3_sd ){
                    return "< - 3 SD";
                }
                if($weight >= $data->min_3_sd && $weight < $data->min_2_sd){
                    return "- 3 SD < - 2 SD";
                }
                if($weight >= $data->min_2_sd && $weight <= $data->plus_1_sd){
                    return "- 2 SD <  1 SD";
                }
                if($weight > $data->plus_1_sd && $weight <= $data->plus_2_sd){
                    return "> 1 SD <  2 SD";
                }
                if($weight > $data->plus_2_sd && $weight <= $data->plus_3_sd){
                    return "> 2 SD <  3 SD";
                }
                if($weight > $data->plus_3_sd){
                    return "> 3 SD";
                }

            }else{
                    return "Z-Score Tidak Tersedia";
            }

            // echo "<br>";
            // echo $weight;
            // echo "<br>";
            // echo $length;
            // dd($data);
        }
    }
}
