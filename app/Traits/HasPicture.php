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
        return $this->picture ? url('storage/' . $this->picture) : null ;
    }

    public function deletePicture()
    {
        if($this->picture) {
            Storage::disk('public')->delete($this->picture);
        }
        $this->picture = null;
        $this->save();
        return $this;
    }
}
