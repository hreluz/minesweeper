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

	public function getNumberOfMinesWithXAndYPosition(int $x, int $y, array $mines)
	{
		if(isset($mines[$x][$y]))
			return -1 ;

		$number_mines = 0;

		$coordinates_x = [-1 , -1 , -1 , 0, 0, +1 , +1, +1 ];
		$coordinates_y = [-1 , 0 , +1 , -1, +1, -1, 0, +1 ];

		for ($i=0; $i < count($coordinates_y) ; $i++):
			$_x = $coordinates_x[ $i ] + $x;
			$_y = $coordinates_y[ $i ] + $y;

			$is_mine = isset($mines[ $_x ][ $_y ]);
			$number_mines = $is_mine ? $number_mines + 1 : $number_mines;
		endfor;

		return $number_mines;
	}

	public function createGrid(array $mines)
	{
		$grid = [];

		for ($i=0; $i < $this->x ; $i++):
			$grid[$i] = [];

			for ($j=0; $j < $this->y ; $j++):
				$grid[$i][$j] = $this->getNumberOfMinesWithXAndYPosition($i, $j, $mines);
			endfor;
		endfor;

		return $grid;
	}

	//Setters and Getters
	public function getX()
	{
		return $this->x;
	}

	public function getY()
	{
		return $this->y;
	}
	
	public function getGrid()
	{
		$mines = $this->getMinesPositions();
		return $this->createGrid( $mines  );
	}

}