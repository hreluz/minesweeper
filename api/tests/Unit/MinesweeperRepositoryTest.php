<?php

use App\Repositories\MinesweeperRepository;

class MinesweeperRepositoryTest extends TestCase
{

	public function test_correct_mines_position()
	{
		$minesweeperRepository = new MinesweeperRepository(5, 5, 10);
		$positions = $minesweeperRepository->getMinesPositions();
		$response = true;

		foreach ($positions as $x => $position):
			foreach ($position as $y => $value):
				if($x >= 5 || $y >= 5 || $x < 0 || $y < 0):
					$response =  false;
				endif;
			endforeach;
		endforeach;

		 $this->assertTrue($response);
	}

	public function test_correct_number_mines()
	{
		$minesweeperRepository = new MinesweeperRepository(5, 5, 10);
		$positions = $minesweeperRepository->getMinesPositions();

		$number_mines = 0;
		foreach ($positions as $position)
			$number_mines += count($position);

		 $this->assertTrue($number_mines == 10);
	}

	public function test_how_many_mines_has_xy_around()
	{
		$minesweeperRepository = new MinesweeperRepository(5, 5, 3);
		$number_mines = $minesweeperRepository->getNumberOfMinesWithXAndYPosition(4, 2 , $this->getTestMinesPositions());

		$this->assertTrue($number_mines == 2);
	}

	public function test_created_grid()
	{
		$minesweeperRepository = new MinesweeperRepository(5, 5, 3);

		$test_mines_positions =  $this->getTestMinesPositions();	

		$test_grid = $this->getTestGrid();

		$grid = $minesweeperRepository->createGrid($test_mines_positions);

		$this->assertTrue($test_grid == $grid);
	}
}
