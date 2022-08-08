<?php
namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait UploadTrait {
    public function uploadOne(UploadedFile $uploadedFile, $folder) { // $folder = target folder
        return $uploadedFile->store($folder);
    }

    public function deleteOne($path) { // $path = path with image name
        if(Storage::exists($path)) {
            Storage::delete($path);
        }
    }
}
?>
