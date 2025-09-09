<?php

namespace Wiz\FilamentExtend\Forms\Components;

use Filament\Forms\Components\RichEditor;
use Illuminate\Support\Str;
use League\Flysystem\Visibility;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class WizEditor extends RichEditor
{
    public function setUploadFolder(): static
    {
        $this->fileAttachmentsDisk('r2')
            ->fileAttachmentsDirectory('r2/' . Str::slug(config('app.name')) . '/' . 'editor/' . date('Y/m'))
            ->fileAttachmentsVisibility(Visibility::PRIVATE)
            ->getUploadedAttachmentUrlUsing(function ($file) {
                return '/storage/' . $file;
                //return route('zi_image.full', ['file' => $file]);
            });
        return $this;
    }

    public function setToolbar(): static
    {
        $this->toolbarButtons([
            'attachFiles',
            'blockquote',
            'bold',
            'bulletList',
            'codeBlock',
            'h2',
            'h3',
            'italic',
            'link',
            'orderedList',
            'redo',
            'strike',
            'underline',
            'undo',
        ]);
        return $this;
    }
}
