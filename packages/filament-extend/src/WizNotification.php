<?php

namespace Wiz\FilamentExtend;

use Filament\Notifications\Notification;
use Illuminate\Support\Arr;

class WizNotification extends Notification
{
    public function bodyFromValidatorError($validator): static
    {
        $body = '';
        foreach ($validator->errors()->getMessages() as $key => $value) {
            $body .= "\n" . implode("\n",$value);
        }
        $this->body($body);
        return $this;
    }
}
