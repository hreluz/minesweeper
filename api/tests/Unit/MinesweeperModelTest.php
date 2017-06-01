<?php
use App\Minesweeper;
use App\Repositories\MinesweeperRepository;

class MinesweeperModelTest extends TestCase
{
	public function test_adding_grid_generates_a_json_grid()
	{
		$minesweeper = factory(Minesweeper::class)->make([
			'x' => 5,
			'y' => 5,
			'grid' => $this->getTestGrid(),
			'token' => '$123498jvnmv'
		]);

		$this->assertSame(json_encode($this->getTestGrid()),$minesweeper->grid);
	}

	public function test_adding_a_token_automatically_if_it_is_not_send()
	{
		$minesweeper = factory(Minesweeper::class)->make([
			'x' => 5,
			'y' => 5,
			'grid' => $this->getTestGrid()
		]);
		
		$this->assertTrue(!empty(strlen($minesweeper->token)));
	}
}
