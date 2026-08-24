<?php

namespace Tests\Unit;

use App\Support\JsonList;
use App\Support\RichText;
use PHPUnit\Framework\TestCase;

class JsonListTest extends TestCase
{
    public function test_normalize_decodes_json_array_string(): void
    {
        $raw = '["Standard Accounts", "Islamic Account", "Raw Account", "Fast Order Execution"]';

        $this->assertSame(
            ['Standard Accounts', 'Islamic Account', 'Raw Account', 'Fast Order Execution'],
            JsonList::normalize($raw)
        );
    }

    public function test_normalize_flattens_nested_json_in_array(): void
    {
        $raw = ['["Standard Accounts", "Islamic Account"]'];

        $this->assertSame(['Standard Accounts', 'Islamic Account'], JsonList::normalize($raw));
    }

    public function test_to_plain_text_joins_items(): void
    {
        $this->assertSame(
            'Standard Accounts, Islamic Account, Raw Account',
            JsonList::toPlainText(['Standard Accounts', 'Islamic Account', 'Raw Account'])
        );
    }

    public function test_rich_text_converts_json_array_string_to_plain_text(): void
    {
        $raw = '["Standard Accounts", "Islamic Account", "Raw Account", "Fast Order Execution"]';

        $this->assertSame(
            'Standard Accounts, Islamic Account, Raw Account, Fast Order Execution',
            RichText::toPlainText($raw)
        );
    }
}
