<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use FarhanWazir\GoogleMaps\GMaps;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public static function map($data)
    {
        if(isset($data->address)){
            $center = $data->address->latitude.", ".$data->address->longitude;
        }
        else{
            $center = $data->pick_up_latitude.", ".$data->pick_up_longitude;
        }
        $config = array();
        $config['center'] = $center;
        $config['zoom'] = '10';
        $config['map_height'] = '400px';

        $config['onboundschanged'] = 'if (!centreGot) {
            var mapCentre = map.getCenter();
            marker_0.setOptions({
                position: new google.maps.LatLng(mapCentre.lat(), mapCentre.lng())
            });
        }
        centreGot = true;';

        app('map')->initialize($config);

        // set up the marker ready for positioning
        // once we know the users location
//        $marker['infowindow_content'] = 'Sydney Airport,Sydney';

        $marker = array();
        $marker['position'] = $center;

        app('map')->add_marker($marker);

        $marker['position'] = $data->delivery_latitude.", ".$data->delivery_longitude;
        $marker['icon'] =asset('admin/images/map-marker-green.png');
        app('map')->add_marker($marker);

        $map = app('map')->create_map();

        return $map;
//        echo "<html><head><script type="text/javascript">var centreGot = false;</script>".$map['js']."</head><body>".$map['html']."</body></html>";

//        if(isset($data->address)){
//            $center = $data->address->latitude.", ".$data->address->longitude;
//        }
//        else{
//            $center = $data->pick_up_latitude.", ".$data->pick_up_longitude;
//        }
//        $config['center'] = $center;
//        $config['zoom'] = '10';
//        $config['map_height'] = '400px';

//        $gmap = new GMaps();
//        $gmap->initialize($config);
//
//        $marker['position'] = $center;
////        $marker['infowindow_content'] = 'Sydney Airport,Sydney';
//
//        $gmap->add_marker($marker);
//        $map = $gmap->create_map();
//        return $map;
    }

}
