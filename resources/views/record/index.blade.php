@extends('layouts.app')

@section('content')
    <div class="container">
        @include('flash::message')
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                            <h3 class="card-title">Danh sách Record</h3>
                        <div class="card-tools">
                            <a href="{{ route('record.add') }}" class="btn btn-success">+ Add Record</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">IP</th>
                                <th scope="col" >ID record</th>
                                <th scope="col" >Handle</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($records as $key => $item)
                                <tr>
                                    <th scope="row">{{ $key+1 }}</th>
                                    <td>{{ $item->record }}</td>
                                    <td>{{ $item->new_ip }}</td>
                                    <td>{{ $item->id_record }}</td>
                                    <td>
                                        <a href="{{ route('record.fetchdns', $item->id) }}" class="btn btn-outline-success">Update info</a>
                                        <a href="{{ route('record.updatedns', $item->id) }}" class="btn btn-outline-primary">Update DNS</a>
                                    <a href="{{ route('record.delete', $item->id) }}" class="btn btn-outline-danger">Delete</a>
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
