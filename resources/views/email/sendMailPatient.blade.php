<style>
    body {
        font-family: "Poppins", sans-serif;
        font-weight: 400;
        font-style: normal;
    }

    /********************* Footer Styling ***********************/
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

    /********************* Header Styling ***********************/
    .header {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /********************* Main Content CSS Start***********************/
    .case {
        /* width: 230px; */
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
</style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Catamaran:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

<header class="header px-3 border-bottom shadow bg-body-tertiary">
    <div>
        <a href=""><img class="logo img-fluid" src="{{ URL::asset('/assets/logo.png') }}" alt=""></a>
    </div>
</header>


<div class="container">
    <div class="header_part">
        <div>
            @if ($sender->users)
                @if ($sender->users->userRoles->role_id == 1)
                    <h2>Message from {{ $sender->first_name }}
                        {{ $sender->last_name }}</h2>
                @else
                    <h2>Message from {{ $sender->first_name }}
                        {{ $sender->last_name }}</h2>
                @endif
            @endif
        </div>
    </div>

    <!-- this div is for main content -->
    <div class="main-container">
        <h3> Hii, {{ $data->first_name }} {{ $data->last_name }}</h3>
        <p>{{ $note }}</p>
    </div>
</div>

<footer class="footer-section">
    <div class="footer">
        <span>Terms of Conditions</span> | <span>Privacy Policy</span>
    </div>
</footer>
