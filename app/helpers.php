<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

function uploads($file, $path)
{
    $fileName   = time() . $file->getClientOriginalName();
    Storage::disk('public')->put($path . $fileName, File::get($file));
    $filePath   = 'storage/'.$path . $fileName;

    return $fileName;
}