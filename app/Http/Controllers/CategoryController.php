<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    function create(Request $r)
    {
        $array = ['message' => ''];

        $rules = [
            'title' => 'required',
        ];
        $validator = Validator::make($r->all(), $rules);
        if ($validator->fails()) {
            $array['message'] = $validator->messages();
            return $array;
        }

        $r->color === null ? $itens = $r->only('title') : $itens = $r->only('title', 'color');
        $itens['user_id'] = $r->user()->id;

        $category = Category::create($itens);
        $array['category'] = $category;

        return $array;
    }

    function readAll(Request $r)
    {
        $array = ['message' => ''];

        $array['categories'] = User::find($r->user()->id)->categories;

        return $array;
    }

    function update(Request $r)
    {
        $array = ['message' => ''];

        $title = $r->title;
        $color = $r->color;

        $category = Category::find($r->id);
        if ($category && $category->user_id == $r->user()->id) {
            $title ? $category->title = $title : false;
            $color ? $category->color = $color : false;
            $category->save();

            $array['message'] = 'Categoria atualizada com sucesso!';
            return $array;
        }

        $array['message'] = 'Aconteceu algum erro!';
        return $array;
    }

    function delete(Request $r, $id)
    {
        $array = ['message' => ''];

        $category = Category::find($id);

        if ($category && $category->user_id == $r->user()->id) {
            $category->delete();

            $array['message'] = 'Categoria movida para lixeira com sucesso!';
            return $array;
        }

        $array['message'] = 'Algo deu errado, tente novamente mais tarde!';
        return $array;
    }
    function restore(Category $category, $id)
    {
        $array = ['message' => ''];

        $category = $category->where('id', $id);

        // Restaura:
        $category->restore();

        return $array;
    }
    function forceDelete(Category $category, $id)
    {
        $array = ['message' => ''];

        $category = $category->where('id', $id);
        $category->forceDelete() ? $array['message'] = 'Categoria excluída permanente com sucesso!' : $array['message'] = 'Algo deu errado, tente novamente mais tarde!';

        return $array;
    }
}
