<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class AcademicService
{
	public const FAIL_CONNECTION = 504;
	public const FAIL_REQUEST = 400;
	public const SUCCESS = 200;

	public const STUDENTS = 0;
	public const CLASSES = 1;

	/**
	 * get data from academic api
	 * @param int $type
	 * @return object
	 */
	public static function getFromAcademic(int $type): object
	{
		$result = (object)[
			"code" => self::SUCCESS,
			"message" => "",
			"data" => []
		];
		try {
			$url = config('external-api.academic');
			switch ($type) {
				case self::STUDENTS:
					$url = $url . '/api/pay';
					break;
				case self::CLASSES:
					$url = $url . '/api/pay/kelas';
					break;
			}

			$response = Http::asForm()->post($url, [
				'form_kode' => 'O@#$J!AKSD#$%!@',
				'form_sync' => Carbon::now()->format('Y-m-d'),
			]);
			if ($response->successful()) {
				$data = $response->json();
				$result->data = $data;
			} else {
				$result->code = self::FAIL_REQUEST;
				$result->message = $response->reason();
			}
			return $result;
		} catch (ConnectionException $e) {
			$result->code = self::FAIL_CONNECTION;
			$result->message = $e->getMessage();
			return $result;
		}
	}
}