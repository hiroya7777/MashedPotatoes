<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;  // 追加
use Illuminate\Http\Exceptions\HttpResponseException;  // 追加

class JwtLoginRequest extends FormRequest
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
            'email'                 => 'required|email|max:255',
            'password'              => 'required|string|min:8|max:255',
        ];
    }

    public function messages(){
        return [
            'email.required'    => 'メールアドレスは必須です',
            'email.max'         => 'メールアドレスは255文字以内です',
            'email.email'       => '不正な形式です', 
            'password.required' => 'パスワードは必須です',
            'password.string'   => '不正な文字が含まれています',
            'password.min'      => 'パスワードは8文字以上必要です',
            'password.max'      => 'パスワードは255文字以内です',
        ];
    }

    /**
     * [override] バリデーション失敗時ハンドリング
     * 
     * @param Validator $validator
     * @throw HttpResponseException
     * @see FormRequest::failedValidation()
     */
    protected function failedValidation( Validator $validator )
    {
        $response['data']    = [];
        $response['status']  = 'NG';
        $response['summary'] = 'Failed validation.';
        $response['errors']  = $validator->errors()->toArray();

        throw new HttpResponseException(
            response()->json( $response, 422 )
        );
    }
}
