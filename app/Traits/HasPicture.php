<?php

namespace App\Traits;


trait HasPicture
{

    public function setPicture(string $path)
    {
        $this->picture = $path;
        $this->save();
        return $this;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function deletePicture()
    {
        $this->picture = null;
        $this->save();
        return $this;
    }
}
