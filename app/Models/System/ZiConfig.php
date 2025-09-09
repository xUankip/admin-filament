<?php

namespace App\Models\System;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Yaml\Yaml;
use Wiz\Helper\Casts\EncryptFields;

/**
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string $key
 * @property string $value
 * @property string group
 * @property int id
 */
class ZiConfig extends Model
{
    use HasFactory;
    use SoftDeletes;

    static $config = [];

    protected $table = 'zi_configs';

    protected $casts
        = [
            'value' => EncryptFields::class,
        ];
    protected $fillable = ['group', 'key', 'value'];
    protected $guarded = [];

    static bool $cachedOn = true;

    public static function getConfigByKey($key, $group = 'global')
    {
        if (!self::$cachedOn) {
            return self::query()->where('key', $key)->where('group', $group)->first();
        }

        try {
            $cacheKey = "zi_cache:{$key}:{$group}";

            return Cache::remember($cacheKey, 3600, function () use ($key, $group) {
                return self::query()->where('key', $key)->where('group', $group)->first();
            });
        } catch (\Exception $exception) {
            return [];
        }

    }

    /**
     * @param $key
     * @param $value array only
     * @param string $group
     * @return mixed
     */
    public static function mergeAndSaveConfig($key, array $value, string $group = 'global'): mixed
    {
        $config = self::query()->where('key', $key)->where('group', $group)->first();
        if (!$config) {
            $config = new self();
        } else {
            $oldValue = self::parserValue($config);
            $value    = array_merge($oldValue, $value);
        }
        $value         = Yaml::dump($value);//convert array to string
        $config->key   = $key;
        $config->value = $value;
        $config->group = $group;
        $config->save();

        $cacheKey = "zi_cache:{$key}:{$group}";
        Cache::forget($cacheKey);//force remove cache

        return $config;
    }

    public static function parserValue($config): array
    {
        if (empty($config->value)) {
            return [];
        }
        return Yaml::parse($config->value);
    }

    public static function getConfigAndParserValue($configKey, $group = 'global'): array
    {
        if (empty(self::$config[$group][$configKey])) {
            self::$config[$group][$configKey] = self::parserValue(self::getConfigByKey($configKey, $group));
        }
        return self::$config[$group][$configKey];

    }

}
