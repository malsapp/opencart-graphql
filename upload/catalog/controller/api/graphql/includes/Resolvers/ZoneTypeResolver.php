<?php
namespace GQL\Resolvers;

trait ZoneTypeResolver {
    
    public function ZoneType_country ($root, $args, &$ctx) {
        $ctx->load->model ('localisation/country');
        return $ctx->model_localisation_country->getCountry ($root['country_id']);
    }

}
?>
