<?php

namespace App\Http\Requests;

class ContractTplRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

        ];
    }

    /**
     * @param $data
     * @return array|bool
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateStore($data)
    {
        $rule = [
            'section_id' => 'required',
            'content' => 'required',
        ];
        return $this->check($data, $rule);
    }

    /**
     * @param $data
     * @return array|bool
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateUpdate($data)
    {
        $rule = [
            'section_id' => 'required',
            'content' => 'required',
        ];
        return $this->check($data, $rule);
    }
}
