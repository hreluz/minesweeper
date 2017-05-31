<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\PlayingGameRepository;

class VerifyTokenGame
{

    public function __construct(PlayingGameRepository $playingGameRepository)
    {
        $this->playingGameRepository = $playingGameRepository;
    }

    public function handle($request, Closure $next)
    {
        //Get the game token from the header or the content
        $token = !empty($request->headers->get('token-game')) ? $request->headers->get('token-game')  : $request->get('token-game');
        $playingGame = $this->playingGameRepository->verifyTokenGame($token);

        if(!$playingGame){
            return response()->json(['error' => 'unauthorized' ], 401);
        }
        return $next($request);
    }
}
