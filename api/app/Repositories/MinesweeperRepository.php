<?php

namespace App\Repositories;

class MinesweeperRepository{

	protected $x;
	protected $y;
	protected $mines;

	public function __construct($x = 9 , $y = 9, $mines = 10)
	{
		$this->x  = $x;
		$this->y  = $y;
		$this->mines = $mines;
	}

	public function getMinesPositions()
	{
		$mines = $this->mines;
		$positions = [];

		while($mines > 0 ):
			$x = rand(0, $this->x - 1);
			$y = rand(0, $this->y - 1);

			if(!isset( $positions[$x] ))
				$positions[$x] = [];
			
			if(!isset($positions[ $x ][ $y ])):
				$positions[$x][$y] = true;
				$mines--;
			endif;
		endwhile;

		return $positions;
	}
}