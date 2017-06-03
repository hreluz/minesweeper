<?php

use App\Repositories\PlayingGameRepository;

class PlayingGameRepositoryTest extends TestCase
{

	public function test_when_selecting_a_mine_the_game_is_over()
	{
		//Having
		$playingGameRepository = new PlayingGameRepository;
		$game_response = $playingGameRepository->coordinate_selected(1,1, $this->getTestGrid(), [0 => [0 => 1 ]] );

		//Expecting
		$game_over_grid  = $this->data_for_when_selecting_a_mine_the_game_is_over();

		$response_expected = [
			'is_finished' => true,
			'success_game' => false,
			'grid' => $game_over_grid,
			'my_new_grid' => []
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
		$empty_cell_grid = $this->data_for_when_selecting_an_empty_cell();

		$response_expected = [
			'is_finished' => false,
			'success_game' => false,
			'grid' => $empty_cell_grid,
			'my_new_grid' => $empty_cell_grid
		];

		return $this->assertTrue($game_response == $response_expected);
	}
	
	public function test_select_a_cell_and_win_the_game()
	{
		$data = $this->data_for_when_select_the_last_cell_and_win_the_game();
		$winner_grid = $data['winner_grid'];
		$grid_without_mines = $data['grid_without_mines'];
		$winner_area_unlocked = $data['winner_area_unlocked'];

		//Having
		$playingGameRepository = new PlayingGameRepository;	
		$game_response = $playingGameRepository->coordinate_selected(0,0,  $this->getTestGrid(), $grid_without_mines);

		//Expecting
		$response_expected = [
			'is_finished' => true,
			'success_game' => true,
			'grid' => $winner_area_unlocked,
			"my_new_grid" => $winner_grid
			];

		return $this->assertTrue($game_response == $response_expected);
	}


	/*------------------------------------------------------------------*/
	private function data_for_when_selecting_a_mine_the_game_is_over()
	{
        /*

        This is the original grid
      	| 1 | 1 | 1 | 0 | 0 |
        ______________________
        | 1 | * | 1 | 0 | 0 |
        ______________________
        | 1 | 1 | 2 | 1 | 1 |
        ______________________
        | 0 | 0 | 2 | * | 2 |
        ______________________
        | 0 | 0 | 2 | * | 2 |    


        And you are playing this grid

        | 1 |   |   |   |   |
        ______________________
        |   |   |   |   |   |
        ______________________
        |   |   |   |   |   |
        ______________________
        |   |   |   |   |   |
        ______________________
        |   |   |   |   |   |

		Then you click on 1,1 and you will click a mine, and the game will be over, giving you back this

        | 1 |   |   |   |   |
        ______________________
        |   | * |   |   |   |
        ______________________
        |   |   |   |   |   |
        ______________________
        |   |   |   | * |   |
        ______________________
        |   |   |   | * |   |
    

        */

		$grid_game_over =  [
			0 => [
				0 => 1
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
		ksort($grid_game_over);

		return $grid_game_over;
	}

	private function data_for_when_selecting_an_empty_cell()
	{

        /*

        This is the original grid
      	| 1 | 1 | 1 | 0 | 0 |
        ______________________
        | 1 | * | 1 | 0 | 0 |
        ______________________
        | 1 | 1 | 2 | 1 | 1 |
        ______________________
        | 0 | 0 | 2 | * | 2 |
        ______________________
        | 0 | 0 | 2 | * | 2 |    


        And You having this empty grid and clicking in 4 0

        |   |   |   |   |   |
        ______________________
        |   |   |   |   |   |
        ______________________
        |   |   |   |   |   |
        ______________________
        |   |   |   |   |   |
        ______________________
        |   |   |   |   |   |

		You will click an empty cell, and this will open another empty cell, and this keeps going until there is no more empty

        |   |   |   |   |   |
        ______________________
        |   |   |   |   |   |
        ______________________
        | 1 | 1 | 2 |   |   |
        ______________________
        | 0 | 0 | 2 |   |   |
        ______________________
        | 0 | 0 | 2 |   |   |

        */

		$empty_cell_grid = [
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
		ksort($empty_cell_grid);

		return $empty_cell_grid;
	}

	private function data_for_when_select_the_last_cell_and_win_the_game()
	{
		//This is a grid without mines and a cell unselected
		$grid_without_mines = $this->getTestGrid();
		foreach ($this->getTestMinesPositions() as $x => $cell)
			foreach ($cell as $y => $value)
				unset($grid_without_mines[$x][$y]);

		//If you have this grid, you will win
		$winner_grid = $grid_without_mines;


		//This the grid you are playing, you just need one more cell and win the game
		$expected_value = $grid_without_mines[0][0];
		unset($grid_without_mines[0][0]);

		/*
        This is the grid you are playing, then you click on 0, 0

      	|   | 1 | 1 | 0 | 0 |
        ______________________
        | 1 |   | 1 | 0 | 0 |
        ______________________
        | 1 | 1 | 2 | 1 | 1 |
        ______________________
        | 0 | 0 | 2 |   | 2 |
        ______________________
        | 0 | 0 | 2 |   | 2 |    

        Giving you back the winner grid

      	| 1 | 1 | 1 | 0 | 0 |
        ______________________
        | 1 |   | 1 | 0 | 0 |
        ______________________
        | 1 | 1 | 2 | 1 | 1 |
        ______________________
        | 0 | 0 | 2 |   | 2 |
        ______________________
        | 0 | 0 | 2 |   | 2 |    
	

        */

		$winner_area_unlocked = [
			0 => [
				0 => $expected_value
			]
		];
		ksort($winner_area_unlocked);

        return [
        	'grid_without_mines' => $grid_without_mines,
        	'winner_grid' => $winner_grid,
        	'expected_value' => $expected_value,
        	'winner_area_unlocked' => $winner_area_unlocked
        ];
	}
}
