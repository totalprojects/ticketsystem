<?php
namespace App\Traits;
use Image;


trait ProcessImage
{
    public function processImage($image,$storage_path='app/public/kyc_documents') {
        $input = [];
        $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = storage_path($storage_path);
        $img = Image::make($image->getRealPath());
        $result = $img->resize(800, 600, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$input['imagename']);
        $destinationPath = $destinationPath.'/'.$input['imagename'];

        return $destinationPath;
    }
}

?>