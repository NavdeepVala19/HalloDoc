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

    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }


    .submitType {
        font-size: 1.5rem;
        text-decoration: none;
    }


    .header_part {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
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

<div class="container">

    <header class="header px-3 border-bottom shadow bg-body-tertiary">
        <div>
            <a href=""><img class="logo img-fluid" src="{{ URL::asset('/assets/logo.png') }}" alt=""></a>
        </div>
    </header>

    <h2>Forgot Your Password ?</h2>

    <h4>
        Click on Below Button To Reset Password
    </h4>
    <a href="{{ route('reset.password', $token) }}" type="button" class="case submitType" id="patient">Reset Password</a>

</div>