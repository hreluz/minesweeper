<?php

use App\Repositories\PlayingGameRepository;

class PlayingGameRepositoryTest extends TestCase
{

	public function test_when_selecting_a_mine_the_game_is_over()
	{
		//Having
		$playingGameRepository = new PlayingGameRepository;
		$game_response = $playingGameRepository->coordinate_selected(1,1, $this->getTestGrid(), [0 => [0 => 2]] );

		//Expecting
		$grid_area_unlocked = [
			0 => [
				0 => 2
			],
			1 => [
				1 => -1
			],
			3 => [
				3 => -1
			],
			4 => [
				3 => -1
			]
		];

		$response_expected = [
			'is_finished' => true,
			'success_game' => false,
			'grid' => $grid_area_unlocked
		];

		return $this->assertTrue($game_response == $response_expected);
	}

	public function test_when_selecting_an_empty_cell()
	{
		//Having
		$playingGameRepository = new PlayingGameRepository;
		$grid = $this->getTestGrid();
	
		$game_response = $playingGameRepository->coordinate_selected(4,0, $grid, []);

		//Expecting

		$grid_area_unlocked = [
				2 => [
					0 => 1,
					1 => 1,
					2 => 2
				],
				3 => [
					0 => 0,
					1 => 0,
					2 => 2,
				],
				4 => [
					0 => 0,
					1 => 0,
					2 => 2,
				]
		];

		$response_expected = [
			'is_finished' => false,
			'success_game' => false,
			'grid' => $grid_area_unlocked
		];

		return $this->assertTrue($game_response == $response_expected);
	}
	
	public function test_select_a_cell_and_win_the_game()
	{
		//This is a grid without mines and a cell unselected
		$grid_without_mines = $this->getTestGrid();
		foreach ($this->getTestMinesPositions() as $x => $cell)
			foreach ($cell as $y => $value)
				unset($grid_without_mines[$x][$y]);

		$expected_value = $grid_without_mines[0][0];
		unset($grid_without_mines[0][0]);

		//Having
		$playingGameRepository = new PlayingGameRepository;	
		$game_response = $playingGameRepository->coordinate_selected(0,0,  $this->getTestGrid(), $grid_without_mines);

		//Expecting
		$response_expected = [
			'is_finished' => true,
			'success_game' => true,
			'grid' => [
				0 => [
					0 => $expected_value
				]
			]
		];
		return $this->assertTrue($game_response == $response_expected);
	}
}
