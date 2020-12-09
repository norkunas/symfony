<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Notifier\Channel;

use Symfony\Component\Notifier\Message\WebPushMessage;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Notification\WebPushNotificationInterface;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

/**
 * @author Tomas Norkūnas <norkunas.tom@gmail.com>
 */
class WebPushChannel extends AbstractChannel
{
    public function notify(Notification $notification, RecipientInterface $recipient, string $transportName = null): void
    {
        $message = null;
        if ($notification instanceof WebPushNotificationInterface) {
            $message = $notification->asWebPushMessage($recipient, $transportName);
        }

        if (null === $message) {
            $message = WebPushMessage::fromNotification($notification);
        }

        if (null !== $transportName) {
            $message->transport($transportName);
        }

        if (null === $this->bus) {
            $this->transport->send($message);
        } else {
            $this->bus->dispatch($message);
        }
    }

    public function supports(Notification $notification, RecipientInterface $recipient): bool
    {
        return true;
    }
}
