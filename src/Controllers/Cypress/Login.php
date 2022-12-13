<?php
declare(strict_types=1);

namespace Forme\Framework\Controllers\Cypress;

use Forme\Framework\Controllers\AbstractController;
use Forme\Framework\Models\User;
use Laminas\Diactoros\Response\JsonResponse;

final class Login extends AbstractController
{
    public function __invoke($request)
    {
        $id         = $request->input('id') ?? null;
        $role       = $request->input('role') ?: 'subscriber';
        $user       = null;

        if ($id) {
            /** @var ?User */
            $user = User::find($id);
        }

        if (!$user) {
            $data = [
                'user_login' => faker()->userName,
                'user_pass'  => faker()->password,
                'role'       => $role,
            ];
            if ($id) {
                $data['ID'] = $id;
            }
            $id   = wp_insert_user($data);
            $user = User::find($id);
        }

        wp_clear_auth_cookie();
        wp_set_current_user($id);
        wp_set_auth_cookie($id);

        return new JsonResponse($user);
    }
}
