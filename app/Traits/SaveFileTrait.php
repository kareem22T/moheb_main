<?php
namespace App\Traits;
trait SaveFileTrait
{

    function saveFile($photo, $folder, $name = null)
    {
     $file_extension = $photo->getClientOriginalExtension();

     if (empty($file_extension)) {
         $file_extension = 'jpg';
     }

     $fileNameWithExtension = $photo->getClientOriginalName();
     $fileName = $name ? $name : pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
     $path = $folder;

     $counter = 1;

     // Check if a file with the same name and extension already exists
     if (!$name):
         while (file_exists($path . '/' . $fileName . '.' . $file_extension)) {
             $fileName = $fileName . '_' . $counter;
             $counter++;
         }
     endif;

     // Append the correct file extension
     $fileName = $fileName . '.' . $file_extension;

     // Move the file to the destination folder
     $photo->move($path, $fileName);

     return $fileName;
    }
 }
