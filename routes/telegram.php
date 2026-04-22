<?php
/** @var SergiX44\Nutgram\Nutgram $bot */

use App\Telegram\Conversations\ExpenseConversation;
use SergiX44\Nutgram\Nutgram;

$bot->onCommand('start', ExpenseConversation::class);

$bot->onText('([0-9]+)', ExpenseConversation::class);

