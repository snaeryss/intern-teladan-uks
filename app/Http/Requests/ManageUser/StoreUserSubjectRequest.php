<?php

namespace App\Http\Requests\ManageUser;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUserSubjectRequest extends FormRequest
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
			'class' => 'required|integer|in:7,8,9',
			'subject' => 'required|integer|exists:subjects,id',
		];
	}

	public function validated($key = null, $default = null)
	{
		$data = parent::validated();
		$data['class_level'] = $data['class'];
		$data['subject_id'] = $data['subject'];
		unset($data['class'], $data['subject']);
		return $data;
	}
}
