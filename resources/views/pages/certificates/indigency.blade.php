<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<style>
    @page {
        height: 792pt;
        width: 612pt;
    }

    * {
        box-sizing: border-box;
        margin:0;
        padding: 0;

    }

    body {
        height: 792pt;
        width: 612pt;
        position: relative;
    }

    .certificate {
        height: 792pt;
        width: 612pt;
        top:0;
        left: 0;
    }

    .absolute {
        position: absolute;
    }

    #name {
        top: 373px;
        left: 295px;
        width: 275px;
        text-align: center;

    }

    #civil_status {
        top: 373px;
        left: 543px;
        width: 65px;
        text-align: center;
    }

    #address {
        top: 399px;
        left: 235px;
        width: 270px;
        text-align: center;
    
    }

    #purpose {
        top: 538px;
        left: 390px;
        width: 68px;
        text-align: center;
    }

    #issued_this {
        top: 586px;
        left: 224px;
        width: 25px;
        background: white;
        text-align: center;

    }

    #issued_of {
        top: 586px;
        left: 310px;
        width: 28px;
        text-align: center;
    }

</style>
<body>
    <img class="absolute certificate" src="{{url('/images/indigency.png')}}" alt="">
    
    <p id="name" class="absolute">{{$resident->first_name}} {{$resident->middle_name ?? ''}} {{$resident->last_name}}</p>
    {{-- <p id="civil_status" class="absolute">{{ucfirst($resident->civil_status)}}</p> --}}
    @php
        $address = 
        $resident->house_number . ' ' .
        $resident->street . ' ' .
        ($resident->others ? " $resident->others" : '' );

        $timestamp = strtotime($resident->created_at);
        $day = date('jS', $timestamp);
        $month = date('F', $timestamp);
    @endphp

    <p id="address" class="absolute">{{$address}}</p>

    @foreach ($fields as $field)
        <p id="{{$field->input_id}}" class="absolute">{{$field->value}}</p>    
    @endforeach

    <p id="issued_this" class="absolute">{{$day}}</p>
    <p id="issued_of"class="absolute">{{$month}}</p>

</body>
<script>
    window.addEventListener('load', () => {
        document.querySelectorAll('p').forEach(p => {adjustTextSize(p)})

        function adjustTextSize(element) {
            const fontSize = window.getComputedStyle(element, null).getPropertyValue('font-size');
            element.style.fontSize = `${parseFloat(fontSize) - .5}px`
            if(element.scrollWidth <= element.offsetWidth) {
                return;
            }
            adjustTextSize(element);
        }

    })
    window.print();
    setTimeout(window.close, 500); 
</script>
</html>