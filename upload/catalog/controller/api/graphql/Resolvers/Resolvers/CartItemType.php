<?php
namespace GQL;

trait CartItemTypeResolver {
    
    public function CartItemType_option ($root, $args, &$ctx) { return $root ['option']; }

    public function CartItemType_download ($root, $args, &$ctx) { return $root ['download']; }

    public function CartItemType_recurring ($root, $args, &$ctx) { return $root ['recurring']; }

}
?>