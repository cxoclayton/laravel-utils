


@foreach($details as $table => $tableDetails)
     {{$table}}
     sprintf("%s<fg=white;options=bold,underscore>%s</>", $table);

@endforeach
