<?php
/**
 * Created by PhpStorm.
 * User: MinhDuc
 * Date: 7/8/2020
 * Time: 5:14 PM
 */

namespace app\modules\services;

use app\widgets\maps\layers\TileLayer;
use app\widgets\maps\layers\LayerGroup;

class MapService
{
    public static function createBaseMaps()
    {
        $hcmgis_layer = new TileLayer([
            'urlTemplate' => 'https://thuduc-maps.hcmgis.vn/thuducserver/gwc/service/wmts?layer=thuduc:thuduc_maps&style=&tilematrixset=EPSG:900913&Service=WMTS&Request=GetTile&Version=1.0.0&Format=image/png&TileMatrix=EPSG:900913:{z}&TileCol={x}&TileRow={y}',
            'layerName' => 'HCMGIS',
            'clientOptions' => [
                'layers' => 'thuduc:thuduc_maps'
            ],
        ]);

        $osm_layer = new TileLayer([
            'urlTemplate' => 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            'layerName' => 'OSM',
            'clientOptions' => [
                'attribution' => '© OpenStreetMap contributors',
                'maxZoom' => 22,
            ],
        ]);

        $google_layer = new TileLayer([
            'urlTemplate' => 'http://{s}.google.com/vt/lyrs=r&x={x}&y={y}&z={z}',
            'layerName' => 'GMAP',
            'clientOptions' => [
                'attribution' => '© GoogleMap contributors',
                'maxZoom' => 22,
                'subdomains' => ['mt0', 'mt1', 'mt2', 'mt3']
            ],
        ]);

        return [$hcmgis_layer, $osm_layer, $google_layer];
    }

    public static function createOverlays(){
        $overlays = [];
        $user = \Yii::$app->user->identity;

        switch ($user->xa_id){
            case 0:
                $ranhthua_longthoi = new TileLayer([
                    'urlTemplate' => 'http://nhknhabe.hcmgis.vn/geo113/nhanhokhau_nhabe/wms?',
                    'service' => TileLayer::WMS,
                    'layerName' => 'Long Thới',
                    'clientOptions' => [
                        'layers' => 'nhanhokhau_nhabe:ranhthua_longthoi',
                        'transparent' => true,
                        'format' => 'image/png8',
                        'maxZoom' => 22,
                    ],
                ]);

                $ranhthua_nhonduc = new TileLayer([
                    'urlTemplate' => 'http://nhknhabe.hcmgis.vn/geo113/nhanhokhau_nhabe/wms?',
                    'service' => TileLayer::WMS,
                    'layerName' => 'Nhơn Đức',
                    'clientOptions' => [
                        'layers' => 'nhanhokhau_nhabe:ranhthua_nhonduc',
                        'transparent' => true,
                        'format' => 'image/png8',
                        'maxZoom' => 22,
                    ],
                ]);

                $ranhthua_phuocloc = new TileLayer([
                    'urlTemplate' => 'http://nhknhabe.hcmgis.vn/geo113/nhanhokhau_nhabe/wms?',
                    'service' => TileLayer::WMS,
                    'layerName' => 'Phước Lộc',
                    'clientOptions' => [
                        'layers' => 'nhanhokhau_nhabe:ranhthua_phuocloc',
                        'transparent' => true,
                        'format' => 'image/png8',
                        'maxZoom' => 22,
                    ],
                ]);

                $layerLongThoi = new LayerGroup();
                $layerLongThoi->addLayer($ranhthua_longthoi);
                $layerNhonDuc = new LayerGroup();
                $layerNhonDuc->addLayer($ranhthua_nhonduc);
                $layerPhuocLoc = new LayerGroup();
                $layerPhuocLoc->addLayer($ranhthua_phuocloc);

                array_push($overlays, $layerLongThoi);
                array_push($overlays, $layerNhonDuc);
                array_push($overlays, $layerPhuocLoc);
                break;
            case 4:
                $ranhthua_nhonduc = new TileLayer([
                    'urlTemplate' => 'http://nhknhabe.hcmgis.vn/geo113/nhanhokhau_nhabe/wms?',
                    'service' => TileLayer::WMS,
                    'layerName' => 'Nhơn Đức',
                    'clientOptions' => [
                        'layers' => 'nhanhokhau_nhabe:ranhthua_nhonduc',
                        'transparent' => true,
                        'format' => 'image/png8',
                        'maxZoom' => 22,
                    ],
                ]);
                $layerNhonDuc = new LayerGroup();
                $layerNhonDuc->addLayer($ranhthua_nhonduc);
                array_push($overlays, $layerNhonDuc);

                break;
        }

        return $overlays;
    }
}