<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Minesweeper;
use App\Http\Requests\Api\Game\ClickCoordinateRequest;

class GameController extends Controller
{
	public function click_coordinate(ClickCoordinateRequest $request)
	{
		$x = $request->get('x');
		$y = $request->get('y');

		$minesweeper = Minesweeper::where('token',$request->headers->get('token-game'))->firstOrFail();
		return $minesweeper->clickCoordinate($x, $y);
	}
}
