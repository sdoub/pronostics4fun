<?php
define('VALID_ACCESS_CONFIG_',		true);
require_once("config/config.php");

session_cache_expire(60*60*1); //1 hour
session_set_cookie_params(60*60*1); //1 hour

session_start();

/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
  /**
   * Save the file to the specified path
   * @return boolean TRUE on success
   */
  function save($path) {
    $input = fopen("php://input", "r");
    $fp = @fopen($path, "w");
    if(!$fp){
      return false;
    }
    while ($data = fread($input, 1024)) {
      fwrite($fp, $data);
    }

    fclose($fp);
    fclose($input);
    return true;
  }
  function getName() {
    return $_GET['qqfile'];
  }
  function getSize() {
    $headers = apache_request_headers();
    return (int) $headers['Content-Length'];
  }
}


class QQUploadFileXhr {

  private $tmp_fp = null;

  function __construct(){
    $input = fopen("php://input", "r");

    $this->tmp_fp = tmpfile();

    while($content = fread($input,1024)){
      fwrite($this->tmp_fp,$content);
    }
    fclose($input);
  }

  function save($path){

    $fp = fopen($path, "w");
    fseek( $this->tmp_fp , 0 , SEEK_SET );

    while ($data = fread($this->tmp_fp, 1024)){
      fwrite($fp,$data);
    }
    fclose($fp);
    return true;
  }

  function getName(){
    return $_GET['qqfile'];
  }

  function getSize(){
    if( function_exists('apache_request_headers') ){

      $headers = apache_request_headers();
      return (int)$headers['Content-Length'];

    } else {

      fseek( $this->tmp_fp , 0 , SEEK_END );
      return ftell( $this->tmp_fp );

    }

  }

  function close(){
    fclose($this->tmp_fp);
  }

}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {
  /**
   * Save the file to the specified path
   * @return boolean TRUE on success
   */
  function save($path) {
    if(!@move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
      return false;
    }
    return true;
  }
  function getName() {
    return $_FILES['qqfile']['name'];
  }
  function getSize() {
    return $_FILES['qqfile']['size'];
  }
}

class qqFileUploader {
  private $allowedExtensions = array();
  private $sizeLimit = 10485760;
  private $file;

  function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760){
    $allowedExtensions = array_map("strtolower", $allowedExtensions);

    $this->allowedExtensions = $allowedExtensions;
    $this->sizeLimit = $sizeLimit;

    if (isset($_GET['qqfile'])) {
      $this->file = new QQUploadFileXhr();
    } elseif (isset($_FILES['qqfile'])) {
      $this->file = new qqUploadedFileForm();
    } else {
      $this->file = false;
    }
  }

  /**
   * Returns array('success'=>true) or array('error'=>'error message')
   */
  function handleUpload($uploadDirectory, $replaceOldFile = FALSE, $tempFileName) {
    if (!$this->file){
      return array('error' => 'No files were uploaded.');
    }

    $size = $this->file->getSize();

    if ($size == 0) {
      return array('error' => 'File is empty');
    }

    if ($size > $this->sizeLimit) {
      return array('error' => 'File is too large');
    }

    $pathinfo = pathinfo($this->file->getName());
    if ($tempFileName) {
      $filename = $tempFileName;
    }
    else {
      $filename = $pathinfo['filename'];
    }
    //$filename = md5(uniqid());
    $ext = $pathinfo['extension'];

    if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
      $these = implode(', ', $this->allowedExtensions);
      return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
    }

    if(!$replaceOldFile){
      /// don't overwrite previous files that were uploaded
      while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
        $filename .= rand(10, 99);
      }
    }

    if ($this->file->save($uploadDirectory . $filename . '.' . $ext)){
      return array('success'=>true, 'filePath'=>ROOT_SITE . '/' . $uploadDirectory . $filename . '.' . $ext, 'fileExt'=>$ext);
    } else {
      return array('error'=> 'Server error. Could not save uploaded file.');
    }


  }
}

//@ validate inclusion
define('VALID_ACCESS_AUTHENTICATION_',		true);

//@ load dependency files
require('classes/authentication.php');
//@ new acl instance
$_authorisation = new Authorization;

// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array("jpeg","png","jpg","gif");
// max file size in bytes
$sizeLimit = 2 * 1024 * 1024;

$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
$result = $uploader->handleUpload('images/avatars/',true, $_authorisation->getConnectedUserKey()."original");
// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
unset ($uploader);
unset ($_authorisation);
?>