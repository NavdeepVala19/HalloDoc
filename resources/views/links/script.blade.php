   {{-- Jquery CDN Link --}}
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

   {{-- Bootstrap js Link --}}
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

   {{-- Phone number input field JQuery plugin --}}
   {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/19.2.16/js/intlTelInput-jquery.js"></script> --}}

   <script defer src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/21.1.3/js/intlTelInput.min.js"></script>
   <script defer src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/21.1.3/js/utils.min.js"></script>

   {{-- Jquery file for header/navigation --}}
   <script defer src="{{ URL::asset('assets/script.js') }}"></script>

   {{-- Jquery file for Provider Page --}}
   <script defer src="{{ URL::asset('assets/providerPage/provider.js') }}"></script>

   {{-- Jquery file for Admin Page --}}
   <script defer src="{{ URL::asset('assets/adminPage/admin.js') }}"></script>

   {{-- Include Custom Script --}}
   @yield('script')
