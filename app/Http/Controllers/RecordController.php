<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecordRequest;
use App\Models\Domains;
use App\Models\HistoryDns;
use App\Models\RecordList;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = RecordList::with(['domain'])->get();
        return view('record.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $domains = Domains::all();
       return view('record.add', compact('domains'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RecordRequest $request)
    {
        RecordList::create([
            'domain_id' => $request->get('domain_id'),
            'record' =>  $request->get('record'),
            'old_ip'  =>  $request->get('old_ip'),
            'new_ip'  => $request->get('old_ip')
        ]);
        flash('Thêm record Thành công!')->success();
        return redirect()->route('record');
    }

    /**
     * Update Dns.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        if ($record_data->result[0]->content == $ip)
        {
            flash('IP Hiện tại chính xác, không cần thay đổi')->success();
        } else {
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
               flash('Đã thay đổi dns của ' .$record->record . ' từ ip '.$record->new_ip. ' qua '. $ip)->success();

               $record->update([
                   'new_ip' => $ip,
                   'old_ip' => $record->new_ip
               ]);
               HistoryDns::create([
                   'record_id' => $id,
                   'new_ip' => $ip,
                   'old_ip' => $record->new_ip
               ]);
           }else{
               flash('Có lỗi xảy ra trong quá trình đổi ip')->error();
           }
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->updateDns($id);
        return redirect()->route('record');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = RecordList::find($id);
        $record->delete();
        flash('Đã xóa '. $record->record .' khỏi danh sách' )->success();
        return redirect()->route('record');
    }

    public function fetchNewInfo($id)
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
        if ($record_data->success == true){
            $record->update([
                'new_ip' => $record_data->result[0]->content,
                'old_ip' => $record->new_ip
            ]);
            flash('Đã update dữ liệu mới nhất cho '. $record->record )->success();
        }else{
            flash('Có lỗi xảy ra trong quá trình cập nhật')->error();
        }
        return redirect()->route('record');
    }

    public function autoUpdateAllRecord()
    {
        $records = RecordList::with('domain')->get();
        foreach ($records as $record){
            $this->updateDns($record->id);
        }
    }
}
