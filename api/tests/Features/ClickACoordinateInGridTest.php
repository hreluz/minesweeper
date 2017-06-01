<?php

class ClickACoordinateInGridTest extends FeatureTestCase
{
	public function test_click_a_mine_and_game_is_over()
	{
		//Having
		$minesweeper = $this->defaultMinesweeper();
		$coordinates_with_mine = ['x' => 1, 'y' => 1];

        //When
        $response = $this->json('POST', route('api.game.click_coordinate'), $coordinates_with_mine, ['token-game' => 'token']);

        //Then
        $response->seeStatusCode(200)
         		->seeJson([
	                'is_finished' => true,
        			'success_game' => false
	            ]);

	    //In Database
        $this->seeInDatabase('minesweepers',[
        	'token' => 'token',
        	'is_finished' => true,
        	'success_game' => false
        ]);
	}

	public function test_click_an_empty_cell()
	{
		//Having
		$minesweeper = $this->defaultMinesweeper();
		$coordinates_with_mine = ['x' => 4, 'y' => 0];

        //When
        $response = $this->json('POST', route('api.game.click_coordinate'), $coordinates_with_mine, ['token-game' => 'token']);

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

        //Then
        $response->seeStatusCode(200)
         		->seeJson([
	                'is_finished' => false,
        			'success_game' => false,
        			'grid' => $grid_area_unlocked
	            ]);

	    //In Database
        $this->seeInDatabase('minesweepers',[
        	'token' => 'token',
        	'is_finished' => false,
        	'success_game' => false,
        ]);
	}

	public function test_click_not_an_empty_cell()
	{
		//Having
		$minesweeper = $this->defaultMinesweeper();
		$coordinates_with_mine = ['x' => 1, 'y' => 2];

        //When
        $response = $this->json('POST', route('api.game.click_coordinate'), $coordinates_with_mine, ['token-game' => 'token']);

        $grid =  [1 => [2 => 1]];

	    //Then
        $response->seeStatusCode(200)
         		->seeJson([
	                'is_finished' => false,
        			'success_game' => false,
        			'grid' => $grid
	            ]);

	    //In Database
        $this->seeInDatabase('minesweepers',[
        	'token' => 'token',
        	'is_finished' => false,
        	'success_game' => false,
        	'playing_grid' => json_encode([1 => [2 => 1]], JSON_FORCE_OBJECT)
        ]);
	}

	public function test_click_coordinate_validation_with_empty_values()
	{
		$minesweeper = $this->defaultMinesweeper();

        $response = $this->json('POST', route('api.game.click_coordinate'), [], ['token-game' => 'token']);
        $errors = $response->decodeResponseJson();


		$expected_errors = [
        	'x' => [
        		'The x field is required.',
        	],
        	'y' => [
        		'The y field is required.'
        	]
        ];

		$this->assertTrue($expected_errors == $errors);
	}

	public function test_click_coordinate_validation_with_incorrect_values()
	{
		$minesweeper = $this->defaultMinesweeper();

        $response = $this->json('POST', route('api.game.click_coordinate'), ['x' => "a" , 'y' => "b"], ['token-game' => 'token']);
        $errors = $response->decodeResponseJson();


		$expected_errors = [
        	'x' => [
        		'The x must be an integer.',
        	],
        	'y' => [
        		'The y must be an integer.',
        	]
        ];

		$this->assertTrue($expected_errors == $errors);
	}

	public function test_click_coordinate_validation_are_off_limits_of_grid()
	{
		$minesweeper = $this->defaultMinesweeper();

        $response = $this->json('POST', route('api.game.click_coordinate'), ['x' => 222 , 'y' => 222], ['token-game' => 'token']);
        $errors = $response->decodeResponseJson();

		$expected_errors = [
        	'coordinates' => [
        		'The coordinates are off limit'
        	],
        ];

		$this->assertTrue($expected_errors == $errors);
	}
}
