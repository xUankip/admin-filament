<?php

namespace Wiz\Helper\Traits;

use Wiz\Helper\WizSecurity;



trait HasSid
{
    public string $sid = '';

    public function getSid(): string
    {
        if (!$this->sid) {
            //$this->sid = WizSecurity::buildSID($this->id, $this->table . env('DB_DATABASE'));
            $this->sid = WizSecurity::buildSID($this->id, $this->table . env('DB_DATABASE'));
        }
        return $this->sid;
    }

    public static function getIdFromSid($sid): ?int
    {
        $table = app(static::class)->table;
        return WizSecurity::getIDFromSID($sid, $table . env('DB_DATABASE'));
    }

    public function getUID(): string
    {
        return WizSecurity::encode_number_basic($this->id);
    }
    public static function getIdFromUID($uid): int
    {
        return WizSecurity::decode_string_basic($uid);
}
}
