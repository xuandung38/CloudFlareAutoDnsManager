<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountAPIRequest;
use App\Models\AccountApi;
use Illuminate\Http\Request;

class AccountAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = AccountApi::with('domain')->get();
        return view('account.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('account.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountAPIRequest $request)
    {
        AccountApi::create([
            'email' => $request->input('email'),
            'api_key' => $request->input('api_key')
        ]);
        flash('Thêm tài khoản Cloudflare thành công!')->success();
        return redirect()->route('account');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $account = AccountApi::findorFail($id);
       return  view('account.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AccountAPIRequest $request, $id)
    {
        $account = AccountApi::findorFail($id);
        $account->update([
            'email' => $request->input('email'),
            'api_key' => $request->input('api_key')
        ]);
        flash('Sửa tài khoản Cloudflare thành công!')->success();
        return redirect()->route('account');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $account = AccountApi::findorFail($id);
        $account->delete();
        flash('Sửa tài khoản Cloudflare thành công!')->warning();
        return redirect()->route('account');
    }
}
