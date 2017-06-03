<?php

namespace App\Repositories;

class PlayingGameRepository
{
	private $my_new_grid = [];

	public function verifyTokenGame($token)
	{
		return \App\Minesweeper::where('token', $token)->first();
	}

	public function coordinate_selected($x, $y, array $original_grid, array $playing_grid)
	{
		if(!is_numeric($x) || !is_numeric($y))
			abort(404);

		$coordinate_value = $original_grid[$x][$y];
		$grid_unlock = [];

		//When is a mine
		if($coordinate_value == -1):
			$is_finished = true;
			$success_game = false;
			$grid_unlock = $this->get_my_grid_with_mines($original_grid, $playing_grid);
		else:
			//When is an empty cell
			if($coordinate_value == 0):
				$grid_unlock = $this->get_grid_when_is_empty_cell($x, $y, $original_grid, $playing_grid, []);
			else:
				//When is not an empty cell and not a mine
				$grid_unlock[$x][$y] = $coordinate_value;
			endif;

			$is_finished = $this->is_end_of_game($original_grid, $playing_grid, $grid_unlock);
			$success_game = $is_finished;
		endif;

		ksort($grid_unlock);
		ksort($this->my_new_grid);

		return [
			'is_finished' => $is_finished,
			'success_game' => $success_game,
			'grid' => $grid_unlock,
			'my_new_grid' => $this->my_new_grid
		];
	}

	//Will give you back your grid, with the mines on it
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

	//Will give you back all the cells around it, if there is another empty, will keep doing the same
	private function get_grid_when_is_empty_cell($x, $y, array $original_grid, array $grid_unlock )
	{
		if(!is_numeric($x) || !is_numeric($y))
			abort(404, 'X,Y must be numeric, integer');

		$coordinates_x = [-1 , -1 , -1 , 0, 0, +1 , +1, +1 ];
		$coordinates_y = [-1 , 0 , +1 , -1, +1, -1, 0, +1 ];

		for ($i=0; $i < count($coordinates_y) ; $i++):
			$_x = $coordinates_x[ $i ] + $x;
			$_y = $coordinates_y[ $i ] + $y;

			if(!isset($original_grid[ $_x ][ $_y ]))
				continue;

			$coordinate_selected = $original_grid[$_x][$_y];
			$original_grid[$_x][$_y] = -2;

			if($coordinate_selected == 0)
				$grid_unlock = $this->get_grid_when_is_empty_cell($_x, $_y, $original_grid, $grid_unlock);

			if($coordinate_selected != -2):
				if(!isset($grid_unlock[$_x]))
					$grid_unlock[$_x] = [];

				$grid_unlock[$_x][$_y] = $coordinate_selected;
			endif;
		endfor;

		return $grid_unlock;
	}

	private function is_end_of_game(array $original_grid, array $playing_grid, array $grid_area)
	{
		$grid_winner = $this->get_grid_winner($original_grid, $playing_grid);
		$my_grid = $this->get_my_new_grid($grid_area, $playing_grid);
		return $grid_winner == $my_grid;
	}

	//Will give you back your new grid with the cell that you clicked
	private function get_my_new_grid(array $grid_area, array $playing_grid)
	{
		foreach ($grid_area as $x => $cell):
			foreach ($cell as $y => $value):
				if(!isset($playing_grid[$x]))
					$playing_grid[$x] = [];

				$playing_grid[$x][$y] = $value;
			endforeach;
		endforeach;

		return $this->my_new_grid = $playing_grid;
	}

	private function get_grid_winner(array $original_grid)
	{
		$grid_without_mines = [];

		foreach ($original_grid as $x => $grid):
			foreach ($grid as $y => $value):
				if($value != -1):
					if(!isset($grid_without_mines[$x]))
						$grid_without_mines[$x] = [];

					$grid_without_mines[$x][$y] = $value;
				endif;
			endforeach;
		endforeach;

		return $grid_without_mines;
	}
}