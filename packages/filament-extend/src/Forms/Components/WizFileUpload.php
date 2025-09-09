<?php

namespace Wiz\FilamentExtend\Forms\Components;

use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class WizFileUpload extends FileUpload
{
    public function setUploadFolder($folder = '', $date = true): static
    {
        if (!$folder) {
            $folder = Str::slug(config('app.name'));
        } else {
            $folder = Str::slug(config('app.name')) . '/' . $folder;
        }
        $this->getUploadedFileNameForStorageUsing(
            fn(TemporaryUploadedFile $file): string => (string)str(substr(Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)), 0, 30) . '.' . $file->getClientOriginalExtension())
                ->prepend(Str::random(9) . '-')->lower(),
        )->disk('r2')->directory(fn() => 'r2/' . $folder  . ($date ? date('/Y/m') : ''));
        return $this;
    }
}
