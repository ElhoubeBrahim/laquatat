<?php

/**
 * This Trait is Used To Deal With File Uploads
 */

trait Upload {

 // The Files Array -- $_FILES['your file'] --
 public $image;

 // The Uploaded File Goes on This Directory
 public $upload_dir;

 // Array Contain Allowed Extensions
 public $extensions;

 // The New Name Of The File After Uploading
 public $upload_name;

 /**
  * This Function is Used To Upload The File After Setting All Properties
  *
  * @param [array] $file
  * @return bool
  */
 public function upload($file) {

  // If The Extension is Not Allowed
  if (!in_array(strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)), $this->extensions)) {
   echo '<script>alert("please upload a valid image")</script>';
   return false;
  }

  // If The Target Directory Does Not Exists
  if (!is_dir($this->upload_dir)) {
   // Create One
   mkdir($this->upload_dir);
  }

  // Move The Uploaded File From Temporary Files To Target Directory
  if (!move_uploaded_file($file['tmp_name'], $this->upload_dir . basename($file['name']))) {
   echo '<script>alert("We Could Not Upload The Image, Please Try Again Later")</script>';
   return false;
  }

  // Rename The Uploaded File
  rename($this->upload_dir . basename($file['name']), $this->upload_dir . $this->upload_name);

  // Tell The User That The File Was Uploaded Successfully
  return true;

 }
}
