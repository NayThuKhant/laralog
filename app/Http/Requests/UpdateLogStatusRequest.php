<?php

namespace App\Http\Requests;

use App\Enums\LogStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLogStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $logStatuses = implode(',', array_column(LogStatusEnum::cases(), 'value'));

        return [
            'status' => ['string', 'required', "in:$logStatuses"],
        ];
    }
}
