<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UsersModel;

class User extends ResourceController
{
    
    protected $users = null;

    public function __construct()
    {
        $this->users = new UsersModel();
    }

    public function index()
    {
        $response = [
            'isError' => false,
            'data' => $this->users->findAll(),
            'message' => null
        ];
        return $this->respond($response);
    }

    public function show($id = null)
    {
        $idUser = (int) $id;

        if (empty($idUser)) {
            $this->respondNoContent('ID пользователя отсутствует.');
        }

        $user = $this->users->where('idUser', $id)->first();

        if (empty($user)) {
            $response = [
                'isError' => false,
                'data' => null,
                'message' => "Пользователя по ID $idUser не найдено."
            ];
            return $this->respond($response, $response['message']);
        }

        $response = [
            'isError' => false,
            'data' => $user,
            'message' => ""
        ];
        return $this->respond($response);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }
}
