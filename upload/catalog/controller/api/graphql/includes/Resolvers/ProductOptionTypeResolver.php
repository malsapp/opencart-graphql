<?php
namespace GQL\Resolvers;

trait ProductOptionTypeResolver {
    
    public function ProductOptionType_product_option_value ($root, $args, &$ctx) {
        return $root['product_option_value'];
    }

}
?>