@extends('layouts.app')

@section('content')
    <div class="container">
        @include('flash::message')
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                            <h3 class="card-title">Danh sách Account Cloudflare</h3>
                        <div class="card-tools">
                            <a href="{{ route('account.add') }}" class="btn btn-success">+ Add Account</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Email</th>
                                <th scope="col">Domain</th>
                                <th scope="col" >Handle</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($accounts as $key => $item)
                            <tr>
                                <th scope="row">{{ $key+1 }}</th>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->domain->count() }}</td>
                                <td>
                                    <a href="{{ route('account.edit', $item->id) }}" class="btn btn-outline-success">Edit</a>
                                    <a href="{{ route('account.delete', $item->id) }}" class="btn btn-outline-danger">Delete</a>
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
