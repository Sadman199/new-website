<?php

namespace Tests\Unit;

use App\Support\RichText;
use PHPUnit\Framework\TestCase;

class RichTextTest extends TestCase
{
    public function test_to_plain_text_strips_legacy_paragraph_markup(): void
    {
        $html = '<p></p><p></p><p dir="auto" style="white-space-collapse: preserve;">Commission-free Standard accounts; $3.50 per side on Raw (up to $7/lot round-turn); no inactivity or deposit fees.</p>';

        $this->assertSame(
            'Commission-free Standard accounts; $3.50 per side on Raw (up to $7/lot round-turn); no inactivity or deposit fees.',
            RichText::toPlainText($html)
        );
    }

    public function test_to_plain_text_returns_null_for_empty_markup(): void
    {
        $this->assertNull(RichText::toPlainText('<p></p><p>&nbsp;</p>'));
        $this->assertNull(RichText::toPlainText(''));
        $this->assertNull(RichText::toPlainText(null));
    }

    public function test_for_display_unwraps_single_plain_paragraph(): void
    {
        $this->assertSame(
            'Fast withdrawals within 24 hours.',
            RichText::forDisplay('<p dir="auto">Fast withdrawals within 24 hours.</p>')
        );
    }

    public function test_for_display_decodes_entities_in_wrapped_paragraph(): void
    {
        $this->assertSame(
            'Swap-Free Trading with 1:1000 Leverage & AI-Powered OctaVision – Streamline Analysis for 2025 Scalpers and Copiers.',
            RichText::forDisplay('<p>Swap-Free Trading with 1:1000 Leverage &amp; AI-Powered OctaVision – Streamline Analysis for 2025 Scalpers and Copiers.</p>')
        );
    }

    public function test_to_plain_text_decodes_entities(): void
    {
        $this->assertSame(
            'Swap-Free Trading with 1:1000 Leverage & AI-Powered OctaVision – Streamline Analysis for 2025 Scalpers and Copiers.',
            RichText::toPlainText('<p>Swap-Free Trading with 1:1000 Leverage &amp; AI-Powered OctaVision – Streamline Analysis for 2025 Scalpers and Copiers.</p>')
        );
    }

    public function test_list_items_extracts_list_entries(): void
    {
        $html = '<ul><li><p>Low spreads</p></li><li>Strong regulation</li></ul>';

        $this->assertSame(['Low spreads', 'Strong regulation'], RichText::listItems($html));
    }
}
