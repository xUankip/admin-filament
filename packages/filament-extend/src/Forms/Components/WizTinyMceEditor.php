<?php

namespace Wiz\FilamentExtend\Forms\Components;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use Illuminate\Support\Str;
use Jiten14\JitoneAi\Traits\WithAIContent;
use League\Flysystem\Visibility;

class WizTinyMceEditor extends TinyEditor
{
    use WithAIContent;

    public function init(): static
    {
        $this->fileAttachmentsDisk('r2')
            /*->withAI([
                'model'       => 'gpt-4',
                'max_tokens'  => 860,
                'temperature' => 0.6,
            ])*/
            ->fileAttachmentsDirectory('r2/' . Str::slug(config('app.name')) . '/' . 'editor/' . date('Y/m'))
            ->fileAttachmentsVisibility(Visibility::PRIVATE)
            //->profile('default|simple|full|minimal|none|custom')
            ->profile('default')
            ->getUploadedAttachmentUrlUsing(function ($file) {
                return '/storage/' . $file;
                //return route('zi_image.full', ['file' => $file]);
            });
        return $this;
    }
}
