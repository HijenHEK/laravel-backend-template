<?php

namespace App\Traits;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Storage;

trait HasPicture
{

    public function setPicture(string $path)
    {
        $this->picture = $path;
        $this->save();
        return $this;
    }

    public function getPictureUrl()
    {
        return url('/storage//' . $this->picture);
    }

    public function deletePicture()
    {
        Storage::disk('public')->delete($this->picture);
        $this->picture = null;
        $this->save();
        return $this;
    }
}
