<?php

namespace App\Services;

use App\Models\ForexBonus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ForexBonusAdminService
{
    protected string $uploadDir = 'uploads/forex_bonuses';

    /** @var string[] */
    protected array $fields = [
        'title', 'slug', 'publish_date', 'author_name', 'promo_type', 'description',
        'link', 'participate', 'how_to_participate', 'details', 'general_terms', 'prize',
        'eligibility_criteria', 'expiry_date', 'min_deposit', 'bonus_amount', 'bonus_percentage',
        'wagering_requirement', 'max_credit', 'eligible_clients', 'volume_requirement',
        'bonus_type_details', 'terms_conditions_url', 'affiliate_link', 'bonus_category',
        'promotion_status', 'meta_title', 'meta_keywords', 'meta_description', 'broker_id',
    ];

    public function save(ForexBonus $bonus, Request $request): ForexBonus
    {
        $bonus->fill($request->only($this->fields));
        $bonus->is_featured = $request->boolean('is_featured');
        $bonus->promotion_status = $request->input('promotion_status', 'ongoing');

        if (empty($bonus->slug)) {
            $bonus->slug = Str::slug($request->input('title', $bonus->title));
        }

        EditorialAssignmentService::applyFromRequest($bonus, $request);

        if (! $bonus->author_name) {
            $bonus->author_name = EditorialAssignmentService::primaryWriterName($bonus) ?? 'Editorial Team';
        }

        $this->handleFeatureImage($request, $bonus);
        $bonus->save();

        return $bonus;
    }

    public function delete(ForexBonus $bonus): void
    {
        $this->deletePublicFile($bonus->feature_image);
        $bonus->delete();
    }

    protected function handleFeatureImage(Request $request, ForexBonus $bonus): void
    {
        if (! $request->hasFile('feature_image')) {
            return;
        }

        $this->deletePublicFile($bonus->feature_image);

        $file = $request->file('feature_image');
        $filename = 'feature_image_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($this->uploadDir), $filename);
        $bonus->feature_image = $this->uploadDir . '/' . $filename;
    }

    protected function deletePublicFile(?string $relativePath): void
    {
        if (! $relativePath) {
            return;
        }

        $path = public_path(ltrim($relativePath, '/'));
        if (is_file($path)) {
            unlink($path);
        }
    }
}
