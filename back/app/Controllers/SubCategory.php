<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\RequestTrait;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Models\SubCategory as SubCategoryModel;

class SubCategory extends ResourceController
{
    use RequestTrait;

    protected $subCategories;
    protected $db;
    protected $spreadsheet;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->subCategories = new SubCategoryModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $builder = $this->db->table('subCategory');
        $builder->select('subCategory.idSubCategory, subCategory.name AS name, subCategory.icon AS icon, category.name AS nameCategory, subCategory.idCategory')->join('category', 'category.idCategory = subCategory.idCategory');
        $subCategories = $builder->get()->getResultArray();
        $response = [
            'isError' => false,
            'data' => $subCategories,
            'message' => null
        ];
        return $this->respond($response);
    }

    public function show($id = null)
    {
        $idSubCategory = (int) $id;
        $subCategory = $this->subCategories->where('idSubCategory', $idSubCategory)->first();

        if (empty($subCategory)) {
            $response = [
                'isError' => false,
                'data' => null,
                'message' => "Подкатегория по ID $idSubCategory не найдена."
            ];
            return $this->respond($response, $response['message']);
        }

        $response = [
            'isError' => false,
            'data' => $subCategory,
            'message' => null
        ];
        return $this->respond($response);
    }

    public function new()
    {
        //
    }

    public function create()
    {
        $data = [
            'name' => $this->request->getVar('name'),
            'icon' => $this->request->getVar('icon'),
            'idCategory' => $this->request->getVar('idCategory'),
            'idSubCategory' => $this->request->getVar('idSubCategory')
        ];

        $action = empty($data['idSubCategory']) ? 'создана' : 'изменена';

        $isCreated = $this->subCategories->save($data);
        if ($isCreated) {
            $response = [
                'isError' => false,
                'data' => null,
                'message' => "Подкатегория успешно $action."
            ];
            return $this->respondCreated($response, $response['message']);
        } 

        return $this->fail('Error');
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

    public function delete($id = null)
    {
        $idSubCategory = (int) $id;
        $isDeleted = $this->subCategories->delete($idSubCategory);
        if ($isDeleted) {
            $response = [
                'isError' => false,
                'data' => null,
                'message' => 'Подкатегория успешно удалена.'
            ];
            return $this->respondDeleted($response, $response['message']);
        }
    }

}
