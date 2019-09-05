<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function getRoot($word){
        $query = DB::table('kata_dasar')->where('kata', $word)->get();

        $awalan = array("di","ke","se","ter","te","me","mem","men","meng","meny","pe","pem","pen","peng","peny",
          "ber","bel","be","per","pel","pe");

        $akhiran = array("i","kan","an","kah","lah","tah","pun","ku","mu","nya");

        $indexAwalan = array();
        $indexAkhiran = array();
        $root = array();
        $hasil = array();
        $hasil2 = array();

        foreach ($query as $key => $row) {
            # code...
            $kataId = $row->id;
        }

        if(!empty($kataId)){
           $hasil2 = $word; 
           return $hasil2;
        }else{
            for($x = 0;$x<5;$x++){
                $start = substr($word, 0,$x);
                if(in_array($start, $awalan)){
                    array_push($indexAwalan, $x);
                }
            }
            for($x=1;$x<4;$x++){
                $l = strlen($word);
                $end = substr($word, $l-$x, $l);
                if(in_array($end, $akhiran)){
                    array_push($indexAkhiran, $x);
                }
            }

            if(count($indexAwalan) != 0){
                for($x=0;$x<count($indexAwalan);$x++){
                    $prefix = substr($word,0,$indexAwalan[$x]);
                    $key = substr($word, $indexAwalan[$x], strlen($word));
                    if($prefix == "meng" || $prefix == "peng"){
                        $key1 = "k".$key;
                        array_push($root, $key1);
                        array_push($root, $key);
                    }else if($prefix == "men" || $prefix == "pen"){
                        $key1 = "t".$key;
                        array_push($root, $key1);
                        array_push($root, $key);
                    }else if($prefix == "meny" || $prefix == "peny"){
                        $key1 = "s".$key;
                        array_push($root, $key1);
                        array_push($root, $key);
                    }else if($prefix == "mem" || $prefix == "pem"){
                        $key1 = "p".$key;
                        array_push($root, $key1);
                        array_push($root, $key);
                    }else{
                        $key = $key;
                        array_push($root,$key);
                    }
                }
                if(count($indexAkhiran) != 0){
                    foreach ($root as $kata) {
                        for($x=0;$x<count($indexAkhiran);$x++){
                            $key = substr($kata,0,strlen($kata)-$indexAkhiran[$x]);
                            array_push($hasil,$key);
                        }
                    }
                }else{
                    $hasil = $root;
                }
            }
                
            if(count($indexAkhiran) != 0){
                for($x=0;$x<count($indexAkhiran);$x++){
                    $key = substr($word,0,strlen($word)-$indexAkhiran[$x]);
                    array_push($hasil,$key);
                    array_push($hasil,$word);
                }
            }else{
                array_push($hasil,$word);
            }
            

        
            $result = array_merge($hasil,$root);

            $indexAwalan = array();
            $indexAkhiran = array();
            $root = array();
            $hasil = array();

            foreach ($result as $kata) {
                # code...
                 for($x = 0;$x<5;$x++){
                    $start = substr($kata, 0,$x);
                    if(in_array($start, $awalan)){
                        array_push($indexAwalan, $x);
                    }
                }

                if(count($indexAwalan)!=0){
                    for($x=0;$x<count($indexAwalan);$x++){
                        $prefix = substr($kata,0,$indexAwalan[$x]);
                        $key = substr($kata, $indexAwalan[$x], strlen($kata));
                        if($prefix == "meng" || $prefix == "peng"){
                            $key1 = "k".$key;
                            array_push($root, $key1);
                            array_push($root, $key);
                        }else if($prefix == "men" || $prefix == "pen"){
                            $key1 = "t".$key;
                            array_push($root, $key1);
                            array_push($root, $key);
                        }else if($prefix == "meny" || $prefix == "peny"){
                            $key1 = "s".$key;
                            array_push($root, $key1);
                            array_push($root, $key);
                        }else if($prefix == "mem" || $prefix == "pem"){
                            $key1 = "p".$key;
                            array_push($root, $key1);
                            array_push($root, $key);
                        }else{
                            $key = $key;
                            array_push($root,$key);
                        }
                    }
                }
            }

            $result = array_merge($result,$root);

            $result = array_unique($result);

            $indexAwalan = array();
            $indexAkhiran = array();
            $root = array();
            $hasil = array();

            foreach ($result as $kata) {
                # code...
                for($x = 0;$x<5;$x++){
                    $start = substr($kata, 0,$x);
                    if(in_array($start, $awalan)){
                        array_push($indexAwalan, $x);
                    }
                }
            }

            $indexAwalan = array_unique($indexAwalan);
            foreach ($result as $kata) {
                # code...
                if(count($indexAwalan) != 0){
                    foreach ($indexAwalan as $iAwalan) {
                        # code...

                        $key = substr($kata, $iAwalan, strlen($kata));
                        array_push($root,$key);
                    }
                    
                }
            }
            $root = array_unique($root);

            $result = array_merge($result,$root);

            $result = array_unique($result);



            foreach ($result as $kata ) {
                $query = DB::table('kata_dasar')->where('kata',$kata)->get();
                foreach ($query as $key) {
                    # code...
                    if($key->kata != null){
                        $hasil2= $key->kata;
                        return $hasil2;    
                    }else{
                        $hasil2 = "string";
                        return $hasil2;    
                    }
                }
            }

        }
        
        
    }   


    public function stem(Request $request){
        $start_time = microtime(true); 

        $stopword = array("yang","di","dan","itu","dengan","untuk","tidak","ini","dari","dalam","akan","pada","juga","saya","ke","karena","tersebut","bisa","ada","mereka","lebih","kata","tahun","sudah","atau","saat","oleh","menjadi","orang","ia","telah","sejak","adalah","seperti","sebagai","bahwa","dapat","para","harus","namun","kita","dua","satu","masih","hari","hanya","kepada","kami","setelah","lalu","belum","lain","dia","kalau","banyak","anda","hingga","tak","baru","ketika","saja","jalan","sekitar","secara","sementara","tapi","sangat","hal","yaitu","diri","langsung","perlu","bahkan","wib","cukup","terus","bila","mungkin","umum","terus","semakin","merupakan","sering","sendiri","atas","sedangkan","bukan","kemudian","tetapi","begitu"."lagi","justru","memang","tanpa","sekarang","maka","pun");
        $word = $request->keyword;
        // $word = strtolower($word);    

        
        $word = preg_replace("/[^a-z]+/i", "\n", "$word");

        $word = strtolower($word);
        $word = preg_replace('/\b('.implode('|',$stopword).')\b/','',$word);
        //echo $word;
        $kata = preg_split('/[\s\n]/', $word);
        $kata = array_unique($kata);

       
        $hasil = array();
        $hasil2 = array();

        foreach ($kata as $key) {
            # code...
            $hasil = $this->getRoot($key);

            array_push($hasil2, $hasil);
        }
       
         
        $execution_time = (microtime(true) - $start_time); 
        //secho " Execution time of script = ".$execution_time." sec"; 
        $file = $request->keyword;

        return view('home.stem', compact('hasil2','word','kata','execution_time','file'));
    }

    public function find(){
        
        $filePath = $_FILES["dokumen"]["name"];

        return view('home.stem',compact('filePath'));
    }

}
