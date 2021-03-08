<?php


namespace App\Message;


class email
{
    /**
     * @var int
     */
    private $status;
    /**
     * @var int
     */
    private $orderId;

    public function __construct(int $orderId,int $status)
    {

        $this->status = $status;
        $this->orderId = $orderId;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

}