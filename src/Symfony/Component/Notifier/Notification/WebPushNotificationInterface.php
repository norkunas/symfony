<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Notifier\Notification;

use Symfony\Component\Notifier\Message\WebPushMessage;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

/**
 * @experimental in 5.3
 */
interface WebPushNotificationInterface
{
    public function asWebPushMessage(RecipientInterface $recipient, string $transport = null): ?WebPushMessage;
}
