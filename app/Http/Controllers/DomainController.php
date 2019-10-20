<?php

namespace App\Http\Controllers;

use App\Http\Requests\DomainRequest;
use App\Http\Requests\RecordRequest;
use App\Models\AccountApi;
use App\Models\Domains;
use App\Models\RecordList;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $domains = Domains::with(['account', 'record'])->get();
        return view('domain.index', compact('domains'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $apis = AccountApi::all();
        return view('domain.add', compact('apis'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(DomainRequest $request)
    {
        Domains::create([
            'domain' => $request->input('domain'),
            'api_id' => $request->input('api_id')
        ]);
        $this->getZoneId($request->input('domain'),$request->input('api_id'));
        flash('Thêm tên miền thành công!')->success();
        return redirect()->route('domain');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $domain = Domains::findorFail($id);
        $headers = [
            'X-Auth-Email: '. $domain->account->email,
            'X-Auth-Key: ' . $domain->account->api_key,
            'Content-Type: application/json'
        ];
        $response = Curl::to('https://api.cloudflare.com/client/v4/zones/'.$domain->zone_id.'/dns_records')
            ->withHeaders($headers)
            ->get();
        $result = json_decode($response);
        return view('domain.records', compact('domain', 'result'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $apis = AccountApi::all();
        $domain = Domains::findorFail($id);
        return view('domain.edit', compact('apis', 'domain'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(DomainRequest $request, $id)
    {
        Domains::findorFail($id)->update([
            'domain' => $request->input('domain'),
            'api_id' => $request->input('api_id')
        ]);
        $this->getZoneId($request->input('domain'),$request->input('api_id'));
        flash('Sửa tên miền thành công!')->success();
        return redirect()->route('domain');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $domain = Domains::with('record')->findOrFail($id);
       $domain->record->delete();
       $domain->delete();

       flash('Xóa tên miền và các record của nó thành công!')->warning();
       return redirect()->route('domain');
    }

    private function getZoneId($domain,$api_id)
    {
        $api = AccountApi::find($api_id);
        $headers = [
            'X-Auth-Email: '. $api->email,
            'X-Auth-Key: ' . $api->api_key,
            'Content-Type: application/json'
        ];
        $response = Curl::to('https://api.Cloudflare.com/client/v4/zones?name='.$domain)
            ->withHeaders($headers)
            ->get();
        $result = json_decode($response);
        if ($result->result){
            $thisdomain = Domains::where('domain',$domain)->first();
            $thisdomain->update([
                'zone_id' => $result->result[0]->id
            ]);
            flash('Thông tin domain chính xác! Đã cập nhật zone_id')->success();
        } else{
            flash('Thông tin domain không chính xác! Không thể cập nhật zone id')->error();
        }
    }

    public function addRecord(RecordRequest $request)
    {
        RecordList::create([
            'domain_id' => $request->get('domain_id'),
            'record' =>  $request->get('record'),
            'old_ip'  =>  $request->get('old_ip'),
            'new_ip'  => $request->get('old_ip'),
            'id_record'  =>  $request->get('id_record')
        ]);
        flash('Thêm record Thành công!')->success();
        return redirect()->route('domain.show',$request->get('domain_id'));
    }
}
