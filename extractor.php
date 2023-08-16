<?php 

$errorMsg = null;
$successMsg = null;


// dd($_SERVER);


if($_SERVER["REQUEST_METHOD"] == "POST"){

    $filename = $_FILES['csfile']['name'];
    $targetFolder = 'uploadedCSV/';
    $filepath = $targetFolder.basename($filename);
   
    $file = $_FILES['csfile'];

    $type =  $_POST['type'];

    //validate form
    $validate = validateForm($file, $type);

    if($validate !== true){
        $errorMsg = $validate;
        redirect('error',$errorMsg);
    }

    //uploadFile
   
    if(!uploadFile($file, $filepath)){
        $errorMsg = "<span style='color: red'>Error occured while uploading file</span>";
        redirect('error',$errorMsg);

    }

    switch ($type) {
        case 1:
            $successMsg = ['type' => 1, 'data' => $filepath] ;
            break;
        case 2:
            $successMsg = ['type' => 2, 'data' => $filepath];
            break;
        case 3:
            $successMsg = ['type' => 3, 'data' =>  $filepath];
            break;
        
        default:
            $successMsg = ['type' => 0, 'data' =>  $filepath];
            break;
    }

    redirect('success',$successMsg);


}else{
    $errorMsg = "<span style='color:red;'>Cannot process request (invalid request method) </span>";
    redirect('error',$errorMsg);
}

function uploadFile($file, $filepath) : bool {
    
    if(move_uploaded_file($file["tmp_name"], $filepath)){
        return true;
    }

    return false;
}

function dd($data) {
    die('<pre>'. json_encode( json_decode(json_encode($data), true), true). '</pre>');
}

function validateForm($file, $type){
    //check if input submited are empty
    if(!isset($type) && !isset($file)){
        return "<span style='color:red'> all fields are required</span>";
    }
    if($file['size'] > 100){
        return  "<span style='color:red'> File size must not be greater than 100kb</span>";
    }

    if($file['type'] !== 'text/csv'){
        return  "<span style='color:red'> File must be a .CSV file</span>";
    }

    return true;

}

function getCSVHeaders($file){
    $csv = array_map("str_getcsv", file($file,FILE_SKIP_EMPTY_LINES));


    $keys = array_shift($csv);

    return $keys;

    //  dd($keys);
}

function getCSVContentOnly($file){
    $csv = array_map("str_getcsv", file($file,FILE_SKIP_EMPTY_LINES));

    //remove the headers using the array shift method
    array_shift($csv);

    // return the rest of the array 
    return $csv;
    // dd($csv);
}

function extractAllCSVcontent($file)
{

    $csv = array_map("str_getcsv", file($file,FILE_SKIP_EMPTY_LINES));


    $keys = array_shift($csv);

    $csvArrayData = [];

    foreach ($csv as $i=>$row) {

        $csv[$i] = array_combine($keys, $row);

        array_push($csvArrayData, $csv[$i]);

    }

    return $csvArrayData;

}

function redirect($resType, $resData){
    if(!in_array($resType, ['error', 'success'])){
        die("<p style='color: red; font-weight: bold'>Response type is undefined</p>");
    };

    $url = $_SERVER['HTTP_REFERER'];   
    if(is_array($resData)){
        $data = base64_encode(json_encode($resData));
    }else{
        $data = $resData;
    }

       

    $urlparam = '?'. $resType.'='.urlencode($data);

    $page = ($resType == 'success')? 'resultPage.php' : '' ;
    
    $url.= $page. $urlparam;


    header("Location: $url");
    exit();
}