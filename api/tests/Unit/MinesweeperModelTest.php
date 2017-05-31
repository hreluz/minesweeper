<?php
use App\Minesweeper;
use App\Repositories\MinesweeperRepository;

class MinesweeperModelTest extends TestCase
{
	public function test_adding_grid_generates_a_json_grid()
	{
		$minesweeperRepository = new MinesweeperRepository(5,5,3);

		$minesweeper = factory(Minesweeper::class)->make([
			'x' => 5,
			'y' => 5,
			'grid' => $this->getTestGrid(),
			'token' => '$123498jvnmv'
		]);

		$this->assertSame(json_encode($this->getTestGrid()),$minesweeper->grid);
	}
}
