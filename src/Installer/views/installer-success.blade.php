@extends('index')

@section('content')
    <div class="container-xl py-5">
        <div class="bg-success p-4">
            <p style="font-size: 1.3rem"><stong>Étape terminé !</stong> {{ $success }}</p>
            @if($end)
                <p><a href="{{ $adminPath }}" class="btn btn-primary"><i class="fas fa-user-shield"></i> Panneau d'administration</a></p>
            @else
                <p><a href="" class="btn btn-primary">Suivant <i class="fas fa-arrow-circle-right"></i></a></p>
            @endif
        </div>
    </div>
@endsection
