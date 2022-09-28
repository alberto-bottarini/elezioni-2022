<?php

namespace App\Models\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

trait Sluggable
{
    public function getRouteKey()
    {
        return Str::slug($this->nome) . '-' . $this->id;
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $parts = explode('-', $value);
        $id = array_pop($parts);
        $slug = implode('-', $parts);

        $model = self::where('id', $id)->firstOrFail();
        if (Str::slug($model->nome) != $slug) {
            throw new HttpResponseException(redirect(route($this->getDetailRouteName(), $model)));
        }

        return $model;
    }

    abstract protected function getDetailRouteName(): string;
}
