<?php

namespace App\Libraries\Traits;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Str;

/*
 * This file is part of BiisCorp project
 *
 * (c) Rizky Miftaqul <tizqy.miftaqul77@gmail.com.com>
 *
 */

trait InteractWithQrCodeGenerator
{
	protected function generate($message = null)
	{
		return (string)QrCode::size(200)->generate($message ?: $this->getCode());
	}

	public function qrCode($messages)
	{
		return $this->generate($messages);
	}

	public function getCode($message = null)
	{
		return $message ?: Str::random();
	}
}