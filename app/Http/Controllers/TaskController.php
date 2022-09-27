<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    function create(Request $r)
    {
        $array = ['message' => ''];

        $rules = [
            'title' => ['required', 'min:2'],
            'due_date' => ['required', 'date'],
            'category_id' => ['required']
        ];
        $validator = Validator::make($r->all(), $rules);
        if ($validator->fails()) {
            $array['message'] = $validator->messages();
            return $array;
        }

        $category = Category::find($r->category_id);
        if ($category && $category->user_id == $r->user()->id) {

            $newTask = new Task();
            $newTask->title = $r->title;
            $r->description ? $newTask->description = $r->description : false;
            $newTask->due_date = $r->due_date;
            $newTask->user_id = $r->user()->id;
            $newTask->category_id = $r->category_id;

            $newTask->save();

            $array['message'] = 'Cadastro com sucesso!';
            return $array;
        }

        $array['message'] = 'Aconteceu algum erro!';
        return $array;
    }

    function readAll(Request $r)
    {
        $array = ['message' => ''];

        $category = Category::find($r->id);
        if ($category && $category->user_id == $r->user()->id) {
            $array['category'] = $category;
            $array['tasks'] = Category::find($r->id)->tasks;

            $array['message'] = 'Tasks pegas com sucesso!';
            return $array;
        }

        $array['message'] = 'Erro ao pegar as Tasks!';
        return $array;
    }

    function update(Request $r)
    {
        $array = ['message' => ''];

        $title = $r->title;
        $description = $r->description;
        $due_date = $r->due_date;
        $category_id = $r->category_id;

        $task = Task::find($r->id);
        if ($task && $task->user_id == $r->user()->id) {
            $title ? $task->title = $title : false;
            $description ? $task->description = $description : false;
            $due_date ? $task->due_date = $due_date : false;

            if(Category::find($r->category_id)->user_id == $r->user()->id){
                $category_id ? $task->category_id = $category_id : false;
            }

            $task->save();

            $array['message'] = 'Task atualizada com sucesso!';
            return $array;
        }

        $array['message'] = 'Algo deu errado, tente novamente mais tarde!';
        return $array;
    }

    function delete($id)
    {
        $array = ['message' => ''];

        $task = Task::where('id', $id);
        $task->delete() ? $array['message'] = 'Task excluída com sucesso!' : $array['message'] = 'Task não encontrado!';

        return $array;
    }
}
