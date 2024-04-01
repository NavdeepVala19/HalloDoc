{{-- Finalize Pop-up appears when the provider has finalized the encounter form --}}
    {{-- The Encounter form should redirect to conclude page and will show these pop-up --}}
    {{-- The pop-up will give download link of the medical-report(Encounter Form) --}}
    <div class="pop-up encounter-finalized">
        <div class="popup-heading-section d-flex align-items-center justify-content-between">
            <span>Encounter Form</span>
            <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
        </div>
        <form action="{{ route('provider.download.encounterForm') }}" method="POST">
            @csrf
            <input type="text" name="requestId" class="requestId" value="" hidden>
            <div class="encounter-finalized-container">
                <p>Encounter Form is finalized successfully!</p>
                <div class="text-center">
                    <button type="submit" class="primary-fill download-btn">Download</button>
                </div>
            </div>
        </form>
    </div>