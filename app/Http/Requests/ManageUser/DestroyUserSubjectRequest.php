<?php

namespace App\Http\Requests\ManageUser;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DestroyUserSubjectRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return Auth::check();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			'subject' => 'required|integer|exists:user_subject_classes,id',
		];
	}

	public function validated($key = null, $default = null)
	{
		$data = parent::validated();
		$data['id'] = $data['subject'];
		unset($data['subject']);
		return $data;
	}
}
