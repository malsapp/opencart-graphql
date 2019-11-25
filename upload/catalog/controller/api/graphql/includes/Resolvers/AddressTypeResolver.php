<?php
namespace GQL\Resolvers;

trait AddressTypeResolver {
    
    public function AddressType_zone ($root, $args, &$ctx) {
        return [
            'zone_id'   => $root['zone_id'],
            'name'      => $root['zone'],
            'code'      => $root['zone_code']
        ];
    }

    public function AddressType_country ($root, $args, &$ctx) {
        return [
            'country_id'    => $root['country_id'],
            'name'          => $root['country'],
            'iso_code_2'    => $root['iso_code_2'],
            'iso_code_3'    => $root['iso_code_3'],
            'address_format'    => $root['address_format']
        ];
    }

}
?>