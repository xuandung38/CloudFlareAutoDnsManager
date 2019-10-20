@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Sửa Account</h3>
                    </div>

                    <div class="card-body">
                        <form role="form" action="{{ route('account.edit', $account->id) }}" method="POST" id="add_account">
                            @csrf
                            @method('POST')
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="{{ $account->email }}">
                            </div>
                            <div class="form-group">
                                <label for="api_key">API Key</label>
                                <input type="text" class="form-control" id="api_key" name="api_key" placeholder="API Key" value="{{ $account->api_key }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\AccountAPIRequest','#add_account'); !!}
@endsection
