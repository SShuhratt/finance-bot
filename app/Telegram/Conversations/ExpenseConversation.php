<?php

namespace App\Telegram\Conversations;

use App\Models\Transaction;
use App\Services\AiParserService;
use App\Services\CurrencyService;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class ExpenseConversation extends Conversation
{
    public ?float $amount = null;
    public ?string $category = null;
    public ?string $type = 'expense';
    public ?string $currency = 'UZS';
    public ?string $note = null;

    public function start(Nutgram $bot)
    {
        $bot->sendMessage('Enter your transaction (e.g., "Spent 50k for lunch" or "Received 1M salary"):');
        $this->next('parseInput');
    }

    public function parseInput(Nutgram $bot)
    {
        $text = $bot->message()?->text;
        if (!$text) {
            $bot->sendMessage('Please enter some text.');
            return;
        }

        $parser = new AiParserService();
        $parsed = $parser->parse($text);

        $this->amount = $parsed['amount'];
        $this->type = $parsed['type'];
        $this->category = $parsed['category'];
        $this->note = $parsed['note'];
        $this->currency = $parsed['currency'];

        if ($this->amount <= 0) {
            $bot->sendMessage('I couldn\'t detect the amount. Please enter it manually (e.g., 50000):');
            $this->next('askAmountManually');
            return;
        }

        if ($this->type === 'debt') {
            $bot->sendMessage('Who is the lender/borrower?');
            $this->next('askDebtPerson');
            return;
        }

        $this->confirmAndSave($bot);
    }

    public function askAmountManually(Nutgram $bot)
    {
        $this->amount = (float)$bot->message()?->text;
        if ($this->amount <= 0) {
            $bot->sendMessage('Invalid amount. Try again:');
            return;
        }
        
        if ($this->type === 'debt') {
            $bot->sendMessage('Who is the lender/borrower?');
            $this->next('askDebtPerson');
        } else {
            $this->confirmAndSave($bot);
        }
    }

    public function askDebtPerson(Nutgram $bot)
    {
        $person = $bot->message()?->text;
        $this->note .= " (Person: $person)";
        $this->confirmAndSave($bot);
    }

    public function confirmAndSave(Nutgram $bot)
    {
        $currencyService = new CurrencyService();
        $baseAmountUzs = $currencyService->convertToUzs($this->amount, $this->currency);

        Transaction::create([
            'chat_id' => $bot->chatId(),
            'type' => $this->type,
            'amount' => $this->amount,
            'base_amount_uzs' => $baseAmountUzs,
            'category' => $this->category,
            'note' => $this->note,
            'status' => $this->type === 'debt' ? 'pending' : 'paid',
        ]);

        $bot->sendMessage("✅ Saved {$this->type}: " . number_format($this->amount) . " {$this->currency} (" . number_format($baseAmountUzs) . " UZS)");
        $this->end();
    }
}
