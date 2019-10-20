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
                        <h3 class="card-title">Thêm Record</h3>
                    </div>

                    <div class="card-body">
                        <form role="form" action="{{ route('record.add') }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="form-group">
                                <label for="domain_id">Domain</label>
                                <select class="form-control" name="domain_id" id="domain_id">
                                    @foreach($domains as $item)
                                        <option value="{{ $item->id }}">{{ $item->domain }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="domain">Record</label>
                                <input type="text" class="form-control" id="record" name="record" placeholder="vd trade.envilstore.vn">
                            </div>
                            <div class="form-group">
                                <label for="old_ip">Old Ip</label>
                                <input type="text" class="form-control" id="old_ip" name="old_ip" placeholder="IP hiện tại">
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
    {!! JsValidator::formRequest('App\Http\Requests\RecordRequest'); !!}
@endsection
