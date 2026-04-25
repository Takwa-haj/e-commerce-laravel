@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
         <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                  <h3>Contacts</h3>
                  <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                      <li>
                       <a href="{{ route('admin.index') }}">
                              <div class="text-tiny">Tableau de bord</div>
                       </a>
                   </li>
                   <li>
                       <i class="icon-chevron-right"></i>
                   </li>
                   <li>
                        <div class="text-tiny">Tous les messages</div>
                    </li>
                </ul>
         </div>

                 <div class="wg-box">
                    <div class="flex items-center justify-between gap10 flex-wrap">
                     <div class="wg-filter flex-grow">
                        <form class="form-search" action="{{ route('admin.search.contacts') }}" method="POST">
                         @csrf
                         <fieldset class="name">
                             <input type="text" placeholder="Rechercher par nom, email, téléphone ou message..." class="" name="query"
                                                        value="{{ request()->input('query') }}" required>
                             </fieldset>
                             <div class="button-submit">
                      <button class="" type="submit"><i class="icon-search"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                        <a class="tf-button style-1 w208" href="{{ route('admin.contacts') }}" target="_blank">
                                            <i class="icon-external-link"></i>Page Contact
                                        </a>
                                    </div>

                                    @if(request()->has('query'))
                                    <div class="alert alert-info mb-3">
                                        Résultats de recherche pour : <strong>{{ request()->input('query') }}</strong>
                                        <a href="{{ route('admin.contacts') }}" class="float-end">Voir tous</a>
                                    </div>
                                    @endif

                                    <div class="wg-table table-all-user">
                                        <div class="table-responsive">
                                                      @if (Session::has('status'))
                                            <p class="alert alert-success">{{ session::get('status') }}</p>

                                            @endif
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Nom</th>
                                                        <th>Téléphone</th>
                                                        <th>Email</th>
                                                        <th>Message</th>
                                                        <th> Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if($contacts->count() > 0)
                                                        @foreach ($contacts as $contact )
                                                        <tr>
                                                            <td>{{ $contact->id }}</td>
                                                            <td>{{ $contact->name }}</td>
                                                            <td>{{ $contact->phone }}</td>
                                                            <td>{{ $contact->email }}</td>
                                                            <td>{{ Str::limit($contact->comment, 50) }}</td>
                                                            <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                                                            <td>
                                                                <div class="list-icon-function">
                                                                    <!-- View Details Button -->
                                                                    <a href="{{ route('admin.contact.details', $contact->id) }}"
                                                                    class="btn btn-sm btn-info me-2"
                                                                    title="Voir les détails">
                                                                        <i class="icon-eye"></i>
                                                                    </a>

                                                                    @if(!$contact->read)
                                                                        <a href="{{ route('admin.contact.mark-read', $contact->id) }}"
                                                                        class="btn btn-sm btn-success me-2"
                                                                        title="Marquer comme lu">
                                                                            <i class="icon-check"></i>
                                                                        </a>
                                                                    @endif

                                                                    <form action="{{ route('admin.contact.delete', $contact->id) }}"
                                                                        method="POST"
                                                                        class="d-inline delete-form">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                                class="btn btn-sm btn-danger"
                                                                                title="Supprimer"
                                                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')">
                                                                            <i class="icon-trash-2"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="7" class="text-center py-4">
                                                                Aucun message trouvé.
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                                            {{ $contacts->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
     </div>
    </div>
@endsection


@push('scripts')
<script>
$(function(){
    $('.delete').on('click', function(e){
        e.preventDefault();
        var form = $(this).closest('form');

        // Check if SweetAlert is loaded
        if (typeof Swal === 'undefined') {
            console.error('SweetAlert is not loaded!');
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

</script>
@endpush
