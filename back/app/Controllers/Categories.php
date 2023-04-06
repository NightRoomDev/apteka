<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\CategoryModel;

class Categories extends ResourceController
{

    use ResponseTrait;

    private $categories;

    public function __construct()
    {
        $this->categories = new CategoryModel();
    }

    public function index(): ResponseInterface
    {
        $data = $this->categories->findAll();
        $data ?? $data;
        return $this->respond($data);
    }

    public function show($id = null): ResponseInterface
    {

        $data = $this->categories->find($id);

        if (empty($data)) {
            return $this->respondNoContent("Категория по ID {$id} не найдена.");
        }

        return $this->respond($data);
    }

    public function new()
    {
        //
    }

    public function create(): ResponseInterface
    {
        $data = [
            'name' => $this->request->getVar('name'),
            'icon' => $this->request->getVar('icon'),
            'idCategory' => $this->request->getVar('idCategory')
        ];

        $action = empty($data['idCategory']) ? 'создана' : 'изменена';

        $createdCategory = $this->categories->save($data);
        if ($createdCategory) {
            $response = [
                'isError' => false,
                'data' => null,
                'message' => "Категория успешно $action."
            ];
            return $this->respondCreated($response, $response['message']);
        } 

        return $this->fail('Error');
    }

    public function edit($id = null)
    {
        //
    }

    public function update($id = null)
    {
        //
    }

    public function delete($id = null)
    {
        $idCategory = (int) $id;
        $categoryDeleted = $this->categories->delete($idCategory);
        if ($categoryDeleted) {
            $response = [
                'isError' => false,
                'data' => null,
                'message' => 'Категория успешно удалена.'
            ];
            return $this->respondDeleted($response, $response['message']);
        }
    }
}
