<h1>Provider Request to Edit Profile</h1>

<p>Provider Id: {{ $provider->id }}</p>
<p>Provider Name: {{ $provider->first_name }} {{ $provider->last_name }}</p>
<h3>Profile Changes requested by Provider:</h3>
<p>{{ $data->message }}</p>
