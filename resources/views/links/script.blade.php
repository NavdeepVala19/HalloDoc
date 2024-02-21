   {{-- Jquery CDN Link --}}
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
       integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
       crossorigin="anonymous" referrerpolicy="no-referrer"></script>

   {{-- Bootstrap js Link --}}
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
       integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
   </script>


   {{-- Phone number input field JQuery plugin --}}
   <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/19.2.16/js/intlTelInput-jquery.js"
       integrity="sha512-wfyreFPhEylQoV2JBgRQEOY9pfHb9nkHHZ0aaQK8pnlHUnLR8njnaGPSlUOCGjkMSS8FXbttaOuA9Hxd5YBFmg=="
       crossorigin="anonymous" referrerpolicy="no-referrer"></script>

   {{-- Jquery file for header/navigation --}}
   <script defer src="{{ URL::asset('assets/script.js') }}"></script>
   
   {{-- Jquery file for Provider Page (Searching & Filtering) Feature --}}
   <script defer src="{{ URL::asset('assets/providerPage/provider.js') }}"></script>
   
   {{-- Javascript for patient login password field --}}
   <script defer src="{{ URL::asset('assets/patientSite/patientLoginPassword.js') }}"></script>

<script defer src="{{ URL::asset('assets/adminPage/admin.js') }}"></script>