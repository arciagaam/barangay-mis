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
        height: 936pt;
        width: 612pt;
    }

    * {
        box-sizing: border-box;
        margin:0;
        padding: 0;
    }

    body {
        height: 936pt;
        width: 612pt;
        position: relative;
    }

    .certificate {
        height: 936pt;
        width: 612pt;
        top:0;
        left: 0;
    }

    .absolute {
        position: absolute;
    }

    /* #address {
        top: 399px;
        left: 185px;
        width: 370px;
        text-align: center;
    } */

    #business_name {
        top: 325px;
        left: 160px;
        width: 500px;
        text-align: center;
    }

    #location {
        top: 387px;
        left: 160px;
        width: 500px;
        text-align: center;
    }

    #operator {
        top: 447px;
        left: 160px;
        width: 500px;
        text-align: center;
    }

    #address {
        top: 507px;
        left: 160px;
        width: 500px;
        text-align: center;
    }

    #complying {
        top: 630px;
        left: 190px;
        width: 100px;
        text-align: center;
    }

    #partially_complying {
        top: 715px;
        left: 190px;
        width: 100px;
        text-align: center;
    }

    #no_objection {
        top: 842px;
        left: 190px;
        width: 100px;
        text-align: center;
    }

    #recommends {
        top: 908px;
        left: 190px;
        width: 100px;
        text-align: center;
    }

    #issued_this {
        top: 1035px;
        left: 158px;
        width: 35px;
        text-align: center;
    }

    #issued_of {
        top: 1035px;
        left: 245px;
        width: 80px;
        text-align: center;
    }

    p {
        white-space: nowrap;
    }

</style>
<body>
    <img class="absolute certificate" src="{{url('/images/business_permit.png')}}" alt="">
    @php
        $address = 
        ($resident->block ? "Blk $resident->block" : '') . 
        ($resident->lot ? " Lot $resident->lot" : '') . 
        ($resident->block || $resident->block ? " " : '') .
        $resident->house_number . 
        ($resident->others ? " $resident->others" : '' ) . 
        ($resident->subdivision ? ", $resident->subdivision" : '') ;

        $timestamp = strtotime($resident->created_at);
        $day = date('jS', $timestamp);
        $month = date('F', $timestamp);
    @endphp

    @foreach ($fields as $field)
        @php
            $value = $field->value
        @endphp

        @if ($field->input_id == 'complying' || $field->input_id == 'partially_complying' || $field->input_id == 'no_objection' || $field->input_id == 'recommends')
            @php
                $value = $field->value == 'on' ? 'X' : '';
            @endphp  
        @endif
        <p id="{{$field->input_id}}" class="absolute">{{$value}}</p>    
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

        window.print();
        setTimeout(window.close, 500); 
    })
</script>
</html>