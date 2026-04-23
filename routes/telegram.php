<?php
/** @var SergiX44\Nutgram\Nutgram $bot */

use App\Telegram\Conversations\ExpenseConversation;
use App\Models\Transaction;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

$bot->onCommand('start', ExpenseConversation::class);
$bot->onCommand('add', ExpenseConversation::class);

$bot->onCommand('balance', function (Nutgram $bot) {
    $income = Transaction::where('chat_id', $bot->chatId())->where('type', 'income')->sum('base_amount_uzs');
    $expense = Transaction::where('chat_id', $bot->chatId())->where('type', 'expense')->sum('base_amount_uzs');
    $balance = $income - $expense;

    $bot->sendMessage("💰 *Net Balance*: " . number_format($balance) . " UZS\n\n📈 Income: " . number_format($income) . "\n📉 Expense: " . number_format($expense), [
        'parse_mode' => 'Markdown'
    ]);
});

$bot->onCommand('stats', function (Nutgram $bot) {
    sendStats($bot, 'daily');
});

$bot->onCallbackQueryData('stats:(.*)', function (Nutgram $bot, $period) {
    sendStats($bot, $period);
    $bot->answerCallbackQuery();
});

function sendStats(Nutgram $bot, $period) {
    $query = Transaction::where('chat_id', $bot->chatId());
    
    if ($period === 'daily') {
        $query->whereDate('created_at', now()->today());
        $label = "Today's";
    } elseif ($period === 'weekly') {
        $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        $label = "This Week's";
    } elseif ($period === 'monthly') {
        $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        $label = "This Month's";
    }

    $income = (clone $query)->where('type', 'income')->sum('base_amount_uzs');
    $expense = (clone $query)->where('type', 'expense')->sum('base_amount_uzs');
    $debt = (clone $query)->where('type', 'debt')->sum('base_amount_uzs');

    $message = "📊 *$label Stats*\n\n" .
               "📥 Income: " . number_format($income) . " UZS\n" .
               "📤 Expense: " . number_format($expense) . " UZS\n" .
               "💸 Debts: " . number_format($debt) . " UZS";

    $keyboard = InlineKeyboardMarkup::make()
        ->addRow(
            InlineKeyboardButton::make('Daily', callback_data: 'stats:daily'),
            InlineKeyboardButton::make('Weekly', callback_data: 'stats:weekly'),
            InlineKeyboardButton::make('Monthly', callback_data: 'stats:monthly')
        );

    if ($bot->isCallbackQuery()) {
        $bot->editMessageText($message, [
            'parse_mode' => 'Markdown',
            'reply_markup' => $keyboard
        ]);
    } else {
        $bot->sendMessage($message, [
            'parse_mode' => 'Markdown',
            'reply_markup' => $keyboard
        ]);
    }
}

$bot->onText('.*', ExpenseConversation::class);
