    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap" async
        defer></script>
    <script src="{{ URL::asset('build/plugins/gmaps/map-custom-script.js') }}"></script>
