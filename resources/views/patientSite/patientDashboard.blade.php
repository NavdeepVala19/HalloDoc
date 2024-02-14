@extends('index')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientDashboard.css') }}">

@endsection

@section('nav-links')

<a href="">Dashboard</a>
<a href="">Profile</a>

@endsection

@section('content')

<div class="container-fluid">

    <h2>Medical History</h2>

    <div class="content">

        <div class="button">
            <a href="" type="button" class="btn primary-empty create-btn">Create a new Request</a>

            <a href="" type="button" class="btn primary-empty plus"><i class="bi bi-plus"></i></a>

        </div>

        <div class="listing-table">

            <table class="table ">
                <thead class="table-secondary">
                    <tr>
                        <td>Created At</td>
                        <td>Current Status</td>
                        <td>Document</td>
                    </tr>
                </thead>


                <tbody>
                    <tr>
                        <td> 27 sept 2024</td>
                        <td>Accepted</td>
                        <td><a href="" type="button" class="primary-empty btn ">Docs</a></td>
                    </tr>

                </tbody>

            </table>
            <!-- 
            <div class="row">
               {{-- {{$patientData->links()}} --}}
            </div> -->

            <div class="accordions">

          

            <button class="accordion"> <i class="bi bi-clock"></i> Created-Date:27 sept 2024</button>
            <div class="panel">
                <div>
                <i class="bi bi-person"></i>  Provider :- Dr.xyz
                </div>
                <div>
                <i class="bi bi-check-circle"></i>  Current Status:Cancelled By Admin
                </div>
            </div>

            <button class="accordion"> <i class="bi bi-clock"></i> Created-Date:27 sept 2024</button>
            <div class="panel">
                <div>
                <i class="bi bi-person"></i>  Provider :- Dr.xyz
                </div>
                <div>
                <i class="bi bi-check-circle"></i>  Current Status:Cancelled By Admin
                </div>
            </div>

            <button class="accordion"> <i class="bi bi-clock"></i> Created-Date:27 sept 2024</button>
            <div class="panel">
                <div>
                <i class="bi bi-person"></i>  Provider :- Dr.xyz
                </div>
                <div>
                <i class="bi bi-check-circle"></i>  Current Status:Cancelled By Admin
                </div>
            </div> 
            </div> 
        </div>

        <script>

            var acc = document.getElementsByClassName("accordion");
            var i;

            for (i = 0; i < acc.length; i++) {
                acc[i].addEventListener("click", function () {
                    this.classList.toggle("active");
                    var panel = this.nextElementSibling;
                    if (panel.style.display === "block") {
                        panel.style.display = "none";
                    } else {
                        panel.style.display = "block";
                    }
                });
            }
        </script>

    </div>

    @endsection