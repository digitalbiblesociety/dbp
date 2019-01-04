@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
@endsection

@section('content')

    @include('layouts.partials.banner', ['title' => 'Orgs'])

    <section class="container">
        <div class="columns is-multiline">

            <div class="card column is-6">
                <div class="card-content">

                    <div class="media">
                        <div class="media-left">
                            <figure class="image is-48x48">
                                <img src="https://images.bible.cloud/icon/american-bible-society_icon.svg" alt="Placeholder image">
                            </figure>
                        </div>
                        <div class="media-content">
                            <p class="title is-4">American Bible Society</p>
                            <p class="subtitle is-6"><i class="fas fa-envelope"></i> ballison@americanbible.org</p>
                        </div>
                    </div>

                    <div class="content">
                        The American Bible Society exists to make the Bible available in a language and format that every person can understand and afford.

                        <div class="field is-grouped is-grouped-multiline mt30">
                            <div class="control">
                                <div class="tags has-addons">
                                    <span class="tag is-dark">Member</span>
                                    <span class="tag is-info">FOBAI</span>
                                </div>
                            </div>

                            <div class="control">
                                <div class="tags has-addons">
                                    <span class="tag is-dark">Card Holder</span>
                                    <span class="tag is-success">DBL</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <footer class="card-footer">
                    <a href="#" class="card-footer-item">Site</a>
                    <a href="#" class="card-footer-item">Edit</a>
                </footer>
            </div>

        </div>
    </section>

@endsection

@section('footer')
<script>
	axios.get('https://api.dbp.test/organizations?key=1234&v=4')
		.then(function (response) {
			// handle success
			console.log(response);
		})
		.catch(function (error) {
			// handle error
			console.log(error);
		})
		.then(function () {
			// always executed
		});
</script>
@endsection