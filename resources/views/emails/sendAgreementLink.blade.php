<h1>Link for Agreement</h1>
{{-- {{dd($data)}} --}}
<p> {{ $data->requestClient->first_name }} {{ $data->requestClient->last_name }}, Click on the below link to
    read the agreement.</p>
{{-- {{ $data->id }}  --}}

<a href="{{ route('patientAgreement', $data) }}">Agreement Link</a>
