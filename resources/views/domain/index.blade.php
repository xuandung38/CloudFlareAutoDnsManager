@extends('layouts.app')

@section('content')
    <div class="container">
        @include('flash::message')
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                            <h3 class="card-title">Danh sách Domain</h3>
                        <div class="card-tools">
                            <a href="{{ route('domain.add') }}" class="btn btn-success">+ Add Domain</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Account</th>
                                <th scope="col">Domain</th>
                                <th scope="col">Zone ID</th>
                                <th scope="col">Record Count</th>
                                <th scope="col" >Handle</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($domains as $key => $item)
                            <tr>
                                <th scope="row">{{ $key+1 }}</th>
                                <td>{{ $item->account->email }}</td>
                                <td scope="row">{{ $item->domain }}</td>
                                <td>{{ $item->zone_id }}</td>
                                <td>{{ $item->record->count() }}</td>
                                <td>
                                    <a href="{{ route('domain.show', $item->id) }}" class="btn btn-outline-info">Info</a>
                                    <a href="{{ route('domain.edit', $item->id) }}" class="btn btn-outline-success">Edit</a>
                                    <a href="{{ route('domain.delete', $item->id) }}" class="btn btn-outline-danger">Delete</a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
