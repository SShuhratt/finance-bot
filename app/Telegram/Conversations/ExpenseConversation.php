<?php

namespace App\Telegram\Conversations;

use App\Models\Transaction;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class ExpenseConversation extends Conversation
{
    public ?float $amount = null;
    public ?string $category = null;

    public function start(Nutgram $bot)
    {
        $bot->sendMessage('How much did you spend?');
        $this->next('askCategory');
    }

    public function askCategory(Nutgram $bot)
    {
        $this->amount = (float)$bot->message()?->text;

        if ($this->amount <= 0) {
            $bot->sendMessage('Please enter a valid amount:');
            return;
        }

        $bot->sendMessage('Select a category:', [
            'reply_markup' => InlineKeyboardMarkup::make()
                ->addRow(
                    InlineKeyboardButton::make('Food', callback_data: 'Food'),
                    InlineKeyboardButton::make('Transport', callback_data: 'Transport')
                )
                ->addRow(
                    InlineKeyboardButton::make('Bill', callback_data: 'Bill'),
                    InlineKeyboardButton::make('Other', callback_data: 'Other')
                )
        ]);

        $this->next('saveExpense');
    }

    public function saveExpense(Nutgram $bot)
    {
        if (!$bot->isCallbackQuery()) {
            $bot->sendMessage('Please select a category from the buttons.');
            return;
        }

        $this->category = $bot->callbackQuery()->data;

        Transaction::create([
            'chat_id' => $bot->chatId(),
            'amount' => $this->amount,
            'category' => $this->category,
            'date' => now(),
            'note' => 'Added via Telegram Bot',
        ]);

        $bot->answerCallbackQuery();
        $bot->sendMessage("✅ Saved: {$this->amount} in {$this->category}");
        $this->end();
    }
}
