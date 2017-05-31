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

}
