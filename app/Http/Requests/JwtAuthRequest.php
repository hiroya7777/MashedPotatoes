<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;  // 追加
use Illuminate\Http\Exceptions\HttpResponseException;  // 追加

class JwtAuthRequest extends FormRequest
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
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|max:255|unique:users',
            'password'              => 'required|string|min:8|max:255|confirmed',
            'password_confirmation' => 'required|string|min:8|max:255',
        ];
    }

    public function messages(){
        return [
            'name.required'     => '名前は必須項目です',
            'name.string'       => '不正な文字が含まれています',
            'name.max'          => '名前は100文字以内です',
            'email.required'    => 'メールアドレスは必須です',
            'email.max'         => 'メールアドレスは255文字以内です',
            'email.email'       => '不正な形式です', 
            'email.unique'      => '既に登録済みのメールアドレスです',
            'password.required' => 'パスワードは必須です',
            'password.string'   => '不正な文字が含まれています',
            'password.min'      => 'パスワードは8文字以上必要です',
            'password.max'      => 'パスワードは255文字以内です',
            'password.confirmed'=> 'パスワードと確認用パスワードが異なります',
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

