<?php

namespace App\Repositories;
use App\Minesweeper;

class PlayingGameRepository
{
	public function verifyTokenGame($token)
	{
		return Minesweeper::where('token', $token)->first();
	}

}