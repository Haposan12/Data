@extends('layouts.app')

@section('content')
<?php
    
    
    if(!empty($filePath)){
        $userDoc = "E:\\Tugas Akhir\\PorterWeb\\web\\".$filePath;
        $text = read_file_docx($userDoc);    
    }

?>
<div class="search" style="text-align: center; margin-top: 50px">
	<h3>Stemming Bahasa Indonesia</h3>
	<br>

    <form method="POST" enctype="multipart/form-data" action="{{ route('stem') }}">
        {{ csrf_field() }}
       
        <textarea name="keyword" value="" id="search" style="width: 800px;height: 200px"><?php if(!empty($text)){echo $text;}else if(!empty($file)){echo $file;}?></textarea>
        <button type="submit" id="button" class="button-primary" style="height: 40px;">Stem</button>
    </form>

    <!-- <textarea name="hasil" style="width: 400px;height: 250px">
        
        <?php  
            
            if(!empty($kata)){

                foreach ($kata as $key) {
                    echo $key."\n";
                }

            }
            
        ?>
            
    </textarea> -->

    <textarea name="hasil" style="width: 800px;height: 250px">
        <?php  
            
            if(!empty($hasil2)){

                foreach ($hasil2 as $key) {
                    # code...
                    echo $key." ";
                }
            }
            
        ?>
            
    </textarea>


    <?php if(!empty($execution_time) && !empty($kata)){
        echo "</br>";
        echo "Jumlah kata dalam teks: ".count($kata)." kata";
        echo "</br>";
        //echo "Waktu: ".$execution_time." second";
    }?>
</div>
<br><br>
<div style="text-align: justify; margin-left: 100px">



	<?php
	
	

    function read_file_docx($filename){

        $striped_content = '';
        $content = '';

        if(!$filename || !file_exists($filename)) return false;

        $zip = zip_open($filename);

        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {

            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

            if (zip_entry_name($zip_entry) != "word/document.xml") continue;

            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

            zip_entry_close($zip_entry);
        }// end while

        zip_close($zip);

        //echo $content;
        //echo "<hr>";
        //file_put_contents('1.xml', $content);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $striped_content = strip_tags($content);

        return $striped_content;
    }

    

	function getAffix($word, $hasil2){
		 $awalan = array("di","ke","se","ter","me","mem","men","meng","meny","pe","pem","pen","peng","peny",
          "ber","bel","be","per","pel","pe");

        $akhiran = array("i","kan","an","kah","lah","tah","pun","ku","mu","nya");

        $indexAwalan = array();
        $indexAkhiran = array();

        
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
                $root = substr($word, $indexAwalan[$x]);
                $prefix = substr($word,0,$indexAwalan[$x]); 
                if($root == $hasil2){
                    $a = $prefix;
                }else if($prefix == "meng" || $prefix == "peng"){
                    if("k".$root == $hasil2){
                        $a = $prefix;
                    }   
                }else if($prefix == "meny" || $prefix == "peny"){
                    if("s".$root == $hasil2){
                        $a = $prefix;
                    }   
                }else if($prefix == "mem" || $prefix == "pem"){
                    if("p".$root == $hasil2){
                        $a = $prefix;
                    }   
                }else if($prefix == "men" || $prefix == "pen"){
                    if("t".$root == $hasil2){
                        $a = $prefix;
                    }   
                }else{
                    if(count($indexAkhiran) != 0){
                        for($k=0;$k<count($indexAwalan);$k++){
                            for($y=0;$y<count($indexAkhiran);$y++){
                                $root = substr($word, $indexAwalan[$k], -$indexAkhiran[$y]);
                                $prefix2 = substr($word,0,$indexAwalan[$k]); 
                                $suffix = substr($word, strlen($word)-$indexAkhiran[$y], strlen($word));
                                    
                                if($root == $hasil2){
                                    $a = $prefix2;
                                    $b = $suffix;
                                }else{
                                    if($prefix2 == "meny" || $prefix2 == "peny"){
                                        if("s".$root == $hasil2){
                                            $a = $prefix2;
                                            $b = $suffix;
                                        }
                                    }else if($prefix2 == "men" || $prefix2 == "pen"){
                                        if("t".$root == $hasil2){
                                            $a = $prefix2;
                                            $b = $suffix;
                                        }
                                    }else if($prefix2 == "mem" || $prefix2 == "pem"){
                                        if("p".$root == $hasil2){
                                            $a = $prefix2;
                                            $b = $suffix;
                                        }
                                    }else if($prefix2 == "meng" || $prefix2 == "peng"){
                                        if("k".$root == $hasil2){
                                            $a = $prefix2;
                                            $b = $suffix;
                                        }
                                    }
                                }
                            }
                        }
                            
                    }
                }
            }
        }else{
            for($x=0;$x<count($indexAkhiran);$x++){
                $suffix = substr($word, strlen($word)-$indexAkhiran[$x], strlen($word));
                $root = substr($word, 0, -$indexAkhiran[$x]);
                if($root == $hasil2){
                    $b = $suffix;
                }
            }
        }
            




        if(!empty($a)){ 
            echo "Awalan : ".$a."-";
        }else{
            echo "Awalan : -";
        }
        echo "<br/>";

        if(!empty($b)){
            if($b == "ku" || $b == "mu" || $b == "nya"){
                echo "Kata ganti kepunyaan : "."-".$b;
                echo "<br/>";
                echo "Partikel : -";
                echo "<br/>";
                echo "Akhiran : -";
            }else if($b == "lah" || $b == "kah" || $b == "tah" || $b == "pun"){
                echo "Kata ganti kepunyaan : -";
                echo "<br/>";
                echo "Partikel : "."-".$b;
                echo "<br/>";
                echo "Akhiran : -";
            }else{
                echo "Kata ganti kepunyaan : -";
                echo "<br/>";
                echo "Partikel : -";
                echo "<br/>";
                echo "Akhiran : "."-".$b;
            }
                
        }else{
            echo "Kata ganti kepunyaan : -";
            echo "<br/>";
            echo "Partikel : -";
            echo "<br/>";
            echo "Akhiran : -";
        }
        echo "<br/>";
        echo '<strong>'."Kata dasar :"." ".$hasil2.'</strong>';

            
	}


			
		
	?>
</div>

<script type="text/javascript">
		
	
</script>