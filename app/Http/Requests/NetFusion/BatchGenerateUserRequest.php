<?php

namespace App\Http\Requests\NetFusion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class BatchGenerateUserRequest extends FormRequest
{
    public function authorize()
    {
        return Session::has('router_session');
    }

    public function rules()
    {
        return [
            'qty' => 'required|integer|min:1|max:100',
            'server' => 'nullable|string',
            'mode' => 'required|in:up,vc',
            'user_len' => 'required|integer|min:3|max:20',
            'prefix' => 'nullable|string|max:10',
            'char_type' => 'required|in:lower,upper,upplow,mix,mix1,mix2,num',
            'profile' => 'required|string',
            'limit_uptime' => 'nullable|string',
            'limit_bytes_total' => 'nullable|integer|min:1',
            'limit_bytes_unit' => 'nullable|in:KB,MB,GB,TB',
            'comment' => 'nullable|string|max:200',
        ];
    }
}
