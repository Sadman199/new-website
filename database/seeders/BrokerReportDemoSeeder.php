<?php

namespace Database\Seeders;

use App\Models\Broker;
use App\Models\BrokerReport;
use Illuminate\Database\Seeder;

class BrokerReportDemoSeeder extends Seeder
{
    public function run(): void
    {
        $xm = Broker::where('slug', 'xm')->first();
        $fbs = Broker::where('slug', 'fbs')->first();

        if ($xm) {
            BrokerReport::updateOrCreate(
                ['broker_id' => $xm->id, 'reporter_email' => 'demo.trader@example.com', 'issue_type' => 'withdrawal_problem'],
                [
                    'broker_name' => $xm->name,
                    'reporter_name' => 'Demo Trader',
                    'message' => 'Demo report: delayed withdrawal request during high-volatility week. Included for UI testing only.',
                    'status' => 'reviewed',
                    'admin_notes' => 'Demo seed entry — no action required.',
                ]
            );
        }

        if ($fbs) {
            BrokerReport::updateOrCreate(
                ['broker_id' => $fbs->id, 'reporter_email' => 'compliance.check@example.com', 'issue_type' => 'fake_regulation'],
                [
                    'broker_name' => $fbs->name,
                    'reporter_name' => 'Compliance Check',
                    'message' => 'Demo report: user questioned offshore entity vs EU license routing. Editorial review recommended.',
                    'status' => 'pending',
                ]
            );
        }

        BrokerReport::updateOrCreate(
            ['broker_name' => 'OneRoyal', 'reporter_email' => 'verify@example.com', 'issue_type' => 'verification_request'],
            [
                'reporter_name' => 'Site Visitor',
                'message' => 'Demo verification request for broker not yet in database.',
                'status' => 'pending',
            ]
        );
    }
}
