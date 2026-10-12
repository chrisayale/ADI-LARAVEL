@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Demandes de création de SACCO en attente</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($requests->isEmpty())
        <p>Aucune demande en attente.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Code</th>
                    <th>Demandé par</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($requests as $req)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $req->name }}</td>
                    <td>{{ $req->code }}</td>
                    <td>{{ $req->requester->name ?? $req->requester->email }}</td>
                    <td>{{ $req->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <!-- Status badge -->
                        <span class="badge bg-{{ $req->status === 'pending' ? 'warning' : ($req->status === 'approved' ? 'success' : 'danger') }}">
                            {{ ucfirst($req->status) }}
                        </span>

                        <!-- Actions dropdown -->
                        <div class="dropdown mt-2">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $req->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $req->id }}">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.request-sacco.show', $req) }}">
                                        <i class="fas fa-eye me-1"></i> Voir les détails
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.request-sacco.approve', $req) }}">
                                        <i class="fas fa-check me-1"></i> Approuver
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.request-sacco.reject', $req) }}">
                                        <i class="fas fa-times me-1"></i> Rejeter
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection