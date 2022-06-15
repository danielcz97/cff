<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
        #map {
            height: 400px;
            width: 100%;
            margin: 0px;
            padding: 0px
        }
    </style>

</head>
<body>

<div class="flex-center position-ref full-height">

    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}">Home</a>
                <a href="{{ url('/delivery-calculator') }}">Delivery Calculator</a>
            @else
                <a href="{{ route('login') }}">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        </div>
    @endif

    <div class="content">
        <div class="title m-b-md">
            Delivery Calculator
        </div>
        <div class="row align-items-center">
            <div class="col-md-6">
            <form method="GET" action="/delivery-calculator">
                 <div class="form-group">
                        <label for="from">From</label>
                        <input type="text" class="form-control" id="from" name="from" placeholder="From">
                    </div>
                    <div class="form-group">
                        <label for="from">To</label>
                        <input type="text" class="form-control" id="to" name="to" placeholder="To">
                    </div>
                <div class="form-group">
                    <label for="from">Price</label>
                    <input type="number" step="1" class="form-control" id="price" name="price" placeholder="Price">
                </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
                <div class="col-md-6" id="result">
                    <h3 class="bold"> Distance: {{$distance}}</h3>
                    <br>
                    <h3 class="bold"> Price: £{{$priceTotal}}</h3>
                </div>
            </div>
    </div>
</div>
<script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDYw7ZwobNRqIwngXjcMQWRILXdCaOvZhc&libraries=places"
        async defer>
</script>
<script>
    window.addEventListener('DOMContentLoaded', function() {

        const input = document.getElementById("from");
        const input2 = document.getElementById("to");

        const center = {lat: 50.064192, lng: -130.605469};

        const defaultBounds = {
            north: center.lat + 0.1,
            south: center.lat - 0.1,
            east: center.lng + 0.1,
            west: center.lng - 0.1,
        };
        const options = {
            bounds: defaultBounds,
            componentRestrictions: {country: "uk"},
            fields: ["address_components", "geometry", "icon", "name"],
            strictBounds: false,
            types: ["establishment"],
        };
        const autocomplete = new google.maps.places.Autocomplete(input, options);
        const autocomplete2 = new google.maps.places.Autocomplete(input2, options);

    })
</script>
</body>

</html>
