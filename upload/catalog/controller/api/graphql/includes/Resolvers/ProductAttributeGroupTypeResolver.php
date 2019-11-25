<?php
namespace GQL\Resolvers;

trait ProductAttributeGroupTypeResolver {
    
    public function ProductAttributeGroupType_attribute ($root, $args, &$ctx) { return $root ['attribute']; }

}
?>