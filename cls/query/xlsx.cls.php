<?php
class query_xlsx_cls extends query_cls {
   
    // public function config($allAssoc = false, $title = "Untitled" ,$filename = "Untitled" , $fieltype = "csv") {
       
    //     header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     // header('Content-Type:text/html; charset=UTF-8'); 
    //     header('Accept-Language:en-US,en;q=0.8,fa;q=0.6');
    //     header("Content-Disposition: attachment; filename=$filename.$fieltype");  
    //     header('Content-Transfer-Encoding: binary');
    //     header("Pragma: no-cache"); 
    //     header("Expires: 0");
        
           
    //     //define separator (defines columns in excel & tabs in word)
    //     $sep = ($fieltype == "csv") ? "\t" : "\t\t\t";
    //     $newline = "\n";

    //     echo $sep;
    //     echo $title;
    //     echo $newline;
    //     if(is_array($allAssoc) && !empty($allAssoc) && isset($allAssoc[current(array_keys($allAssoc))])){            
    //         foreach ($allAssoc[current(array_keys($allAssoc))] as $key => $value) {
    //             print(_($key));
    //             echo  $sep;
    //         }
    //         print($newline); 

    //         foreach ($allAssoc as $key => $value) {
    //             if(is_array($value)){

    //                 foreach ($value as $k => $v) {
    //                         print(($v) . $sep);
    //                 }
    //                 print $newline;
    //             }
    //         }
    //         print $newline;
    //     }
    //     exit();
    // }

     public function config($allAssoc = false, $title = "Untitled" ,$filename = "Untitled" , $fieltype = "csv") {

        $type = $fieltype;
        $filename = $filename;
        $data = $allAssoc;

        // disable caching
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}.{$type}");
        header("Content-Transfer-Encoding: binary");


        if (count($data) == 0 || !$data || empty($data)) {
            echo  null;
            // die();
        }

        ob_start();

        $df = fopen("php://output", 'w');

        fputcsv($df, array_keys(reset($data)));

        foreach ($data as $row) {
            fputcsv($df, $row);
        }

        fclose($df);
        echo ob_get_clean();

        die();
    }
}
?>