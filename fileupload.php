<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.04e
 *  
 *
 **************************************************************
 *  fileupload.php
 *  Upload pictures for entries.
 *
 *************************************************************/

require_once('./Core.php');
require_once ('./lib/Templates/fileUpload.Template.php');

global $globalSqlLink, $globalUsers, $lang;
$options = new Options();
$globalUsers->checkForLogin('admin');


// ** DENY ACCESS IF UPLOAD IS NOT ALLOWED
if (($options->getpicAllowUpload() != 1) && ($_SESSION['usertype'] != "admin")) {
    reportScriptError("File uploading has been turned off in this installation.");
    exit();
}

// ** BEGIN
require(FILE_LIB_UPLOAD);
$fileUpTemplate = new fileUploadTemplate();
#--------------------------------#
# Variables
#--------------------------------#

// The name of the file field in your form.
$upload_file_name = "file_".rand(10,999999).rand(1,999);
$path = "./mugshots/";

// ACCEPT mode - if you only want to accept
// a certain type of file.
// possible file types that PHP recognizes includes:
//
// OPTIONS INCLUDE:
//  text/plain
//  image/gif
//  image/jpeg
//  image/png
	
// Accept ONLY gifs's
#$acceptable_file_types = "image/gifs";
// Accept GIF and JPEG files
$acceptable_file_types = "image/gif|image/jpeg";

// Accept ALL files
#$acceptable_file_types = "";

// If no extension is supplied, and the browser or PHP
// can not figure out what type of file it is, you can
// add a default extension - like ".jpg" or ".txt"
$default_extension = "";

// MODE: if your are attempting to upload
// a file with the same name as another file in the
// $path directory
//
// OPTIONS:
//   1 = overwrite mode
//   2 = create new with incremental extention
//   3 = do nothing if exists, highest protection
$mode = $options->getpicDupeMode();
$success = false;

if (isset($_REQUEST['submitted'])) {
    $my_uploader = new uploader($lang['ThisLanguage']);
    // OPTIONAL: set the max filesize of uploadable files in bytes
    $my_uploader->max_filesize($options->getMaxFileSize());

    // OPTIONAL: if you're uploading images, you can set the max pixel dimensions
    $my_uploader->max_image_size($options->getpicWidth(), $options->getpicHeight()); // max_image_size($width, $height)

    // UPLOAD the file
    if ($my_uploader->upload($upload_file_name, $acceptable_file_types, $default_extension)) {
        $my_uploader->save_file($path, $mode);
    }

    // RETURN RESULTS
    if ($my_uploader->error) {
        reportScriptError($my_uploader->error );
    }
    else {
        // Successful upload!
        $success = true;
        $body['file']=$my_uploader->file['name'];
    }

}
//
$body['file']= $upload_file_name;
$body['phpself'] = $_SERVER['PHP_SELF'];

$output = webheader($lang['TAB'], $lang['CHARSET'], 'upload.script.js', true);
if($success){
    $output .=$fileUpTemplate->uploadSuccessbody($body, $lang);
}
else{
    $output .=$fileUpTemplate->uploadBody($body, $lang, $options);
}
$output .= printFooter();
Display($output);

