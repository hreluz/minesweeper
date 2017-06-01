<?php

namespace App\Repositories;

class PlayingGameRepository
{
	public function verifyTokenGame($token)
	{
		return \App\Minesweeper::where('token', $token)->first();
	}

	public function coordinate_selected(int $x, int $y, array $original_grid, array $playing_grid)
	{
		$coordinate_value = $original_grid[$x][$y];
		$grid_unlock = [];

		if($coordinate_value == -1):
			$is_finished = true;
			$success_game = false;
			$grid_unlock = $this->get_my_grid_with_mines($original_grid, $playing_grid);
		endif;

		return [
			'is_finished' => $is_finished,
			'success_game' => $success_game,
			'grid' => $grid_unlock
		];
	}

	private function get_my_grid_with_mines(array $original_grid, array $playing_grid)
	{
		foreach ($original_grid as $x => $grid):
			foreach ($grid as $y => $value):
				if($value == -1)
					$playing_grid[$x][$y] = -1;
			endforeach;
		endforeach;

		return $playing_grid;
	}
}