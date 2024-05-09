<?php

namespace NotificationChannels\CustomSms;

use Illuminate\Notifications\Notification;
use NotificationChannels\CustomSms\Exceptions\CouldNotSendNotification;
use CustomSms\ApiClient\Api\Model\OutboundMessage;
use CustomSms\ApiClient\Api\Model\SendMessageBody;
use CustomSms\ApiClient\Client;

class CustomSmsChannel
{
    /** @var Client */
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function send($notifiable, Notification $notification): void
    {
        $to = $notifiable->routeNotificationFor('customsms');

        if (! $to) {
            $to = $notifiable->routeNotificationFor(CustomSmsChannel::class);
        }

        if (! $to) {
            return;
        }

        $message = $notification->toCustomsms($notifiable);

        if (is_string($message)) {
            $message = new CustomSmsMessage($message);
        }

        if (! $message instanceof CustomSmsMessage) {
            return;
        }

        $apiMessage = (new OutboundMessage())
            ->setTo($to)
            ->setFrom($message->sender ?? config('services.customsms.default_sender') ?? 'SHARED_NUMBER')
            ->setBody($message->content)
            ->setReference($message->reference)
            ->setMetadata($message->metadata);

        if ($message->sendAt) {
            $apiMessage->setDate($message->sendAt->format(\DateTimeInterface::ATOM));
        }

        $response = $this->client->sendMessages(
            (new SendMessageBody())->setMessages([$apiMessage])
        );

        if (! $response || count($response->getData()->getErrors())) {
            $error = $response->getData()->getErrors()[0];
            throw new CouldNotSendNotification($error->getErrorCode().($error->getErrorHelp() ? ' - '.$error->getErrorHelp() : ''), 400);
        }
    }
}
