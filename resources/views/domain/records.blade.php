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
        @include('flash::message')
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                            <h3 class="card-title">Thông tin {{ $domain->domains }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('domain') }}" class="btn btn-success"><< Back Domain List</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Type</th>
                                <th scope="col">Name</th>
                                <th scope="col">Content</th>
                                <th scope="col" >Proxy status</th>
                                <th scope="col" >Handle</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($result->result as  $item)
                            <tr>
                                <th scope="row">{{ $item->id }}</th>
                                <td>{{ $item->type }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->content }}</td>
                                <td>
                                   @if($item->proxied == true)
                                        <strong class="badge badge-danger">Yes</strong>
                                   @else
                                        <strong class="badge badge-success">No</strong>
                                   @endif
                                </td>
                                <td>
                                    <a href="{{ route('domain.addrecord', [
                                    'domain_id' => $domain->id,
                                    'record' => $item->name,
                                    'old_ip' =>  $item->content,
                                    'id_record' =>  $item->id
                                     ]) }}" class="btn btn-outline-success">Add DB</a>
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
