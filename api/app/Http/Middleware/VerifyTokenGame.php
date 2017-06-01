<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\PlayingGameRepository;
use Symfony\Component\HttpFoundation\HeaderBag;

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
        
        $request->server->set('HTTP_ACCEPT', 'application/json');
        $request->headers = new HeaderBag($request->server->getHeaders());
        return $next($request);
    }
}
