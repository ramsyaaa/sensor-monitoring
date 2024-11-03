<?php
if (!function_exists('checkUrlIcon')) {
    function checkUrlIcon($sensorName) {
        switch ($sensorName) {
            case 'Flow velocity':
                return asset('asset/img/Icon/flow-velocity.png');
            case 'Water level':
                return asset('asset/img/Icon/water-level.png');
            case 'Instantaneous flow':
                return asset('asset/img/Icon/instantaneous-flow.png');
            case 'Height':
                return asset('asset/img/Icon/height.png');
            case 'Temperature':
                return asset('asset/img/Icon/temperature.png');
            case 'Humidity':
                return asset('asset/img/Icon/humidity.png');
            case 'Noise':
                return asset('asset/img/Icon/noice.png');
            case 'Wind Speed':
                return asset('asset/img/Icon/wind.png');
            case 'Wind Direction':
                return asset('asset/img/Icon/wind-direction.png');
            case 'PM2.5':
                return asset('asset/img/Icon/pm2.png');
            case 'PM10':
                return asset('asset/img/Icon/pm10.png');
            case 'Atmosphere':
                return asset('asset/img/Icon/atmosphere.png');
            case 'Optical rainfall':
                return asset('asset/img/Icon/rainfall.png');
            default:
                return "http://webplus-cn-shenzhen-s-5decf7913c3f2876a5adc591.oss-cn-shenzhen.aliyuncs.com//images/temperature.png";
        }
    }
}
