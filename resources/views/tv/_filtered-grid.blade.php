@foreach($shows as $show)
    <x-show-card :show="$show" :loop="$loop" />
@endforeach 