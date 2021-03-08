<?php


namespace App\Serializer;
use App\Message\newDelivery;
use App\Message\receiveDelivery;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;


class deliverySerializer implements SerializerInterface
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
        if(array_key_exists('quantity',$data)) {
            $message = new receiveDelivery($data['productId'],$data['quantity']);
        } else {
            $message = new newDelivery($data['productId']);
        }
        return new Envelope($message, $stamps);
    }
    public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();


        if ($message instanceof newDelivery) {

            $data = ['productId' => $message->getProductId()];
        } elseif ($message instanceof receiveDelivery) {

            $data = ['productId' => $message->getProductId(),'quantity' => $message->getQuantity()];
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