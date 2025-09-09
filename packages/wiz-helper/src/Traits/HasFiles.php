<?php

namespace Wiz\Helper\Traits;


use App\Models\System\ZiFileModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;



trait HasFiles
{
    public function files(): BelongsToMany
    {
        return $this->morphToMany(ZiFileModel::class, 'model', 'zi_file_ables', 'model_id', 'zi_file_id')
            ->withPivot('type');
    }

    public function file(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ZiFileModel::class, 'zi_file_id');
    }


}
