@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/records.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="">Profile</a>
    <a href="">Provider</a>
    <a href="">Profession</a>
    <a href="">Halo</a>
    <a href="" class="active-link">History</a>
@endsection

@section('content')
    <div class="m-5 spacing">
        <h3>Cancel History</h3>
        <div class="section">
            <form action="{{ route('cancel.case.search') }}" method="POST">
                @csrf
                <div class="grid-4">
                    <div class="form-floating ">
                        <input type="text" name="name" class="form-control empty-fields" id="floatingInput"
                            placeholder="Name">
                        <label for="floatingInput">Name</label>
                    </div>
                    <div class="form-floating">
                        <input type="date" name="date" class="form-control empty-fields" id="floatingInput"
                            placeholder="date">
                        <label for="floatingInput">Date</label>
                    </div>

                    <div class="form-floating ">
                        <input type="email" name="email" class="form-control empty-fields" id="floatingInput"
                            placeholder="name@example.com">
                        <label for="floatingInput">Email</label>
                    </div>

                    <input type="tel" name="phone_number" class="form-control phone empty-fields" id="telephone">
                </div>
                <div class="text-end mb-3">
                    <button class="primary-empty clearButton">Clear</button>
                    <button type="submit" class="primary-fill">Search</button>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table">
                    <thead class="table-secondary">
                        <td>Patient Name</td>
                        <td>Phone</td>
                        <td>Email</td>
                        <td>Modified Date</td>
                        <td>Cancelled By Admin Note</td>
                        <td>Cancelled By Physician Note</td>
                    </thead>
                    <tbody>
                        @if (!empty($cancelCases))
                            @foreach ($cancelCases as $cancelCase)
                                @if (!empty($cancelCase->request))
                                    <tr>
                                        <td>
                                            {{ $cancelCase->request->requestClient->first_name }}
                                            {{ $cancelCase->request->requestClient->last_name }}
                                        </td>
                                        <td>{{ $cancelCase->request->requestClient->phone_number }}</td>
                                        <td>{{ $cancelCase->request->requestClient->email }}</td>
                                        <td>{{ $cancelCase->updated_at }}</td>
                                        <td>{{ $cancelCase->notes }}</td>
                                        <td>notes</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
