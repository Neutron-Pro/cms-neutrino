@extends('index')

@section('content')
    <div class="container-xl py-5">
        @foreach($errors as $error)
            <div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ $error }}
            </div>
        @endforeach

        <div class="jumbotron">
            <h1 class="display-4">Installation de Neutrino CMS</h1>
            <p class="lead">Ce CMS a été développé par NeutronStars.</p>
            <hr class="my-4">
            <p>Pour en savoir plus, veuillez-vous référer sur le répertoire GitHub du framework.</p>
            <p class="lead">
                <a class="btn btn-primary btn-lg" href="https://github.com/Neutron-Pro/neutrino-framework" role="button"><i class="fab fa-github"></i> GitHub</a>
            </p>
        </div>

        <div class="row">
            <div class="col-12">
                <h1>{{ $h1 }}</h1>
                {!! $form->toHTML() !!}
            </div>
        </div>
    </div>
@endsection
