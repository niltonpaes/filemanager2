<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $guarded = [];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function compactMime()
    {
        if (strlen($this->mime_type) > 25)
            return substr($this->mime_type, 0, 25) . "...";
        else
            return $this->mime_type;
    }
}
