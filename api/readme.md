<p align="center"><img src="https://lh5.ggpht.com/Z38RGi9W_pEdZDmEYafzHviPk2GXQ2_RmQ0xbGEZbjp7H6LgPOoq-j0Di65MgSmhjxD_=w300"></p>


## About Minesweeper API
Hi, looks like you have been looking for a minesweepers API, this is your lucky day, you found the coolest API

We have all the tests you need integration, unit, feature tests, I mean tests codes, not classroom tests hahaha

This is a very easy API to use, we have just 2 urls, how hard could be, right?

So let's go 

1. If you would like to get a minesweeper, you will call this url, is a GET resource
> [/api/minesweepers/create](https://api/minesweepers/create).

If everything goes right(of course it will), you wil get a JSON like this :
```json
{
     "result": true,
     "token": "this is the token of your game", 
     "x": "this is a number, and it will let you how many horizontal rows you have to create",
     "y": "this is a number too , and it will let you how many vertical rows you have to create",
}
```


What more? That's it, you have now a minesweeper API to consume with the toke we gave you

2. Now, you want to play right? . Ok take it easy pal', we just need one more url and we are done.  The url is this one, is a POST resource :

> [/api/game/select_coordinate](https://api/game/select_coordinate).

You will just have to send us your X and Y coordinate selected, and of course the token in the header, because we are not wizards, at least not now .

So in the header you will send the token, like this
token: "here goes your token"

We know sometimes some developer are lazy, so you can send the token in the content too, we will do that work for you, no worries

Then, in the content you should send the x and y coordinate you selected, like this :
```
x : number x position, 
y : number y position
```
That's all you have to send, so let's sum up you just need to send this

```
token: " the token of the game you are asking",
x : number x position,
y : number y position
```
If you are thinking to send another types of values to break or hack our API, then try it Mr Robot, we have a very sofisticated validation that will tell you what are you doing wrong

If you are not trying to break it, let's keep going.
The API will answer with the result of your move, there are just three types of answer you will expect, the keep playing, the win, and game over answer

In the three of them you will receive this :
```json
{
  "is_finished" : "boolean type",
  "success_game": "boolean_type",
  "grid": "an array with the coordinates you will have to replace"
}
```

## The keep playing answer :
If the *is_finished* and *success_game* are **false**
That means you have not selected a mine

## The WIN answer :
If the *is_finished* and *sucess_game* are **true**
That will happen when you unlock the grid without selecting a mine

## The GAME OVER answer :
When *is_finished* is **true** and *sucess_game* is **false**
This will occur when you have selected a mine

That's all . Enjoy the game :+1:

## Adittional Info
We know API answers can be kind of intimidating,  so we would like to explain you the answer when you are playing, specifically about the grid field. Here some examples

So in a grid where x = 5 and y = 5, and when you can **keep playing** you will get something like this

```
{
  "is_finished" : false,
  "success_game": false,
  "grid": [
     0 => [
       1 => 1,
       2 => 2,
     ]
  ]
}
```
You will have to show something like this


```
|   | 1 | 2 |   |   |
______________________
|   |   |   |   |   |
______________________
|   |   |   |   |   |
______________________
|   |   |   |   |   |
______________________
|   |   |   |   |   |

```

In a grid where x = 5 and y = 5, and when you  **game is over** you will get something like this

```
{
  "is_finished" : true,
  "success_game": false,
  "grid": [
     4 => [
       2 => -1,
     ],
     1 => [
       3 => -1
     ],
     0 => [
       0 => -1,
       3 => -1
     ]
  ]
}
```
You will have to show something like this


```
| * |   | * |   |   |
______________________
|   |   | * |   |   |
______________________
|   |   |   |   |   |
______________________
|   |   | * |   |   |
______________________
|   |   |   |   |   |

```
