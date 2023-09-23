<?php 
session_start();

$errorMsg = null;
$successMsg = null;

//To move between directories from your current directory

// For PHP < 5.3 use:

// $upOne = realpath(dirname(__FILE__) . '/..');
// In PHP 5.3 to 5.6 use:

// $upOne = realpath(__DIR__ . '/..');
// In PHP >= 7.0 use:

// $upOne = dirname(__DIR__, 1);




if($_SERVER["REQUEST_METHOD"] === "POST"){
    
   // pass each input into a variable 

    $file = $_FILES['csfile'];

    $type =  $_POST['type'];

    //validate form inputs
    $validate = validateForm($file, $type);

    

    if($validate !== true){
        $errorMsg = $validate;
        redirect('error',$errorMsg);
    }

    // prepare file for upload
    $filename = $_FILES['csfile']['name'];
    $targetFolder = dirname( __FILE__, 2).'/uploadedCSV/';
    $filepath = $targetFolder.basename($filename);
   

    //uploadFile

    if(!uploadFile($file, $filepath)){
        $errorMsg = "<span style='color: red'>Error occured while uploading file</span>";
        redirect('error',$errorMsg);

    }

    dd(file($filepath,FILE_SKIP_EMPTY_LINES));

    switch ($type) {
        case 1:
            $headers = getCSVHeaders($filepath);
            $successMsg = ['type' => 1, 'headers' => $headers] ;
            break;
        case 2:
            $content = extractAllCSVcontent($filepath);
            $successMsg = ['type' => 2, 'content' => $content];
            break;
        case 3:
            $headers = getCSVHeaders($filepath);
            $allContents = extractAllCSVcontent($filepath);
            $successMsg = ['type' => 3, 'headers' =>  $headers, 'content' => $allContents];
            break;
        
        default:
            $headers = getCSVHeaders($filepath);
            $allContents = extractAllCSVcontent($filepath);
            $successMsg = ['type' => 3, 'headers' =>  $headers, 'content' => $allContents];
            break;
    }

    redirect('success',$successMsg);


}

function uploadFile($file, $filepath) : bool {
    
    if(move_uploaded_file($file["tmp_name"], $filepath)){
        return true;
    }

    return false;
}

// this is a helper function for debuging response 
function dd($data) {
    die('<pre>'. json_encode( json_decode(json_encode($data), true), true). '</pre>');
}

function validateForm($file, $type){
    //check if input submited are empty
    if(!isset($type) && !isset($file)){
        return "<span style='color:red'> all fields are required</span>";
    }
    if($file['size'] > 20024){
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

   
    if(is_array($resData)){
        $data = json_encode($resData);
    }else{
        $data = $resData;
    }

    $url = $_SERVER['HTTP_ORIGIN']. '/projects/csv_extractor/';   

    $urlparam = '?'. $resType.'='.$resType;

    $page = ($resType == 'success')? 'resultPage.php' : 'index.php' ;
    
    $url.= $page. $urlparam;

    if($resType == 'error'){
        $_SESSION['error'] =  $data;
    }else{
        $_SESSION['success'] =  $data;
    }


    header("Location: $url");
    exit();
}