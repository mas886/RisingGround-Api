<?php

/*
 * Copyright (C) 2017 mas886/redrednose/arnau and judit09/tinez09/judit
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './vendor/autoload.php';
include_once("./class/User.php");
include_once("./class/Message.php");
include_once("./class/GameMessage.php");
include_once("./class/Character.php");
include_once("./class/CharacterMonster.php");
include_once("./class/Dungeon.php");
include_once("./class/Build.php");
include_once("./class/Item.php");
include_once("./class/Monster.php");
include_once("./class/Reward.php");
include_once("./class/Shop.php");

$app = new \Slim\App;

//User system functions

$app->post('/user/logout', function (Request $request, Response $response, $args = []) {
    $token = $request->getParam('token');
    $user = new User();
    //Will return 1 if successfull 0 if fail
    $response->getBody()->write(json_encode(array('Message' => $user->logout($token))));
    return $response;
});

$app->post('/user/login', function (Request $request, Response $response, $args = []) {
    $username = $request->getParam('username');
    $password = $request->getParam('password');
    $user = new User();
    //Will return 0 if failed, the token if successfull
    $response->getBody()->write(json_encode(array('Message' => $user->login($username, $password))));
    return $response;
});

$app->post('/user/signup', function (Request $request, Response $response, $args = []) {
    $username = $request->getParam('username');
    $password = $request->getParam('password');
    $email = $request->getParam('email');
    $user = new User();
    //Will return 1 when successfull
    $response->getBody()->write(json_encode(array('Message' => $user->signUp($username, $password, $email))));
    return $response;
});

$app->post('/user/getuser', function (Request $request, Response $response, $args = []) {
    $token = $request->getParam('token');
    $user = new User();
    //Will returns an array with the gold and gems of an user when successfull
    $response->getBody()->write(json_encode(array('Message' => $user->getUser($token))));
    return $response;
});

$app->post('/user/checktoken', function (Request $request, Response $response, $args = []) {
    $token = $request->getParam('token');
    $user = new User();
    //Will return 1 when successfull
    $response->getBody()->write(json_encode(array('Message' => $user->tokenIsValid($token))));
    return $response;
});

//User to user messaging system functions

$app->post('/message/send', function (Request $request, Response $response, $args = []) {
    $token = $request->getParam('token');
    $receiver = $request->getParam('receiver');
    $text = $request->getParam('text');
    $message = new Message;
    //Will return 1 when successfull
    $response->getBody()->write(json_encode(array('Message' => $message->sendMessage($token, $receiver, $text))));
    return $response;
});

$app->post('/message/getmessages', function (Request $request, Response $response, $args = []) {
    $token = $request->getParam('token');
    $message = new Message;
    //Will return an array with the messages / empty if successfull
    $response->getBody()->write(json_encode(array('Message' => $message->getMessages($token))));
    return $response;
});

$app->post('/message/deletemessage', function (Request $request, Response $response, $args = []) {
    $token = $request->getParam('token');
    $messageId = $request->getParam('messageId');
    $message = new Message;
    //Will return an array with the messages / empty if successfull
    $response->getBody()->write(json_encode(array('Message' => $message->deleteMessage($token, $messageId))));
    return $response;
});

//Game to user messaging system functions

$app->post('/gamemessage/getmessages', function (Request $request, Response $response, $args = []) {
    $token = $request->getParam('token');
    $gamemessage = new GameMessage;
    //Will return an array with the messages / empty if successfull
    $response->getBody()->write(json_encode(array('Message' => $gamemessage->getMessages($token))));
    return $response;
});

$app->post('/gamemessage/deletemessage', function (Request $request, Response $response, $args = []) {
    $token = $request->getParam('token');
    $messageId = $request->getParam('messageId');
    $gamemessage = new GameMessage;
    //Will return an array with the messages / empty if successfull
    $response->getBody()->write(json_encode(array('Message' => $gamemessage->deleteMessage($token, $messageId))));
    return $response;
});

//Character system functions

$app->post('/character/addcharacter', function (Request $request, Response $response, $args = []) {
    $characterName = $request->getParam('characterName');
    $token = $request->getParam('token');
    $character = new Character;
    //Will return 1 when successfull
    $response->getBody()->write(json_encode(array('Message' => $character->addCharacter($characterName, $token))));
    return $response;
});

$app->post('/character/deletecharacter', function (Request $request, Response $response, $args = []) {
    $characterName = $request->getParam('characterName');
    $token = $request->getParam('token');
    $character = new Character;
    //Will return 1 when successfull
    $response->getBody()->write(json_encode(array('Message' => $character->deleteCharacter($characterName, $token))));
    return $response;
});

$app->post('/character/characterlist', function (Request $request, Response $response, $args = []) {
    $token = $request->getParam('token');
    $character = new Character;
    //Will return a characterId[] when successfull
    $response->getBody()->write(json_encode(array('Message' => $character->characterList($token))));
    return $response;
});

$app->post('/character/getcharacter', function (Request $request, Response $response, $args = []) {
    //return format: {"Message":{"userId":"x","experience":"0","buildSlots":"3","buildId":null,"amulet":null,"resting":false,"restingUntil":null}}
    $characterName = $request->getParam('characterName');
    $token = $request->getParam('token');
    $character = new Character;
    //Will return array of character's information when succesfull
    $response->getBody()->write(json_encode(array('Message' => $character->getCharacter($characterName, $token))));
    return $response;
});
$app->post('/character/updatebuild', function (Request $request, Response $response, $args = []) {
    $buildId = $request->getParam('buildId');
    $characterName = $request->getParam('characterName');
    $token = $request->getParam('token');
    $character = new Character;
    //Will return a 1 when succesfull
    $response->getBody()->write(json_encode(array('Message' => $character->selectBuild($buildId, $characterName, $token))));
    return $response;
});


//character_monsters system functions

$app->post('/charactermonster/monsterlist', function (Request $request, Response $response, $args = []) {
    $characterName = $request->getParam('characterName');
    $token = $request->getParam('token');
    $characterMonster = new CharacterMonster;
    //Will return a 1 when succesfull
    $response->getBody()->write(json_encode(array('Message' => $characterMonster->monsterList($characterName, $token))));
    return $response;
});

$app->post('/charactermonster/changebuild', function (Request $request, Response $response, $args = []) {
    $characterMonsterId = $request->getParam('characterMonsterId');
    $buildId = $request->getParam('buildId');
    $token = $request->getParam('token');
    $characterMonster = new CharacterMonster;
    //Will return a 1 when succesfull
    $response->getBody()->write(json_encode(array('Message' => $characterMonster->changeBuild($characterMonsterId, $buildId, $token))));
    return $response;
});
$app->post('/charactermonster/getcharactermonster', function (Request $request, Response $response, $args = []) {
    $characterMonsterId = $request->getParam('characterMonsterId');
    $token = $request->getParam('token');
    $characterMonster = new CharacterMonster;
    //Will return a 1 when succesfull
    $response->getBody()->write(json_encode(array('Message' => $characterMonster->getCharacterMonster($characterMonsterId, $token))));
    return $response;
});

//Dungeon system functions

$app->post('/dungeon/getavailabledungeons', function (Request $request, Response $response, $args = []) {
    $characterName = $request->getParam('characterName');
    $token = $request->getParam('token');
    $dungeon = new Dungeon;
    //Will return a 1 when succesfull
    $response->getBody()->write(json_encode(array('Message' => $dungeon->getCharacterAvailableDungeons($characterName, $token))));
    return $response;
});

$app->post('/dungeon/listdungeonlevels', function (Request $request, Response $response, $args = []) {
    $characterName = $request->getParam('characterName');
    $token = $request->getParam('token');
    $dungeonId = $request->getParam('dungeonId');
    $dungeon = new Dungeon;
    //Will return a 1 when succesfull
    $response->getBody()->write(json_encode(array('Message' => $dungeon->listDungeonLevels($characterName, $token, $dungeonId))));
    return $response;
});

$app->post('/dungeon/getlevelstages', function (Request $request, Response $response, $args = []) {
    $characterName = $request->getParam('characterName');
    $token = $request->getParam('token');
    $levelId = $request->getParam('levelId');
    $dungeon = new Dungeon;
    //Will return level stages when successful
    $response->getBody()->write(json_encode(array('Message' => $dungeon->getCharacterDungeonLevelStages($characterName, $token, $levelId))));
    return $response;
});

$app->post('/dungeon/proceedlevel', function (Request $request, Response $response, $args = []) {
    $characterName = $request->getParam('characterName');
    $token = $request->getParam('token');
    $stageId = $request->getParam('stageId');
    $dungeon = new Dungeon;
    //Will return level stages when successful
    $response->getBody()->write(json_encode(array('Message' => $dungeon->proceedLevel($characterName, $token, $stageId))));
    return $response;
});

//Build system functions

$app->post('/build/addbuild', function (Request $request, Response $response, $args = []) {
    $characterName = $request->getParam('characterName');
    $buildName = $request->getParam('buildName');
    $token = $request->getParam('token');
    $build = new Build;
    //Will return 1 when successful
    $response->getBody()->write(json_encode(array('Message' => $build->addBuild($characterName, $buildName, $token))));
    return $response;
});

$app->post('/build/deletebuild', function (Request $request, Response $response, $args = []) {
    $buildId = $request->getParam('buildId');
    $token = $request->getParam('token');
    $build = new Build;
    //Will return 1 when successful
    $response->getBody()->write(json_encode(array('Message' => $build->deleteBuild($buildId, $token))));
    return $response;
});

$app->post('/build/changename', function (Request $request, Response $response, $args = []) {
    $buildId = $request->getParam('buildId');
    $buildName = $request->getParam('buildName');
    $token = $request->getParam('token');
    $build = new Build;
    //Will return 1 when successful
    $response->getBody()->write(json_encode(array('Message' => $build->changeName($buildId, $buildName, $token))));
    return $response;
});

$app->post('/build/getcharacterbuilds', function (Request $request, Response $response, $args = []) {
    $characterName = $request->getParam('characterName');
    $token = $request->getParam('token');
    $build = new Build;
    //Will return a id array of character builds when successful
    $response->getBody()->write(json_encode(array('Message' => $build->getCharacterBuilds($characterName, $token))));
    return $response;
});

//Monster 

$app->post('/monster/getmonster', function (Request $request, Response $response, $args = []) {
    $monsterId = $request->getParam('monsterId');
    $token = $request->getParam('token');
    $monster = new Monster;
    //Will return monster values array when successful 
    $response->getBody()->write(json_encode(array('Message' => $monster->getMonster($monsterId, $token))));
    return $response;
});

//Item

$app->post('/item/getitem', function (Request $request, Response $response, $args = []) {
    $itemId = $request->getParam('itemId');
    $token = $request->getParam('token');
    $item = new Item;
    //Will return item values array when successful 
    $response->getBody()->write(json_encode(array('Message' => $item->getItem($itemId, $token))));
    return $response;
});

//Reward

$app->post('/reward/listavailablerewards', function (Request $request, Response $response, $args = []) {
    $characterName = $request->getParam('characterName');
    $token = $request->getParam('token');
    $reward = new Reward;
    //Will return a 1 when succesfull
    $response->getBody()->write(json_encode(array('Message' => $reward->listAvailableRewards($characterName, $token))));
    return $response;
});

$app->post('/reward/claimreward', function (Request $request, Response $response, $args = []) {
    $characterName = $request->getParam('characterName');
    $token = $request->getParam('token');
    $rewardId = $request->getParam('rewardId');
    $reward = new Reward;
    //Will return a 1 when succesfull
    $response->getBody()->write(json_encode(array('Message' => $reward->claimReward($characterName, $token, $rewardId))));
    return $response;
});

//shop funcions
$app->post('/shop/getarticles', function (Request $request, Response $response, $args = []) {
    $token = $request->getParam('token');
    $shop = new Shop;
    //Will return a shop array->(gold, gems)->inside->(items to buy) when succesfull
    $response->getBody()->write(json_encode(array('Message' => $shop->getArticles($token))));
    return $response;
});

$app->post('/shop/buyarticlegold', function (Request $request, Response $response, $args = []) {
    $articleId = $request->getParam('articleId');
    $characterName = $request->getParam('characterName');
    $token = $request->getParam('token');
    $amount = $request->getParam('amount');
    $shop = new Shop;
    //Will return 1 when succesfull
    $response->getBody()->write(json_encode(array('Message' => $shop->buyArticleGold($articleId, $characterName, $amount, $token))));
    return $response;
});

$app->post('/shop/buyarticlegems', function (Request $request, Response $response, $args = []) {
    $articleId = $request->getParam('articleId');
    $characterName = $request->getParam('characterName');
    $token = $request->getParam('token');
    $amount = $request->getParam('amount');
    $shop = new Shop;
    //Will return 1 when succesfull
    $response->getBody()->write(json_encode(array('Message' => $shop->buyArticleGems($articleId, $characterName, $amount, $token))));
    return $response;
});

$app->run();
