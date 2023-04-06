<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'product';
    protected $primaryKey       = 'idProduct';
    protected $allowedFields    = ['name', 'text', 'price', 'imageProduct', 'formRelease', 'quantity', 'idCategory'];
}
