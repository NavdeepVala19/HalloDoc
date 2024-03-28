<style>
    body {
        font-family: "Poppins", sans-serif;
        font-weight: 400;
        font-style: normal;

    }


    /********************* Header CSS Start***********************/

    .footer-section {
        position: fixed;
        bottom: 0;
        width: 100%;

    }

    .footer {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .header {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /********************* Header CSS End***********************/



    /********************* Main Content CSS Start***********************/


    .case {
        width: 230px;
        height: 50px;
        border-radius: 10px;
        color: rgb(77, 77, 255);
        background-color: rgb(222, 225, 252);
        border: 1px solid rgb(77, 77, 255);
        display: flex;
        align-items: center;
        justify-content: center;
    }


    .submitType {
        font-size: 1.5rem;
        text-decoration: none;
    }

    .main-container {
        margin-top: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .header_part {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .patient {
        background-color: rgb(222, 225, 253);
        border-color: rgb(76, 76, 255);
    }

    #patient {
        color: rgb(76, 76, 255);
    }


    @media (max-width: 550px) {
        .menu-icon {
            display: none;
        }

        .container {
            margin-top: 30px;
        }

        .logo {
            width: 100px;
            height: 100px;
        }


    }


    /********************* Main Content CSS End***********************/
</style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Catamaran:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<header class="header px-3 border-bottom shadow bg-body-tertiary">
    <div>
        <a href=""><img class="logo img-fluid" src="{{ URL::asset('/assets/logo.png') }}" alt=""></a>
    </div>
</header>


<div class="container">
    <div class="header_part">
        <div>
            <h2>Create account by clicking on below link with below email address</h2>
        </div>
        <div>
            <h4> Email Address : {{$emailAddress}} </h4>
        </div>
    </div>

    <!-- this div is for main content -->
    <div class=" main-container">
        <div class="case">
            <a href="{{route('patientRegister')}}" class="submitType" type="button" id="patient">Create Account</a>
        </div>
    </div>
</div>

<footer class="footer-section">
    <div class="footer">
        <span>Terms of Conditions</span> | <span>Privacy Policy</span>
    </div>
</footer>