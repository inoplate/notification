<?php

namespace Inoplate\Notification\Infrastructure\Repositories;

use Inoplate\Notifier\NotifRepository;
use Inoplate\Notifier\Laravel\EloquentNotif;
use Inoplate\Notification\Notification as Model;

class Notification implements NotifRepository
{
    /**
     * @var Inoplate\Notification\Notification
     */
    protected $model;

    /**
     * @var Inoplate\Notifier\Laravel\EloquentNotif
     */
    protected $baseRepository;

    /**
     * Create new Notification instance
     * 
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->baseRepository = new EloquentNotif($model);
    }

    /**
     * Insert new notification
     * 
     * @param  string $message
     * @param  string $userId
     * @return void
     */
    public function insert($message, $userId)
    {
        $this->baseRepository->insert($message, $userId);
    }

    /**
     * Update notification
     * 
     * @param  mixed $id
     * @param  array $notification
     * @return void
     */
    public function update($id, array $notification)
    {
        $model = $this->model->find($id);
        $model->message = $notification['message'];
        $model->viewed = $notification['viewed'];

        $model->save();
    }

    /**
     * Count unviewed notifications by user id
     * @param  mixed $userId
     * @return int
     */
    public function countUnviewedByUserId($userId)
    {
        return $this->model->where('user_id', $userId)
                           ->where('viewed', 0)
                           ->count();
    }

    /**
     * Retrieve pagination
     * 
     * @param  string $userId
     * @param  integer $start
     * @param  integer $perPage
     * @return Model
     */
    public function get($userId, $page, $perPage)
    {
        return $this->model->where('user_id', $userId)
                           ->skip(($page-1) * $perPage)
                           ->take($perPage)
                           ->orderBy('created_at', 'desc')
                           ->get()
                           ->toArray();
    }

    /**
     * Count data
     *
     * @param  string $userId
     * @return integer
     */
    public function count($userId)
    {
        return $this->model->where('user_id', $userId)->count();
    }
}