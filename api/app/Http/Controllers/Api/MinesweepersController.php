<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\MinesweeperRepository;
use App\Minesweeper;
use Hash;

class MinesweepersController extends Controller
{
	public function create()
	{
		$minesweeperRepository = new MinesweeperRepository;

		$minesweeper = new Minesweeper([
			'grid' => $minesweeperRepository->getGrid(),
			'x' => $minesweeperRepository->getX(),
			'y' => $minesweeperRepository->getY()
		]);

		$response = $minesweeper->save();
		
		return [
			'result' => $response,
			'token' => $minesweeper->token,
			'x' => $minesweeper->x,
			'y' => $minesweeper->y
		];

	}
}
