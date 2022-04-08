<?php

namespace Meshgroup\Megafon;

use Illuminate\Notifications\Notification;
use Meshgroup\Megafon\Exceptions\CouldNotSendNotification;

class MegafonChannel
{
    protected $megafonApi;

    public function __construct(MegafonApi $megafonApi)
    {
        $this->megafonApi = $megafonApi;
    }

    public function send($notifiable, Notification $notification)
    {
        if (! ($to = $this->getRecipients($notifiable, $notification))) {
            return;
        }

        $message = $notification->{'toMegafon'}($notifiable);

        if (\is_string($message)) {
            $message = new MegafonMessage($message);
        }

        $this->sendMessage($to, $message);
    }

    /**
     * Gets a list of phones from the given notifiable.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     *
     * @return string[]
     */
    protected function getRecipients($notifiable, Notification $notification)
    {
        $to = $notifiable->routeNotificationFor('megafon', $notification);

        if ($to === null || $to === false || $to === '') {
            return null;
        }

        return $to;
    }

    protected function sendMessage($recipients, MegafonMessage $message)
    {
        if (\mb_strlen($message->content) > 800) {
            throw CouldNotSendNotification::contentLengthLimitExceeded();
        }

        $params = [
            'to'  => (int) $recipients,
            'message' => $message->content,
            'sender'  => $message->from,
            //'callback_url' => ''
        ];

        if ($message->sendAt instanceof \DateTimeInterface) {
            $params['time'] = '0'.$message->sendAt->getTimestamp();
        }

        $this->megafonApi->send($params);
    }
}
