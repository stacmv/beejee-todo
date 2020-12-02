<?php

namespace Controllers;

use View;
use Pager;
use Models\Task;
use Models\User;
use Controllers\Controller;

class TaskController extends Controller
{
    public function add()
    {
        $name = $this->post('name');
        $email = $this->post('email');
        $text = $this->post('text');

        if ($name && $email && $text) {  // validation placeholder
            $task = new Task(compact('name', 'email', 'text'));
            $task->save();

            $this->app->session()->put('msg', "New task added.");
            $this->redirect('task/list');
        }

        $this->app->session()->put('msg', "Check form for errors. All fields must be filled. E-mail must be valid.");

        $this->redirect('task/form/add');
    }

    public function edit()
    {
        if (!$this->isAuthenticatedUser()) {
            $this->forbidden();
        }


        $task_id = $this->post('id');

        try {
            $task = Task::byId($task_id);

            $name = $this->post('name');
            $email = $this->post('email');
            $text = $this->post('text');

            if ($name && $email && $text) { // Validation placeholder

                $task->modify([
                    'name' => $name,
                    'email' => $email,
                    'text' => $text,
                    'edited' => 1, // real changes not checked, suppose "edited" id admin clicked "Save changes" on the form.
                ])->save();

                $this->app->session()->put('msg', 'Changes saved.');
                $this->redirect('task/list'); // @TODO redirect with page & sort param! Someday...
            } else {
                $this->app()->session()->put('msg', 'validation error. All fiedlds should be filled.');
                $this->redirect('task/form/edit?id=' . $task_id);
            }
        } catch (Exception $e) {
            $this->notFound();
        }
    }

    public function complete()
    {
        $this->setTaskState(Task::COMPLETED);
    }

    public function formAdd()
    {
        $page_title = "New Task";

        $old = $this->app->session()->get('old');
        if (!$old) {
            $old = [];
        }

        return (new View($this->app))->make('task/task_add', compact('page_title', 'old'));
    }

    public function formEdit()
    {
        if (!$this->isAuthenticatedUser()) {
            $this->forbidden();
        }

        $task_id = $this->get('id');

        if (!$task_id) {
            $this->redirect('task/list');
        }

        $page_title = "Edit Task #" . $task_id;

        $task = Task::byId($task_id);


        return (new View($this->app))->make('task/task_edit', compact('page_title', 'task'));
    }

    public function list(): string
    {
        $page = (int) $this->get('page') ?? 1;
        $sort = $this->get('sort');

        $tasks = Task::paginate($page, $sort);

        $page_title = "Tasks";

        $pager = new Pager('tasks/list', Task::count(), Task::ITEMS_PER_PAGE, $page, ['sort' => $sort]);

        if ($page > $pager->pages_count) {
            $this->redirect('task/list?page=' . $pager->pages_count . ($sort ? '&sort=' . $sort : ''));
        }

        return (new View($this->app))->make('task/task_list', compact('page_title', 'pager', 'sort', 'tasks'));
    }

    public function uncomplete()
    {
        $this->setTaskState(Task::UNCOMPLETED);
    }

    protected function setTaskState($state)
    {
        if (!$this->isAuthenticatedUser()) {
            $this->forbidden();
        }

        $task_id = $this->post('id') ?: $this->get('id'); // for simplicity on client side we accept post request with get parameter

        try {
            $task = Task::byId($task_id);

            $task->modify([
                'state' => $state,
            ])->save();

            $this->app->session()->put('msg', 'Task #' . $task_id . " marked as " . ($state == Task::COMPLETED ? 'completed' : 'uncompleted'));

            $this->redirectBack();
        } catch (Exception $e) {
            $this->notFound();
        }
    }
}
