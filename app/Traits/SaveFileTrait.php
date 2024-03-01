<?php
namespace App\Traits;
trait SaveFileTrait
{

function savefile($photo, $folder) {
    $file_extension = $photo->getClientOriginalExtension();
    $fileNameWithExtension = $photo->getClientOriginalName();
    $fileName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
    $path = $folder;

    $counter = 1;
    while (file_exists($path . '/' . $fileName . '.' . $file_extension)) {
        $fileName = $fileName . '_' . $counter . '.' . $file_extension;
        $counter++;
    }

    $photo->move($path, $fileName);
    return $fileName;
}
}
