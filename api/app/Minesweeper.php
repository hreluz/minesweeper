<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Minesweeper extends Model
{
	protected $fillable = ['x', 'y', 'grid', 'token'];

	protected $casts = [
		'is_finished' => 'boolean',
		'success_game' => 'boolean',
	];


	//Set attributes before saving
	public function setGridAttribute($value)
	{
        $this->attributes['grid'] = json_encode($value);
    	$this->token  = !$this->token ? '' : $this->token;
	}

	public function setTokenAttribute($value)
	{
        $this->attributes['token'] = empty($value) ? \Hash::make('').time() : $value;
	}

	public function setPlayingGridAttribute($value)
	{
        $this->attributes['playing_grid'] = json_encode($value,JSON_FORCE_OBJECT);
	}

	//Custom Attributes
	public function getOriginalGridAttribute()
	{
		return json_decode($this->grid);

	}

	public function getGridGameAttribute()
	{
		return !$this->playing_grid ? [] : json_decode($this->playing_grid, true );
	}


	//functions
	public function clickCoordinate($x, $y)
	{
		if(!is_numeric($x) || !is_numeric($y))
			abort(404, 'X,Y must be numeric, integer');

		if($this->is_finished)
			return [
				'is_finished' => true,
				'success_game' => $this->success_game,
				'grid' => $this->grid_game
			];

		$playingGame = new \App\Repositories\PlayingGameRepository;
		$response = $playingGame->coordinate_selected($x, $y, $this->original_grid, $this->grid_game);


		$this->is_finished = $response['is_finished'];
		$this->success_game = $response['success_game'];
		$this->playing_grid = $response['grid'];
		$this->save();

		return $response;
	}
}
