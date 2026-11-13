<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'display_name',
        'description',
        'is_public',
        'order'
    ];

    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
{
    $setting = self::where('key', $key)->first();
    return $setting ? $setting->value : $default;
}

    /**
     * Set a setting value by key.
     *
     * @param string|array $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->set($k, $v);
            }
            return;
        }

        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget('setting.' . $key);

        return $setting;
    }
}