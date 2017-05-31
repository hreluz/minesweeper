<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class VerifyTokenGameMiddlewareTest extends TestCase
{
	use DatabaseTransactions;

	public function test_token_game_is_valid_for_playing()
	{
		//Having
    
        $response = $this->json('GET',route('api.minesweepers.create'));
        $data = $response->decodeResponseJson();

        //When
        $response = $this->json('GET', 'api/game/check', [], ['token-game' => $data['token']]);

        //Then
        $response->seeStatusCode(200)
         		->seeJson([
	                'result' => true
	            ]);
	}

	public function test_token_game_is_not_valid_for_playing()
	{
        //When
        $response = $this->json('GET', 'api/game/check', [], ['token-game' => 'xxx']);

        //Expecting
        $response->seeStatusCode(401)
         		->seeJson(['error' => 'unauthorized' ]);
	}
}