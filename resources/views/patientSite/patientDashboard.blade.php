@extends('patientSiteIndex')


@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientDashboard.css') }}">
@endsection

@section('nav-links')
    <a href="" class="active-link">Dashboard</a>
    <a href="{{ route('patientProfile') }}" class="">Profile</a>
@endsection


@section('patientSiteContent')
    @if (Session::has('message'))
        <div class="alert alert-success popup-message" role="alert">
            {{ Session::get('message') }}
        </div>
    @endif 
    

    <div class="container-fluid">
        <h2>Medical History</h2>
        <div class="content shadow">
            <div class="button">
                <button class="btn primary-empty create-btn mt-2 me-2 mb-2">Create new Request</button>
                <button class="btn primary-empty plus create-new-request-btn"><i class="bi bi-plus"></i></button>
            </div>

            <div class="listing-table patient-history-table">
                <table class="table">
                    <thead class="table-secondary">
                        <tr>
                            <td>Created At</td>
                            <td>Current Status</td>
                            <td>Document</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $patientData)
                            <tr>
                                <td style="height: 5%;"> {{ $patientData->created_date }}</td>
                                <td style="height: 5%;"> {{ $patientData->status_type }}</td>
                                <td style="height: 5%;">
                                    @if ($patientData->request_id == null)
                                    @else
                                        <a href="{{ route('patientViewDocsFile', $patientData->id) }}"
                                            type="button" class="primary-empty btn ">Documents</a>
                                    @endif
                                </td>
                        @endforeach
                        </tr>
                    </tbody>
                </table>
                {{ $data->links('pagination::bootstrap-5') }}
            </div>

           

            <div class="accordions">
                <!-- create a new request pop-up -->
           
                <div class="pop-up-accordion new-request-create">
                    <div class="popup-heading-section d-flex align-items-center justify-content-between">
                        <span>Create new Request</span>
                        <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
                    </div>
                    <p class="m-2">Here I want to create new request</p>
                    <div class="p-4 d-flex align-items-center justify-content-center gap-2">
                        <button class="primary-empty btn-me btn-active">
                            me
                        </button>
                        <button class="primary-empty btn-someone">
                            someone else
                        </button>
                    </div>
                    <div class="p-2 d-flex align-items-center justify-content-end gap-2">
                        <button class="primary-fill continue-btn">Continue</button>
                        <button class="primary-empty hide-popup-btn">Cancel</button>
                    </div>
                </div>

                @foreach ($data as $patientData)
                    <button class="accordion"> <i class="bi bi-clock"></i>
                        Created-Date:{{ $patientData->created_date }}</button>
                    <div class="panel">
                        <div class="m-2">
                            <i class="bi bi-check-circle"></i> Current Status:{{ $patientData->status_type }}
                        </div>
                        @if ($patientData->request_id == null)
                            -
                        @else
                            <div>
                                <a href="{{ route('patientViewDocsFile', $patientData->id) }}" type="button"
                                    class="primary-empty btn ">Documents</a>
                            </div>
                        @endif
                    </div>
                @endforeach
                {{ $data->links('pagination::bootstrap-5') }}
            </div>

            <!-- create a new request pop-up -->
         <div class="overlay"></div> 
            
            <div class="pop-up new-request">
                
                <div class="popup-heading-section d-flex align-items-center justify-content-between">
                    <span>Create new Request</span>
                    <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
                </div>
                <p class="m-2">Here I want to create new request</p>
                <div class="p-4 d-flex align-items-center justify-content-center gap-2">
                    <button class="primary-empty btn-me btn-active">
                        me
                    </button>
                    <button class="primary-empty btn-someone">
                        someone else
                    </button>
                </div>
                <div class="p-2 d-flex align-items-center justify-content-end gap-2">
                    <button class="primary-fill continue-btn">Continue</button>
                    <button class="primary-empty hide-popup-btn">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('script')
        <script defer src="{{ URL::asset('assets/patientSite/patientSite.js') }}"></script>
    @endsection
