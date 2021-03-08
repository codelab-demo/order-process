<?php


namespace App\Serializer;
use App\Message\modifyOrder;
use App\Message\newOrder;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;


class orderSerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        $body = $encodedEnvelope['body'];
        $headers = $encodedEnvelope['headers'];

        $stamps = [];
        if (isset($headers['stamps'])) {
            $stamps = unserialize($headers['stamps']);
        }

        $data = json_decode($body, true);
        if(array_key_exists('orderStatus',$data)) {
            $message = new modifyOrder($data['orderId'],$data['orderStatus']);
        } else {
            $message = new newOrder($data['orderId']);
        }
        return new Envelope($message, $stamps);
    }
    public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();


        if ($message instanceof newOrder) {

            $data = ['orderId' => $message->getOrderId()];
        } elseif ($message instanceof modifyOrder) {

            $data = ['orderId' => $message->getOrderId(),'orderStatus' => $message->getOrderStatus()];
        } else {
            throw new \Exception('Unsupported message class');
        }
        $allStamps = [];
        foreach ($envelope->all() as $stamps) {
            $allStamps = array_merge($allStamps, $stamps);
        }
        return [
            'body' => json_encode($data),
            'headers' => [
                'stamps' => serialize($allStamps)
            ],
        ];
    }
}