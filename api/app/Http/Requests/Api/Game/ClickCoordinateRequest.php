<?php

namespace App\Http\Requests\Api\Game;

use Illuminate\Foundation\Http\FormRequest;
use App\Minesweeper;

class ClickCoordinateRequest extends FormRequest
{
    protected $my_rules = [];
    protected $my_messages = [];
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->my_rules();

        return [
            'x' => 'required|integer',
            'y' => 'required|integer'
        ] + $this->my_rules;
    }

    public function messages()
    {
        return $this->my_messages;
    }

    private function my_rules()
    {
        $token = !empty(\Request::header('token-game')) ? \Request::header('token-game')  : $this->request->get('token-game');
        $minesweeper = Minesweeper::where('token', $token)->firstOrFail();

        if( $this->request->get('x') >= $minesweeper->x  || $this->request->get('y')  >= $minesweeper->y ):
            $this->my_rules['coordinates'] = 'required';
            $this->my_messages['coordinates.required'] = 'The coordinates are off limit';
        endif;
    }
}
