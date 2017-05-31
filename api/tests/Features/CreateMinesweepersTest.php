<?php

class CreateMinesweepersTest extends FeatureTestCase
{
	public function test_create_a_minesweeper()
	{
		//When
        $response = $this->json('GET',route('api.minesweepers.create'));

        //Then
        $response->seeStatusCode(200)
         		->seeJson([
	                'result' => true,
	                'x' => 9,
	                'y' => 9,
	            ]);


        //Check in Database
        $data = $response->decodeResponseJson();

        $this->seeInDatabase('minesweepers',[
        	'x' => 9,
        	'y' => 9,
        	'token' => $data['token']
        ]);
	}
}
