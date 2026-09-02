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

    public function test_for_display_keeps_headings_and_lists(): void
    {
        $html = '<h2>Low spreads</h2><p>We ranked brokers on <strong>cost</strong>.</p><ul><li>EURUSD</li></ul>';

        $this->assertStringContainsString('<h2>', (string) RichText::forDisplay($html));
        $this->assertStringContainsString('<ul>', (string) RichText::forDisplay($html));
        $this->assertStringContainsString('<strong>', (string) RichText::forDisplay($html));
    }

    public function test_sanitize_strips_scripts_and_keeps_safe_markup(): void
    {
        $html = '<p onclick="alert(1)">Hello <script>alert(1)</script><a href="javascript:alert(1)">x</a><a href="https://example.com">ok</a></p>';

        $clean = RichText::sanitize($html);

        $this->assertStringNotContainsString('<script', (string) $clean);
        $this->assertStringNotContainsString('onclick', (string) $clean);
        $this->assertStringNotContainsString('javascript:', (string) $clean);
        $this->assertStringContainsString('https://example.com', (string) $clean);
    }

    public function test_sanitize_keeps_alignment_and_color(): void
    {
        $html = '<p style="text-align: center; color: #e8822a; position: absolute;">Aligned</p>';

        $clean = RichText::sanitize($html);

        $this->assertStringContainsString('text-align: center', (string) $clean);
        $this->assertStringContainsString('color: #e8822a', (string) $clean);
        $this->assertStringNotContainsString('position', (string) $clean);
    }

    public function test_list_items_extracts_list_entries(): void
    {
        $html = '<ul><li><p>Low spreads</p></li><li>Strong regulation</li></ul>';

        $this->assertSame(['Low spreads', 'Strong regulation'], RichText::listItems($html));
    }
}
