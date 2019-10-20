@extends('layouts.app')

@section('content')
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Sửa Domain</h3>
                    </div>

                    <div class="card-body">
                        <form role="form" action="{{ route('domain.edit', $domain->id) }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="form-group">
                                <label for="api_id">Account</label>
                                <select class="form-control" name="api_id" id="api_id">
                                    @foreach($apis as $item)
                                        <option value="{{ $item->id }}" @if(old('api_id', $domain->api_id) == $item->id) selected @endif>{{ $item->email }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="domain">Domain</label>
                                <input type="text" class="form-control" id="domain" name="domain" placeholder="Domain" value="{{ $domain->domain }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\DomainRequest'); !!}
@endsection
