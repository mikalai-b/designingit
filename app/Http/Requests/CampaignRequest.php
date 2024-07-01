<?php

namespace App\Http\Requests;

use Campaign;
use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'success_message' => 'nullable|string',
            'effects' => 'nullable|array',
        ];
    }

    public function populate(Campaign $campaign)
    {
        
    }

}