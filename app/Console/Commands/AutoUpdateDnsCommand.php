<?php

namespace App\Console\Commands;

use App\Models\HistoryDns;
use App\Models\RecordList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;

class AutoUpdateDnsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dns:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto update all dns';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $records = RecordList::with('domain')->get();
        foreach ($records as $record){
            $this->updateDns($record->id);
        }
    }

    public function updateDns($id)
    {
        $record = RecordList::with('domain')->find($id);
        $headers = [
            'X-Auth-Email: '. $record->domain->account->email,
            'X-Auth-Key: ' . $record->domain->account->api_key,
            'Content-Type: application/json'
        ];

        $response = Curl::to('https://api.Cloudflare.com/client/v4/zones/'. $record->domain->zone_id.'/dns_records?name=' . $record->record)
            ->withHeaders($headers)
            ->get();
        $record_data = json_decode($response);

        $ip = Curl::to('https://api.ipify.org')
            ->withTimeout(2)
            ->get();
        if ($record_data->result[0]->content !== $ip)
        {
            $data = array(
                'type' => $record_data->result[0]->type,
                'name' => $record->record,
                'content' => $ip,
                'ttl' => $record_data->result[0]->ttl,
                'proxied' => $record_data->result[0]->proxied
            );
            $setdns =  Curl::to('https://api.Cloudflare.com/client/v4/zones/'. $record->domain->zone_id.'/dns_records/' . $record_data->result[0]->id)
                ->withHeaders($headers)
                ->withData(json_encode($data))
                ->put();

            $json = json_decode($setdns);
            if ($json->success == true)
            {

                $record->update([
                    'new_ip' => $ip,
                    'old_ip' => $record->new_ip
                ]);
                HistoryDns::create([
                    'record_id' => $id,
                    'new_ip' => $ip,
                    'old_ip' => $record->new_ip
                ]);
                $msg = 'Đã thay đổi dns của ' .$record->record . ' từ ip '.$record->new_ip. ' qua '. $ip;
                Log::info($msg);

                $this->line($msg);
            }else{
                $msg = 'Có lỗi xảy ra trong quá trình đổi ip';
                $this->line( $msg );
                Log::info($msg);
            }
        }
    }
}
