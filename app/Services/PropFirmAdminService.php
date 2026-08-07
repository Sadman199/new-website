<?php

namespace App\Services;

use App\Models\PropFirm;
use App\Models\PropFirmFaq;
use App\Models\PropFirmProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PropFirmAdminService
{
    /** @var string[] */
    protected array $fillableScalars = [
        'prop_firm_category_id', 'name', 'slug', 'description', 'website', 'affiliate_link',
        'founded_year', 'headquarters', 'max_funding', 'profit_split', 'min_fee', 'max_fee',
        'trust_score', 'editor_rating', 'user_rating', 'overall_rating',
        'meta_title', 'meta_description', 'meta_keywords', 'sort_order',
    ];

    public function save(PropFirm $propFirm, Request $request): PropFirm
    {
        $propFirm->fill($request->only($this->fillableScalars));

        $propFirm->scaling_available = $request->boolean('scaling_available');
        $propFirm->is_featured = $request->boolean('is_featured');
        $propFirm->is_verified = $request->boolean('is_verified');
        $propFirm->is_active = $request->boolean('is_active', true);

        if (empty($propFirm->slug)) {
            $propFirm->slug = Str::slug($request->input('name', $propFirm->name));
        }

        $this->handleUpload($request, 'logo', 'uploads/prop-firms/logos', 'logo_', $propFirm, 'logo');
        $this->handleUpload($request, 'cover_image', 'uploads/prop-firms/covers', 'cover_', $propFirm, 'cover_image');
        $this->handleUpload($request, 'og_image', 'uploads/prop-firms/og', 'og_', $propFirm, 'og_image');

        $propFirm->save();

        $propFirm->attributes()->sync($request->input('attribute_ids', []));
        $this->syncPrograms($propFirm, $request->input('programs', []));
        $this->syncFaqs($propFirm, $request->input('faqs', []));

        return $propFirm->fresh(['category', 'programs', 'attributes', 'faqs']);
    }

    public function delete(PropFirm $propFirm): void
    {
        foreach (['logo', 'cover_image', 'og_image'] as $field) {
            $this->deletePublicFile($propFirm->{$field});
        }

        $propFirm->delete();
    }

    /** @param array<int, array<string, mixed>> $programs */
    protected function syncPrograms(PropFirm $propFirm, array $programs): void
    {
        $keepIds = [];

        foreach ($programs as $index => $row) {
            if (empty($row['name'])) {
                continue;
            }

            $data = [
                'name' => $row['name'],
                'account_size' => $row['account_size'] ?? null,
                'entry_fee' => $row['entry_fee'] ?? null,
                'profit_target' => $row['profit_target'] ?? null,
                'daily_drawdown' => $row['daily_drawdown'] ?? null,
                'max_drawdown' => $row['max_drawdown'] ?? null,
                'profit_split' => $row['profit_split'] ?? null,
                'min_trading_days' => $row['min_trading_days'] ?? null,
                'news_trading' => ! empty($row['news_trading']),
                'weekend_holding' => ! empty($row['weekend_holding']),
                'ea_allowed' => ! empty($row['ea_allowed']),
                'copy_trading' => ! empty($row['copy_trading']),
                'hedging' => ! empty($row['hedging']),
                'refund_available' => ! empty($row['refund_available']),
                'sort_order' => (int) ($row['sort_order'] ?? $index),
                'is_active' => ! isset($row['is_active']) || ! empty($row['is_active']),
            ];

            if (! empty($row['id'])) {
                $program = PropFirmProgram::where('prop_firm_id', $propFirm->id)->find($row['id']);
                if ($program) {
                    $program->update($data);
                    $keepIds[] = $program->id;
                    continue;
                }
            }

            $program = $propFirm->programs()->create($data);
            $keepIds[] = $program->id;
        }

        $propFirm->programs()->whereNotIn('id', $keepIds)->delete();
    }

    /** @param array<int, array<string, mixed>> $faqs */
    protected function syncFaqs(PropFirm $propFirm, array $faqs): void
    {
        $keepIds = [];

        foreach ($faqs as $index => $row) {
            if (empty($row['question'])) {
                continue;
            }

            $data = [
                'question' => $row['question'],
                'answer' => $row['answer'] ?? '',
                'sort_order' => (int) ($row['sort_order'] ?? $index),
                'is_active' => ! isset($row['is_active']) || ! empty($row['is_active']),
            ];

            if (! empty($row['id'])) {
                $faq = PropFirmFaq::where('prop_firm_id', $propFirm->id)->find($row['id']);
                if ($faq) {
                    $faq->update($data);
                    $keepIds[] = $faq->id;
                    continue;
                }
            }

            $faq = $propFirm->faqs()->create($data);
            $keepIds[] = $faq->id;
        }

        $propFirm->faqs()->whereNotIn('id', $keepIds)->delete();
    }

    protected function handleUpload(
        Request $request,
        string $inputName,
        string $directory,
        string $prefix,
        PropFirm $propFirm,
        string $column
    ): void {
        if (! $request->hasFile($inputName)) {
            return;
        }

        $file = $request->file($inputName);
        $targetDir = public_path(trim($directory, '/'));

        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $this->deletePublicFile($propFirm->{$column});

        $filename = $prefix . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $file->move($targetDir, $filename);
        $propFirm->{$column} = trim($directory, '/') . '/' . $filename;
    }

    protected function deletePublicFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        $full = public_path(ltrim($path, '/'));

        if (is_file($full)) {
            @unlink($full);
        }
    }
}
