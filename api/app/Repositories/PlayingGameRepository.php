<?php

namespace App\Repositories;

class PlayingGameRepository
{
	private $my_new_grid = [];
	private $grid_for_empty_cell = [];
	private $coordinates_x = [-1 , -1 , -1 , 0,   0, +1 , +1, +1 ];
	private $coordinates_y = [-1 ,  0 , +1 , -1, +1, -1,   0, +1 ];

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
				$this->grid_for_empty_cell = $original_grid;
				$grid_unlock = $this->get_grid_when_is_empty_cell($x, $y, $playing_grid, []);
			endif;
			
			$grid_unlock[$x][$y] = $coordinate_value;

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

	//Will give you back all the cells around it, if there is another empty, will keep doing the same, in a recursive function
	private function get_grid_when_is_empty_cell($x, $y, array $grid_unlock, array $waiting )
	{
		if(!is_numeric($x) || !is_numeric($y))
			abort(404, 'X,Y must be numeric, integer');


		for ($i=0; $i < 8 ; $i++):
			$_x = $this->coordinates_x[ $i ] + $x;
			$_y = $this->coordinates_y[ $i ] + $y;

			if($this->is_not_allow_to_enter($x, $y, $_x, $_y, $waiting))
				continue;

			$coordinate_selected = $this->grid_for_empty_cell[$_x][$_y];
			$this->grid_for_empty_cell[$_x][$_y] = -2;

			if($coordinate_selected != -2)
				$grid_unlock[$_x][$_y] = $coordinate_selected;

			if($coordinate_selected == 0):
				$waiting = $this->add_cells_around_to_waiting_list($i, $x, $y, $waiting);
				$grid_unlock = $this->get_grid_when_is_empty_cell($_x, $_y, $grid_unlock, $waiting);
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

	//Functions use in get_grid_when_is_empty_cell()

	private function is_not_allow_to_enter( $x, $y, $_x, $_y, array $waiting )
	{ 
		//Check if this cell is in the waiting list, so we will skip or if this cell has already been read ( -2 ) or if it does not exist in the grid
		$temp_x_y = $_x.','.$_y	;
		return isset($waiting[ $temp_x_y ]) && $waiting[ $temp_x_y ]  != $x.$y ||	!isset($this->grid_for_empty_cell[ $_x ][ $_y ]) ||  $this->grid_for_empty_cell[ $_x ][ $_y ] == -2;
	}

	private function add_cells_around_to_waiting_list($i, $x, $y, $waiting)
	{
		//We add the cells around that has not been already added to the waiting array. Why? It could work without this waiting list, but in a huge grid, the server will run out of memory, so we keep a track of the cells around  so the recursive function will go back faster

		for ($j= $i ; $j < 8 ; $j++):
			$temp_x = $this->coordinates_x[ $j ] + $x;
			$temp_y = $this->coordinates_y[ $j ] + $y;
			$temp_x_y = $temp_x.','.$temp_y;

			if($temp_x >= 0 && $temp_y >= 0 && !isset($waiting[$temp_x_y])):
				$waiting[$temp_x_y] = $x.$y;
			endif;
		endfor;

		return $waiting;
	}

}