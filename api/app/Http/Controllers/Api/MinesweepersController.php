<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\MinesweeperRepository;
use App\Minesweeper;

class MinesweepersController extends Controller
{
	public function create(Request $request)
	{
		$x = $request->get('x');
		$y = $request->get('y');
		$mines = $request->get('mines');

		if(is_numeric($x) && $x > 0 && is_numeric($y) && $y > 0 && is_numeric($mines) && $mines > 0 )
			$minesweeperRepository = new MinesweeperRepository($x, $y , $mines);
		else
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
