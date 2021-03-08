<?php


namespace App\Message;


class modifyOrder {

    private $orderId;
    private $orderStatus;

    public function __construct(int $orderId, $orderStatus)
    {
        $this->orderId = $orderId;
        $this->orderStatus = $orderStatus;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param mixed $orderId
     */
    public function setOrderId($orderId): void
    {
        $this->orderId = $orderId;
    }

    /**
     * @return mixed
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * @param mixed $orderStatus
     */
    public function setOrderStatus($orderStatus): void
    {
        $this->orderStatus = $orderStatus;
    }
}
