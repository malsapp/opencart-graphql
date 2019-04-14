<div class="list-group">
  <?php foreach ($faqcategories as $faqcategory) { ?>
  <?php if ($faqcategory['faqcategory_id'] == $faqcategory_id) { ?>
  <a href="<?php echo $faqcategory['href']; ?>" class="list-group-item active"><?php echo $faqcategory['title']; ?></a>
 <?php } else { ?>
  <a href="<?php echo $faqcategory['href']; ?>" class="list-group-item"><?php echo $faqcategory['title']; ?></a>
  <?php } ?>
  <?php } ?>
</div>
