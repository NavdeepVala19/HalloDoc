    {{-- Case Cleared Successfully --}}
    @if (session('caseCleared'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('caseCleared') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif

    {{-- Order Created Successfully Pop-up Message --}}
    @include('alertMessages.orderPlacedSuccess')

    {{-- SendLink Completed Successfully --}}
    @include('alertMessages.sendLinkSuccess')

    {{-- Clear Case Pop-up --}}
    {{-- This pop-up will open when admin clicks on “Clear case” link from Actions menu. From the pending and close
state, admin can clear the case from the action grid. --}}
    @include('popup.adminClearCase')

    {{-- Send Agreement Pop-up --}}
    {{-- This pop-up will open when admin/provider will click on “Send agreement” link from Actions menu. From the
pending state, providers need to send an agreement link to patients. --}}
    @include('popup.adminSendAgreement')

    {{-- Send Link pop-up -> used to send link of Submit Request Screen page to the patient via email and SMS --}}
    @include('popup.adminSendLink')

    {{-- Request DTY Support pop-up ->  --}}
    @include('popup.requestDTYSupport')

  <table class="table table-hover ">
      <thead class="table-secondary">
          <tr>
              <th>Name</th>
              <th>Date Of Birth</th>
              <th>Region</th>
              <th>Physician Name</th>
              <th>Date Of Service</th>
              <th>Address</th>
              <th>Notes</th>
              <th>Actions</th>
          </tr>
      </thead>
      <tbody id="dropdown-data-body">
          @if ($cases->isEmpty())
          <tr>
              <td colspan="100" class="no-record">No Cases Found</td>
          </tr>
          @endif
          @foreach ($cases as $case)
          @if (!empty($case->requestClient))
          <tr class="type-{{ $case->request_type_id }}">
              <td>{{ $case->requestClient->first_name }}
                  {{ $case->requestClient->last_name }}
              </td>
              <td>{{ $case->requestClient->date_of_birth }}</td>
              <td>Region</td>
              <td>
                  @if ($case->provider)
                  {{ $case->provider->first_name }} {{ $case->provider->last_name }}
                  @endif
              </td>
              <td>{{ $case->created_at }}</td>
              <td>
                  {{ $case->requestClient->street }},
                  {{ $case->requestClient->city }},{{ $case->requestClient->state }}
              </td>
              <td>{{ $case->notes }}</td>
              <td>
                  <div class="action-container">
                      <button class="table-btn action-btn" data-id={{ $case->id }}>Actions</button>
                      <div class="action-menu">
                          <a href="{{ route('admin.view.case', Crypt::encrypt($case->id)) }}"><i class="bi bi-journal-arrow-down me-2 ms-3"></i>View Case</a>
                          <a href="{{ route('admin.view.upload', Crypt::encrypt($case->id)) }}"><i class="bi bi-file-earmark-arrow-up-fill me-2 ms-3"></i>View
                              Uploads</a>
                          <a href="{{ route('admin.view.note', Crypt::encrypt($case->id)) }}"><i class="bi bi-journal-text me-2 ms-3"></i>View
                              Notes</a>
                          <a href="{{ route('admin.view.order', Crypt::encrypt($case->id)) }}"><i class="bi bi-card-list me-2 ms-3"></i>Orders</a>
                          <a href="{{ route('admin.close.case', Crypt::encrypt($case->id)) }}">
                              <i class="bi bi-x-circle me-2 ms-3"></i>Close Case</a>
                          <button class="clear-btn" data-id="{{ $case->id }}"><i class="bi
                                                    bi-x-circle me-2 ms-3"></i>Clear
                              Case</button>
                          <a href="{{ route('admin.encounter.form', Crypt::encrypt($case->id)) }}" class="encounter-form-btn"><i class="bi bi-text-paragraph me-2 ms-3"></i>Encounter</a>
                      </div>
                  </div>
              </td>
          </tr>
          @endif
          @endforeach
      </tbody>
  </table>
  <div class="page ">
      {{ $cases->links('pagination::bootstrap-5') }}
  </div>