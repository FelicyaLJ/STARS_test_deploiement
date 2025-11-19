@foreach ($evenements as $evenement)
    <x-item-evenement :evenement="$evenement" />
@endforeach
