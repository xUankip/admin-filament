<?php

namespace Wiz\Helper;

class ViewHelper
{
    static function outputJsonValidateError($validator): \Illuminate\Http\JsonResponse
    {
        @app('debugbar')->disable();
        $out['message'] = 'Error validation';
        $out['errors'] = $validator->errors();
        return response()->json($out, 442);

    }
}
