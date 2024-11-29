@foreach($movies as $movie)
    <x-movie-card :movie="$movie" :loop="$loop" />
@endforeach 