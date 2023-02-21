<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\UserManager;
use Aura\Di\Container;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequest;

class UserController
{
    public static function userToResponse(User $user): array
    {
        return [
            'id' => $user->id(),
            'name' => $user->name(),
            'lastName' => $user->lastName(),
            'key' => $user->key(),
        ];
    }

    public static function usersByName(): callable
    {
        return function (ServerRequest $request, Container $di) {
            $request = json_decode(
                json: $request->getBody()->getContents(),
                associative: true,
                flags: JSON_THROW_ON_ERROR
            );

            if (! isset($request['name'])) {
                return new JsonResponse(
                    [
                        'status' => 'fail'
                    ]
                );
            }

            /** @var UserManager $userManager */
            $userManager = $di->get(UserManager::class);

            $data['data'] = array_map(function (User $user) {
                return self::userToResponse($user);
            }, $userManager->usersByName($request['name'])->get());

            return new JsonResponse($data);
        };
    }

    public static function usersByNames(): callable
    {
        return function (ServerRequest $request, Container $di) {
            $request = json_decode(
                json: $request->getBody()->getContents(),
                associative: true,
                flags: JSON_THROW_ON_ERROR
            );

            if (! isset($request['nameList'])) {
                return new JsonResponse(
                    [
                        'status' => 'fail'
                    ]
                );
            }

            /** @var UserManager $userManager */
            $userManager = $di->get(UserManager::class);

            $data['data'] = array_map(function (User $user) {
                return self::userToResponse($user);
            }, $userManager->usersByNames($request['nameList'])->get());

            return new JsonResponse($data);
        };
    }

    public static function usersOlder(): callable
    {
        return function (ServerRequest $request, Container $di) {
            $age = (int) $request->getAttribute('age');

            /** @var UserManager $userManager */
            $userManager = $di->get(UserManager::class);

            $data['data'] = array_map(function (User $user) {
                return self::userToResponse($user);
            }, $userManager->usersOlder($age)->get());

            return new JsonResponse($data);
        };
    }

    public static function createUser(): callable
    {
        return function (ServerRequest $request, Container $di) {
            $request = json_decode(
                json: $request->getBody()->getContents(),
                associative: true,
                flags: JSON_THROW_ON_ERROR
            );

            if (
                ! isset($request['name']) ||
                ! isset($request['lastName'])
            ) {
                return new JsonResponse(
                    [
                        'status' => 'fail'
                    ]
                );
            }

            /** @var UserManager $userManager */
            $userManager = $di->get(UserManager::class);
            $userManager->createUser($request['name'], $request['lastName']);

            return new JsonResponse(['status' => 'success']);
        };
    }

    public static function createUsers(): callable
    {
        return function (ServerRequest $request, Container $di) {
            $request = json_decode(
                json: $request->getBody()->getContents(),
                associative: true,
                flags: JSON_THROW_ON_ERROR
            );

            if (! isset($request['usersList'])) {
                return new JsonResponse(
                    [
                        'status' => 'fail'
                    ]
                );
            }

            /** @var UserManager $userManager */
            $userManager = $di->get(UserManager::class);
            $userManager->createUsers($request['usersList']);

            return new JsonResponse(['status' => 'success']);
        };
    }
}
