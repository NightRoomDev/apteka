<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\ProductModel;

class Products extends ResourceController
{

    use ResponseTrait;

    private ProductModel $products;

    public function __construct() {
        $this->products = new ProductModel();
    }

    public function index(): ResponseInterface
    {

        $data = $this->products
            ->select('
                idProduct, product.name AS productName, text, 
                price, formRelease, imageProduct, 
                quantity, product.idCategory, category.name AS categoryName, category.icon
            ')
            ->join('category', 'category.idCategory = product.idCategory')
            ->findAll();
        $data ?? $data;
        $response = [
            'isError' => false,
            'data' => $data,
            'message' => ''
        ];
        return $this->respond($response);
    }

    public function getProductsByCategoryId(int $id): ResponseInterface {

        $response = [];
        $id ?? $id;

        $data = $this->products
            ->select('
                idProduct, product.name AS productName, text, 
                price, formRelease, imageProduct, 
                quantity, product.idCategory, category.name AS categoryName, category.icon
            ')
            ->join('category', 'category.idCategory = product.idCategory')
            ->where('product.idCategory', $id)
            ->findAll();


        if (empty($data)) {
            $response = [
                'isError' => false, 
                'data' => [],
                'message' => 'Товаров по такой категории не найдено.'
            ];
            return $this->respond($response);
        }

        $response = [
            'isError' => false,
            'data' => $data,
            'message' => ''
        ];

        return $this->respond($response);
    
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $response = [];
        $id ?? $id;

        $data = $this->products
            ->select('
                idProduct, product.name AS productsName, text, 
                price, formRelease, imageProduct, 
                quantity, product.idCategory, category.name AS categoryName, category.icon
            ')
            ->join('category', 'category.idCategory = product.idCategory')
            ->where('product.idProduct', $id)
            ->findAll();

        if (empty($data)) {
            $response = [
                'isError' => false,
                'data' => [],
                'message' => "Товара с ID {$id} не найдено."
            ];
            return $this->respond($response);
        }

        $response = [
            'isError' => false,
            'data' => $data,
            'message' => ''
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
        $data = [
            'idProduct' => $this->request->getVar('idProduct'),
            'name' => $this->request->getVar('productName'),
            'text' => $this->request->getVar('text'),
            'price' => $this->request->getVar('price'),
            'formRelease' => $this->request->getVar('formRelease'),
            'quantity' => $this->request->getVar('quantity'),
            'idCategory' => $this->request->getVar('idCategory'),
            'imageProduct' => $this->request->getVar('imageProduct')
        ];

        $action = empty($data['idProduct']) ? 'создана' : 'изменена';
        $isCreated = $this->products->save($data);

        if ($isCreated) {
            $response = [
                'isError' => false,
                'data' => null,
                'message' => "Товар успешно $action."
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
        $idProduct = (int) $id;
        $isDeleted = $this->products->delete($idProduct);
        if ($isDeleted) {
            $response = [
                'isError' => false,
                'data' => null,
                'message' => 'Товар успешно удалена.'
            ];
            return $this->respondDeleted($response, $response['message']);
        }
    }
}
