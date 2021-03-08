<?php


namespace App\Message;


class delivery
{
    private $productId;

    public function __construct(int $productId)
    {
        $this->productId = $productId;
    }


}