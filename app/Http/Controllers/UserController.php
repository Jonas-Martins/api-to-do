<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use App\Models\User;

class UserController extends Controller
{
    function create(Request $r)
    {
        $array = ['message' => ''];

        $rules = [
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'max:50', Password::min(4)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(),],
            'password_confirmation' => 'required | max:50 | min:4'
        ];
        $validator = Validator::make($r->all(), $rules);
        if ($validator->fails()) {
            $array['message'] = $validator->messages();
            return $array;
        }

        $name = $r->input('name');
        $email = $r->input('email');
        $password = password_hash($r->input('password'), PASSWORD_DEFAULT);

        $newUser = new User();
        $newUser->name = $name;
        $newUser->email = $email;
        $newUser->password = $password;
        $newUser->save();

        $array['message'] = 'Cadastrado com sucesso!';
        return $array;
    }

    function login(Request $r)
    {
        $array = ['message' => ''];

        $fields = $r->only('email', 'password');

        if (Auth::attempt($fields)) {
            $user = User::where('email', $fields['email'])->first();
            $item = time() . rand(0, 9999);
            $token = $user->createToken($item)->plainTextToken;

            // $user['token'] = $token;
            $array['token'] = $token;
            $array['user'] = $user;
            $array['message'] = 'Usuário logado com sucesso!';
            return $array;
        }
        
        $array['message'] = 'E-mail ou senha incorretos!';
        // return $array;
        return response($array, 500); 
    }

    function logout(Request $r)
    {
        $array = ['message' => ''];

        $user = $r->user();
        if ($user->tokens()->delete()) {
            $array['message'] = 'Deslogado com sucesso!';
            return $array;
        }

        $array['message'] = 'Erro ao deslogar!';
        return $array;
    }

    function delete(Request $r)
    {
        $array = ['message' => ''];

        $user = $r->user();
        $user->delete() ? $array['message'] = 'Usuário excluído com sucesso!' : $array['message'] = 'Usuário não encontrado!';

        return $array;
    }

    function restore(User $user, Request $r)
    {
        $user = $user->where('email', $r->email);

        // Restaura:
        $user->restore();

        // Também é possível fazer assim, em uma única linha:
        // $post = $post->find($id)->restore();
    }

    public function forceDelete(Request $r)
    {
        $array = ['message' => ''];

        $user = $r->user();
        $user->forceDelete() ? $array['message'] = 'Usuário excluído permanente com sucesso!' : $array['message'] = 'Usuário não encontrado!';

        return $array;
    }

    function update(Request $r)
    {
        $array = ['message' => ''];

        $rules = [
            'name' => ['min:3'],
            'email' => ['email', 'unique:users,email'],
            'oldPassword' => 'required',
            'newPassword' => ['confirmed', 'max:50', Password::min(4)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(),],
            'newPassword_confirmation' => 'max:50 | min:4'
        ];
        $validator = Validator::make($r->all(), $rules);
        if ($validator->fails()) {
            $array['message'] = $validator->messages();
            return $array;
        }

        $fields = ['id' => $r->user()->id];
        $fields['password'] = $r->oldPassword;
        if (Auth::guard('web')->attempt($fields)) {
            $user = $r->user();
            $r->name ? $user->name = $r->name : false;
            $r->email ? $user->email = $r->email : false;

            $r->newPassword
                ? $user->password = password_hash($r->newPassword, PASSWORD_DEFAULT)
                : false;
            $user->save();

            $array['message'] = 'Usuário atualizado com sucesso!';
            return $array;
        }

        $array['message'] = 'Senha atual não bate!';
        return $array;
    }
}
