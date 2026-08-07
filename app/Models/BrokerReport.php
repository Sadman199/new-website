<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrokerReport extends Model
{
    public const ISSUE_TYPES = [
        'withdrawal_problem' => 'Withdrawal problem',
        'fake_regulation' => 'Fake regulation',
        'account_issue' => 'Account issue',
        'scam_suspicion' => 'Scam suspicion',
        'verification_request' => 'Verification request',
    ];

    /** Issue types available on the public "Report This Broker" form. */
    public const REPORT_ISSUE_TYPES = [
        'withdrawal_problem' => 'Withdrawal problem',
        'fake_regulation' => 'Fake regulation',
        'account_issue' => 'Account issue',
        'scam_suspicion' => 'Scam suspicion',
        'poor_support' => 'Poor customer support',
        'bonus_issues' => 'Bonus / promotion issues',
        'platform_issues' => 'Platform / execution issues',
        'other' => 'Other issue',
    ];

    public const STATUSES = [
        'pending' => 'Pending',
        'reviewed' => 'Reviewed',
        'approved' => 'Approved',
        'dismissed' => 'Dismissed',
    ];

    protected $fillable = [
        'broker_id',
        'broker_name',
        'reporter_name',
        'reporter_email',
        'issue_type',
        'message',
        'status',
        'admin_notes',
        'ip_address',
    ];

    public function broker(): BelongsTo
    {
        return $this->belongsTo(Broker::class);
    }

    public function issueLabel(): string
    {
        return self::REPORT_ISSUE_TYPES[$this->issue_type]
            ?? self::ISSUE_TYPES[$this->issue_type]
            ?? ucfirst(str_replace('_', ' ', (string) $this->issue_type));
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }
}
