<?php 


namespace App\DTOs ;

use phpDocumentor\Reflection\Types\Boolean; 


class ProductImageDTO 
{
      public function __construct(
            public readonly int $product_id ,
            public readonly string $image ,
            public readonly bool $primary=true , 
      ){}


      public function toArray()  :array 
      {
            return [
                  'product_id'=>$this->product_id , 
                  'image'=>$this->image , 
                  'primary'=>$this->primary ,
            ];
      }
}