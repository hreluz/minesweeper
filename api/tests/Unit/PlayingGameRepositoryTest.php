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
}
