@extends('layouts.app')

@section('title', '')

@section('content')
    <div class="container">

        <div class="row my-4">
            <div class="col-6">
                <div class="card card-body">
                    ผู้ใช้
                </div>
            </div>
            <div class="col-3">

                <dashboard-realtime></dashboard-realtime>

            </div>
            <div class="col-3">
                <div class="card card-body">
                    <div class="mb-0">ใช้ต่อเดือน</div>
                </div>
            </div>
        </div>
    </div>
@endsection
